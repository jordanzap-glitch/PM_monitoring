<?php
// Include the database connection file
include 'db_connection.php';

// Get POST data
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute SQL query to check both tables
$sql = "
    SELECT 'admin' as user_type, AdminUsername as username, Password 
    FROM ADMIN_INFO 
    WHERE AdminUsername = ? AND Password = ?
    UNION
    SELECT 'user' as user_type, Username as username, Password 
    FROM USER_INFO 
    WHERE Username = ? AND Password = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $password, $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['user_type'] == 'admin') {
        echo "Admin login successful!";
    } else {
        echo "User login successful!";
    }
} else {
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>
