<?php
session_start();

// Database connection setup (adjust as per your configuration)
$mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$_SESSION["receiver_id"] = $_POST['studentId'];
// Get logged-in user ID and selected student ID from POST data
$loggedInUserId = $_SESSION["id"];
$studentId = $_POST['studentId'];

// Prepare and execute SQL query to fetch messages
$query = "SELECT * FROM message
          WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
          ORDER BY timestamp ASC";
$stmt = $mysqli->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("iiii", $loggedInUserId, $studentId, $studentId, $loggedInUserId);
if ($stmt->execute() === false) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);

// Output messages in HTML format (adjust HTML structure as per your requirements)
foreach ($messages as $message) {
    if ($message['sender'] == $loggedInUserId) {
        echo '<div id="incomingmessage">';
        echo '<h3>' . htmlspecialchars($message['message']) . '</h3>';
        echo '<p>' . htmlspecialchars($message['timestamp']) . '</p>';
        echo '</div>';
    } else {
        echo '<div id="outgoingmessage">';
        echo '<h3>' . htmlspecialchars($message['message']) . '</h3>';
        echo '<p>' . htmlspecialchars($message['timestamp']) . '</p>';
        echo '</div>';
    }
}
?>


<link rel="stylesheet" href="style3.css">


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> -->
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    
</body>
</html>