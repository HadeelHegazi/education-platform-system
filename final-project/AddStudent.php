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

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }


    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $userphone = $_POST['phone'];
    $useremail = $_POST['email'];
    $userpassword = $_POST['password'];
    $userconfirmpassword = $_POST['confirmpassword'];
    $userschool = $_POST['school'];

    if($_POST['grade'] == 'Grade 7') $usergrade = 7;
    if($_POST['grade'] == 'Grade 8') $usergrade = 8;
    if($_POST['grade'] == 'Grade 9') $usergrade = 9;
    if($_POST['grade'] == 'Grade 10') $usergrade = 10;
    if($_POST['grade'] == 'Grade 11') $usergrade = 11;
    if($_POST['grade'] == 'Grade 12') $usergrade = 12;

   // echo "firstname: " . $firstname . "- lastname: " . $lastname . "- userphone: " . $userphone . "- useremail: " . $useremail . "- userpassword: " . $userpassword . "- usergrade: " . $usergrade . "- userschool: " . $userschool;


   if (isset($_POST['submit'])) {
    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Retrieve form data
    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $userphone = $_POST['phone'];
    $useremail = $_POST['email'];
    $userpassword = $_POST['password'];
    $userconfirmpassword = $_POST['confirmpassword'];
    $userschool = $_POST['school'];
    $usergrade = '';

    switch ($_POST['grade']) {
        case 'Grade 7':
            $usergrade = 7;
            break;
        case 'Grade 8':
            $usergrade = 8;
            break;
        case 'Grade 9':
            $usergrade = 9;
            break;
        case 'Grade 10':
            $usergrade = 10;
            break;
        case 'Grade 11':
            $usergrade = 11;
            break;
        case 'Grade 12':
            $usergrade = 12;
            break;
        default:
            // Handle default case
            break;
    }

    // Check password match
    if ($userpassword != $userconfirmpassword) {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "ان كلمات السر غير متطابقة";
        include_once 'PopUpAlert.php';
    } else {
        // Prepare the SELECT query to check for existing emails
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
                // Prepare the INSERT query to insert new student record
                $query = "INSERT INTO `student` (`first_name`, `last_name`, `phone_number`, `email`, `password`, `grade`, `school`) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);

                if ($stmt === false) {
                    // Handle query preparation error
                    die('MySQL prepare error: ' . $mysqli->error);
                }

                // Bind parameters
                $stmt->bind_param("sssssss", $firstname, $lastname, $userphone, $useremail, $userpassword, $usergrade, $userschool);

                // Execute statement
                $result = $stmt->execute();

                if ($result) {
                    $_SESSION['main_success_message'] = "الاضافة تمت بنجاح";
                    $_SESSION['success_message'] = "لقد تمت اضافة هذا الطالب بنجاح.";
                    include_once 'PopUpAlert.php';
                    
                } else {
                    $_SESSION['main_success_message'] = "خطأ في المعطيات";
                    $_SESSION['success_message'] = "لم تتم اضافة هذا الطالب, حاول مرة اخرى";
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
}



?>







<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    
    <title>Add New Student</title>
</head>
<body>
    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>اضافة طالب جديد</h2>
            <div class="contant">
                <div class="input-box">
                    <label for="name">الاسم الشخصي</label>
                    <input type="text" placeholder="ادخل الاسم الشخصي" name="Firstname" required>
                </div>
                <div class="input-box">
                    <label for="name">العائلة</label>
                    <input type="text" placeholder="ادخل اسم العائلة" name="Lastname" required>
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
                    <label for="password">كلمة السر</label>
                    <input type="password" placeholder="ادخل كلمة السر" name="password" required>
                </div>
                <div class="input-box">
                    <label for="confirm-password">تاكيد كلمة السر</label>
                    <input type="password" placeholder="ادخل كلمة السر مرة اخرى" name="confirmpassword" required>
                </div>
                
                <div class="input-box">
                <label for="grade">الصف:</label>
                <select class="select" name="grade" id="grade">
                    <option value="Grade 7">السابع</option>
                    <option value="Grade 8">الثامن</option>
                    <option value="Grade 8">التاسع</option>
                    <option value="Grade 10">العاشر</option>
                    <option value="Grade 11">الحادي عشر</option>
                    <option value="Grade 12">الثاني عشر</option>
                </select>
                </div>

                <div class="input-box">
                <label for="school">المدرسة:</label>
                <select class="select" name="school" id="school">
                    <option value="مدرسة البيان">مدرسة البيان</option>
                    <option value="مدرسة الخورزمي">مدرسة الخورزمي</option>
                    <option value="مدرسة الدكتور هشام">مدرسة الدكتور هشام</option>
                    <option value="مدرسة الفرابي">مدرسة الفرابي</option>
                    <option value="مدرسة ابن خلدون">مدرسة ابن خلدون</option>
                    <option value="مدرسة المتنبي">مدرسة المتنبي</option>
                    <option value="مدرسة الحكمة">مدرسة الحكمة</option>
                </select>
                </div>

            </div>
            <div class="alert">
                <p>بالنقر على 'إضافة طالب'، سيتم إرسال بريد إلكتروني إلى بريد الطالب، يحتوي على بريده الإلكتروني وكلمة المرور الخاصة به.</p>
            </div>
            <div class="button-container">
                <button name="submit" type="submit">إضافة طالب</button>
            </div>
        </form>
    </div>

</body>
</html>






