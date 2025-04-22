<?php
// db_connection.php

$servername = "sql12.freesqldatabase.com";
$username = "sql12728443";
$password = "cbS3jn814c";
$dbname = "sql12728443";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>