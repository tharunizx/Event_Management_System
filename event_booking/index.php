<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_booking";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a specific event ID is passed in the URL
$eventId = $_GET['event_id'] ?? null;

if ($eventId) {
    // Fetch the specific event details
    $eventId = $conn->real_escape_string($eventId);
    $sql = "SELECT * FROM events WHERE id = $eventId";
    $result = $conn->query($sql);
    $event = $result->fetch_assoc();
} else {
    // Fetch only future events
    $currentDate = date('Y-m-d H:i:s'); // Get the current date and time
    $sql = "SELECT * FROM events WHERE date > '$currentDate' ORDER BY date ASC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
    /* ... existing styles ... */

    /* Adjustments for Smaller Screens */
    @media (max-width: 768px) {
    
        .header {
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .header h1 {
            font-size: 20px;
        }

        .header nav button {
            padding: 6px 12px;
            font-size: 12px;
        }

        #search-bar {
            width: 90%; /* Use a percentage for smaller screens */
        }

        .event-list {
            flex-direction: column;
            gap: 15px;
        }

        .event-item {
            width: 90%; /* Use percentages to prevent overflow */
        }

        .event-detail {
            flex-direction: column;
            align-items: center;
        }

        .event-detail img {
            width: 90%; /* Ensure images don't overflow */
            max-width: 300px;
        }

        .event-detail-content {
            text-align: center;
            margin-top: 20px;
        }

        .footer-content {
            flex-direction: column;
            align-items: center;
        }

        .footer-content h3 {
            text-align: center;
        }

        .contact-info, .social-media {
            flex: unset;
            width: 90%; /* Adjust to fit smaller screens */
            text-align: center;
        }

        .social-media {
            justify-content: center;
        }
    }
</style>

    <style>
       /* General Body Styling */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

/* Header Styling */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background-color: #007BFF;
    color: white;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.header h1 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
}

.header nav {
    display: flex;
    gap: 10px;
}

.header nav form, .header nav a {
    margin: 0;
}

.header nav button, .header nav a {
    padding: 8px 16px;
    background-color: white;
    color: #007BFF;
    border: 1px solid white;
    border-radius: 5px;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header nav button:hover, .header nav a:hover {
    background-color: #0056b3;
    color: white;
}

/* Main Content */
h1 {
    text-align: center;
    margin: 40px 0 20px;
    color: #333;
    font-size: 28px;
}

/* Event List */
.event-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 0;
    margin: 20px;
}

.event-item {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 300px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-item:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
}

.event-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.event-details {
    padding: 15px;
    text-align: left;
}

.event-details p {
    margin: 5px 0;
    color: #555;
}

.event-item h2 {
    margin: 0;
    font-size: 20px;
    color: #007BFF;
}

