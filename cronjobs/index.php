
<?php
require '../vendor/autoload.php';
require '../app/init.php';






$smmapi   = new SMMApi();
$link = $_SERVER['HTTP_HOST'];

$url = "https://$link/crons/cronjobs/autolike.php";
 $order    = $smmapi->action(array('action' =>"0"), $url);
$url = "https://$link/cronjobs/autoreply.php";
 $order    = $smmapi->action(array('action' =>"0"), $url);
$url = "https://$link/cronjobs/average.php";
 $order    = $smmapi->action(array('action' =>"0"), $url);
$url = "https://$link/cronjobs/dripfeed.php";
 $order    = $smmapi->action(array('action' =>"0"), $url);
 $url = "https://$link/cronjobs/orders.php";
 $order    = $smmapi->action(array('action' =>"0"), $url);
 $url = "https://$link/cronjobs/payments.php";
 $order    = $smmapi->action(array('action' =>"0"), $url);
 $url = "https://$link/cronjobs/refill.php";
 $order    = $smmapi->action(array('action' =>"0"), $url);
 $url = "https://$link/cronjobs/seller-sync.php";
 $order    = $smmapi->action(array('action' =>"0"), $url);

echo "Done";


