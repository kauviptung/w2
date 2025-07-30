<?php
if (!defined('BASEPATH')) { die('Direct access to the script is not allowed'); }

function normalizeStatus(string $s): string
{
  return match (strtolower(trim($s))) {
    'pending', 'awaiting'                           => 'pending',
    'inprogress', 'processing', 'progress',
    'in progress', 'running'                       => 'in progress',
    'completed', 'finished', 'success'             => 'completed',
    'partial', 'partially completed', 'partiald'   => 'partial',
    'canceled', 'cancelled', 'rejected', 'refunded'=> 'canceled',
    default                                        => 'unknown',
  };
}

$filter = route(1) ?: 'all';
if ($filter=='inprogress'||$filter=='processing') $filter='in progress';

$sql = $filter!='all'
     ? "AND order_status='$filter'"
     : '';

$rows = $conn->query("
   SELECT order_id, order_status, remains
   FROM orders
   WHERE client_id = :cid
     $sql
   ORDER BY order_id DESC
   LIMIT 200
", ['cid'=>$uid])->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as &$r) {
   $r['order_status'] = ucfirst(normalizeStatus($r['order_status']));
}

echo json_encode($rows);
