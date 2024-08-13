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

// Check if the request method is POST and if the 'courseId' parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['courseId'])) {
    // Retrieve session variables and validate them
    if (isset($_SESSION["id"]) && isset($_SESSION["type"])) {
        $userid = $_SESSION["id"];
        $usertype = $_SESSION["type"];
        
        // Retrieve course ID from POST data
        $courseid = $_GET['courseId'];

        // Prepare SQL statement to insert into 'usercheckin' table
        $query = "INSERT INTO `usercheckin` (`user_id`, `user_type`, `course_id`) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);

        // Check if prepare statement failed
        if (!$stmt) {
            $response = array('status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error);
        } else {
            // Bind parameters to the prepared statement
            $stmt->bind_param("isi", $userid, $usertype, $courseid);

            // Execute the statement
            $result = $stmt->execute();

            // Check if execution was successful
            if ($result) {
                $response = array('status' => 'success', 'message' => 'تم تسجيل الحضور بنجاح.');
            } else {
                $response = array('status' => 'error', 'message' => 'لم يتم تسجيل الحضور بنجاح.');
            }

            // Close the statement
            $stmt->close();
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Session variables not set.');
    }
} else {
    // Handle invalid requests
    $response = array('status' => 'error', 'message' => 'Invalid request or missing courseId.');
}

// Close the database connection
$mysqli->close();

// Output JSON response
echo json_encode($response);
exit;
?>


<?php if (isset($_SESSION['main_success_message'])): ?>
    <script>
        alert("<?= $_SESSION['main_success_message'] ?>");
    </script>
    <?php unset($_SESSION['main_success_message']); ?>
<?php endif; ?>
