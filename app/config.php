<?php
$url = $_SERVER["SERVER_NAME"];  
define('PATH', realpath('.'));
define('SUBFOLDER', false);
define('URL', 'https://smm247vn.com');
define('STYLESHEETS_URL', '//smm247vn.com' );
date_default_timezone_set('Asia/Ho_Chi_Minh');
error_reporting(0);
return [
  'db' => [
    'name'    =>  'nhsit4nq_smm' ,
    'host'    =>  'localhost',
    'user'    =>  'nhsit4nq_admin' ,
    'pass'    =>  'Ducanhtung1221site@' ,
    'charset' =>  'utf8mb4' 
  ]
];
?>