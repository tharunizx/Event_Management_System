<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_booking";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $quantity = $_POST['quantity'];

    // Check availability
    $sql = "SELECT availability FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    if ($quantity > $event['availability']) {
        die("Not enough tickets available.");
    }

    // Insert booking
    $sql = "INSERT INTO bookings (event_id, name, email, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $event_id, $name, $email, $quantity);
    $stmt->execute();

    // Update availability
    $new_availability = $event['availability'] - $quantity;
    $sql = "UPDATE events SET availability = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_availability, $event_id);
    $stmt->execute();

    // Store success message
    $_SESSION['message'] = "Booking successful for $quantity tickets!";
    header("Location: index.php");
    exit();
}
?>
