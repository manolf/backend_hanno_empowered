<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; // Change this to your database username
$password = "m2d2023"; // Change this to your database password
$dbname = "Advent2023"; // Change this to your database name

// database connection
$dsn = 'mysql:host=localhost;dbname=Advent2023';
$username = 'root';
$password = 'm2d2023';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//$conn = new PDO($dsn, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM day
        JOIN tabata ON (day.easy  = tabata.tabataId)
        ORDER BY day.dayId"; 
        
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();
?>
