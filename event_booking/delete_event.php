<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
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

// Handle event deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = intval($_POST['event_id']);

    // Delete from bookings table
    $delete_bookings_sql = "DELETE FROM bookings WHERE event_id = ?";
    $delete_bookings_stmt = $conn->prepare($delete_bookings_sql);
    $delete_bookings_stmt->bind_param("i", $event_id);
    $delete_bookings_stmt->execute();

    // Delete from events table
    $delete_event_sql = "DELETE FROM events WHERE id = ?";
    $delete_event_stmt = $conn->prepare($delete_event_sql);
    $delete_event_stmt->bind_param("i", $event_id);

    if ($delete_event_stmt->execute()) {
        $_SESSION['message'] = "Event deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting event: " . $conn->error;
    }

    $delete_event_stmt->close();
    $delete_bookings_stmt->close();

    header("Location: delete_event.php");
    exit();
}

// Fetch all events
$sql = "SELECT * FROM events";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Events</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 5px 10px;
            background-color: #FF0000;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #CC0000;
        }
        .message {
            color: green;
            font-weight: bold;
        }
        .back-btn {
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    text-decoration: none;
    position: absolute;
    top: 20px;   /* Adjust distance from the top */
    right: 20px; /* Adjust distance from the right */
}

.back-btn:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>


    <!-- Display success/error message -->
    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Tickets Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo date("F j, Y, g:i a", strtotime($row['date'])); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td>₹<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['availability']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No events found.</p>
    <?php endif; ?>
</body>
</html>
