<?php
session_start();
require_once "db.php";

// 確認使用者已登入，而且是編輯
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "editor") {
    header("Location: login.php");
    exit;
}

// 取得稿件 ID
$id = intval($_GET["id"] ?? 0);

// 查詢稿件及作者資料
$stmt = $conn->prepare(
    "SELECT
        submissions.id,
        users.username,
        submissions.category,
        submissions.title,
        submissions.content,
        submissions.status,
        submissions.created_at
     FROM submissions
     INNER JOIN users
        ON submissions.author_id = users.id
     WHERE submissions.id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$submission = $result->fetch_assoc();

// 確認稿件是否存在
if (!$submission) {
    echo "找不到這篇稿件。";
    exit;
}

// 只允許查看審查中的稿件
if ($submission["status"] !== "reviewing") {
    echo "這篇稿件目前不在審查狀態。";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>稿件詳細內容</title>

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
            max-width: 850px;
            margin: 0 auto;
        }

        .card {
            background-color: #ffffff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #2563a6;
            font-size: 28px;
        }

        h2 {
            margin-top: 30px;
            margin-bottom: 15px;
            color: #2563a6;
            font-size: 21px;
        }

        .info {
            padding: 15px 18px;
            margin-bottom: 10px;
            background-color: #f8fbff;
            border-radius: 7px;
            line-height: 1.8;
        }

        .info strong {
            color: #475569;
        }

        .article-content {
            padding: 22px;
            min-height: 220px;
            background-color: #f8fbff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            line-height: 1.9;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .divider {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #e2e8f0;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .actions button {
            padding: 12px 25px;
            border: none;
            border-radius: 7px;
            color: #ffffff;
            font-size: 15px;
            cursor: pointer;
        }

        .approve {
            background-color: #2563a6;
        }

        .approve:hover {
            background-color: #1d4f85;
        }

        .return {
            background-color: #64748b;
        }

        .return:hover {
            background-color: #475569;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
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

            .card {
                padding: 24px 18px;
            }

            h1 {
                font-size: 24px;
            }

            .actions {
                flex-direction: column;
            }

            .actions button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">

            <h1>稿件詳細內容</h1>

            <div class="info">
                <strong>作者：</strong>
                <?php echo htmlspecialchars($submission["username"]); ?>
            </div>

            <div class="info">
                <strong>類別：</strong>
                <?php echo htmlspecialchars($submission["category"]); ?>
            </div>

            <div class="info">
                <strong>標題：</strong>
                <?php echo htmlspecialchars($submission["title"]); ?>
            </div>

            <div class="info">
                <strong>建立時間：</strong>
                <?php echo htmlspecialchars($submission["created_at"]); ?>
            </div>

            <hr class="divider">

            <h2>文章內容</h2>

            <div class="article-content">
                <?php
                echo nl2br(htmlspecialchars($submission["content"]));
                ?>
            </div>

            <hr class="divider">

            <h2>審查決定</h2>

            <form method="POST" action="review_action.php">

                <input type="hidden" name="id"
                       value="<?php echo $submission["id"]; ?>">

                <div class="actions">

                    <button class="approve"
                            type="submit"
                            name="action"
                            value="approve"
                            onclick="return confirm('確定要通過這篇稿件嗎？');">
                        通過稿件
                    </button>

                    <button class="return"
                            type="submit"
                            name="action"
                            value="return"
                            onclick="return confirm('確定要退回這篇稿件嗎？');">
                        退回稿件
                    </button>

                </div>

            </form>

            <a class="back-link" href="review_list.php">
                ← 返回待審查清單
            </a>

        </div>
    </div>

</body>
</html>