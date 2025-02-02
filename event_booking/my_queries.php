<?php
session_start();

// Check if the user is logged in
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

// Fetch user queries
$user_id = $_SESSION['user_id'];
$sql = "SELECT query_text, created_at, response, responded_at FROM user_queries WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Queries</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            position: relative;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .no-queries {
            text-align: center;
            color: #555;
            margin-top: 20px;
        }
        .top-buttons {
            position: absolute;
            top: 6px;
            right: 20px;
        }
        .top-buttons button {
            padding: 10px 20px;
            margin-left: 10px;
            background-color: #007BFF;
            color: white;
            border: 10px;
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
    <h1>My Queries</h1>

    <!-- Top-right buttons -->
    <div class="top-buttons">
        <button><a href="index.php">Back to Home</a></button>
        <button><a href="request.php">Query</a></button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Query</th>
                <th>Date Submitted</th>
                <th>Response</th>
                <th>Date Responded</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['query_text']); ?></td>
                        <td><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></td>
                        <td><?php echo $row['response'] ? htmlspecialchars($row['response']) : 'No response yet'; ?></td>
                        <td><?php echo $row['responded_at'] ? date("F j, Y, g:i a", strtotime($row['responded_at'])) : 'N/A'; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="no-queries">You have not submitted any queries yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
