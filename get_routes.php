<?php
require 'db_connect.php';

if (isset($_GET['source']) && isset($_GET['destination'])) {
    $source = $_GET['source'];
    $destination = $_GET['destination'];

    // Get source coordinates
    $sql = "SELECT latitude, longitude FROM places WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $source);
    $stmt->execute();
    $stmt->bind_result($src_lat, $src_lng);
    $stmt->fetch();
    $stmt->close();

    // Get destination coordinates
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $destination);
    $stmt->execute();
    $stmt->bind_result($dest_lat, $dest_lng);
    $stmt->fetch();
    $stmt->close();

    if (!$src_lat || !$dest_lat) {
        echo json_encode(["error" => "Invalid source or destination"]);
        exit;
    }

    // Fetch routes using OpenRouteService
    $apiKey = "YOUR_OPENROUTINGSERVICE_API_KEY";
    $url = "https://api.openrouteservice.org/v2/directions/driving-car?api_key=$apiKey&start=$src_lng,$src_lat&end=$dest_lng,$dest_lat";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!$data || !isset($data['routes'])) {
        echo json_encode(["error" => "Failed to fetch routes"]);
        exit;
    }

    $routes = $data['routes'];
    echo json_encode([
        "shortest_route" => $routes[0]['geometry'],
        "shortest_time" => round($routes[0]['duration'] / 60) . " mins"
    ]);
} else {
    echo json_encode(["error" => "Missing parameters"]);
}
?>
