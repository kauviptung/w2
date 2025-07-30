<?php
ini_set('max_execution_time', 600);
define("BASEPATH", true);

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'].'/app/init.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

$currencies_array = get_currencies_array('enabled');

/*--------------------------------------------------------------------------
| 1 – Lấy ra các giao dịch INR chưa xử lý
*--------------------------------------------------------------------------*/
$rows = $conn->query(
    "SELECT * FROM payments
     WHERE payment_method = 39      /* ID cổng INR */
       AND payment_status = 1       /* chờ duyệt */
       AND payment_delivery = 1"    /* chưa giao dịch */
)->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $payment) {

    $payment_id   = $payment['payment_id'];
    $client_id    = $payment['client_id'];
    $raw_amount   = $payment['payment_amount'];   // INR
    $methodCurr   = 'INR';

    /*----------------------------------------------------------------------
    | 2 – Quy đổi về tiền chuẩn của site (USD, VND …)
    *---------------------------------------------------------------------*/
    $convertedAmount = from_to(
        $currencies_array,
        $methodCurr,
        $settings['site_base_currency'],
        $raw_amount
    ); // float

    /*----------------------------------------------------------------------
    | 3 – Cộng tiền + ghi log trong 1 transaction
    *---------------------------------------------------------------------*/
    $conn->beginTransaction();
    try {

        /* 3.1 – Tăng số dư (atomic) */
        $conn->prepare(
            "UPDATE clients
             SET balance = balance + :amt
             WHERE client_id = :cid"
        )->execute([
            'amt' => $convertedAmount,
            'cid' => $client_id
        ]);

        /* 3.2 – Lấy số dư mới để ghi vào payments */
        $newBalance = $conn->query(
            "SELECT balance FROM clients WHERE client_id = $client_id"
        )->fetchColumn();

        /* 3.3 – Cập nhật bảng payments */
        $conn->prepare(
            "UPDATE payments
             SET payment_amount  = :amt,
                 client_balance  = :bal,
                 payment_status  = 3,      /* hoàn tất */
                 payment_delivery= 2       /* đã giao */
             WHERE payment_id = :pid"
        )->execute([
            'amt' => $convertedAmount,
            'bal' => $newBalance,
            'pid' => $payment_id
        ]);

        /* 3.4 – Ghi log */
        $conn->prepare(
            "INSERT INTO client_report
             SET client_id   = :cid,
                 action      = :act,
                 report_ip   = :ip,
                 report_date = NOW()"
        )->execute([
            'cid' => $client_id,
            'act' => "Deposit +$convertedAmount {$settings['site_base_currency']}",
            'ip'  => GetIP()
        ]);

        $conn->commit();

    } catch (Exception $e) {
        $conn->rollBack();
        error_log("PAYMENTS CRON ERROR #$payment_id – ".$e->getMessage());
    }
}
