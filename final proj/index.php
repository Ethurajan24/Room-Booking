<?php
session_start(); // Start the session

// If the user is already logged in, redirect them to the home page
if (isset($_SESSION['user_id'])) {
    header("Location: notif.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "user_system"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];

    // Check if the user exists
    $sql = "SELECT * FROM users WHERE username='$login_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($login_password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: home.html");
            exit();
        } else {
            echo "Invalid username or password!";
        }
    } else {
        echo "No user found with that username.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <!-- Show message if the user is already logged in -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>You are already logged in as <?php echo $_SESSION['username']; ?>.</p>
            <a href="home.php"><button>Go to Home</button></a>
        <?php else: ?>
            <!-- Login Form -->
            <form method="POST" action="index.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
