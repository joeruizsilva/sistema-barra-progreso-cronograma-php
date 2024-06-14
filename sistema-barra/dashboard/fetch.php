<?php
require 'db.php';

$result = $conn->query("SELECT * FROM cronograma ORDER BY id ASC");
$data = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($data);
?>