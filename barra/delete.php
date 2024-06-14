<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Eliminar el registro
    $stmt = $conn->prepare("DELETE FROM personas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Renumerar los IDs
    $conn->query("SET @count = 0;");
    $conn->query("UPDATE personas SET id = @count:= @count + 1;");
    $conn->query("ALTER TABLE personas AUTO_INCREMENT = 1;");

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>