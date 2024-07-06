<?php
include_once "connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

$res = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  if (!isset($_GET['id'])) {
    http_response_code(400);
    $res['code'] = 400;
    $res['status'] = 'Bad Request';
    $res['data']['error'] = 'Missing id parameter';
    echo json_encode($res);
    die();
  }

  try {
    $sql = "SELECT * FROM reviews INNER JOIN users ON reviews.user_id = users.id WHERE program_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($data) > 0) {
      $res['code'] = 200;
      $res['status'] = 'OK';
      $res['data'] = $data;
      http_response_code($res['code']);
      echo json_encode($res);
    } else {
      include_once "errors/404.php";
    }
  } catch (Exception $e) {
    $res['code'] = 500;
    $res['status'] = 'Internal Server Error';
    $res['data']['error'] = $e->getMessage();
    http_response_code($res['code']);
    echo json_encode($res);
  }
} else {
  include_once "errors/400.php";
}
