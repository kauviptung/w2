<?php
if (!defined('BASEPATH')) { die('Direct access to the script is not allowed'); }

/* ─────────────────────────  COMMON  ───────────────────────── */

function normalizeStatus(string $status): string
{
  return match (strtolower(trim($status))) {
    'pending', 'awaiting'                           => 'pending',
    'inprogress', 'processing', 'progress',
    'in progress', 'running'                       => 'in progress',
    'completed', 'finished', 'success'             => 'completed',
    'partial', 'partially completed', 'partiald'   => 'partial',
    'canceled', 'cancelled', 'rejected', 'refunded'=> 'canceled',
    default                                        => 'unknown',
  };
}

/* ─────────────────────────  ROUTER  ───────────────────────── */

$req   = $_POST;
$action= strtolower($req['action'] ?? '');

switch ($action) {

/* ------- 1. Đặt đơn (type = API) ------- */
case 'add':
  /* (1) Validate + lấy service, price … — giữ y nguyên mã cũ */
  …
  /* (2) Trừ tiền & ghi đơn trong 1 transaction  */
  $conn->beginTransaction();

  /*  INSERT INTO orders  */
  $INSERT_API_ORDER = $conn->prepare("
    INSERT INTO orders
    SET client_id      = :cid,
        service_id     = :sid,
        link           = :lnk,
        quantity       = :qty,
        order_charge   = :chg,
        order_status   = 'pending',
        api_service    = 1
  ")->execute([
      'cid'=> $user['client_id'],
      'sid'=> $SERVICE_ID,
      'lnk'=> $req['link'],
      'qty'=> $qty,
      'chg'=> $charge
  ]);

  /*  UPDATE balance (atomic)  */
  $update_user = $conn->prepare("
      UPDATE clients
      SET balance = balance - :cost
      WHERE client_id = :cid
  ")->execute([
      'cost'=> $charge,
      'cid' => $user['client_id']
  ]);

  /*  INSERT log  */
  $insert_order_log = $conn->prepare("
      INSERT INTO client_report
      SET client_id = :cid,
          action    = :act,
          report_ip = :ip,
          report_date = NOW()
  ")->execute([
      'cid' => $user['client_id'],
      'act' => "Order -$charge ".$settings['site_base_currency'],
      'ip'  => GetIP()
  ]);

  /* (3) Nếu ba bước OK → COMMIT  (bị thiếu gây rollback!) */
  if ($INSERT_API_ORDER && $update_user && $insert_order_log) {
      $ORDER_ID = $conn->lastInsertId();
      $conn->commit();                       // <── SỬA: thêm commit
      echo json_encode(['order'=>$ORDER_ID]); exit;
  }
  /* (4) Ngược lại rollback  */
  $conn->rollBack();
  echo json_encode(['error'=>'Database error']); exit;

/* ------- 2. Hỏi trạng thái ------- */
case 'status':
  $order = $conn->prepare("SELECT * FROM orders WHERE order_id = :id");
  $order->execute(['id'=>(int)$req['order']]);
  $row = $order->fetch(PDO::FETCH_ASSOC);
  if (!$row) { echo json_encode(['error'=>'Order not found']); exit; }

  echo json_encode([
      'status' => ucfirst(normalizeStatus($row['order_status'])),
      'charge' => $row['order_charge'],
      'start'  => $row['start_counter'],
      'remains'=> $row['remains']
  ]); exit;

/* … giữ nguyên các nhánh khác … */
}
