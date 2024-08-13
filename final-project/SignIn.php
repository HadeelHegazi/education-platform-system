<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "db.php";

if (isset($_POST['submit'])) {
    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    /**************************************************if the user is the admin************************************************************************/

    if ($email == 'admin1234' && $password == 'admin1234') {
        $_SESSION["type"] = 'admin';
        $_SESSION["useremail"] = 'admin1234';
        $_SESSION["userpassword"] = 'admin1234';
        $_SESSION["id"] = 'admin';
        header('Location: profile.php');
        exit;
    }

    /**************************************************if the user is student************************************************************************/
    $query = "SELECT `id`, `first_name`, `last_name`, `phone_number`, `email`, `password`, `grade`, `school`, `deleted`, `deleted_date` FROM `student` WHERE `email` = ? AND `deleted` = 0";
    $stmt1 = $mysqli->prepare($query);

    if (!$stmt1) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Bind parameters
    $stmt1->bind_param("s", $email);

    // Execute statement
    $stmt1->execute();

    // Bind result variables
    $stmt1->bind_result($id, $firstname, $lastname, $userphone, $useremail, $userpassword, $usergrade, $userschool, $isdeleted, $deleteddate);

    // Fetch results
    if ($stmt1->fetch() && $password == $userpassword) {
        $_SESSION["type"] = 'student';
        $_SESSION["id"] = $id;
        $_SESSION["fullname"] = $firstname . ' ' . $lastname;
        $_SESSION["firstname"] = $firstname;
        $_SESSION["lastname"] = $lastname;
        $_SESSION["userphone"] = $userphone;
        $_SESSION["useremail"] = $useremail;
        $_SESSION["userpassword"] = $userpassword;
        $_SESSION["usergrade"] = $usergrade;
        $_SESSION["userschool"] = $userschool;
        $_SESSION["isdeleted"] = $isdeleted;
        $_SESSION["deleteddate"] = $deleteddate;

        if (!empty($_POST['remember']) && $_POST['remember'] == 'on') {
            setcookie("email", $email, time() + 60 * 60 * 24 * 30);
        }

        $_SESSION['main_success_message'] = "الاضافة الدخول بنجاح";
        $_SESSION['success_message'] = "لقد تمت عملية الدخول للموقع بنجاح.";
        include_once 'PopUpAlert.php';
    } else {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "هنالك خطا في اسم المستخدم او كلمة المرور.";
        include_once 'PopUpAlert.php';
    }

    // Free result and close statement
    $stmt1->free_result();
    $stmt1->close();

    /**************************************************if the user is teacher************************************************************************/
    $query = "SELECT `id`, `first_name`, `last_name`, `phone_number`, `email`, `password`, `mathematics`, `physices`, `chemistry`, `biology`, `arts`, `deleted`, `deleted_date` FROM `teacher` WHERE `email` = ? AND `deleted` = 0";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param("s", $email);

    // Execute statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($id, $firstname, $lastname, $userphone, $useremail, $userpassword, $math, $physics, $chemistry, $biology, $arts, $isdeleted, $deleteddate);

    // Fetch results
    if ($stmt->fetch() && $password == $userpassword) {
        $_SESSION["type"] = 'teacher';
        $_SESSION["id"] = $id;
        $_SESSION["fullname"] = $firstname . ' ' . $lastname;
        $_SESSION["firstname"] = $firstname;
        $_SESSION["lastname"] = $lastname;
        $_SESSION["userphone"] = $userphone;
        $_SESSION["useremail"] = $useremail;
        $_SESSION["userpassword"] = $userpassword;
        $_SESSION["math"] = $math;
        $_SESSION["physics"] = $physics;
        $_SESSION["chemistry"] = $chemistry;
        $_SESSION["biology"] = $biology;
        $_SESSION["arts"] = $arts;
        $_SESSION["isdeleted"] = $isdeleted;
        $_SESSION["deleteddate"] = $deleteddate;

        if (!empty($_POST['remember']) && $_POST['remember'] == 'on') {
            setcookie("email", $email, time() + 60 * 60 * 24 * 30);
        }

        $_SESSION['main_success_message'] = "االدخول بنجاح";
        $_SESSION['success_message'] = "لقد تمت عملية الدخول للموقع بنجاح.";
        include_once 'PopUpAlert.php';
    } else {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "هنالك خطا في اسم المستخدم او كلمة المرور.";
        include_once 'PopUpAlert.php';
    }

    // Free result and close statement
    $stmt->free_result();
    $stmt->close();

    // Handle success and error messages
    $mainsuccessMessage = isset($_SESSION['main_success_message']) ? $_SESSION['main_success_message'] : null;
    unset($_SESSION['main_success_message']);

    $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
    unset($_SESSION['success_message']);
}

// $mysqli->close();
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <title>Sign In</title>
    
</head>
<body>
    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>تسجيل الدخول</h2>
            <div class="contant">
                <div class="input-box">
                    <label for="email">البريد الالكتروني:</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="input-box">
                    <label for="password">كلمة المرور:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-box">
                    <label for="remember">تذكرني:</label>
                    <input type="checkbox" name="remember" id="remember" />
                </div>
                <div class="input-box"></div>
            </div>
            <div class="button-container">
                <button name="submit" type="submit">تسجيل الدخول</button>
            </div>
        </form>
    </div>

   
</body>
</html>
