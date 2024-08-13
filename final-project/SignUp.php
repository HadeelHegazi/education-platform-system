<?php

error_reporting(-1);
  require_once "db.php";

session_start();
$_GET;

if(isset($_POST['submit']))
{
    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $username = $_POST['uname'];
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

   // echo "firstname: " . $firstname . "- lastname: " . $lastname . "- username: " . $username . "- userphone: " . $userphone . "- useremail: " . $useremail . "- userpassword: " . $userpassword . "- usergrade: " . $usergrade . "- userschool: " . $userschool;


    if($userpassword != $userconfirmpassword)
    {
        echo "passsword not matched";
    } else {
            
            // Prepare the SELECT query to check for existing records
            $query = "SELECT * FROM `student` WHERE `username` = ?";
            $stmt = $mysqli->prepare($query);

            // Bind parameters
            $stmt->bind_param("s",$username);

            // Execute statement
            $stmt->execute();

            // Get result
            $result = $stmt->get_result();

            if($result->num_rows > 0)
            {
                // If any record with the same username exists, display an error message
                echo "Username already exists";
                //$error[] = 'Username already exists!';
            } else {
                // Prepare the SELECT query to check for existing records
                $query = "SELECT * FROM `student` WHERE `email` = ? ";
                $stmt = $mysqli->prepare($query);

                // Bind parameters
                $stmt->bind_param("s",$useremail);

                // Execute statement
                $stmt->execute();

                // Get result
                $result = $stmt->get_result();

                if($result->num_rows > 0) 
                {
                    // If any record with the same email exists, display an error message
                    echo "email already exists";
                    //$error[] = 'email already exists!';
                } else {
                        // Prepare the SELECT query to check for existing records
                        $query = "SELECT * FROM `student` WHERE `phone number` = ?";
                        $stmt = $mysqli->prepare($query);

                        // Bind parameters
                        $stmt->bind_param("s",$userphone);

                        // Execute statement
                        $stmt->execute();

                        // Get result
                        $result = $stmt->get_result();

                        if($result->num_rows > 0)  
                        {
                            // If any record with the same phone number exists, display an error message
                            echo "phone number already exists";
                            //$error[] = 'phone number already exists';
                        } else {

                            $query = "INSERT INTO `student` (`firstname`, `lastname`, `username`, `phone number`, `email`, `password`, `grade`, `school`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $mysqli->prepare($query);

                            // Bind parameters
                            $stmt->bind_param("ssssssss", $firstname, $lastname, $username, $userphone, $useremail, $userpassword, $usergrade, $userschool);

                            // Execute statement
                            $result = $stmt->execute();

                            if($result)
                            {
                                echo " successful";
                            // header('Location: mainpage1.php');
                            // exit();
                            }
                            else
                            {
                                echo "Registration failed";
                            }
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
    
    <title>Student Sign Up</title>
</head>
<body>
    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>انشاء حساب لطالب</h2>
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
                    <label for="username">اسم المستخدم</label>
                    <input type="text" placeholder="ادخل اسم المستخدم" name="uname" required>
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
                <button name="submit" type="submit">إنشاء حساب</button>
            </div>
        </form>
    </div>

</body>
</html>






