<?php
include 'db_connection.php';
include 'includes/session.php';
// Start the session

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

// Query to get the number of plants added over time
$query_dates = "
    SELECT DATE(DATE_ADDED) as date_added, COUNT(*) as count
    FROM TBL_PLANT_INFO
    WHERE SPONSOR_FIRST_NAME = ? AND SPONSOR_LAST_NAME = ?
    GROUP BY DATE(DATE_ADDED)
    ORDER BY DATE(DATE_ADDED)";
$stmt_dates = $conn->prepare($query_dates);
$stmt_dates->bind_param("ss", $first_name, $last_name);
$stmt_dates->execute();
$result_dates = $stmt_dates->get_result();

$dates = [];
$counts = [];
while ($row_dates = $result_dates->fetch_assoc()) {
    $dates[] = $row_dates['date_added'];
    $counts[] = $row_dates['count'];
}

// Query to get plant names, scientific names, and their active status
$query_plants = "
    SELECT PLANT_NAME, SCIENTIFIC_NAME, is_active 
    FROM TBL_PLANT_INFO 
    WHERE SPONSOR_FIRST_NAME = ? AND SPONSOR_LAST_NAME = ?";
$stmt_plants = $conn->prepare($query_plants);
$stmt_plants->bind_param("ss", $first_name, $last_name);
$stmt_plants->execute();
$result_plants = $stmt_plants->get_result();

$plants = [];
while ($row_plants = $result_plants->fetch_assoc()) {
    $plants[] = $row_plants;
}

// Query to get the tallest plant
$query_tallest = "
    SELECT PLANT_NAME, HEIGHT 
    FROM TBL_PLANT_INFO 
    WHERE SPONSOR_FIRST_NAME = ? AND SPONSOR_LAST_NAME = ?
    ORDER BY HEIGHT DESC 
    LIMIT 1";
$stmt_tallest = $conn->prepare($query_tallest);
$stmt_tallest->bind_param("ss", $first_name, $last_name);
$stmt_tallest->execute();
$result_tallest = $stmt_tallest->get_result();
$tallest_plant = $result_tallest->fetch_assoc();

$stmt->close();
$stmt_dates->close();
$stmt_plants->close();
$stmt_tallest->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-green-100">

    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-center text-2xl text-green-700 mb-6">Welcome to Your Dashboard, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>!</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <a href="pages/viewlistalive.php" class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-center shadow hover:shadow-lg transition-transform transform hover:scale-105">
                <h2 class="text-green-700 text-xl">Alive Plants/Trees</h2>
                <p class="text-gray-600">You have <?php echo $alive_count; ?> Alive Plants/Trees.</p>
            </a>
            <a href="pages/viewlistdead.php" class=" bg-gray-100 border border-gray-300 rounded-lg p-4 text-center shadow hover:shadow-lg transition-transform transform hover:scale-105">
                <h2 class="text-green-700 text-xl">Dead Plants/Trees</h2>
                <p class="text-gray-600">You have <?php echo $dead_count; ?> Dead Plants/Trees.</p>
            </a>
        </div>

        <div class="flex flex-col md:flex-row justify-between">
            <div class="w-full md:w-1/2 mb-6 md:mb-0">
                <div class="bg-white rounded-lg shadow p-4">
                    
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="p-2 bg-gray-100 text-center">Plant/Tree Status</th>
                                <th class="p-2 bg-gray-100 text-center">Scientific Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($plants as $plant): ?>
                                <tr>
                                    <td class="p-2 border-b border-gray-300 text-center">
                                        <?php echo htmlspecialchars($plant['PLANT_NAME']); ?>
                                        <?php if ($plant['is_active'] == 1): ?>
                                            <img src="Resources/Green_Arrow_Up.png" alt="Growing Arrow" class="inline-block w-5 h-5 ml-2" />
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-2 border-b border-gray-300 text-center"><?php echo htmlspecialchars($plant['SCIENTIFIC_NAME']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="w-full md:w-1/5 flex flex-col items-center mb-6">
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <h2 class="text-lg font-semibold mb-2"></h2>
                    <div class="text-lg font-semibold mb-2"> DID YOU KNOW:</div>
                    <div class="mt-2">Your Donated Tallest Plant/Tree is the  <?php echo htmlspecialchars($tallest_plant['PLANT_NAME']); ?> (at the Height: <?php echo htmlspecialchars($tallest_plant['HEIGHT']); ?> cm)</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <h2 class="text-lg font-semibold mb-2"></h2>
                    <canvas id="barChart" width="300" height="300"></canvas>
                </div>
            </div>
            <div class="w-full md:w-1/4 flex flex-col items-center">
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <h2 class="text-lg font-semibold mb-2"></h2>
                    <canvas id="plantChart" width="300" height="300"></canvas>
                </div>
             
            </div>
        </div>

        <script>
            const ctxPie = document.getElementById('plantChart').getContext('2d');
            const aliveCount = <?php echo $alive_count; ?>;
            const deadCount = <?php echo $dead_count; ?>;
            const totalCount = aliveCount + deadCount;

            const plantChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Alive Plants', 'Dead Plants'],
                    datasets: [{
                        label: 'Plant Status',
                        data: [aliveCount, deadCount],
                        backgroundColor: [
                            'rgba(46, 125, 50, 0.6)',
                            'rgba(255, 0, 0, 0.6)'
                        ],
                        borderColor: [
                            'rgba(46, 125, 50, 1)',
                            'rgba(255, 0, 0, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Percentage of Alive and Dead Plants'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const percentage = ((tooltipItem.raw / totalCount) * 100).toFixed(2);
                                    return tooltipItem.label + ': ' + percentage + '%';
                                }
                            }
                        }
                    }
                }
            });

            const ctxBar = document.getElementById('barChart').getContext('2d');
            const dates = <?php echo json_encode($dates); ?>;
            const counts = <?php echo json_encode($counts); ?>;

            const barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Plants Added Over Time',
                        data: counts,
                        backgroundColor: 'rgba(46, 125, 50, 0.6)',
                        borderColor: 'rgba(46, 125, 50, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Number of Plants Added Over Time'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Plants'
                            }
                        }
                    }
                }
            });
        </script>
    </body>
</html>