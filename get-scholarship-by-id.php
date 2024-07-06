<?php
include_once "connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

$res = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  try {
    $sql = "SELECT * FROM scholarships WHERE id = ?";
  } catch (Exception $e) {
    $res['code'] = 500;
    $res['status'] = 'Internal Server Error';
    $res['data']['error'] = $e->getMessage();
    http_response_code($res['code']);
    echo json_encode($res);
  }
}
