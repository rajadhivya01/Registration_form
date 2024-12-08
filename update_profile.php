<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in before updating profile
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to update your profile.";
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Update user profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : $user['name'];
    $date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : $user['date_of_birth'];
    $contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : $user['contact_number'];

    // Only update fields that have changed
    $updates = [];

    if ($name != $user['name']) {
        $updates[] = "name='$name'";
    }
    if ($date_of_birth != $user['date_of_birth']) {
        $updates[] = "date_of_birth='$date_of_birth'";
    }
    if ($contact_number != $user['contact_number']) {
        $updates[] = "contact_number='$contact_number'";
    }

    if (!empty($updates)) {
        $sql_update = "UPDATE users SET " . implode(", ", $updates) . " WHERE id='$user_id'";

        if ($conn->query($sql_update) === TRUE) {
            echo "Profile updated successfully!";
            header("Location: profile.php");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        $no_changes = true;
    }
}

$conn->close();

// Function to calculate age from date of birth
function calculate_age($dob) {
    $dob = new DateTime($dob);
    $now = new DateTime();
    $interval = $now->diff($dob);
    return $interval->y; // Return the age in years
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        /* General Body Style */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff5f6d, #ffc3a0);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        /* Container for content */
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }

        /* Heading */
        .container h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        /* Input Group */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            font-size: 1rem;
            color: #fff;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            margin-top: 5px;
        }

        .input-group input[type="date"] {
            background-color: #f4f4f4;
        }

        .submit-btn {
            background-color: #ff5f6d;
            color: white;
            padding: 16px 35px;
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #e04d4d;
        }

        /* Back Button */
        .back-btn {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        /* Message */
        .message {
            color: black; /* Change text color to black */
            background-color: #f8d7da; /* Light red background */
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Update Profile</h2>
        <form action="update_profile.php" method="POST">
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" required>
            </div>

            <div class="input-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo isset($user['date_of_birth']) ? htmlspecialchars($user['date_of_birth']) : ''; ?>" required>
            </div>

            <div class="input-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" value="<?php echo isset($user['contact_number']) ? htmlspecialchars($user['contact_number']) : ''; ?>" required>
            </div>

            <div class="input-group">
                <button type="submit" class="submit-btn">Update Profile</button>
            </div>
        </form>

        <div class="input-group">
            <button onclick="window.location.href='profile.php'" class="back-btn">Back to Profile</button>
        </div>

        <?php if (isset($no_changes) && $no_changes): ?>
            <div class="message">No changes made.</div>
        <?php endif; ?>

        <?php if (isset($user['date_of_birth'])): ?>
            <p><strong>Current Age:</strong> <?php echo calculate_age($user['date_of_birth']); ?> years</p>
        <?php endif; ?>
    </div>

</body>
</html>
