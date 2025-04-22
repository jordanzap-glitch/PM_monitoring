<?php
// update_db_connection.php

// Hardcoded admin credentials (for demonstration purposes)
$admin_credentials = [
    'username' => 'superadmin',
    'password' => 'superadmin'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];

    // Validate admin credentials
    if ($admin_username === $admin_credentials['username'] && $admin_password === $admin_credentials['password']) {
        $servername = $_POST['servername'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $dbname = $_POST['dbname'];

        // Construct the new content for db_connection.php
        $newConfig = <<<PHP
<?php
// db_connection.php

\$servername = "$servername";
\$username = "$username";
\$password = "$password";
\$dbname = "$dbname";

// Create connection
\$conn = new mysqli(\$servername, \$username, \$password, \$dbname);

// Check connection
if (\$conn->connect_error) {
    die("Connection failed: " . \$conn->connect_error);
}
?>
PHP;

        // Write the new configuration to db_connection.php
        if (file_put_contents('db_connection.php', $newConfig)) {
            echo "Configuration updated successfully!";
        } else {
            echo "Failed to update configuration.";
        }
    } else {
        // Invalid admin credentials
        echo "Invalid admin username or password.";
    }
} else {
    echo "Invalid request method.";
}
?>
