<?php
require_once "config.php";
header("Content-Type: application/json");
$method = $_SERVER["REQUEST_METHOD"];

session_start();
$userId = $_SESSION["id"];

switch($method) {
    case "GET":
        $stmt = $pdo->prepare("SELECT * FROM Todo WHERE user_id=?");
        $stmt->execute([$userId]);
        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT username FROM User WHERE id=?");
        $stmt->execute([$userId]);
        $userInfo = $stmt->fetch();

        echo json_encode(["userInfo" => $userInfo,"todos" => $todos]);
        break;
    case "POST":
        $body = json_decode(file_get_contents("php://input"));
        $name = $body->name;
        $description = $body->description;

        $stmt = $pdo->prepare("INSERT INTO Todo (name, description, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $userId]);

        echo json_encode(["message" => "Inserted successfully"]);
        break;
    case "DELETE":
        $body = json_decode(file_get_contents("php://input"));
        $id = $body->id;

        $stmt = $pdo->prepare("DELETE FROM Todo WHERE id=? AND user_id=?");
        $stmt->execute([$id, $userId]);

        echo json_encode(["message" => "Removed successfully"]);
        break;

}