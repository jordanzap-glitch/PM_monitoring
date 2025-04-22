<?php
include 'db_connection.php';
include 'includes/session.php';
// Start the session

// Check if the user is logged in
if (!isset($_SESSION['userFirstName']) || !isset($_SESSION['userLastName'])) {
    // Redirect to login page or show an error
    header("Location: login.php");
    exit();
}

// Get the user's first name and last name from the session
$first_name = $_SESSION['userFirstName'];
$last_name = $_SESSION['userLastName'];

// Query to count the number of alive and dead plants for the specific user
$query = "
    SELECT 
        COUNT(CASE WHEN is_active = 1 THEN 1 END) as alive_count,
        COUNT(CASE WHEN is_active = 0 THEN 1 END) as dead_count
    FROM TBL_PLANT_INFO 
    WHERE SPONSOR_FIRST_NAME = ? AND SPONSOR_LAST_NAME = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $first_name, $last_name);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$alive_count = $row['alive_count'];
$dead_count = $row['dead_count'];

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
            text-decoration: none; /* Remove underline from link */
            color: inherit; /* Inherit text color */
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
        <h1>Welcome to Your Dashboard, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>!</h1>

        <div class="dashboard">
            <a href="pages/viewlistalive.php" class="box">
                <h2>Alive Plants/Trees</h2>
                <p>You have <?php echo $alive_count; ?> Alive Plants/Trees.</p>
            </a>
            <a href="pages/viewlistdead.php" class="box">
                <h2>Dead Plants/Trees</h2>
                <p>You have <?php echo $dead_count; ?> Dead Plants/Trees.</p>
            </a>
        </div>
    </div>

</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>