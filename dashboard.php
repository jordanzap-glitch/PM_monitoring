<?php
include 'db_connection.php';
include 'includes/session.php';
// Check if the user is logged in
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            background-color: #e0f2e9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .box:hover {
            transform: scale(1.05);
        }

        .box h2 {
            color: #2e7d32;
            margin: 0 0 10px;
        }

        .box p {
            color: #555;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="container">
        <?php
        // Get the username and extract the part before '@'
        $username = htmlspecialchars($_SESSION['username']);
        $username_parts = explode('@', $username);
        $display_name = $username_parts[0]; // Get the first part of the username
        ?>
        <h1>Welcome to Your Dashboard, <?php echo $display_name; ?>!</h1>

        <div class="dashboard">
            <div class="box">
                <h2>Profile</h2>
                <p>View and edit your profile information.</p>
            </div>
            <div class="box">
                <h2>Messages</h2>
                <p>Check your messages and notifications.</p>
            </div>
            <div class="box">
                <h2>Settings</h2>
                <p>Manage your account settings.</p>
            </div>
            <div class="box">
                <h2>Reports</h2>
                <p>View your activity reports.</p>
            </div>
            <div class="box">
                <h2>Support</h2>
                <p>Get help and support.</p>
            </div>
            <div class="box">
                <h2>Logout</h2>
                <p>Sign out of your account.</p>
            </div>
        </div>
    </div>

</body>
</html>