<?php
include_once "connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

$res = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  try {
    $sql = "SELECT * FROM scholarships WHERE (registration_start <= ? OR registration_start IS NULL)";

    $filter = $_GET['filter'] ?? 'false';

    $d1 = '';
    $d2 = '';
    $d3 = '';
    $d4 = '';
    $s1 = '';
    $s2 = '';
    $s3 = '';
    $non_degree = '';
    $country = '';
    $funding_type = '';
    $start = date("Y/m/d");
    $end = date("Y/m/d");

    if ($_GET['filter'] === 'true') {

      $conditions = array();

      if (isset($_GET['start'])) {
        $start = $_GET['start'];
      }

      if (isset($_GET['end'])) {
        $conditions[] = 'registration_end <= ?';
        $end = $_GET['end'];
      } else {
        $conditions[] = '(registration_end >= ? OR registration_end IS NULL)';
      }

      if (isset($_GET['d1'])) {
        $conditions[] = "d1 = ?";
        $d1 = $_GET['d1'];
      }

      if (isset($_GET['d2'])) {
        $conditions[] = "d2 = ?";
        $d2 = $_GET['d2'];
      }

      if (isset($_GET['d3'])) {
        $conditions[] = "d3 = ?";
        $d3 = $_GET['d3'];
      }

      if (isset($_GET['d4'])) {
        $conditions[] = "d4 = ?";
        $d4 = $_GET['d4'];
      }

      if (isset($_GET['s1'])) {
        $conditions[] = "s1 = ?";
        $s1 = $_GET['s1'];
      }

      if (isset($_GET['s2'])) {
        $conditions[] = "s2 = ?";
        $s2 = $_GET['s2'];
      }

      if (isset($_GET['s3'])) {
        $conditions[] = "s3 = ?";
        $s3 = $_GET['s3'];
      }

      if (isset($_GET['non_degree'])) {
        $conditions[] = "non_degree = ?";
        $non_degree = $_GET['non_degree'];
      }

      if (isset($_GET['country'])) {
        $conditions[] = "country = ?";
        $country = $_GET['country'];
      }

      if (isset($_GET['funding_type'])) {
        $conditions[] = "funding_type = ?";
        switch ($_GET['funding_type']) {
          case 'FULLY_FUNDED':
            $funding_type = 'Fully Funded';
            break;
          case 'PARTIALLY_FUNDED':
            $funding_type = 'Partially Funded';
            break;
        }
      }

      $sql .= ' AND ' . implode(' AND ', $conditions);
      $i = 1;

      $stmt = $db->prepare($sql);
      $stmt->bindParam($i++, $start, PDO::PARAM_STR);
      $stmt->bindParam($i++, $end, PDO::PARAM_STR);

      if (isset($_GET['d1'])) {
        $stmt->bindParam($i++, $d1, PDO::PARAM_BOOL);
      }

      if (isset($_GET['d2'])) {
        $stmt->bindParam($i++, $d2, PDO::PARAM_BOOL);
      }

      if (isset($_GET['d3'])) {
        $stmt->bindParam($i++, $d3, PDO::PARAM_BOOL);
      }

      if (isset($_GET['d4'])) {
        $stmt->bindParam($i++, $d4, PDO::PARAM_BOOL);
      }

      if (isset($_GET['s1'])) {
        $stmt->bindParam($i++, $s1, PDO::PARAM_BOOL);
      }

      if (isset($_GET['s2'])) {
        $stmt->bindParam($i++, $s2, PDO::PARAM_BOOL);
      }

      if (isset($_GET['s3'])) {
        $stmt->bindParam($i++, $s3, PDO::PARAM_BOOL);
      }

      if (isset($_GET['non_degree'])) {
        $stmt->bindParam($i++, $non_degree, PDO::PARAM_BOOL);
      }

      if (isset($_GET['country'])) {
        $stmt->bindParam($i++, $country, PDO::PARAM_STR);
      }

      if (isset($_GET['funding_type'])) {
        $stmt->bindParam($i++, $funding_type, PDO::PARAM_STR);
      }
    } else {
      $sql .= ' AND (registration_end >= ? OR registration_end IS NULL)';
      $stmt = $db->prepare($sql);
      $stmt->bindParam(1, $start, PDO::PARAM_STR);
      $stmt->bindParam(2, $end, PDO::PARAM_STR);
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
