<?php

error_reporting(-1);
  require_once "db.php";

session_start();
$_GET;

//secure the admin pages!!!
if($_SESSION["type"]  != 'admin'){
    header('Location: index.php');
}

$mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');


// Fetch student data from the database
$query = "SELECT * FROM `student` WHERE `deleted`= 0 ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if(isset($_POST['submit']))
{

    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    $student_id = $_POST['student'];
    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $userphone = $_POST['phone'];
    $useremail = $_POST['email'];
    $userpassword = $_POST['password'];
    $userschool = $_POST['school'];

    if($_POST['grade'] == 'Grade 7') $usergrade = 7;
    if($_POST['grade'] == 'Grade 8') $usergrade = 8;
    if($_POST['grade'] == 'Grade 9') $usergrade = 9;
    if($_POST['grade'] == 'Grade 10') $usergrade = 10;
    if($_POST['grade'] == 'Grade 11') $usergrade = 11;
    if($_POST['grade'] == 'Grade 12') $usergrade = 12;

   // echo $firstname,$lastname,$userphone , $useremail, $userpassword , $usergrade , $userschool;


    $query = "UPDATE `student` SET `first_name`=?, `last_name`=?, `phone_number`=?, `email`=?, `password`=?, `grade`=?, `school`=? WHERE `id`=?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param("sssssssi", $firstname, $lastname, $userphone, $useremail, $userpassword, $usergrade, $userschool, $student_id);

    // Execute statement
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['main_success_message'] = "التعديل تم بنجاح";
        $_SESSION['success_message'] = "تم تعديل تفاصيل الطالب بنجاح.";
        include_once 'PopUpAlert.php';
        
    } else {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "لم تتم التعديلات على هذا الطالب, حاول مرة اخرى";
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
    <script src="app.js" defer></script>

    <title>Edit Student</title>
</head>
<body>

    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>

    <div class="container">
        <form action="" method="post" >
            <h2>تعديل تفاصيل الطالب</h2>
            <div class="contant">
                <div class="input-box">
                    <label for="student">تفاصيل الطالب</label>
                    <select class="input-box" name="student" id="student">
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo $student['id']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-box"> <!-- empty div -->
                </div>


                <div class="input-box">
                    <label for="name">الاسم الشخصي</label>
                    <input type="text"  name="Firstname" id="Firstname" > <!-- placeholder="الاسم الشخصي" -->
                </div>
                <div class="input-box">
                    <label for="name">العائلة</label>
                    <input type="text"  name="Lastname" id="Lastname" > <!-- placeholder="اسم العائلة" -->
                </div>
                <div class="input-box">
                    <label for="email">البريد الالكتروني</label>
                    <input type="text" placeholder="البريد الالكتروني" name="email" id="email" >
                </div>
                <div class="input-box">
                    <label for="phone">رقم الهاتف</label>
                    <input type="tel" placeholder="رقم الهاتف" name="phone" id="phone" >
                </div>
                <div class="input-box">
                    <label for="password">كلمة السر</label>
                    <input type="password" placeholder="كلمة السر" name="password" id="password" >
                    <button type="button" class="toggle-password">عرض</button>
                </div>

                <div class="input-box">
                    <label for="grade">الصف:</label>
                    <select class="select" name="grade" id="grade">
                        <option value="" disabled selected>اختر الصف</option>
                        <option value="Grade 7">السابع</option>
                        <option value="Grade 8">الثامن</option>
                        <option value="Grade 9">التاسع</option>
                        <option value="Grade 10">العاشر</option>
                        <option value="Grade 11">الحادي عشر</option>
                        <option value="Grade 12">الثاني عشر</option>
                    </select>
                </div>

                <div class="input-box">
                    <label for="school">المدرسة:</label>
                    <select class="select" name="school" id="school">
                        <option value="" disabled selected>اختر المدرسة</option>
                        <option value="مدرسة البيان">مدرسة البيان</option>
                        <option value="مدرسة الخورزمي">مدرسة الخورزمي</option>
                        <option value="مدرسة الدكتور هشام">مدرسة الدكتور هشام</option>
                        <option value="مدرسة الفرابي">مدرسة الفرابي</option>
                        <option value="مدرسة ابن خلدون">مدرسة ابن خلدون</option>
                        <option value="مدرسة المتنبي">مدرسة المتنبي</option>
                        <option value="مدرسة الحكمة">مدرسة الحكمة</option>
                    </select>
                </div>

                <div class="input-box"> <!-- empty div -->
                </div>

            </div>
                
            <div class="button-container">
                <button name="submit" type="submit">تعديل تفاصيل الطالب</button>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            var passwordField = document.getElementById('password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.textContent = 'إخفاء';
            } else {
                passwordField.type = 'password';
                this.textContent = 'عرض';
            }
        });
    </script>


    <script>
        document.getElementById('student').addEventListener('change', function() {
            var selectedStudentId = this.value;
            var selectedStudent = <?php echo json_encode($students); ?>.find(function(student) {
                return student.id == selectedStudentId;
            });
            // Populate input fields with selected student's details
            
            var school = selectedStudent['school'];
            
            if (school == 'مدرسة البيان') {
                document.getElementById('school').value = 'مدرسة البيان';
            } else if (school == 'مدرسة الخورزمي') {
                document.getElementById('school').value = 'مدرسة الخورزمي';
            } else if (school == 'مدرسة الدكتور هشام') {
                document.getElementById('school').value = 'مدرسة الدكتور هشام';
            } else if (school == 'مدرسة الفرابي') {
                document.getElementById('school').value = 'مدرسة الفرابي';
            } else if (school == 'مدرسة ابن خلدون') {
                document.getElementById('school').value = 'مدرسة ابن خلدون';
            } else if (school == 'مدرسة المتنبي') {
                document.getElementById('school').value = 'مدرسة المتنبي';
            } else if (school == 'مدرسة الحكمة') {
                document.getElementById('school').value = 'مدرسة الحكمة';
            } else { document.getElementById('school').value = ''; }

            var grade = selectedStudent['grade'];
            
            if (grade == '7') {
                document.getElementById('grade').value = 'Grade 7';
            } else if (grade == '8') {
                document.getElementById('grade').value = 'Grade 8';
            } else if (grade == '9') {
                document.getElementById('grade').value = 'Grade 9';
            } else if (grade == '10') {
                document.getElementById('grade').value = 'Grade 10';
            } else if (grade == '11') {
                document.getElementById('grade').value = 'Grade 11';
            } else if (grade == '12') {
                document.getElementById('grade').value = 'Grade 12';
            } else { document.getElementById('grade').value = ''; }

            document.getElementById('Firstname').value = selectedStudent['first_name'];
            document.getElementById('Lastname').value = selectedStudent['last_name'];
            document.getElementById('email').value = selectedStudent['email'];
            document.getElementById('phone').value = selectedStudent['phone_number'];
            document.getElementById('password').value = selectedStudent['password'];

            
        });
    </script>



</body>
</html>