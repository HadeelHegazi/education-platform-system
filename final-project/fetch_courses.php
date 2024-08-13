<?php
header('Content-Type: application/json');

$mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');
if ($mysqli->connect_error) {
    echo json_encode(['error' => $mysqli->connect_error]);
    exit();
}

session_start(); // Ensure session is started if needed

$coursestoregist = []; // Initialize the variable

if (isset($_POST['subject'])) {
    $subject = $_POST['subject'];
    $student_grade = $_SESSION["usergrade"];

    $query_courses = "SELECT c.*, CONCAT(t.`first_name`, ' ', t.`last_name`) AS `teacher_name`, c.`grade`
        FROM `course` c
        JOIN `teacher` t ON c.`teacher_id` = t.`id`
        WHERE c.`deleted` = 0 AND c.`subject` = ? AND c.`grade` = ?";

    $stmt_courses = $mysqli->prepare($query_courses);
    if ($stmt_courses) {
    $stmt_courses->bind_param("ss", $subject, $student_grade);
    $stmt_courses->execute();
    $result = $stmt_courses->get_result();
    $coursestoregist = $result->fetch_all(MYSQLI_ASSOC);

    // Debug output
    echo json_encode($coursestoregist);
    } else {
    echo json_encode(['error' => $mysqli->error]);
    }   
}

?>

