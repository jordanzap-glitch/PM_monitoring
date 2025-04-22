<?php
// Include the database connection file
include 'db_connection.php';

// Get plant_id from query parameters
$plant_id = $_GET['plant_id'];

// Prepare SQL statement to fetch plant details
$sql = "SELECT SEQ_NUM, PLANT_NAME, SCIENTIFIC_NAME, DATE_ADDED, HEIGHT, DESCRIPTION, SPONSOR_FIRST_NAME, SPONSOR_MIDDLE_NAME, SPONSOR_LAST_NAME, PLANT_PIC FROM TBL_PLANT_INFO WHERE SEQ_NUM = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch plant data
    $plant = $result->fetch_assoc();
    // Convert binary data to base64 to display the image
    $plant['PLANT_PIC'] = base64_encode($plant['PLANT_PIC']);
    echo json_encode($plant);
} else {
    echo json_encode(["error" => "No plant found with the provided ID."]);
}

$stmt->close();
$conn->close();
?>
