<?php
// book_room.php
session_start();
include 'db.php';

if (isset($_POST['room_id']) && isset($_POST['booking_date']) && isset($_SESSION['user_id'])) {
    $room_id = $_POST['room_id'];
    $booking_date = $_POST['booking_date'];

    // Check if the room is available
    $sql = "SELECT available FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();

        if ($room['available']) {
            // Update the room to be booked
            $sql = "UPDATE rooms SET available = 0, booking_date = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $booking_date, $room_id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Room successfully booked"]);
            } else {
                echo json_encode(["error" => "Failed to book the room"]);
            }
        } else {
            echo json_encode(["error" => "Room is already booked"]);
        }
    } else {
        echo json_encode(["error" => "Room not found"]);
    }
} else {
    echo json_encode(["error" => "Missing parameters"]);
}

$conn->close();
?>
