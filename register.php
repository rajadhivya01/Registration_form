<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $date_of_birth = $_POST['date_of_birth'];
    $contact_number = $_POST['contact_number'];

    // Sanitize and escape input values to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
    $contact_number = mysqli_real_escape_string($conn, $contact_number);

    // SQL query to insert the data into the database
    $sql = "INSERT INTO users (name, username, password, date_of_birth, contact_number) 
            VALUES ('$name', '$username', '$password', '$date_of_birth', '$contact_number')";

    // Execute the query and check for errors
    if ($conn->query($sql) === TRUE) {
        // Registration successful, redirect to login page
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
