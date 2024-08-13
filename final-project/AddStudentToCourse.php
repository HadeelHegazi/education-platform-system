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

// Fetch course data from the database
$query = "SELECT c.*, CONCAT(t.`first_name`, ' ', t.`last_name`) AS `teacher_name`, c.`grade`
          FROM `course` c 
          JOIN `teacher` t ON c.`teacher_id` = t.`id` 
          WHERE c.`deleted`= 0";

$stmt = $mysqli->prepare($query);
$stmt->execute();
$courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['submit'])) {

    $student_id = $_POST['student'];
    $course_id = $_POST['course'];

    // Check if the student is already enrolled in the course
    $query = "SELECT * FROM `studentincourse` WHERE `student_id` = ? AND `course_id` = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "ان هذا الطالب موجود في الدورة";
        include_once 'PopUpAlert.php';
    } else {
        // Insert the student into the course
        $query = "INSERT INTO `studentincourse` (`student_id`, `course_id`) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ii", $student_id, $course_id);
        $stmt->execute();
        $_SESSION['main_success_message'] = "الاضافة تمت بنجاح";
        $_SESSION['success_message'] = "لقد تمت اضافة هذا الطالب للدورة بنجاح.";
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
    
    <title>Add Student To Course</title>
</head>
<body>
    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">

        <h2>اضافة طالب الى دورة</h2>
            <div class="contant"> 

                <div class="input-box">
                    <label for="student">تفاصيل الطالب</label>
                    <select class="input-box" name="student" id="student">
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo $student['first_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-box"></div> <!-- empty div -->
                <div class="input-box">
                    <label for="name">الاسم الشخصي</label>
                    <input type="text" name="firstname"  id="firstname" disabled>
                </div>
                <div class="input-box">
                    <label for="name">العائلة</label>
                    <input type="text" name="lastname"  id="lastname" disabled>
                </div>
                <div class="input-box">
                    <label for="email">البريد الالكتروني</label>
                    <input type="text" name="email"  id="email" disabled>
                </div>
                <div class="input-box">
                    <label for="phone">رقم الهاتف</label>
                    <input type="tel" name="phone"  id="phone" disabled>
                </div>
                
                <div class="input-box">
                    <label for="grade">الصف</label>
                    <input type="text" name="grade"  id="grade" disabled>
                </div>

                <div class="input-box">
                    <label for="school">المدرسة</label>
                    <input type="text" name="school"  id="school" disabled>
                </div>
   

            </div>

            <div class="contant">

                <div class="input-box">
                    <label for="course">تفاصيل الدورة</label>
                    <select class="input-box" name="course" id="course">
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>"><?php echo $course['id']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-box"></div> <!-- empty div -->
                <div class="input-box">
                    <label for="subject">الموضوع</label>
                    <input type="text" name="subject"  id="subject" disabled>
                </div>

                <div class="input-box">
                    <label for="teacher">المعلم</label>
                    <input type="text" name="teacher"  id="teacher" disabled>
                </div>

                <div class="input-box">
                    <label for="coursegrade">الصف</label>
                    <input type="text" name="coursegrade"  id="coursegrade" disabled>
                </div>
                <div class="input-box"></div> <!-- empty div -->

                <div class="input-box">
                    <label for="sunday">الأحد في الساعة: </label>
                    <input type="time" id="sunday-time" name="sunday-time" disabled>
                </div>

                <div class="input-box">
                    <label for="monday">الأثنين في الساعة: </label>
                    <input type="time" id="monday-time" name="monday-time" disabled>
                </div>
                
                <div class="input-box">
                    <label for="tuesday">الثلاثاء في الساعة: </label>
                    <input type="time" id="tuesday-time" name="tuesday-time" disabled>
                </div>
                
                <div class="input-box">
                    <label for="wednesday">الأربعاء في الساعة: </label>
                    <input type="time" id="wednesday-time" name="wednesday-time" disabled>
                </div>    
                
                <div class="input-box">
                    <label for="thursday">الخميس في الساعة: </label>
                    <input type="time" id="thursday-time" name="thursday-time" disabled>
                </div>
                
                <div class="input-box">
                    <label for="friday">الجمعه في الساعة: </label>
                    <input type="time" id="friday-time" name="friday-time" disabled>
                </div>
                
                <div class="input-box">
                    <label for="saturday">السبت في الساعة: </label>
                    <input type="time" id="saturday-time" name="saturday-time" disabled>
                </div>

            </div>

            <div class="button-container">
                    <button name="submit" type="submit">اضاف الطالب الى الدورة</button>
            </div>

            

        </form>
    </div>

    <script>
        document.getElementById('student').addEventListener('change', function() {
            var selectedStudentId = this.value;
            var selectedStudent = <?php echo json_encode($students); ?>.find(function(student) {
                return student.id == selectedStudentId;
            });
            // Populate input fields with selected student's details
            document.getElementById('firstname').value = selectedStudent['first_name'];
            document.getElementById('lastname').value = selectedStudent['last_name'];
            document.getElementById('email').value = selectedStudent.email;
            document.getElementById('phone').value = selectedStudent['phone_number']; 
            document.getElementById('grade').value = selectedStudent.grade;
            document.getElementById('school').value = selectedStudent.school;
        });
    </script>
    <script>
        document.getElementById('course').addEventListener('change', function() {
            var selectedCourseId = this.value;
            var selectedCourse = <?php echo json_encode($courses); ?>.find(function(course) {
                return course.id == selectedCourseId;
            });
            // Populate input fields with selected course's details
            document.getElementById('subject').value = selectedCourse.subject;
            document.getElementById('teacher').value = selectedCourse['teacher_name'];
            document.getElementById('coursegrade').value = selectedCourse['grade'];
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