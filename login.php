<?php
session_start();
include 'db_connection.php';

// Start the session

// Get POST data
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute SQL query to check both tables
$sql = "
    SELECT 'admin' as user_type, AdminUsername as username, Password, AdminFirstName as first_name, AdminLastName as last_name 
    FROM ADMIN_INFO 
    WHERE AdminUsername = ? AND Password = ?
    UNION
    SELECT 'user' as user_type, Username as username, Password, UserFirstName as first_name, UserLastName as last_name 
    FROM USER_INFO 
    WHERE Username = ? AND Password = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $password, $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Store the user's first name, last name, and username in the session
    $_SESSION['userFirstName'] = $row['first_name'];
    $_SESSION['userLastName'] = $row['last_name'];
    $_SESSION['username'] = $row['username']; // Store the username in the session
    
    if ($row['user_type'] == 'admin') {
        echo "Admin login successful! Welcome, " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . ".";
    } else {
        echo "User  login successful! Welcome, " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . ".";
    }
} else {
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>