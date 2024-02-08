<?php
// Include your database connection configuration
include 'db_config.php'; // Replace with your actual database configuration

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve data from the 'sign_info' table
$query = "SELECT * FROM sign_info 
         LEFT JOIN road_info ON sign_info.road_id = road_info.road_id
         LEFT JOIN district_info ON sign_info.district_id = district_info.district_id 
         LEFT JOIN sign_type ON sign_info.sign_type = sign_type.sign_type_id
         WHERE sign_info.province_id = 1";
$result = $conn->query($query);

// Check if there are rows in the result
if ($result->num_rows > 0) {
    // Fetch the data and convert it to an associative array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Output the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // No data found
    echo json_encode(array('message' => 'No data found'));
}

// Close the database connection
$conn->close();
?>
