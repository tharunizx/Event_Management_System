<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_booking";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'] ?? '';
$query = $conn->real_escape_string($query);

// Query to fetch only upcoming events (after the current date)
$sql = $query
    ? "SELECT * FROM events WHERE (name LIKE '%$query%' OR description LIKE '%$query%' OR location LIKE '%$query%') AND date > CURDATE() ORDER BY date ASC"
    : "SELECT * FROM events WHERE date > CURDATE() ORDER BY date ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        ?>
        <li class="event-item">
            <h2><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <img src="<?php echo htmlspecialchars($row['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="event-details">
                <p><strong>Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($row['date'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Price:</strong> ₹<?php echo number_format($row['price'], 2); ?></p>
                <p><strong>Availability:</strong> <?php echo htmlspecialchars($row['availability'], ENT_QUOTES, 'UTF-8'); ?> tickets left</p>
            </div>
            <button onclick="location.href='?event_id=<?php echo $row['id']; ?>'">View More</button>
        </li>
        <?php
    endwhile;
else:
    echo '<p>No upcoming events found.</p>';
endif;

$conn->close();
?>
