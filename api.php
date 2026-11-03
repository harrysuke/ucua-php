<?php
include('testFunctions.php');

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM ucuaform WHERE Id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            $sql = "SELECT * FROM ucuaform ORDER BY Id DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $ucua[] = $row;
            }
            echo json_encode($ucua);
        }
        break;

    case 'POST':
        //INSERT
        echo json_encode(["message" => "Method disabled"]);
        break;
    case 'PUT':
        //UPDATE
        echo json_encode(["message" => "Method disabled"]);
        break;
    case 'DELETE':
        echo json_encode(["message" => "Method disabled"]);
    default:
        echo json_encode(["messgae" => "Invalid request method"]);
        break;
}
