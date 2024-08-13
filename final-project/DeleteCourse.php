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
$query = "SELECT c.*, CONCAT(t.`first_name`, ' ', t.`last_name`) AS `teacher_name`, c.`grade` 
          FROM `course` c 
          JOIN `teacher` t ON c.`teacher_id` = t.`id` 
          WHERE c.`deleted`= 0";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


if(isset($_POST['submit']))
{

    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    $course_id = $_POST['course'];
    $deleted = 1;
    $deletedDate = date('Y-m-d');

    $query = "UPDATE `course` SET `deleted`=?, `deleted_date`=? WHERE `id`=?";
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    
    // Bind parameters
    $stmt->bind_param("isi", $deleted, $deletedDate, $course_id);
    
    // Execute statement
    $result = $stmt->execute();
    
    if ($result) {
        $_SESSION['main_success_message'] = "الحذف تم بنجاح";
        $_SESSION['success_message'] = "تم حذف هذه الدورة بنجاح.";
        include_once 'PopUpAlert.php';
        
    } else {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "لم تتم ازالة هذه الدورة, حاول مرة اخرى";
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
    


    <title>Delete Course</title>
</head>
<body>
    
    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>ازالة الدورة</h2>
            <div class="contant">
            <div class="input-box">
                    <label for="course">تفاصيل الدورة</label>
                    <select class="input-box" name="course" id="course">
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>"><?php echo $course['id']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-box"> <!-- empty div -->
                </div>

                <div class="input-box">
                <label for="subject">الموضوع:</label>
                <select class="select" name="subject" id="subject">
                    <option value="" disabled selected>الموضوع</option>
                    <option value="math">رياضيات</option>
                    <option value="physics">فيزياء</option>
                    <option value="chemistry">كيمياء</option>
                    <option value="biology">بيولوجيا</option>
                    <option value="arts">فن</option>
                </select>
                </div>

                <div class="input-box">
                    <label for="teacher">المعلم</label>
                    <input type="text" name="teacher"  id="teacher" disabled>
                </div>

                <div class="input-box">
                <label for="grade">الصف:</label>
                <select class="select" name="grade" id="grade">
                    <option value="" disabled selected>الصف</option>
                    <option value="Grade 7">السابع</option>
                    <option value="Grade 8">الثامن</option>
                    <option value="Grade 9">التاسع</option>
                    <option value="Grade 10">العاشر</option>
                    <option value="Grade 11">الحادي عشر</option>
                    <option value="Grade 12">الثاني عشر</option>
                </select>
                </div>

                <div class="input-box"> <!-- empty div -->
                </div>

                <div class="input-box">
                    <label for="sunday">الأحد في الساعة: </label>
                    <input type="time" id="sunday-time" name="sunday-time" >
                </div>

                <div class="input-box">
                    <label for="monday">الأثنين في الساعة: </label>
                    <input type="time" id="monday-time" name="monday-time" >
                </div>
                
                <div class="input-box">
                    <label for="tuesday">الثلاثاء في الساعة: </label>
                    <input type="time" id="tuesday-time" name="tuesday-time" >
                </div>
                
                <div class="input-box">
                    <label for="wednesday">الأربعاء في الساعة: </label>
                    <input type="time" id="wednesday-time" name="wednesday-time" >
                </div>    
                
                <div class="input-box">
                    <label for="thursday">الخميس في الساعة: </label>
                    <input type="time" id="thursday-time" name="thursday-time" >
                </div>
                
                <div class="input-box">
                    <label for="friday">الجمعه في الساعة: </label>
                    <input type="time" id="friday-time" name="friday-time" >
                </div>
                
                <div class="input-box">
                    <label for="saturday">السبت في الساعة: </label>
                    <input type="time" id="saturday-time" name="saturday-time" >
                </div>
                
                
            </div>
            <div class="button-container">
                    <button name="submit" type="submit">ازالة الدورة</button>
                </div>
            </div>

        </form>
    </div>

    <script>
        document.getElementById('course').addEventListener('change', function() {
            var selectedCourseId = this.value;
            var selectedCourse = <?php echo json_encode($courses); ?>.find(function(course) {
                return course.id == selectedCourseId;
            });
            // Populate input fields with selected course's details
            
            var subject = selectedCourse['subject'];
            
            if (subject == 'math') {
                document.getElementById('subject').value = 'math';
            } else if (subject == 'physics') {
                document.getElementById('subject').value = 'physics';
            } else if (subject == 'chemistry') {
                document.getElementById('subject').value = 'chemistry';
            } else if (subject == 'biology') {
                document.getElementById('subject').value = 'biology';
            } else if (subject == 'arts') {
                document.getElementById('subject').value = 'arts';
            }

            var grade = selectedCourse['grade'];
            
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
            }

            document.getElementById('teacher').value = selectedCourse['teacher_name'];
            document.getElementById('sunday-time').value = selectedCourse['sunday_time'];
            document.getElementById('monday-time').value = selectedCourse['monday_time'];
            document.getElementById('tuesday-time').value = selectedCourse['tuesday_time'];
            document.getElementById('wednesday-time').value = selectedCourse['wednesday_time'];
            document.getElementById('thursday-time').value = selectedCourse['thursday_time'];
            document.getElementById('friday-time').value = selectedCourse['friday_time'];
            document.getElementById('saturday-time').value = selectedCourse['saturday_time'];

            
        });
    </script>

</body>
</html>