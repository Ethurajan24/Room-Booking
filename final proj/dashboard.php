<?php
session_start();  // Start the session to check for the logged-in user

// Check if the user is logged in (i.e., session 'username' is set)
if (!isset($_SESSION['username'])) {
    // If not, redirect to the login page
    header("Location: index.html");
    exit();  // Make sure to stop further script execution after redirect
}

// Display a welcome message with the logged-in user's username
echo "Welcome, " . $_SESSION['username'] . "! You are logged in.";
?>

<!-- Logout Button -->
<form action="logout.php" method="POST">
    <button type="submit">Logout</button>
</form>
