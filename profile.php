<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id=$user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $dob = new DateTime($user['date_of_birth']);
    $age = $dob->diff(new DateTime())->y; // Calculate age
} else {
    echo "User not found!";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff5f6d, #ffc3a0);
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            color: #ff5f6d;
        }

        .btn-logout {
            background-color: #ff5f6d;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #e04d4d;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-card">
        <h2>Welcome, <?php echo $user['name']; ?></h2>
        <p><span class="label">Username:</span> <?php echo $user['username']; ?></p>
        <p><span class="label">Age:</span> <?php echo $age; ?> years</p>
        <p><span class="label">Date of Birth:</span> <?php echo $user['date_of_birth']; ?></p>
        <p><span class="label">Contact Number:</span> <?php echo $user['contact_number']; ?></p>

        <a href="update_profile.php" class="btn-logout">Update Profile</a>
        <a href="login.html" class="btn-logout">Logout</a>
    </div>
</div>

</body>
</html>
