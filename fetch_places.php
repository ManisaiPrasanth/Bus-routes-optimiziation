<?php
header('Content-Type: application/json');
$host = "localhost";  // Change if needed
$user = "root";       // Default XAMPP user
$pass = "";           // Default XAMPP password (leave empty)
$dbname = "madurai_routes";  // Change to your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

$sql = "SELECT name, latitude, longitude FROM places";
$result = $conn->query($sql);

$places = [];
while ($row = $result->fetch_assoc()) {
    $places[] = $row;
}

$conn->close();
echo json_encode($places);
?>
