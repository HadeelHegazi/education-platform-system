<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection setup (adjust as per your configuration)
$mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

session_start();

header('Content-Type: application/json');

// Debugging to check if script is reached
error_log("Reached fetch_notes.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['courseId']) && isset($_GET['date'])) {
    $date = $_GET['date'];
    $courseid = $_GET['courseId'];

    $query = "SELECT * FROM `lessonnote`
              WHERE `course_id` = ? AND `date` = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $courseid, $date);

    // Execute statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $notes = $result->fetch_all(MYSQLI_ASSOC);

        // Determine user type (replace with actual logic to get user type)
        $user_type = 'teacher'; // Example: Replace with your actual logic to determine user type

        // Include the user type in the response
        echo json_encode(['notes' => $notes, 'user_type' => $user_type]);
    } else {
        echo json_encode(['error' => 'Failed to fetch notes']);
    }
}
?>
