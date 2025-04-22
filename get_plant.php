<?php
// Include the database connection file
include 'db_connection.php';

// Query to fetch all plant data
$sql = "SELECT SEQ_NUM, PLANT_NAME FROM TBL_PLANT_INFO";
$result = $conn->query($sql);

$plants = array();

if ($result->num_rows > 0) {
    // Fetch each row and add it to the plants array
    while ($row = $result->fetch_assoc()) {
        $plants[] = $row;
    }
}

// Return the plants data as JSON
echo json_encode($plants);

$conn->close();
?>
