<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection setup (adjust as per your configuration)
$mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

session_start();

$courseid = '';
$date = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['courseId']) && isset($_GET['date'])) {
    $date = $_GET['date'];
    $userid = $_SESSION["id"];
    $courseid = $_GET['courseId'];
}

if(isset($_POST['submit'])){
    
    $courseid = $_GET['courseId'];    
    $date = $_GET['date'];
    $note = $_POST['note'];
    
    $query = "INSERT INTO `lessonnote` (`course_id`, `date`, `note`) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    
    // Bind parameters
    $stmt->bind_param("sss", $courseid, $date, $note);
    
    // Execute statement
    $result = $stmt->execute();
    
    if ($result) {
        $_SESSION['main_success_message'] = "الاضافة تمت بنجاح";
        $_SESSION['success_message'] = "لقد تمت اضافة هذه الدورة بنجاح.";
        include_once 'PopUpAlert.php';
        
    } else {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "لم تتم اضافة هذه الدورة, حاول مرة اخرى";
        include_once 'PopUpAlert.php';
    }

    $mainsuccessMessage = isset($_SESSION['main_success_message']) ? $_SESSION['main_success_message'] : null;
    unset($_SESSION['main_success_message']);

    $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
    unset($_SESSION['success_message']);
    
}

?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    
    <title>Adding Note To A Lesson</title>

</head>
<body>

    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>اضافة ملاحظة للدرس</h2>
            <div class="contant">
                <div class="input-box">
                    <label for="course">رقم الدورة:</label>
                    <input type="text"  name="course" id="course"  value="<?php echo htmlspecialchars($courseid); ?>" readonly>
                </div>
                <div class="input-box">
                    <label for="date">التاريخ:</label>
                    <input type="date"  name="date" id="date"  value="<?php echo htmlspecialchars($date); ?>" readonly>
                </div>
                <div class="input-box">
                    <label for="note">ملاحظات الدرس:</label>
                    <input type="text"  name="note" id="note" >
                </div>
                <div class="input-box"></div>
                <div class="button-container">
                    <button name="submit" type="submit">اضافة الملاحظة</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html><?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection setup (adjust as per your configuration)
$mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

session_start();

$courseid = '';
$date = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['courseId']) && isset($_GET['date'])) {
    $date = $_GET['date'];
    $userid = $_SESSION["id"];
    $courseid = $_GET['courseId'];
}

if(isset($_POST['submit'])){
    
    $courseid = $_GET['courseId'];    
    $date = $_GET['date'];
    $note = $_POST['note'];
    
    $query = "INSERT INTO `lessonnote` (`course_id`, `date`, `note`) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    
    // Bind parameters
    $stmt->bind_param("sss", $courseid, $date, $note);
    
    // Execute statement
    $result = $stmt->execute();
    
    if ($result) {
        $_SESSION['main_success_message'] = "الاضافة تمت بنجاح";
        $_SESSION['success_message'] = "لقد تمت اضافة هذه الدورة بنجاح.";
        include_once 'PopUpAlert.php';
        
    } else {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "لم تتم اضافة هذه الدورة, حاول مرة اخرى";
        include_once 'PopUpAlert.php';
    }

    $mainsuccessMessage = isset($_SESSION['main_success_message']) ? $_SESSION['main_success_message'] : null;
    unset($_SESSION['main_success_message']);

    $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
    unset($_SESSION['success_message']);
    
}

?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    
    <title>Adding Note To A Lesson</title>

</head>
<body>

    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>اضافة ملاحظة للدرس</h2>
            <div class="contant">
                <div class="input-box">
                    <label for="course">رقم الدورة:</label>
                    <input type="text"  name="course" id="course"  value="<?php echo htmlspecialchars($courseid); ?>" readonly>
                </div>
                <div class="input-box">
                    <label for="date">التاريخ:</label>
                    <input type="date"  name="date" id="date"  value="<?php echo htmlspecialchars($date); ?>" readonly>
                </div>
                <div class="input-box">
                    <label for="note">ملاحظات الدرس:</label>
                    <input type="text"  name="note" id="note" >
                </div>
                <div class="input-box"></div>
                <div class="button-container">
                    <button name="submit" type="submit">اضافة الملاحظة</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>