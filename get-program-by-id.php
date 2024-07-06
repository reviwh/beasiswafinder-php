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
    $sql = "SELECT * FROM programs 
        INNER JOIN reviews ON programs.id = reviews.program_id 
        INNER JOIN users ON reviews.user_id = users.id 
        WHERE programs.id = ?";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $_GET['id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($data) > 0) {
      $program = [
        "id" => $data[0]["program_id"],
        "name" => $data[0]["name"],  // Assuming this is the program name
        "image" => $data[0]["image"],
        "description" => $data[0]["description"],
        "price" => $data[0]["price"],
        "fixed_price" => $data[0]["fixed_price"] == 1,
        "discount" => $data[0]["discount"],
        "registered_student" => $data[0]["registered_students"],
        "features" => $data[0]["features"],
        "bonus" => $data[0]["bonus"],
        "category" => $data[0]["category"],
        "reviews" => []
      ];

      foreach ($data as $row) {
        $review = [
          "review" => $row["review"],
          "rating" => $row["rating"],
          "user" => [
            "id" => $row["user_id"],
            "name" => $row["name"],
            "email" => $row["email"],
            "phone_number" => $row["phone_number"],
            "date_of_birth" => $row["date_of_birth"],
            "gender" => $row["gender"],
            "created_at" => $row["created_at"],
            "updated_at" => $row["updated_at"]
          ]
        ];
        $program["reviews"][] = $review;
      }

      $res = [
        'code' => 200,
        'status' => 'OK',
        'data' => $program
      ];

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
