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

/* ------------------  FILTER ------------------ */
$filter = route(1) ?: 'all';
if ($filter == 'inprogress' || $filter == 'processing') $filter = 'in progress';

$valid  = ["all","pending","in progress","completed","partial","canceled"];
if (!in_array($filter,$valid)) $filter = 'all';

$extra  = $filter!='all' ? "AND order_status='$filter'" : '';

/* ------------------  QUERY ------------------ */
$orders = $conn->query("
  SELECT * FROM orders
  WHERE client_id = :cid
    $extra
  ORDER BY order_id DESC
  LIMIT 250
", ['cid'=>$uid])->fetchAll(PDO::FETCH_ASSOC);

/* ------------------  HTML ------------------ */
foreach ($orders as $o) { ?>
  <tr>
    <td><?= $o['order_id'] ?></td>
    <td><?= ucfirst(normalizeStatus($o['order_status'])) ?></td>
    <td><?= htmlspecialchars($o['link']) ?></td>
    <td><?= $o['quantity'] ?></td>
    <td><?= $o['order_charge'] ?></td>
  </tr>
<?php } ?>
