<?php
/**
 * cronjobs/viettinbank.php
 * Auto-credit deposits from VietinBank eKYC/OpenAPI
 * © 2025 YourPanel
 */
ini_set('max_execution_time', 600);
define("BASEPATH", true);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'] . '/app/init.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

/*-----------------------------------------------------------------------
| 0. Chuẩn bị
*-----------------------------------------------------------------------*/
$currencies_array = get_currencies_array('enabled');

/*-----------------------------------------------------------------------
| 1. GỌI API VIETINBANK – LẤY DANH SÁCH GIAO DỊCH MỚI
|    Bạn chỉ cần điền URL & TOKEN do VietinBank cung cấp
*-----------------------------------------------------------------------*/
$vietinApiUrl   = $settings['vietin_api_url'];   // ví dụ https://openapi.vietinbank.vn/transactions
$vietinApiToken = $settings['vietin_api_token']; // Bearer <token>

try {
    $ch = curl_init($vietinApiUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer {$vietinApiToken}",
            "Accept: application/json"
        ],
        CURLOPT_TIMEOUT        => 30
    ]);

    $resp = curl_exec($ch);
    if ($resp === false) {
        throw new Exception('cURL error: ' . curl_error($ch));
    }
    curl_close($ch);

    /** @var array[] $txList  Danh sách giao dịch */
    $txList = json_decode($resp, true);

} catch (Exception $e) {
    error_log('VIETINBANK API ERROR :: ' . $e->getMessage());
    exit; // dừng cron – tránh ghi sai dữ liệu
}

/*-----------------------------------------------------------------------
| 2. LẤY DANH SÁCH MÃ GIAO DỊCH ĐÃ XỬ LÝ
|    Bảng bank_logs: bank_name, bank_ref UNIQUE
*-----------------------------------------------------------------------*/
$doneRefs = $conn
    ->query("SELECT bank_ref FROM bank_logs WHERE bank_name='vietinbank'")
    ->fetchAll(PDO::FETCH_COLUMN);

/*-----------------------------------------------------------------------
| 3. XỬ LÝ TỪNG GIAO DỊCH
*-----------------------------------------------------------------------*/
foreach ($txList as $tx) {

    /* 3.0 – Lấy thông tin cơ bản */
    $bankRef     = trim($tx['tranId']);          // mã giao dịch Vietin
    $amountVnd   = floatval($tx['amount']);      // chỉ lấy giao dịch cộng tiền (credit)
    $description = $tx['description'] ?? '';

    if ($amountVnd <= 0)              continue;  // bỏ giao dịch trừ
    if (in_array($bankRef, $doneRefs)) continue; // đã xử lý

    /* 3.1 – Tìm client_id dựa trên cú pháp "id12345" trong mô tả */
    if (!preg_match('/id(\d+)/i', $description, $m)) {
        // không khớp pattern nạp tiền, đánh dấu đã log & bỏ qua
        $conn->prepare("INSERT IGNORE INTO bank_logs
                        SET bank_name='vietinbank',
                            bank_ref  = :ref,
                            note      = :des,
                            created_at= NOW()")
             ->execute(['ref' => $bankRef, 'des' => $description]);
        continue;
    }
    $client_id = (int)$m[1];

    // kiểm tra user tồn tại
    $user = $conn->query("SELECT client_id FROM clients
                          WHERE client_id = {$client_id} LIMIT 1")
                 ->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        // user không tồn tại ⇒ vẫn log để tránh lặp
        $conn->prepare("INSERT IGNORE INTO bank_logs
                        SET bank_name='vietinbank',
                            bank_ref  = :ref,
                            note      = 'unknown user',
                            created_at= NOW()")
             ->execute(['ref' => $bankRef]);
        continue;
    }

    /*-------------------------------------------------------------------
    | 3.2 – Quy đổi VND → site_base_currency
    *------------------------------------------------------------------*/
    $amountBase = from_to(
        $currencies_array,
        'VND',
        $settings['site_base_currency'],
        $amountVnd
    ); // float

    /*-------------------------------------------------------------------
    | 3.3 – BẮT ĐẦU TRANSACTION
    *------------------------------------------------------------------*/
    $conn->beginTransaction();
    try {
        /* A. Log bank_ref để chống trùng lần sau */
        $conn->prepare("INSERT INTO bank_logs
                        SET bank_name  ='vietinbank',
                            bank_ref   =:ref,
                            client_id  =:cid,
                            amount     =:amt,
                            note       =:des,
                            created_at =NOW()")
             ->execute([
                 'ref' => $bankRef,
                 'cid' => $client_id,
                 'amt' => $amountVnd,
                 'des' => $description
             ]);

        /* B. Ghi bảng payments (đánh dấu đã duyệt ngay)             */
        $conn->prepare("INSERT INTO payments
                        SET client_id       = :cid,
                            payment_method  = 40,               /* 40 = VietinBank */
                            payment_amount  = :amt,
                            payment_status  = 3,                /* completed */
                            payment_delivery= 2,                /* done */
                            client_balance  = (SELECT balance FROM clients WHERE client_id=:cid) + :amt,
                            payment_create  = NOW(),
                            payment_note    = :ref")
             ->execute([
                 'cid' => $client_id,
                 'amt' => $amountBase,
                 'ref' => $bankRef
             ]);

        /* C. Cộng tiền atomic                                         */
        $conn->prepare("UPDATE clients
                        SET balance = balance + :amt
                        WHERE client_id = :cid")
             ->execute([
                 'amt' => $amountBase,
                 'cid' => $client_id
             ]);

        /* D. Log client_report                                        */
        $conn->prepare("INSERT INTO client_report
                        SET client_id = :cid,
                            action    = :act,
                            report_ip = :ip,
                            report_date = NOW()")
             ->execute([
                 'cid' => $client_id,
                 'act' => "Deposit +$amountBase {$settings['site_base_currency']} (VietinBank)",
                 'ip'  => GetIP()
             ]);

        $conn->commit();

    } catch (Exception $e) {
        $conn->rollBack();
        error_log("VIETINBANK TX ERROR {$bankRef} :: ".$e->getMessage());
    }
}
