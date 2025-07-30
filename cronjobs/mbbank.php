<?php
ini_set('max_execution_time', 600);
define("BASEPATH", true);

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'].'/app/init.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

$currencies_array = get_currencies_array('enabled');

/*--------------------------------------------------------------------------
| 1 – Đọc API MB Bank → lấy giao dịch mới (để ngắn gọn, bạn giữ phần API cũ)
|    Kết quả cuối cùng phải có:
|    $client_id      – ID user nạp
|    $payment_id     – ID dòng payments
|    $amount_vnd_raw – số tiền VND nạp (chuỗi “1,000,000” …)
*--------------------------------------------------------------------------*/
... // code gọi API như cũ, rồi gán $client_id, $payment_id, $amount_vnd_raw

$amount_vnd = floatval(str_replace(',', '', $amount_vnd_raw));

/*---------------------------------------------------------------------------
| 2 – Quy đổi VND → tiền chuẩn panel
*----------------------------------------------------------------------------*/
$convertedAmount = from_to(
    $currencies_array,
    'VND',
    $settings['site_base_currency'],
    $amount_vnd
);

/*---------------------------------------------------------------------------
| 3 – Cộng tiền & ghi log (transaction + atomic update)
*----------------------------------------------------------------------------*/
$conn->beginTransaction();
try {
    $conn->prepare(
        "UPDATE clients
         SET balance = balance + :amt
         WHERE client_id = :cid"
    )->execute([
        'amt' => $convertedAmount,
        'cid' => $client_id
    ]);

    $newBalance = $conn->query(
        "SELECT balance FROM clients WHERE client_id = $client_id"
    )->fetchColumn();

    $conn->prepare(
        "UPDATE payments
         SET payment_amount  = :amt,
             client_balance  = :bal,
             payment_status  = 3,
             payment_delivery= 2
         WHERE payment_id = :pid"
    )->execute([
        'amt' => $convertedAmount,
        'bal' => $newBalance,
        'pid' => $payment_id
    ]);

    $conn->prepare(
        "INSERT INTO client_report
         SET client_id = :cid,
             action    = :act,
             report_ip = :ip,
             report_date = NOW()"
    )->execute([
        'cid' => $client_id,
        'act' => "Deposit +$convertedAmount {$settings['site_base_currency']}",
        'ip'  => GetIP()
    ]);

    $conn->commit();

} catch (Exception $e) {
    $conn->rollBack();
    error_log("MBBANK CRON ERROR #$payment_id – ".$e->getMessage());
}
