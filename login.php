<?php
// login.php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$input_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Comment out the password verification as per your current setup
        if ($input_password === $user['password']) {
            // Start session and store user info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: profile.php");
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "No user found with that username!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff5f6d, #ffc3a0);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: #ff5f6d;
            outline: none;
        }

        .btn {
            background-color: #ff5f6d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #e04d4d;
        }

        p {
            color: #555;
        }

        p a {
            color: #ff5f6d;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username">
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button class="btn" type="submit">Login</button>
        </form>

        <p>New user? <a href="register.php">Register here</a></p>
    </div>

</body>
</html>
