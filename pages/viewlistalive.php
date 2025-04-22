<?php
error_reporting(E_ALL);
include '../db_connection.php';
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

// Query to get the alive plants for the specific user, including SEQ_NUM
$query = "SELECT PLANT_NAME, SEQ_NUM FROM TBL_PLANT_INFO WHERE SPONSOR_FIRST_NAME = ? AND SPONSOR_LAST_NAME = ? AND is_active = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $first_name, $last_name);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the plant data
$plants = [];
while ($row = $result->fetch_assoc()) {
    $plants[] = $row; // Store the entire row to access both PLANT_NAME and SEQ_NUM
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tree List - Green Eco Monitoring</title>
    <style>
        body {
            background-color: #e0f2e9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h1 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
        }

        .logout-btn img {
            width: 40px;
            height: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2e7d32;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .view-btn {
            padding: 10px 14px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #1b5e20;
        }

        .survival-rate {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: #2e7d32;
            font-size: 18px;
            font-weight: bold;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 10px;
            width: 70%;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .search-btn {
            padding: 10px 20px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .search-btn:hover {
            background-color: #1b5e20;
        }
    </style>
</head>

<body>
 <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="header">
            <h1>List of Alive Trees</h1>
            <a href="../dashboard.php" class="logout-btn">
                <img src="../Resources/logout.png" alt="Logout">
            </a>
        </div>

        <div class="search-container">
            <input type="text" id="searchSponsor" class="search-input" placeholder="Search by Sponsor's Name">
            <button class="search-btn" onclick="searchBySponsor()">Search</button>
        </div>

        <table id="plantTable">
            <thead>
                <tr>
                    <th>Tree Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plants as $plant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($plant['PLANT_NAME']); ?></td>
                    <td><button class="view-btn" onclick="viewPlant('<?php echo htmlspecialchars($plant['SEQ_NUM']); ?>')">View</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="survival-rate" id="survivalRate">
            <!-- Survival rate information can be displayed here -->
        </div>
    </div>

    <script>
        function searchBySponsor() {
            const input = document.getElementById('searchSponsor').value.toLowerCase();
            const rows = document.querySelectorAll('#plantTable tbody tr');
            rows.forEach(row => {
                const treeName = row.cells[0].textContent.toLowerCase();
                row.style.display = treeName.includes(input) ? '' : 'none';
            });
        }

        function viewPlant(seqNum) {
            // Redirect to plant_detail.html with the SEQ_NUM as a query parameter
            window.location.href = '../plant_detail.html?plant_id=' + encodeURIComponent(seqNum);
        }
    </script>
</body>
</html>