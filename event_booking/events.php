<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_booking";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'], $_POST['quantity'])) {
    $event_id = intval($_POST['event_id']);
    $quantity = intval($_POST['quantity']);
    $user_name = $_SESSION['name']; // Assuming user's name is stored in the session
    $user_email = $_SESSION['email']; // Assuming user's email is stored in the session

    $stmt = $conn->prepare("INSERT INTO bookings (event_id, name, email, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $event_id, $user_name, $user_email, $quantity);

    if ($stmt->execute()) {
        echo "<p>Booking successful!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Fetch events
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking</title>
</head>
<body>
    <h1>Available Events</h1>

    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                    <form action="" method="POST">
                        <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                        <label for="quantity">Tickets:</label>
                        <input type="number" name="quantity" min="1" max="<?php echo $row['availability']; ?>" required>
                        <button type="submit">Book Ticket</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No events available.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
