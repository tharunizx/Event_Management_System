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

// Check session email
if (!isset($_SESSION['email'])) {
    die("Error: User email not found in session.");
}
$user_email = $_SESSION['email'];

// Fetch user bookings
$sql = "SELECT b.id, e.name AS event_name, e.date, e.location, b.quantity, b.booked_at
        FROM bookings b
        JOIN events e ON b.event_id = e.id
        WHERE b.email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query Preparation Error: " . $conn->error);
}

$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Page Header */
        h1 {
            text-align: center;
            margin: 20px 0;
            font-size: 28px;
            color: #007BFF;
        }

        /* Table Styling */
        table {
            width: 90%;
            max-width: 1000px;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background-color: #007BFF;
            color: white;
            font-size: 16px;
        }

        thead th {
            padding: 15px;
            text-align: left;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
        }

        tbody td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
            color: #555;
        }

        .top-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .top-buttons button {
            padding: 10px 20px;
            margin-left: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 20px; /* Makes the buttons curved */
        }

        .top-buttons button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <h1>My Bookings</h1>

    <!-- Top-right buttons -->
    <div class="top-buttons">
        <button><a href="index.php">Back to Home</a></button>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Tickets</th>
                    <th>Booked At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['booked_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
