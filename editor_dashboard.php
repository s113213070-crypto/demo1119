<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "editor") {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯管理首頁</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background: #eef5ff;
            margin: 0;
            padding: 30px;
            color: #26364a;
        }

        .container {
            max-width: 750px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(50, 90, 140, 0.10);
        }

        h1 {
            color: #315b91;
            margin-bottom: 10px;
        }

        .welcome {
            color: #718096;
            margin-bottom: 30px;
        }

        .menu a {
            display: block;
            padding: 17px 20px;
            margin: 15px 0;
            background: #477fc1;
            color: white;
            text-decoration: none;
            border-radius: 9px;
            transition: 0.2s;
        }

        .menu a:hover {
            background: #315b91;
            transform: translateY(-2px);
        }

        .menu .logout {
            background: #e8f1fc;
            color: #315b91;
        }

        .menu .logout:hover {
            background: #d5e5f8;
        }

        @media (max-width: 500px) {
            .container {
                padding: 25px;
                margin: 25px auto;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>編輯管理首頁</h1>

    <p class="welcome">
        歡迎，<?php echo htmlspecialchars($_SESSION["username"]); ?>！
        歡迎使用稿件審查系統。
    </p>

    <div class="menu">

        <a href="review_list.php">查看待審查稿件</a>

        <a href="logout.php" class="logout">登出系統</a>

    </div>

</div>

</body>
</html>