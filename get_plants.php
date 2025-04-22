<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include 'db_connection.php';

// Query to fetch all records regardless of is_active status
$sql = "SELECT SEQ_NUM, PLANT_NAME, is_active FROM TBL_PLANT_INFO";
$result = $conn->query($sql);

$plants = array();
$activePlants = array();
$totalPlants = 0;

if ($result->num_rows > 0) {
    // Loop through all records
    while ($row = $result->fetch_assoc()) {
        $totalPlants++; // Count all plants

        // Only add active plants to the plants array
        if ($row['is_active'] == 1) {
            $activePlants[] = $row;
        }
    }
}

// Return both active plants and total plant count as JSON
echo json_encode([
    'totalPlants' => $totalPlants,
    'plants' => $activePlants
]);

$conn->close();
?>
