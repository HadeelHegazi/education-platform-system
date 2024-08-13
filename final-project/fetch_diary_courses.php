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
error_log("Reached fetch_diary_courses.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['date']) && isset($_GET['day'])) {

    $selected_date = $_GET['date'];
    $selected_day = strtolower($_GET['day']); // Convert to lowercase to match column names
    $user_type = $_SESSION['type']; // Add this line to get the user type

    // Validate the selected day to prevent SQL injection
    $valid_days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    if (!in_array($selected_day, $valid_days)) {
        echo json_encode(['error' => 'Invalid day']);
        exit();
    }

    $time_column = $selected_day . '_time'; // e.g., 'sunday_time'

    // Common query part
    $base_sql = "
        SELECT
            c.id,
            c.subject,
            c.$time_column AS time,
            CONCAT(t.first_name, ' ', t.last_name) AS teacher_name
        FROM
            course c
        JOIN
            teacher t ON c.teacher_id = t.id
    ";

    // Student query
    if ($user_type == 'student') {
        $user_id = $_SESSION['id'];
        $sql = $base_sql . "
            JOIN studentincourse sic ON sic.course_id = c.id
            WHERE
                sic.student_id = ?
                AND c.$selected_day = 1
                AND (c.deleted = 0 OR c.deleted_date > ?)
        ";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("is", $user_id, $selected_date);
    }

    // Teacher query
    if ($user_type == 'teacher') {
        $user_id = $_SESSION['id'];
        $sql = $base_sql . "
            WHERE
                c.teacher_id = ?
                AND c.$selected_day = 1
                AND (c.deleted = 0 OR c.deleted_date > ?)
        ";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("is", $user_id, $selected_date);
    }

    // Admin query
    if ($user_type == 'admin') {
        $sql = $base_sql . "
            WHERE
                c.$selected_day = 1
                AND (c.deleted = 0 OR c.deleted_date > ?)
        ";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $selected_date);
    }

    // Execute statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $courses = $result->fetch_all(MYSQLI_ASSOC);

        // Include the user type in the response
        echo json_encode(['courses' => $courses, 'user_type' => $user_type]);
    } else {
        echo json_encode(['error' => 'Failed to fetch courses']);
    }
}

?>
