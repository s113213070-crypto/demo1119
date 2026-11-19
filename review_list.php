<?php
session_start();
require_once "db.php";

// 確認使用者已登入，而且是編輯
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "editor") {
    header("Location: login.php");
    exit;
}

// 取得審查結果訊息
$message = "";

if (isset($_GET["message"])) {
    if ($_GET["message"] === "success") {
        $message = "審查結果已成功儲存！";
    } elseif ($_GET["message"] === "failed") {
        $message = "審查處理失敗，請重新確認稿件狀態。";
    }
}

// 查詢所有等待審查的稿件
$stmt = $conn->prepare(
    "SELECT
        submissions.id,
        users.username,
        submissions.category,
        submissions.title,
        submissions.created_at
     FROM submissions
     INNER JOIN users
        ON submissions.author_id = users.id
     WHERE submissions.status = 'reviewing'
     ORDER BY submissions.created_at ASC"
);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>待審查稿件</title>

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
            max-width: 1100px;
            margin: 0 auto;
        }

        .card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin-top: 0;
            color: #2563a6;
            font-size: 28px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #2563a6;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .message {
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: 7px;
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .message.failed {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
        }

        th {
            background-color: #e6f2ff;
            color: #2563a6;
            font-weight: bold;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:hover td {
            background-color: #f8fbff;
        }

        .view-link {
            display: inline-block;
            padding: 8px 14px;
            background-color: #2563a6;
            color: #ffffff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .view-link:hover {
            background-color: #1d4f85;
        }

        .empty {
            padding: 30px;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 12px;
            }

            .card {
                padding: 20px 14px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">

            <h1>待審查稿件清單</h1>

            <p>
                <a class="back-link" href="editor_dashboard.php">
                    ← 返回編輯首頁
                </a>
            </p>

            <?php if ($message !== "") { ?>

                <div class="message <?php
                    echo (isset($_GET["message"]) && $_GET["message"] === "failed")
                        ? "failed" : "";
                ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>

            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>作者</th>
                        <th>類別</th>
                        <th>標題</th>
                        <th>建立時間</th>
                        <th>操作</th>
                    </tr>

                    <?php if ($result->num_rows > 0) { ?>

                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($row["username"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["category"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["title"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["created_at"]); ?>
                                </td>

                                <td>
                                    <a class="view-link"
                                       href="review_detail.php?id=<?php echo $row["id"]; ?>">
                                        查看稿件
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="5" class="empty">
                                目前沒有等待審查的稿件。
                            </td>
                        </tr>

                    <?php } ?>

                </table>
            </div>

        </div>
    </div>

    <?php
    $stmt->close();
    $conn->close();
    ?>

</body>
</html>