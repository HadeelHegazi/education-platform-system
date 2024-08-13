<?php

error_reporting(-1);
  require_once "db.php";

session_start();
$_GET;
echo "Got into the POST['send'] part.";

    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Assume $_SESSION["id"] and $_SESSION["receiver_id"] are set elsewhere
    $sender = $_SESSION["id"];
    $receiver = $_SESSION["receiver_id"];
    $message = $_POST['messagetext'];

    $query = "INSERT INTO `message` (`sender`, `receiver`, `message`) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        echo "Preparation failed: (" . $mysqli->errno . ") " . $mysqli->error;
        exit;
    }

    // Bind parameters
    $stmt->bind_param("sss", $sender, $receiver, $message);

    // Execute statement
    $result = $stmt->execute();

    if ($result) {
        echo "Added successfully";
    } else {
        echo "Execution failed: (" . $stmt->errno . ") " . $stmt->error;
    }
?>
