<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿與稿件審查管理系統</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Microsoft JhengHei", sans-serif;
            background: #eef5ff;
            color: #26364a;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 90%;
            max-width: 850px;
            background: white;
            padding: 55px 35px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 8px 30px rgba(50, 90, 140, 0.10);
        }

        h1 {
            color: #315b91;
            font-size: 30px;
            margin-bottom: 15px;
        }

        .description {
            color: #718096;
            line-height: 1.8;
            margin-bottom: 35px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 13px 35px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: 0.2s;
        }

        .login {
            background: #477fc1;
            color: white;
        }

        .register {
            background: #e8f1fc;
            color: #315b91;
        }

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .footer {
            margin-top: 45px;
            color: #a0aec0;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>投稿與稿件審查管理系統</h1>

        <p class="description">
            歡迎使用本系統。<br>
            提供作者投稿、稿件管理，以及編輯審查等功能。
        </p>

        <div class="buttons">
            <a href="login.php" class="btn login">登入系統</a>
            <a href="register.php" class="btn register">註冊帳號</a>
        </div>

        <div class="footer">
            投稿與稿件審查管理系統
        </div>
    </div>

</body>
</html>