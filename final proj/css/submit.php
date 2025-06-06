
<?php 
$name = $_POST['name'];

// Database connection details  
$servername = "localhost";  // Database server (usually localhost)
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$dbname = "sub";     // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    




    
else{
    // Prepare SQL query to insert form data into the database
    $INSERT = "INSERT INTO regg (name ) VALUES ('$name')";
}
    // Execute the query and check if it was successful
    if ($conn->query($INSERT) === TRUE) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $INSERT . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();

