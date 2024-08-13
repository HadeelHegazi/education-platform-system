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
$query = "SELECT * FROM `teacher` WHERE `deleted`= 0 ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$teachers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


if(isset($_POST['submit']))
{
    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    $teacher_id = $_POST['teacher'];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $userphone = $_POST['phone'];
    $useremail = $_POST['email'];
    $userpassword = $_POST['password'];
    $math = $_POST['math'];
    $physics = $_POST['physics'];
    $chemistry = $_POST['chemistry'];
    $biology = $_POST['biology'];
    $arts = $_POST['arts'];


    $query = "UPDATE `teacher` SET `first_name`=?, `last_name`=?, `phone_number`=?, `email`=?, `password`=?, `mathematics`=?, `physices`=?, `chemistry`=?, `biology`=?, `arts`=? WHERE `id`=?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param("ssssssssssi", $firstname, $lastname, $userphone, $useremail, $userpassword, $math, $physics, $chemistry, $biology, $arts, $teacher_id);

    // Execute statement
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['main_success_message'] = "التعديل تم بنجاح";
        $_SESSION['success_message'] = "تم تعديل تفاصيل المعلم بنجاح.";
        include_once 'PopUpAlert.php';
        
    } else {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "لم تتم التعديلات على هذا المعلم, حاول مرة اخرى";
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
    <title>Edit Teacher</title>
</head>
<body>

    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>تعديل تفاصيل استاذ</h2>
            <div class="contant">
                <div class="input-box">
                    <label for="teacher">تفاصيل الاستاذ</label>
                    <select class="input-box" name="teacher" id="teacher">
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['id']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-box"> <!-- empty div -->
                </div>
                <div class="input-box">
                    <label for="firstname">الاسم الشخصي</label>
                    <input type="text" placeholder="ادخل الاسم الشخصي" name="firstname" id="firstname" >
                </div>
                <div class="input-box">
                    <label for="lastname">اسم العائلة</label>
                    <input type="text" placeholder="ادخل اسم العائلة" name="lastname" id="lastname" >
                </div>
                <div class="input-box">
                    <label for="phone">رقم الهاتف</label>
                    <input type="tel" placeholder="ادخل رقم الهاتف" name="phone" id="phone" >
                </div>
                <div class="input-box">
                    <label for="email">البريد الالكتروني</label>
                    <input type="text" placeholder="ادخل البريد الالكتروني" name="email" id="email" >
                </div>
                <div class="input-box">
                    <label for="password">كلمة المرور</label>
                    <input type="password" placeholder="ادخل كلمة المرور" name="password" id="password" >
                    <button type="button" class="toggle-password">عرض</button>
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
                    <input type="checkbox" id="arts" name="arts" value='1'>
                    <label for="arts"> فنون</label>
                </div>
            </div>
            <div class="alert">
            </div>
            <div class="button-container">
                <button name="submit" type="submit">تعديل تفاصيل استاذ</button>
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
            
            document.getElementById('teacher').addEventListener('change', function() {
                var selectedTeacherId = this.value;
                var teachers = <?php echo json_encode($teachers); ?>;
                var selectedTeacher = teachers.find(function(teacher) {
                    return teacher.id == selectedTeacherId;
                });

                if (selectedTeacher) {
                    document.getElementById('firstname').value = selectedTeacher['first_name'];
                    document.getElementById('lastname').value = selectedTeacher['last_name'];
                    document.getElementById('phone').value = selectedTeacher['phone_number'];
                    document.getElementById('email').value = selectedTeacher['email'];
                    document.getElementById('password').value = selectedTeacher['password'];

                    // Set checkbox values
                    document.getElementById('math').checked = selectedTeacher['mathematics'] == 1;
                    document.getElementById('physics').checked = selectedTeacher['physices'] == 1;
                    document.getElementById('chemistry').checked = selectedTeacher['chemistry'] == 1;
                    document.getElementById('biology').checked = selectedTeacher['biology'] == 1;
                    document.getElementById('arts').checked = selectedTeacher['arts'] == 1;
                }
            });
        
            </script>
        


        </form>
    </div>







</body>
</html>