<?php
include 'config.php';

$sql = "SELECT * FROM places";
$result = $conn->query($sql);

$places = [];
while ($row = $result->fetch_assoc()) {
    $places[] = $row;
}

echo json_encode($places);
?>
