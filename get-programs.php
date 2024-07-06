<?php
include_once "connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

$res = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  try {
    $sql = "SELECT * FROM programs";

    $filter = $_GET['filter'] ?? 'false';
    $category = '';
    $test = '';
    $language = '';

    if ($filter === 'true') {

      $conditions = array();

      if (isset($_GET['category'])) {
        $conditions[] = "category = ?";
        $category = $_GET['category'];
      }

      if (isset($_GET['test'])) {
        $conditions[] = "test = ?";
        $test = $_GET['test'];
      }

      if (isset($_GET['language'])) {
        $conditions[] = "language = ?";
        $language = $_GET['language'];
      }

      if (count($conditions) === 0) {
        http_response_code(400);
        $res['code'] = 400;
        $res['status'] = 'Bad Request';
        $res['data']['error'] = 'Missing filter parameter';
        echo json_encode($res);
        die();
      }

      $sql .= ' WHERE ' . implode(' AND ', $conditions);
      $i = 1;

      $stmt = $db->prepare($sql);

      if (isset($_GET['category'])) {
        $stmt->bindParam($i++, $category, PDO::PARAM_STR);
      }

      if (isset($_GET['test'])) {
        $stmt->bindParam($i++, $test, PDO::PARAM_STR);
      }

      if (isset($_GET['language'])) {
        $stmt->bindParam($i++, $language, PDO::PARAM_STR);
      }
    } else {
      $stmt = $db->prepare($sql);
    }

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
