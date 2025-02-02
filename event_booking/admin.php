<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page with Frames</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Roboto', sans-serif;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background: #fdfdfd; /* White/Cream Background */
            color: #333;
            padding: 20px;
            height: 100%;
            overflow-y: auto;
            position: fixed;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            border-right: 1px solid #ddd;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: bold;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar h2 i {
            margin-right: 8px;
            color: #007BFF; /* Blue icon color */
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #333;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 10px 15px;
            border: 1px solid #007BFF; /* Blue outline */
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar ul li a i {
            margin-right: 10px;
            color: #007BFF; /* Blue icon color */
        }

        .sidebar ul li a:hover {
            background: #007BFF; /* Blue hover */
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-footer {
            margin-top: auto;
            text-align: center;
            font-size: 14px;
            color: #666;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .sidebar-footer a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        .sidebar-footer a:hover {
            text-decoration: underline;
        }

        /* Main Content Area */
        .main-frame {
            margin-left: 250px;
            width: calc(100% - 250px);
            height: 100vh;
            border: none;
            background: #f8f9fc;
        }

        /* Responsive Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-frame {
                margin-left: 200px;
                width: calc(100% - 200px);
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-frame {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-tools"></i> Admin Menu</h2>
        <ul>
            <li><a href="admin_add_event.php" target="mainFrame"><i class="fas fa-plus-circle"></i> Add Event</a></li>
            <li><a href="event_modify.php" target="mainFrame"><i class="fas fa-edit"></i> Modify Event</a></li>
            <li><a href="event_listing.php" target="mainFrame"><i class="fas fa-list"></i> Event List</a></li>
            <li><a href="delete_event.php" target="mainFrame"><i class="fas fa-trash"></i> Delete Event</a></li>
            <li><a href="requests.php" target="mainFrame"><i class="fas fa-question-circle"></i> Queries</a></li>
        </ul>
        <div class="sidebar-footer">
            <p>&copy; 2025 Admin Panel</p>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <!-- Default frame source set to event_listing.php -->
    <iframe name="mainFrame" class="main-frame" src="event_listing.php"></iframe>
</body>
</html>
