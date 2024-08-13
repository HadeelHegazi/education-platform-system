<?php

error_reporting(-1);
  require_once "db.php";

session_start();
$_GET;

//secure the admin pages!!!
if($_SESSION["type"]  != 'admin'){
    header('Location: index.php');
}

if(isset($_POST['submit']))
{
    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');


    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $userphone = $_POST['phone'];
    $useremail = $_POST['email'];
    $userpassword = $_POST['password'];
    $userconfirmpassword = $_POST['confirmpassword'];
    $math = $_POST['math'];
    $physics = $_POST['physics'];
    $chemistry = $_POST['chemistry'];
    $biology = $_POST['biology'];
    $arts = $_POST['arts'];



    if($userpassword != $userconfirmpassword)
    {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "ان كلمات السر غير متطابقة";
        include_once 'PopUpAlert.php';
    } else {
        // Prepare the SELECT query to check for existing records
        $query = "(SELECT `email` COLLATE utf8mb4_unicode_ci AS `email` FROM `student` WHERE `email` = ?)
                UNION 
                (SELECT `email` COLLATE utf8mb4_unicode_ci AS `email` FROM `teacher` WHERE `email` = ?)";
        $stmt = $mysqli->prepare($query);

        if ($stmt === false) {
        // Handle query preparation error
        die('MySQL prepare error: ' . $mysqli->error);
        }

        // Bind parameters
        $stmt->bind_param("ss", $useremail, $useremail);

        // Execute statement
        $stmt->execute();

        // Check for errors during execution
        if ($stmt->errno) {
        // Handle execution error
        die('MySQL execute error: ' . $stmt->error);
        }

        // Get result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If any record with the same email exists, display an error message
            $_SESSION['main_success_message'] = "خطأ في المعطيات";
            $_SESSION['success_message'] = "ان البريد الاكتروني قد تم استعماله استعمل بريد اخر.";
            include_once 'PopUpAlert.php';
        } else {
            // Prepare the SELECT query to check for existing phone numbers
            $query = "(SELECT `phone_number` COLLATE utf8mb4_unicode_ci AS `phone_number` FROM `student` WHERE `phone_number` = ?)
                UNION 
                (SELECT `phone_number` COLLATE utf8mb4_unicode_ci AS `phone_number` FROM `teacher` WHERE `phone_number` = ?)";
            $stmt = $mysqli->prepare($query);

            if ($stmt === false) {
                // Handle query preparation error
                die('MySQL prepare error: ' . $mysqli->error);
            }

            // Bind parameters
            $stmt->bind_param("ss", $userphone, $userphone);

            // Execute statement
            $stmt->execute();

            // Check for errors during execution
            if ($stmt->errno) {
                // Handle execution error
                die('MySQL execute error: ' . $stmt->error);
            }

            // Get result
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // If any record with the same phone number exists, display an error message
                $_SESSION['main_success_message'] = "خطأ في المعطيات";
                $_SESSION['success_message'] = "ان رقم الهاتف قد تم استعماله استعمل رقم هاتف اخر.";
                include_once 'PopUpAlert.php';
            } else {
                $query = "INSERT INTO `teacher` (`first_name`, `last_name`, `phone_number`, `email`, `password`, `mathematics`, `physices`, `chemistry`, `biology`, `arts`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                
                // Bind parameters
                $stmt->bind_param("ssssssssss", $first_name, $last_name, $userphone, $useremail, $userpassword, $math, $physics, $chemistry, $biology, $arts);


                // Execute statement
                $result = $stmt->execute();

                if ($result) {
                    $_SESSION['main_success_message'] = "الاضافة تمت بنجاح";
                    $_SESSION['success_message'] = "لقد تمت اضافة هذا المعلم بنجاح.";
                    include_once 'PopUpAlert.php';
                    
                } else {
                    $_SESSION['main_success_message'] = "خطأ في المعطيات";
                    $_SESSION['success_message'] = "لم تتم اضافة هذا المعلم, حاول مرة اخرى";
                    include_once 'PopUpAlert.php';
                }
            
                $mainsuccessMessage = isset($_SESSION['main_success_message']) ? $_SESSION['main_success_message'] : null;
                unset($_SESSION['main_success_message']);
            
                $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
                unset($_SESSION['success_message']);
            }
        }
    }
}








?>








<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <title>Add New Teacher</title>
</head>
<body>
    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>اضافةاستاذ جديد</h2>
            <div class="contant">
                <div class="input-box">
                    <label for="firstname">الاسم الشخصي</label>
                    <input type="text" placeholder="ادخل الاسم الشخصي" name="firstname" required>
                </div>
                <div class="input-box">
                    <label for="lastname">اسم العائلة</label>
                    <input type="text" placeholder="ادخل اسم العائلة" name="lastname" required>
                </div>
                <div class="input-box">
                    <label for="email">البريد الالكتروني</label>
                    <input type="text" placeholder="ادخل البريد الالكتروني" name="email" required>
                </div>
                <div class="input-box">
                    <label for="phone">رقم الهاتف</label>
                    <input type="tel" placeholder="ادخل رقم الهاتف" name="phone" required>
                </div>
                <div class="input-box">
                    <label for="password">كلمة المرور</label>
                    <input type="password" placeholder="ادخل كلمة المرور" name="password" required>
                </div>
                <div class="input-box">
                    <label for="confirm-password">تاكيد كلمة المرور</label>
                    <input type="password" placeholder="تاكيد كلمة المرور" name="confirmpassword" required>
                </div>

                <span class="subject-title">الموضوع:</span>
                <div class="subject-category">
                    <input type="hidden" name="math" value='0'>
                    <input type="checkbox" id="math" name="math" value='1' >
                    <label for="math"> رياضيات</label>
                    <input type="hidden" name="physics" value='0'>
                    <input type="checkbox" id="physics" name="physics" value='1'>
                    <label for="physics"> فيزياء</label>
                    <input type="hidden" name="chemistry" value='0'>
                    <input type="checkbox" id="chemistry" name="chemistry" value='1'>
                    <label for="chemistry"> كيمياء</label>
                    <input type="hidden" name="biology" value='0'>
                    <input type="checkbox" id="biology" name="biology" value='1'>
                    <label for="biology"> بيولوجيا</label>
                    <input type="hidden" name="arts" value='0'>
                    <input type="checkbox" id="chemistry" name="arts" value='1'>
                    <label for="chemistry"> فنون</label>
                </div>
            </div>
            <div class="alert">
            <p>بالنقر على 'إضافة اضافةاستاذ'، سيتم إرسال بريد إلكتروني إلى بريد المدرّس، يحتوي على بريده الإلكتروني وكلمة المرور الخاصة به.</p>
            </div>
            <div class="button-container">
                <button name="submit" type="submit">اضافةاستاذ</button>
            </div>
        </form>
    </div>
</body>
</html>