.event-item button {
    margin: 10px 0 20px;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.event-item button:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }

    .event-list {
        flex-direction: column;
        align-items: center;
    }
}
.search-form {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.search-form input {
    padding: 10px;
    width: 300px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.search-form button {
    padding: 10px 15px;
    margin-left: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.search-form button:hover {
    background-color: #0056b3;
}
.event-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 0;
    margin: 20px;
}
#search-bar {
    width: 100%;
    height: 10px;
    padding: 10px;
    margin: 10px 0;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.event-detail {
    text-align: center;
    margin: 40px;
}

.event-detail img {
    width: 100%;
    max-width: 600px;
    height: auto;
    margin: 20px 0;
}

.event-detail button {
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.event-detail button:hover {
    background-color: #0056b3;
}
.event-detail {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 20px;
    margin: 40px;
    text-align: left;
}

.event-detail img {
    width: 50%;
    max-width: 400px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.event-detail-content {
    flex: 1;
}

.event-detail-content p {
    margin: 10px 0;
    font-size: 16px;
    color: #555;
}

.event-detail-content button {
    margin-top: auto;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.event-detail-content button:hover {
    background-color: #0056b3;
}
.header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #007BFF;
            padding: 10px 20px;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        /* Profile Section */
        .profile {
            display: flex;
            align-items: center;
            position: relative;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .profile-name {
            font-size: 16px;
            font-weight: bold;
        }

        /* Create the 3 dots using CSS */
        .dots-menu {
            width: 20px;
            height: 20px;
            margin-left: 10px;
            cursor: pointer;
            position: relative;
        }

        .dots-menu::before, .dots-menu::after, .dots-menu div {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            margin: 3px auto;
            background-color: #fff;
            border-radius: 50%;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            z-index: 1000;
        }

        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .dropdown-menu a:hover {
            background-color: #f4f4f9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 18px;
            }

            .profile img {
                width: 35px;
                height: 35px;
            }

            .profile-name {
                font-size: 14px;
            }
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
/* Gear Icon */
/* Gear Icon */
.gear-icon {
    font-size: 17px;
    cursor: pointer;
    margin-left: 10px;
    color: white;  /* Set gear color to white */
}

.gear-icon:hover {
    color: white;  /* Ensure the color remains white on hover */
}
.event-item {
    display: flex;
    flex-direction: column; /* Stack content vertically */
    justify-content: space-between; /* Space out content within the card */
    width: 300px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-item button {
    margin: 10px auto 20px; /* Center align button */
    padding: 8px 16px; /* Add space around the text */
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: auto; /* Ensure width matches the content */
    display: inline-block; /* Prevent full-width stretch */
    text-align: center;
    max-width: fit-content; /* Ensure it stays compact */
}





    </style>
</head>
<body>
    <div class="header">
    <h1 style="color: white; font-size: 28px;"><b>Event Booking System</b></h1>
        <nav>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <div class="profile">
            <!-- Check if user has a profile photo, else use a default online photo -->
            <img src="<?php echo isset($_SESSION['profile_photo']) && !empty($_SESSION['profile_photo']) ? $_SESSION['profile_photo'] : 'https://www.w3schools.com/w3images/avatar2.png'; ?>" alt="Profile Photo">
            <span class="profile-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>

            <!-- Gear Icon -->
            <div class="gear-icon" onclick="toggleMenu()"><i class="fa-solid fa-gear"></i></div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php">Admin</a>
                    <?php else: ?>
                        <a href="my_bookings.php">My Bookings</a>
                        <a href="my_queries.php">My Queries</a>

                    <?php endif; ?>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
            <?php else: ?>
            <form action="register.php" method="GET">
                <button type="submit">Register</button>
            </form>
            <form action="login.php" method="GET">
                <button type="submit">Login</button>
            </form>
            <?php endif; ?>
        </nav>

    </div>
    <div class="header">
    
    <input type="text" id="search-bar" placeholder="Search events..." onkeyup="searchEvents(this.value)">
    <nav>
        <!-- existing navigation -->
    </nav>
</div>

     <!-- Success Message -->
     <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green; text-align: center;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>
    <?php if (!$eventId): ?>
    <h1>Upcoming Events</h1>
    <?php if ($result->num_rows > 0): ?>
        <ul class="event-list">
            <?php while ($row = $result->fetch_assoc()): ?>
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
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No events found.</p>
    <?php endif; ?>
<?php endif; ?>
<?php if ($eventId && $event): ?>
    <div class="event-detail">
        <img src="<?php echo htmlspecialchars($event['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($event['name'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="event-detail-content">
            <h1><?php echo htmlspecialchars($event['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><strong>Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($event['date'])); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Price:</strong> ₹<?php echo number_format($event['price'], 2); ?></p>
            <p><strong>Availability:</strong> <?php echo htmlspecialchars($event['availability'], ENT_QUOTES, 'UTF-8'); ?> tickets left</p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8'); ?></p>
            <button onclick="location.href='index.php'">Back to Events</button>
            <?php if ($event['availability'] > 0): ?>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <button onclick="location.href='book.php?event_id=<?php echo $event['id']; ?>'">Book Ticket</button>
    <?php else: ?>
        <button onclick="location.href='login.php'">Login to Book Ticket</button>
    <?php endif; ?>
<?php else: ?>
    <button disabled style="background-color: gray; cursor: not-allowed;">Completely Booked</button>
<?php endif; ?>

        </div>
    </div>
<?php elseif ($eventId): ?>
    <p>Event not found.</p>
<?php endif; ?>



    <?php $conn->close(); ?>
    <script>
        function toggleMenu() {
            const menu = document.getElementById('dropdownMenu');
            menu.style.display = (menu.style.display === 'block' ? 'none' : 'block');
        }

        // Close menu if clicking outside
        window.addEventListener('click', function (e) {
            const menu = document.getElementById('dropdownMenu');
            const profile = document.querySelector('.profile');
            if (menu && !profile.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    </script>
    <script>
function searchEvents(query) {
    const eventList = document.querySelector('.event-list');

    fetch(`search.php?query=${encodeURIComponent(query)}`)
        .then(response => response.text())
        .then(html => {
            eventList.innerHTML = html;
        })
        .catch(error => {
            console.error('Error fetching search results:', error);
            eventList.innerHTML = '<p>Failed to load events. Please try again later.</p>';
        });
}
</script>
<footer class="footer">
    <div class="footer-content">
        <div class="contact-info">
            <h3>Contact Us</h3>
            <p>Email: tharunkm000@gmail.com</p>
            <p>Phone: +91 8919223785</p>
            <p>Address: SRIT, Anantapur</p>
        </div>
        <div class="social-media">
            <h3>Follow Us</h3>
            
            <a href="https://www.linkedin.com/in/tharunkm?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" target="_blank" class="social-link">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="https://www.instagram.com/tharun_izx" target="_blank" class="social-link">
                <i class="fab fa-instagram"></i>
            </a>
            
        </div>
    </div>
    <p>If you have an event idea, <a href="request.php">submit your event request here</a>.</p>


    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> Event Booking System. All rights reserved.</p>
    </div>
</footer>



    
</body>
</html>
