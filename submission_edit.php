<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "author") {
    header("Location: login.php");
    exit;
}

$author_id = $_SESSION["user_id"];
$message = "";

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    die("找不到這篇稿件！");
}

$id = (int)$_GET["id"];

// 查詢作者自己的稿件
$stmt = $conn->prepare(
    "SELECT * FROM submissions
     WHERE id = ? AND author_id = ? AND status = 'draft'"
);

$stmt->bind_param("ii", $id, $author_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("稿件不存在，或目前不能編輯！");
}

$submission = $result->fetch_assoc();
$stmt->close();

// 儲存修改內容
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category = trim($_POST["category"]);
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if ($category == "" || $title == "" || $content == "") {
        $message = "請填寫所有欄位！";
    } else {

        $update = $conn->prepare(
            "UPDATE submissions
             SET category = ?, title = ?, content = ?, updated_at = NOW()
             WHERE id = ? AND author_id = ? AND status = 'draft'"
        );

        $update->bind_param(
            "sssii",
            $category,
            $title,
            $content,
            $id,
            $author_id
        );

        if ($update->execute() && $update->affected_rows >= 0) {
            $message = "稿件修改成功！";

            $submission["category"] = $category;
            $submission["title"] = $title;
            $submission["content"] = $content;
        } else {
            $message = "修改失敗，請再試一次！";
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯投稿</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            font-family: "Microsoft JhengHei", Arial, sans-serif;
            background-color: #f0f7ff;
            color: #334155;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 28px;
            color: #2563a6;
            font-size: 27px;
        }

        label {
            display: block;
            margin-bottom: 9px;
            color: #334155;
            font-weight: bold;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 22px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            background-color: #ffffff;
            color: #334155;
            font-size: 15px;
            font-family: inherit;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.15);
        }

        textarea {
            min-height: 230px;
            resize: vertical;
            line-height: 1.7;
        }

        button {
            padding: 12px 25px;
            background-color: #2563a6;
            color: #ffffff;
            border: none;
            border-radius: 7px;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1d4f85;
        }

        .message {
            margin-top: 20px;
            color: #15803d;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2563a6;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 12px;
            }

            .container {
                padding: 24px 18px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h2>編輯投稿</h2>

    <form method="POST">

        <label>稿件類別</label>
        <select name="category" required>
            <option value="研究論文"
                <?php if ($submission["category"] == "研究論文") echo "selected"; ?>>
                研究論文
            </option>

            <option value="技術報告"
                <?php if ($submission["category"] == "技術報告") echo "selected"; ?>>
                技術報告
            </option>

            <option value="其他"
                <?php if ($submission["category"] == "其他") echo "selected"; ?>>
                其他
            </option>
        </select>

        <label>稿件標題</label>
        <input type="text" name="title"
            value="<?php echo htmlspecialchars($submission["title"]); ?>"
            required>

        <label>文章內容</label>
        <textarea name="content" required><?php
            echo htmlspecialchars($submission["content"]);
        ?></textarea>

        <button type="submit">儲存修改</button>

    </form>

    <p class="message">
        <?php echo htmlspecialchars($message); ?>
    </p>

    <a class="back-link" href="my_submissions.php">
        ← 返回我的投稿
    </a>

</div>

</body>
</html>