<?php
// Sample database connection (update credentials as necessary)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'event_booking';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events from the database
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Listing</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background: #007BFF;
            color: white;
        }

        table thead th {
            padding: 10px;
            text-align: left;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fc;
        }

        table tbody td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #555;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            table thead {
                display: none;
            }

            table tbody tr {
                display: block;
                margin-bottom: 15px;
            }

            table tbody td {
                display: block;
                text-align: right;
                padding: 10px;
                position: relative;
            }

            table tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                text-align: left;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Event Listing</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Event ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Event ID"><?php echo $row['id']; ?></td>
                            <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td data-label="Date"><?php echo $row['date']; ?></td>
                            <td data-label="Location"><?php echo $row['location']; ?></td>
                            <td data-label="Description"><?php echo htmlspecialchars($row['description']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">No events found.</div>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
</body>
</html>
