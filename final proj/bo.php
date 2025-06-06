<?php
include 'db_connection.php'; // Include the database connection file

// Step 1: Automatically clean up expired bookings
$current_time = date('Y-m-d H:i:s'); // Get current date and time
$cleanup_sql = "DELETE FROM bookings WHERE CONCAT(booking_date, ' ', end_time) < '$current_time'"; // Delete expired bookings
$conn->query($cleanup_sql);

// Handle room booking submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_room'])) {
    $room_id = $_POST['room_id'];
    $customer_name = $_POST['customer_name'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $end_date = $_POST['end_date']; // Get the end date

    // Step 2: Check if the selected room is available
    $availability_check = "SELECT * FROM bookings 
                            WHERE room_id = '$room_id' 
                            AND booking_date = '$booking_date' 
                            AND ((start_time BETWEEN '$start_time' AND '$end_time') 
                            OR (end_time BETWEEN '$start_time' AND '$end_time') 
                            OR ('$start_time' BETWEEN start_time AND end_time) 
                            OR ('$end_time' BETWEEN start_time AND end_time))";
    
    $result = $conn->query($availability_check);

    if ($result->num_rows > 0) {
        // Room is not available for the selected time
        echo "<div style='color: red;'>Sorry, the room is already booked at this time.</div>";
    } else {
        // Step 3: Check if the room is full based on its capacity
        $room_capacity_query = "SELECT capacity FROM rooms WHERE room_id = '$room_id'";
        $room_capacity_result = $conn->query($room_capacity_query);
        $room_capacity = $room_capacity_result->fetch_assoc()['capacity'];

        // Count how many bookings are already made for this room on the selected date
        $booking_count_query = "SELECT COUNT(*) AS booking_count FROM bookings WHERE room_id = '$room_id' AND booking_date = '$booking_date'";
        $booking_count_result = $conn->query($booking_count_query);
        $booking_count = $booking_count_result->fetch_assoc()['booking_count'];
    }
        if ($booking_count >= $room_capacity) {
            echo "<div style='color: red;'>Sorry, the room is full on this date.</div>";
        } else {
            // Insert the booking into the database if not full
            $sql = "INSERT INTO bookings (room_id, customer_name, booking_date, start_time, end_time, end_date) 
                    VALUES ('$room_id', '$customer_name', '$booking_date', '$start_time', '$end_time', '$end_date')";

            if ($conn->query($sql) === TRUE) {
                echo "<div style='color: green;'>Room booked successfully!</div>";
            } else {
                echo "<div style='color: red;'>Error: " . $conn->error . "</div>";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking</title>
    <link rel="stylesheet" href="stylo.css">
</head>
<body>
<center>
    <div class="grid">
    <div class="room">
    <h1>Room Booking System</h1>

    <h2>Available Rooms</h2>
    <div class="form">
    <form method="POST" action="">


    
    <?php
        // Fetch rooms grouped by floors
        $sql = "SELECT * FROM rooms ORDER BY floor, room_id";
        $result = $conn->query($sql);
        $rooms_by_floor = [];

        // Group rooms by their floor
        while ($row = $result->fetch_assoc()) {
            $rooms_by_floor[$row['floor']][] = $row;
        }

        // Display rooms for each floor
        foreach ($rooms_by_floor as $floor => $rooms) {
            echo "<h3>Floor $floor</h3>";
            echo "<div class='grid-container'>
                    <div class='room-grid'>";
            
            foreach ($rooms as $room) {
                echo "<div class='room-item' onclick='selectRoom(" . $room['room_id'] . ", \"" . $room['room_name'] . "\")'>
                        <h3>" . $room['room_name'] . "</h3>
                        <p>Capacity: " . $room['capacity'] . "</p>
                        <p>Price: $" . $room['price'] . "/hour</p>
                      </div>";
            }
            
            echo "</div></div>";
        }
        ?>
   </div></div></div>
<div class="m">
        <!-- Hidden fields for booking details -->
        <input type="hidden" name="room_id" id="selected_room_id">
        <input type="hidden" name="room_name" id="selected_room_name">
        
        <h2>Book a Room</h2>
        <label for="customer_name">Customer Name:</label><br>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="booking_date">Start Date:</label><br>
        <input type="date" id="booking_date" name="booking_date" required><br><br>

        <label for="start_time">Start Time:</label><br>
        <input type="time" id="start_time" name="start_time" required><br><br>

        <label for="end_time">End Time:</label><br>
        <input type="time" id="end_time" name="end_time" required><br><br>

        <label for="end_date">End Date:</label><br>
        <input type="date" id="end_date" name="end_date" required><br><br>

        <button type="submit" name="book_room">Book Room</button>
    </form>
    </div>
    <script>
        // JavaScript to select a room and show it in the form
        function selectRoom(roomId, roomName) {
            document.getElementById('selected_room_id').value = roomId;
            document.getElementById('selected_room_name').value = roomName;
            alert("You have selected the room: " + roomName);
        }
    </script>
</center>
</body>
</html>
