<?php
// Include the database connection file
include 'db_connection.php';

$plant_id = $_GET['id'];

// Fetch plant details from the database
$sql = "SELECT * FROM TBL_PLANT_INFO WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $plant = $result->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Plant</title>
    </head>
    <body>
        <h1><?php echo $plant['PLANT_NAME']; ?></h1>
        <p><strong>Scientific Name:</strong> <?php echo $plant['SCIENTIFIC_NAME']; ?></p>
        <p><strong>Description:</strong> <?php echo $plant['DESCRIPTION']; ?></p>
        <p><strong>Height:</strong> <?php echo $plant['HEIGHT']; ?> cm</p>
        <p><strong>Date Added:</strong> <?php echo $plant['DATE_ADDED']; ?></p>
        <p><strong>Sponsor:</strong> <?php echo $plant['SPONSOR_FIRST_NAME'] . ' ' . $plant['SPONSOR_LAST_NAME']; ?></p>
    </body>
    </html>
    <?php
} else {
    echo "Plant not found.";
}

$stmt->close();
$conn->close();
?>
