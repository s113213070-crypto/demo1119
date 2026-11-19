<?php
session_start();
require_once "db.php";

// 確認使用者已登入，而且是作者
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "author") {
    header("Location: login.php");
    exit;
}

// 查詢目前作者的所有稿件
$stmt = $conn->prepare(
    "SELECT id, category, title, status, created_at
     FROM submissions
     WHERE author_id = ?
     ORDER BY created_at DESC"
);

$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();

$result = $stmt->get_result();

// 狀態翻譯
function translateStatus($status) {
    if ($status === "draft") {
        return "編輯中";
    } elseif ($status === "reviewing") {
        return "審查中";
    } elseif ($status === "approved") {
        return "已通過";
    }

    return "未知狀態";
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的投稿</title>

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

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 750px;
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

        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            background-color: #e6f2ff;
            color: #2563a6;
        }

        .actions {
            white-space: nowrap;
        }

        .actions a,
        .actions button {
            display: inline-block;
            margin: 3px 2px;
            padding: 7px 12px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .edit {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .delete {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .submit {
            background-color: #2563a6;
            color: #ffffff;
        }

        .actions a:hover,
        .actions button:hover {
            opacity: 0.8;
        }

        .unavailable {
            color: #94a3b8;
            font-size: 13px;
        }

        .empty {
            padding: 25px;
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

            <h1>我的投稿</h1>

            <p>
                <a class="back-link" href="author_dashboard.php">
                    ← 返回作者首頁
                </a>
            </p>

            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>類別</th>
                        <th>標題</th>
                        <th>狀態</th>
                        <th>建立時間</th>
                        <th>操作</th>
                    </tr>

                    <?php if ($result->num_rows > 0) { ?>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($row["category"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["title"]); ?>
                                </td>

                                <td>
                                    <span class="status">
                                        <?php echo translateStatus($row["status"]); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["created_at"]); ?>
                                </td>

                                <td class="actions">
                                    <?php if ($row["status"] === "draft") { ?>

                                        <a class="edit"
                                           href="submission_edit.php?id=<?php echo $row["id"]; ?>">
                                            編輯
                                        </a>

                                        <form method="POST"
                                              action="submission_delete.php"
                                              style="display:inline;"
                                              onsubmit="return confirm('確定要刪除這篇稿件嗎？');">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?php echo $row["id"]; ?>">

                                            <button class="delete" type="submit">
                                                刪除
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="submission_submit.php"
                                              style="display:inline;"
                                              onsubmit="return confirm('確定要將這篇稿件送交審查嗎？');">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?php echo $row["id"]; ?>">

                                            <button class="submit" type="submit">
                                                送出審查
                                            </button>
                                        </form>

                                    <?php } else { ?>

                                        <span class="unavailable">
                                            不可編輯或刪除
                                        </span>

                                    <?php } ?>
                                </td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="5" class="empty">
                                目前還沒有投稿紀錄。
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