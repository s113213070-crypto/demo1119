<?php
session_start();
require_once "db.php";

// 確認使用者已登入，而且是作者
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "author") {
    header("Location: login.php");
    exit;
}

// 確認是否透過 POST 送出
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: my_submissions.php");
    exit;
}

// 取得稿件 ID
$id = intval($_POST["id"] ?? 0);

// 只刪除本人且狀態為 draft 的稿件
$stmt = $conn->prepare(
    "DELETE FROM submissions
     WHERE id = ? AND author_id = ? AND status = 'draft'"
);

$stmt->bind_param("ii", $id, $_SESSION["user_id"]);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: my_submissions.php?message=deleted");
} else {
    header("Location: my_submissions.php?message=failed");
}

$stmt->close();
$conn->close();
exit;
?>
