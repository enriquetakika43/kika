<?php
// Connecting to the database
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update daily streak
function actualizarRacha($userId) {
    global $conn;

    // Get current date
    $currentDate = date('Y-m-d');

    // Check if a record exists for the current date
    $sql = "SELECT * FROM racha_diaria WHERE user_id = ? AND fecha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $userId, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the existing record
        $sql = "UPDATE racha_diaria SET lecciones_completadas = lecciones_completadas + 1 WHERE user_id = ? AND fecha = ?";
    } else {
        // Insert a new record
        $sql = "INSERT INTO racha_diaria (user_id, fecha, lecciones_completadas) VALUES (?, ?, 1)";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $userId, $currentDate);
    $stmt->execute();

    // Check if there are any completed lessons
    $sql = "SELECT COUNT(*) as total_lecciones FROM lecciones_progreso WHERE user_id = ? AND completado = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalLecciones = $row['total_lecciones'];

    // Update streak if the user has completed lessons
    if ($totalLecciones > 0) {
        // Logic for updating streak goes here
        // For example: Increment streak or reset based on certain conditions
    }
}

// Replace this with actual user ID when calling the function
actualizarRacha(1);
?>