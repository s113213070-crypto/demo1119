<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if (isset($_GET["registered"]) && $_GET["registered"] == "1") {
    $success = "註冊成功，請登入！";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, username, password, role FROM users WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "author") {
                header("Location: author_dashboard.php");
                exit;
            } elseif ($user["role"] == "editor") {
                header("Location: editor_dashboard.php");
                exit;
            }
        }
    }

    $error = "帳號或密碼錯誤！";
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿管理系統 - 登入</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background: #eef5ff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            width: 90%;
            max-width: 400px;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(50, 90, 140, 0.10);
        }

        h2 {
            text-align: center;
            color: #315b91;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #40546c;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #d5e1ef;
            border-radius: 8px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #477fc1;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
        }

        button:hover {
            background: #315b91;
        }

        .error {
            color: #d9534f;
            text-align: center;
        }

        .success {
            color: #26845b;
            text-align: center;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #477fc1;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>投稿與稿件審查管理系統</h2>

    <?php if ($success !== ""): ?>
        <p class="success">
            <?php echo htmlspecialchars($success); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <label>帳號</label>
        <input type="text" name="username" required>

        <label>密碼</label>
        <input type="password" name="password" required>

        <button type="submit">登入</button>

    </form>

    <p class="error">
        <?php echo htmlspecialchars($error); ?>
    </p>

    <a href="index.php" class="back">返回首頁</a>

</div>

</body>
</html>
