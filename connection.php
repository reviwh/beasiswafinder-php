<?php
$dbhost = "localhost";
$dbname = "beasiswafinder";
$dbuser = "root";
$dbpass = "";

try {
  $db = new PDO(dsn: "mysql:host=$dbhost;dbname=$dbname",  username: $dbuser, password: $dbpass);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  $res['code'] = 500;
  $res['status'] = 'Internal Server Error';
  $res['data']['error'] = $e->getMessage();
  http_response_code($res['code']);
  echo json_encode($res);
  die();
}
