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

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query_text'])) {
    $user_id = $_SESSION['user_id'];
    $query_text = trim($_POST['query_text']);

    if (!empty($query_text)) {
        // Store the query in the database
        $sql = "INSERT INTO user_queries (user_id, query_text) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $query_text);

        if ($stmt->execute()) {
            $message = "Your query has been submitted successfully!";
        } else {
            $message = "Error submitting your query: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "Please enter a valid query.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Query</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            height: 100px;
            resize: none;
        }
        button, a {
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        button:hover, a:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            font-weight: bold;
            text-align: center;
        }
        .option {
            text-align: center;
            margin-top: 20px;
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
    <h1>Submit Your Query</h1>
    <a href="index.php">
        <button class="back-btn">Back to Home</button>
    </a>
    <div class="container">
        <!-- Display success/error message -->
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="query_text">Enter your query or request:</label>
            <textarea id="query_text" name="query_text" placeholder="Enter your query here..." required></textarea>
            <button type="submit">Submit Query</button>
        </form>

        <div class="option">
            <p>Or</p>
            <a href="mailto:admin@example.com?subject=User%20Query" target="_blank">Send an Email</a>
        </div>
    </div>
</body>
</html>
