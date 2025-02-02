<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_booking";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID from the URL
$event_id = $_GET['event_id'] ?? null;

if (!$event_id) {
    die("Invalid event ID.");
}

// Fetch event details
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket - <?php echo htmlspecialchars($event['name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }
        h1 {
            color: #007BFF;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #0056b3;
        }
        .footer {
    background-color: #333;
    color: white;
    padding: 20px;
    text-align: center;
    margin-top: 40px;
}
.footer {
    background-color: #007BFF;
    color: white;
    padding: 20px;
    text-align: center;
    margin-top: 40px;
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 20px 0;
}

.contact-info, .social-media {
    flex: 1;
    min-width: 200px;
    margin: 10px;
}

.footer-content h3 {
    font-size: 20px;
    margin-bottom: 10px;
}

.footer-content p, .footer-content a {
    font-size: 14px;
    color: white;
    margin: 5px 0;
    text-decoration: none;
}

.footer-content a:hover {
    text-decoration: underline;
}

.footer-bottom {
    margin-top: 20px;
    font-size: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 10px;
}
/* Footer Styling */
.footer {
    background-color: #007BFF;
    color: white;
    padding: 20px;
    text-align: center;
    margin-top: 40px;
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 20px 0;
}

.contact-info, .social-media {
    flex: 1;
    min-width: 200px;
    margin: 10px;
}

.footer-content h3 {
    font-size: 20px;
    margin-bottom: 10px;
}

.footer-content p {
    font-size: 14px;
    margin: 5px 0;
}

.social-media a {
    display: inline-block;
    margin: 5px;
}

.social-media img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    transition: transform 0.3s ease;
}

.social-media img:hover {
    transform: scale(1.2);
}

.footer-bottom {
    margin-top: 20px;
    font-size: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 10px;
}
/* Footer Styling */
.footer {
    background-color: #333;
    color: white;
    padding: 20px;
    text-align: center;
    margin-top: 40px;
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 20px 0;
}

.contact-info, .social-media {
    flex: 1;
    min-width: 200px;
    margin: 10px;
}

.footer-content h3 {
    font-size: 20px;
    margin-bottom: 10px;
}

.footer-content p {
    font-size: 14px;
    margin: 5px 0;
}

.social-media {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
}

.social-media a {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    background-color: #007BFF;
    color: white;
    border-radius: 50%;
    font-size: 24px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.social-media a:hover {
    background-color: #0056b3;
    transform: scale(1.1);
}

.footer-bottom {
    margin-top: 20px;
    font-size: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 10px;
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 20px 0;
}

.contact-info, .social-media {
    flex: 1;
    min-width: 200px;
    margin: 10px;
}

.footer-content h3 {
    font-size: 20px;
    margin-bottom: 10px;
}

.footer-content p {
    font-size: 14px;
    margin: 5px 0;
}

.social-media {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
}

.social-media a {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    background-color: #007BFF;
    color: white;
    border-radius: 50%;
    font-size: 24px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.social-media a:hover {
    background-color: #0056b3;
    transform: scale(1.1);
}

.footer-bottom {
    margin-top: 20px;
    font-size: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 10px;
}

    </style>
</head>
<body>
    <h1>Book Ticket for <?php echo htmlspecialchars($event['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <p><strong>Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($event['date'])); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Price:</strong> ₹<?php echo number_format($event['price'], 2); ?></p>

    <form action="confirm_booking.php" method="post">
        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Your Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="quantity">Number of Tickets:</label>
        <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $event['availability']; ?>" required>
        <button type="submit">Confirm Booking</button>
    </form>

</body>
</html>
