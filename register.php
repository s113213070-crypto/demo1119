<?php
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $role = $_POST["role"];

    if ($username === "" || $password === "") {
        $message = "請輸入帳號和密碼！";

    } elseif (!in_array($role, ["author", "editor"], true)) {
        $message = "請選擇正確的身分！";

    } else {
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "這個帳號已經有人使用了！";

        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, role)
                    VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $message = "註冊失敗，請稍後再試！";
            }

            $stmt->close();
        }

        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊帳號</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Microsoft JhengHei", sans-serif;
            background: #eef5ff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            width: 90%;
            max-width: 430px;
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

        input, select {
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
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #315b91;
        }

        .message {
            color: #d9534f;
            text-align: center;
            margin-bottom: 15px;
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

<div class="container">

    <h2>建立新帳號</h2>

    <?php if ($message !== ""): ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>帳號</label>
        <input type="text" name="username" required>

        <label>密碼</label>
        <input type="password" name="password" required>

        <label>選擇身分</label>
        <select name="role" required>
            <option value="">請選擇身分</option>
            <option value="author">作者</option>
            <option value="editor">編輯</option>
        </select>

        <button type="submit">註冊帳號</button>

    </form>

    <a href="index.php" class="back">返回首頁</a>

</div>

</body>
</html>