<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "submission_system";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>