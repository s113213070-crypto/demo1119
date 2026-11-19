<?php
session_start();
require_once "db.php";

// 確認使用者已登入，而且是編輯
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "editor") {
    header("Location: login.php");
    exit;
}

// 只接受 POST 請求
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: review_list.php");
    exit;
}

// 取得稿件 ID 和編輯的操作
$id = intval($_POST["id"] ?? 0);
$action = $_POST["action"] ?? "";

// 判斷審查結果
if ($action === "approve") {
    $new_status = "approved";
} elseif ($action === "return") {
    $new_status = "draft";
} else {
    header("Location: review_list.php");
    exit;
}

// 更新稿件狀態
$stmt = $conn->prepare(
    "UPDATE submissions
     SET status = ?, updated_at = NOW()
     WHERE id = ? AND status = 'reviewing'"
);

$stmt->bind_param("si", $new_status, $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: review_list.php?message=success");
} else {
    header("Location: review_list.php?message=failed");
}

$stmt->close();
$conn->close();
exit;
?>