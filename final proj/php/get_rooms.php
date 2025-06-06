<?php
// get_rooms.php
include 'db.php';

$sql = "SELECT id, floor, room_number, available, booking_date FROM rooms";
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    echo json_encode($rooms);
} else {
    echo json_encode([
        "error" => "No rooms found"
    ]);
}

$conn->close();
?>
