<?php
session_start();

// Check if the user is logged in and is an admin
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

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'], $_POST['query_id'])) {
    $reply = $_POST['reply'];
    $query_id = $_POST['query_id'];
    $responded_at = date("Y-m-d H:i:s"); // Current timestamp

    // Update the user_queries table with the reply and responded_at
    $sql = "UPDATE user_queries SET response = ?, responded_at = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $reply, $responded_at, $query_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Reply submitted successfully!";
    } else {
        $_SESSION['message'] = "Error submitting reply: " . $conn->error;
    }

    $stmt->close();
}

// Fetch user queries
$sql = "SELECT user_queries.id, user_queries.query_text, user_queries.created_at, user_queries.response, user_queries.responded_at, users.email, users.username 
        FROM user_queries
        JOIN users ON user_queries.user_id = users.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Queries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .submit-button { background-color: #28a745; color: white; padding: 10px 20px; border: none; }
        .submit-button:hover { background-color: #218838; }
        .responded { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Query</th>
                <th>Date</th>
                <th>Response</th>
                <th>Reply</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['query_text']); ?></td>
                        <td><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></td>
                        <td><?php echo $row['response'] ? htmlspecialchars($row['response']) : 'No response yet'; ?></td>
                        <td>
                            <?php if ($row['response']): ?>
                                <span class="responded">Responded</span>
                            <?php else: ?>
                                <form method="POST">
                                    <input type="hidden" name="query_id" value="<?php echo $row['id']; ?>">
                                    <textarea name="reply" required placeholder="Type your reply here..."></textarea>
                                    <br>
                                    <button type="submit" class="submit-button">Submit</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No queries found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
