<?php
session_start();
require_once "db.php";

// 確認使用者是作者
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "author") {
    header("Location: login.php");
    exit;
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category = trim($_POST["category"]);
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if ($category == "" || $title == "" || $content == "") {
        $message = "請填寫所有欄位！";
        $message_type = "error";
    } else {

        $author_id = $_SESSION["user_id"];

        $stmt = $conn->prepare(
            "INSERT INTO submissions
            (author_id, category, title, content, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, 'draft', NOW(), NOW())"
        );

        $stmt->bind_param(
            "isss",
            $author_id,
            $category,
            $title,
            $content
        );

        if ($stmt->execute()) {
            $message = "投稿建立成功！";
            $message_type = "success";
        } else {
            $message = "投稿失敗，請再試一次。";
            $message_type = "error";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增投稿</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background: #eef5ff;
            padding: 30px;
            margin: 0;
            color: #26364a;
        }

        .container {
            max-width: 650px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(50, 90, 140, 0.10);
        }

        h2 {
            color: #315b91;
            margin-bottom: 25px;
        }

        label {
            display: block;
            color: #40546c;
            margin-bottom: 8px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #d5e1ef;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #477fc1;
        }

        textarea {
            height: 200px;
            resize: vertical;
        }

        button {
            padding: 13px 28px;
            background: #477fc1;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #315b91;
        }

        .message {
            margin-top: 18px;
            font-weight: bold;
        }

        .success {
            color: #26845b;
        }

        .error {
            color: #d9534f;
        }

        .back {
            display: block;
            margin-top: 25px;
            color: #477fc1;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .container {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h2>新增投稿</h2>

    <form method="POST">

        <label>稿件類別</label>
        <select name="category" required>
            <option value="">請選擇類別</option>
            <option value="研究論文">研究論文</option>
            <option value="技術報告">技術報告</option>
            <option value="其他">其他</option>
        </select>

        <label>稿件標題</label>
        <input type="text" name="title" required>

        <label>文章內容</label>
        <textarea name="content" required></textarea>

        <button type="submit">儲存投稿</button>

    </form>

    <?php if ($message !== ""): ?>
        <p class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <a href="author_dashboard.php" class="back">← 返回作者首頁</a>

</div>

</body>
</html>