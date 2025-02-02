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

// Get event ID from query string
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    die("Event ID is required.");
}

$event_id = intval($_GET['event_id']);

// Fetch event details
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Event not found.");
}

$event = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    // Update event in the database
    $sql = "UPDATE events SET name = ?, date = ?, location = ?, price = ?, availability = ?, description = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdissi", $name, $date, $location, $price, $availability, $description, $image_url, $event_id);

    if ($stmt->execute()) {
        echo "<p>Event updated successfully!</p>";
    } else {
        echo "<p>Error updating event: " . $conn->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Modify Event</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
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
    <form method="POST" action="">
        <label for="name">Event Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>

        <label for="date">Date and Time:</label>
        <input type="datetime-local" id="date" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['date'])); ?>" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($event['price']); ?>" required>

        <label for="availability">Tickets Available:</label>
        <input type="number" id="availability" name="availability" value="<?php echo htmlspecialchars($event['availability']); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($event['description']); ?></textarea>

        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($event['image_url']); ?>">

        <button type="submit">Update Event</button>
    </form>
</body>
</html>
