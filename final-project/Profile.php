<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "db.php";


if(isset($_POST['submitTeacher']) && $_SESSION["type"] == 'teacher')
{
    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    $teacher_id = $_SESSION["id"];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $userphone = $_SESSION["userphone"];
    $useremail = $_SESSION["useremail"];
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

if(isset($_POST['submitStudent']) && $_SESSION["type"] == 'student')
{

    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    $student_id = $_SESSION["id"];
    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $userphone = $_SESSION["userphone"];
    $useremail = $_SESSION["useremail"];
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
        $_SESSION["fullname"] = $firstname . ' ' . $lastname;
        $_SESSION["firstname"] = $firstname;
        $_SESSION["lastname"] = $lastname;
        $_SESSION["userphone"] = $userphone;
        $_SESSION["useremail"] = $useremail;
        $_SESSION["userpassword"] = $userpassword;
        $_SESSION["usergrade"] = $usergrade;
        $_SESSION["userschool"] = $userschool;
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

// Import for the student data -> studentcontacts
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] == 'student') {
        $studentid = $_SESSION["id"];
        $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Fetch student data from the database
        $query = "SELECT DISTINCT t.id, CONCAT(t.first_name, ' ', t.last_name) AS teacher_full_name
                  FROM studentincourse sic
                  JOIN course c ON sic.course_id = c.id
                  JOIN teacher t ON c.teacher_id = t.id
                  WHERE sic.student_id = ? AND sic.deleted = 0";
        
        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $mysqli->error);
        }
        
        $stmt->bind_param("i", $studentid);
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $studentcontacts = $result->fetch_all(MYSQLI_ASSOC);

    }
}



// Import for the student data -> studentcontacts
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] == 'teacher') { // Assuming you are now checking for teacher type
        $teacherid = $_SESSION["id"];
        $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Fetch student data from the database
        $query = "SELECT DISTINCT sic.student_id, CONCAT(s.first_name, ' ', s.last_name) AS student_full_name
                  FROM studentincourse sic
                  JOIN course c ON sic.course_id = c.id
                  JOIN student s ON sic.student_id = s.id
                  WHERE c.teacher_id = ? AND sic.deleted = 0";
        
        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $mysqli->error);
        }
        
        $stmt->bind_param("i", $teacherid);
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $teachercontacts = $result->fetch_all(MYSQLI_ASSOC);


    }
}

// Import for the student data -> studentcontacts
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] == 'admin') {

        $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Fetch student data from the database
        $query = "SELECT DISTINCT t.id, CONCAT(t.first_name, ' ', t.last_name) AS teacher_full_name
                FROM teacher t WHERE t.deleted = 0 ";

        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $mysqli->error);
        }

        // $stmt->bind_param();
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $adminteachercontacts = $result->fetch_all(MYSQLI_ASSOC);

        $query = "SELECT DISTINCT s.id, CONCAT(s.first_name, ' ', s.last_name) AS student_full_name
                FROM student s WHERE s.deleted = 0 ";

        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $mysqli->error);
        }

        // $stmt->bind_param();
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $adminstudentcontacts = $result->fetch_all(MYSQLI_ASSOC);
    }
}






// COURSES FOR THE STUDENT TO REGIST
$selected_subject = null;
$coursestoregist = [];




if (isset($_POST['submitRegisterStudent'])) {

    $student_id = $_SESSION["id"];
    $course_id = $_POST['course'];

    // Check if the student is already enrolled in the course
    $query = "SELECT * FROM `studentincourse` WHERE `student_id` = ? AND `course_id` = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "انت موجود في هذه الدورة";
        include_once 'PopUpAlert.php';
    } else {
        // Insert the student into the course
        $query = "INSERT INTO `studentincourse` (`student_id`, `course_id`) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ii", $student_id, $course_id);
        $stmt->execute();
        $_SESSION['main_success_message'] = "الاضافة تمت بنجاح";
        $_SESSION['success_message'] = "لقد تمت اضافتك للدورة بنجاح.";
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

    <!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="style3.css">
    <script src="script.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <title>Profile</title>
</head>
<body class="">

<!-- ------------------------------------ SIDE BAR FOR ADMIN ------------------------------------ -->
 
    <?php
        if(isset($_SESSION["type"]))
        {

            if($_SESSION["type"] == 'admin'){
    ?>
    
    <section id="sidebar" >
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">Admin</span>
        </a>
        <ul class="side-menu top">
            <li class="active"> 
                <a href="#" data-target="schedule">
                    <i class='bx bxs-calendar'></i>
                    <span class="text">البرنامج</span>
                </a>
            </li>
            <li>
                <a href="#" data-target="user-management">
                    <i class='bx bxs-user'></i>
                    <span class="text">ادارة المستخدمين</span>
                </a>
            </li>
            <li>
                <a href="#" data-target="content-management">
                    <i class='bx bxs-book-content' ></i>
                    <span class="text">ادارة المحتوى</span>
                </a>
            </li>
            <li>
                <a href="#" data-target="reports">
                    <i class='bx bxs-report' ></i>
                    <span class="text">تقارير</span>
                </a>
            </li>
            <li>
                <a href="#" data-target="messages">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">الرسائل</span>
                </a>
            </li>
            <li>
                <a href="#" data-target="edit-details">
                    <i class='bx bxs-edit-alt'></i>
                    <span class="text">تعديل التفاصيل الشخصية</span>
                </a>
            </li>
            <li>
                <a href="#" data-target="AI-features">
                    <i class='bx bxs-magic-wand'></i>
                    <span class="text">الذكاء الاصطناعي</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog' ></i>
                    <span class="text">الاعدادات</span>
                </a>
            </li>
            <li>
                <a href="SignOut.php" class="logout">
                    <i class='bx bx-log-out'></i>
                    <span class="text">تسجيل الخروج</span>
                </a>
            </li>
        </ul>
    </section>

    
    <?php
    }
    }
    ?>
<!-- ------------------------------------ SIDE BAR FOR STUDENT AND THE TEACHER ------------------------------------ -->
    <?php
    if(isset($_SESSION["type"])){
        if($_SESSION["type"] == 'teacher' || $_SESSION["type"] == 'student' ){
    ?>
        <section id="sidebar">
                <a href="#" class="brand">
                    <i class='bx bxs-smile'></i>
                    <p>مرحبا, 
                        <span class="text">
                            <?php
                            // Check if the session variable is set and display it
                            if (isset($_SESSION["fullname"])) {
                                echo htmlspecialchars($_SESSION["fullname"]);
                            } else {
                                echo "Guest";
                            }
                            ?>
                        </span>
                    </p>
                </a>
                <ul class="side-menu top">
                    <li class="active">
                        <a href="#" data-target="schedule">
                            <i class='bx bxs-calendar'></i>
                            <span class="text">البرنامج</span>
                        </a>
                    </li>
                    <?php
                        if(isset($_SESSION["type"])){
                            if($_SESSION["type"] == 'student' ){
                    ?>
                            <li>
                                <a href="#" data-target="register-course">
                                    <i class='bx bxs-add-to-queue'></i>
                                    <span class="text">تسجيل لدورة جديدة</span>
                                    
                                </a>
                            </li>
                    <?php
                            }
                        }
                    ?>
                    <li>
                        <a href="#" data-target="messages">
                            <i class='bx bxs-message-dots'></i>
                            <span class="text">الرسائل</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-target="edit-details">
                            <i class='bx bxs-edit-alt'></i>
                            <span class="text">تعديل التفاصيل الشخصية</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-target="AI-features">
                            <i class='bx bxs-magic-wand'></i>
                            <span class="text">الذكاء الاصطناعي</span>
                        </a>
                    </li>
                </ul>
                <ul class="side-menu">
                    <li>
                        <a href="#">
                            <i class='bx bxs-cog' ></i>
                            <span class="text">الاعدادات</span>
                        </a>
                    </li>
                    <li>
                        <a href="SignOut.php" class="logout">
                            <i class='bx bx-log-out'></i>
                            <span class="text">تسجيل الخروج</span>
                        </a>
                    </li>
                </ul>
            </section>
        
    <?php
    }
    }
    ?>
<!-- ------------------------------------ SIDE BAR ------------------------------------ -->
 
<!-- ------------------------------------ CONTENT ------------------------------------ -->
<section id="profilecontent">
    <!-------------- NAVBAR -------------->
    <nav>
        <i class='bx bx-menu' ></i>
        <a href="#" class="profilenav-link">الفئات</a>
        <form action="#">
            <div class="form-input">
                <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>	
                <input type="search" placeholder="Search...">
                
            </div>
        </form>
        <input type="checkbox" id="profileswitch-mode" hidden>
        <label for="profileswitch-mode" class="profileswitch-mode"></label>
        <a href="#" class="notification">
            <i class='bx bxs-bell' ></i>
            <span class="num">8</span>
        </a>
        <a href="#" class="imgprofile">
            <img src="photos\12.jpg">
        </a>
    </nav>
	
    <!-------------- NAVBAR -------------->

<!-- ------------------------------------ MAIN FOR THE ADMIN ------------------------------------ -->
    <?php
        if(isset($_SESSION["type"]))
        {
            if($_SESSION["type"] == 'admin'){
    ?>

            <main>
                    <div class="main-content">
                        <div id="schedule" class="content-section active">
                            <h1>البرنامج</h1>
                            <div class="diarycontainer"> 
                                <?php include_once 'diary.php'; ?>
                            </div>
                        </div>
                        <div id="user-management" class="content-section">
                            <h1>ادارة المستخدمين</h1>
                            <ul class="box-info">
                                <li>
                                    <a href="AddStudent.php" data-target="schedule">
                                        <span class="text">
                                            <h3>اضافة</h3>
                                            <p>طالب جديد</p>
                                        </span>
                                        <i class='bx bxs-user-plus'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="EditStudent.php" data-target="schedule">
                                        <span class="text">
                                            <h3>تعديل</h3>
                                            <p>تعديل تفاصيل طالب</p>
                                        </span>
                                        <i class='bx bxs-user-detail'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="DeleteStudent.php" data-target="schedule">
                                        <span class="text">
                                            <h3>حذف</h3>
                                            <p>حذف طالب</p>
                                        </span>
                                        <i class='bx bxs-user-minus'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="AddTeacher.php" data-target="schedule">
                                        <span class="text">
                                            <h3>اضافة</h3>
                                            <p>معلم جديد</p>
                                        </span>
                                        <i class='bx bxs-user-plus'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="EditTeacher.php" data-target="schedule">
                                        <span class="text">
                                            <h3>تعديل</h3>
                                            <p>تعديل تفاصيل معلم</p>
                                        </span>
                                        <i class='bx bxs-user-detail'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="DeleteTeacher.php" data-target="schedule">
                                        <span class="text">
                                            <h3>حذف</h3>
                                            <p>حذف معلم</p>
                                        </span>
                                        <i class='bx bxs-user-minus'></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div id="content-management" class="content-section">
                            <!-- Content for 'ادارة المحتوى' -->
                            <h1>ادارة المحتوى</h1>
                            <ul class="box-info">
                                <li>
                                    <a href="AddCourse.php" data-target="schedule">
                                        <span class="text">
                                            <h3>اضافة</h3>
                                            <p>إضافة دورة جديد</p>
                                        </span>
                                        <i class='bx bxs-plus-square'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="EditCourse.php" data-target="schedule">
                                        <span class="text">
                                            <h3>تعديل</h3>
                                            <p>تعديل تفاصيل دورة</p>
                                        </span>
                                        <i class='bx bxs-edit'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="DeleteCourse.php" data-target="schedule">
                                        <span class="text">
                                            <h3>حذف</h3>
                                            <p>حذف دورة</p>
                                        </span>
                                        <i class='bx bxs-minus-square'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="AddStudentToCourse.php" data-target="schedule">
                                        <span class="text">
                                            <h3>اضافة</h3>
                                            <p>إضافة طالب إلى دورة</p>
                                        </span>
                                        <i class='bx bxs-user-plus'></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="DeleteStudentFromCourse.php" data-target="schedule">
                                        <span class="text">
                                            <h3>حذف</h3>
                                            <p>حذف طالب من دورة</p>
                                        </span>
                                        <i class='bx bxs-user-minus'></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div id="reports" class="content-section">
                            <!-- Content for 'تقارير' -->
                            <h1>تقارير</h1>

                            <a href="#" class="btn-download">
                                <i class='bx bxs-cloud-download' ></i>
                                <span class="text">Download PDF</span>
                            </a>
                

                            <ul class="box-info">
                                <li>
                                    <a href="#" data-target="schedule">
                                        <span class="text">
                                            <h3>تقرير</h3>
                                            <p>تقرير 1</p>
                                        </span>
                                        <i class='bx bxs-report' ></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-target="schedule">
                                        <span class="text">
                                            <h3>تقرير</h3>
                                            <p>تقرير 2</p>
                                        </span>
                                        <i class='bx bxs-report' ></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-target="schedule">
                                        <span class="text">
                                            <h3>تقرير</h3>
                                            <p>تقرير 3</p>
                                        </span>
                                        <i class='bx bxs-report' ></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div id="messages" class="content-section">
                            <!-- Content for 'الرسائل' -->
                            <h1>الرسائل</h1>
                            <div class="message-content">
                                <div class="message-content-right">
                                    <ul class="contact-user">
                                        <!-- Displaying student contacts -->
                                        <?php if(isset($_SESSION["type"]) && $_SESSION["type"] == 'admin'): ?>
                                            <?php foreach ($adminteachercontacts as $contact): ?>
                                                <li class="">
                                                    <a href="#" class="contact-link" data-id="<?php echo $contact['id']; ?>" data-name="<?php echo htmlspecialchars($contact['teacher_full_name']); ?>">
                                                        <i class='bx bxs-user-circle'></i>
                                                        <span class="text">
                                                            <h3><?php echo 'الاستاذ: ' . htmlspecialchars($contact['teacher_full_name']); ?></h3>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <!-- Displaying teacher contacts -->
                                        <?php if(isset($_SESSION["type"]) && $_SESSION["type"] == 'admin'): ?>
                                            <?php foreach ($adminstudentcontacts as $contact): ?>
                                                <li class="">
                                                    <a href="#" class="contact-link" data-id="<?php echo $contact['id']; ?>" data-name="<?php echo htmlspecialchars($contact['student_full_name']); ?>" >
                                                        <i class='bx bxs-user-circle'></i>
                                                        <span class="text">
                                                            <h3><?php echo "الطالب: " . htmlspecialchars($contact['student_full_name']); ?></h3>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                    <input type="hidden" name="receiverIdInput" id="receiverIdInput">
                                    <script>
                                    $(document).ready(function() {
                                        $('.contact-link').click(function(e) {
                                            e.preventDefault();

                                            var $parentLi = $(this).closest('li');

                                            if ($parentLi.hasClass('active')) {
                                                // If already active, remove the active class
                                                $parentLi.removeClass('active');
                                                $('#message-header i').removeClass('bx bxs-user bxs-user-circle'); // Remove all classes
                                                $('#message-header h3').text(''); // Clear header
                                                $('.messagechatbox').html(''); // Clear message chatbox
                                            } else {
                                                // If not active, make this active and load messages
                                                $('.contact-user li').removeClass('active'); // Remove active class from all <li>
                                                $parentLi.addClass('active'); // Add active class to the clicked <li>

                                                // Get data attributes
                                                var fullName = $(this).data('name');
                                                var studentId = $(this).data('id');
                                                var iconClass = $(this).find('i').attr('class');

                                                // Update message header
                                                $('#message-header i').attr('class', iconClass);
                                                $('#message-header h3').text(fullName);

                                                // Update hidden input for receiver ID
                                                $('#receiverIdInput').val(studentId);

                                                // Load messages for selected user
                                                loadMessages(studentId);
                                            }
                                        });

                                        function loadMessages(studentId) {
                                            $.ajax({
                                                url: 'fetch_messages.php',
                                                method: 'POST',
                                                data: { studentId: studentId },
                                                success: function(response) {
                                                    $('.messagechatbox').html(response); // Update messagechatbox with fetched messages
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('Error loading messages:', error);
                                                }
                                            });
                                        }
                                    });

                                    </script>

                                </div>
                                <div class="message-content-left">
                                    <div id="message-header">
                                        <span>
                                            <i></i>
                                            <h3></h3>
                                        </span> 
                                    </div>
                                    <div id="message-container" >
                                        <div class="messagechatbox" >
                                        
                                        </div>
                                        
                                        <form id="messageForm" class="typing-area">
                                            <button type="submit" name="send" class="messagebtn"><i class='bx bx-send'></i></button>
                                            <input type="text" name="messagetext" class="input-field" placeholder="أدخل رسالة..." autocomplete="off">
                                        </form>
                                    </div>
                                    <script>
                                    $(document).ready(function() {
                                        // Function to load messages
                                        function loadMessages(studentId) {
                                            // AJAX request to fetch messages
                                            $.ajax({
                                                url: 'fetch_messages.php',
                                                method: 'POST',
                                                data: { studentId: studentId },
                                                success: function(response) {
                                                    $('.messagechatbox').html(response); // Update messagechatbox with fetched messages
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('Error loading messages:', error);
                                                }
                                            });
                                        }

                                        // Event handler for form submission
                                        $('#messageForm').submit(function(e) {
                                            e.preventDefault(); // Prevent default form submission

                                            // Get form data
                                            var formData = $(this).serialize();

                                            // Perform AJAX request to send message
                                            $.ajax({
                                                url: 'send_message.php', 
                                                type: 'POST',
                                                data: formData,
                                                success: function(response) {
                                                    console.log('Message sent successfully:', response);
                                                    
                                                    // Optionally, clear the input field after successful submission
                                                    $('#messagetext').val('');
                                                    
                                                    // Reload messages or update the chatbox as needed
                                                    var receiverId = $('#receiverIdInput').val();
                                                    loadMessages(receiverId); // Call your function to reload messages
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('Error sending message:', error);
                                                }
                                            });
                                        });

                                        // Initial load of messages when page loads
                                        var initialReceiverId = $('#receiverIdInput').val();
                                        if (initialReceiverId) {
                                            loadMessages(initialReceiverId);
                                        }
                                    });
                                    </script>

                                </div>
                            </div>


                        </div>
                        <div id="edit-details" class="content-section">
                            <!-- Content for 'تعديل التفاصيل الشخصية' -->
                            <h1>التفاصيل الشخصية</h1>
                            <div class="profilecontainer">
                                <form action="" method="post" >
                                    <div class="profilecontant">
                                        <div class="profile-input-box">
                                        <label for="name">اسم المستخدم</label>
                                            <input type="text" placeholder="الاسم الشخصي" name="Firstname" id="Firstname" value="<?php echo isset($_SESSION["useremail"]) ? htmlspecialchars($_SESSION["useremail"]) : ''; ?>" disabled >
                                        </div>
                                        <div class="profile-input-box">
                                            <label for="name">كلمة السر</label>
                                            <input type="text" placeholder="اسم العائلة" name="Lastname" id="Lastname" value="<?php echo isset($_SESSION["userpassword"]) ? htmlspecialchars($_SESSION["userpassword"]) : ''; ?>" disabled > 
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="AI-features" class="content-section">
                            <!-- Content for 'الذكاء الاصطناعي' -->
                            <h1>الذكاء الاصطناعي</h1>
                            <div class="aicontainer"> 
                                <?php include_once 'ChatBot.php'; ?>
                            </div>
                        </div>
                    </div>
                </main>
    <?php
            }
        }
    
    ?>
<!-- ------------------------------------ MAIN FOR THE STUDENT AND THE TEACHER ------------------------------------ -->

    <?php
    if(isset($_SESSION["type"])){
        if($_SESSION["type"] == 'teacher' || $_SESSION["type"] == 'student' ){
    ?>
            <main>
                <div class="main-content">
                    <div id="schedule" class="content-section active">
                        <!-- Content for 'البرنامج' -->    
                        <h1>البرنامج</h1>
                        <div class="diarycontainer"> 
                            <?php include_once 'diary.php'; ?>
                       </div>
                    </div>

                    <?php
                        if(isset($_SESSION["type"])){
                            if($_SESSION["type"] == 'student' ){
                    ?>

                            <div id="register-course" class="content-section">
                                <!-- Content for 'تسجيل لدورة جديدة' -->
                                <h1>تسجيل لدورة جديدة</h1>
                                <div class="profilecontainer">
                                    <form action="" method="post" >
                                        <div class="profilecontant">
                                            <div class="profile-input-box">
                                                <label for="subject">الموضوع:</label>
                                                <select class="select" name="subject" id="subject">
                                                    <option value="" disabled selected>اختر الموضوع</option>
                                                    <option value="math">رياضيات</option>
                                                    <option value="physics">فيزياء</option>
                                                    <option value="chemistry">كيمياء</option>
                                                    <option value="biology">بيولوجيا</option>
                                                    <option value="arts">فن</option>
                                                </select>
                                            </div>
                                            <div class="profile-button-container">
                                                <!-- BUTTON TO SHOW ALL THE RELEVANT COURSES.. -->
                                                <button id="submitsubject" type="button">عرض جميع الدورات الملاءمة</button>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="course">تفاصيل الدورة</label>
                                                <select class="profile-input-box" name="course" id="course">
                                                    <?php foreach ($coursestoregist as $course): ?>
                                                        <option value="<?php echo $course['id']; ?>"><?php echo $course['id']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="profile-input-box"></div>
                                            <div class="profile-input-box">
                                                <label for="teacher">المعلم</label>
                                                <input type="text" name="registerteacher" id="teacher" disabled>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="coursegrade">الصف</label>
                                                <input type="text" name="registercoursegrade" id="coursegrade" disabled>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="sunday">الأحد في الساعة: </label>
                                                <input type="time" id="sunday-time" name="register-sunday-time" disabled>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="monday">الأثنين في الساعة: </label>
                                                <input type="time" id="monday-time" name="register-monday-time" disabled>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="tuesday">الثلاثاء في الساعة: </label>
                                                <input type="time" id="tuesday-time" name="register-tuesday-time" disabled>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="wednesday">الأربعاء في الساعة: </label>
                                                <input type="time" id="wednesday-time" name="register-wednesday-time" disabled>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="thursday">الخميس في الساعة: </label>
                                                <input type="time" id="thursday-time" name="register-thursday-time" disabled>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="friday">الجمعه في الساعة: </label>
                                                <input type="time" id="friday-time" name="register-friday-time" disabled>
                                            </div>
                                            <div class="profile-input-box">
                                                <label for="saturday">السبت في الساعة: </label>
                                                <input type="time" id="saturday-time" name="register-saturday-time" disabled>
                                            </div>
                                            <div class="profile-input-box"></div>

                                        </div>
                                        <div class="profile-button-container">
                                            <button name="submitRegisterStudent" type="submitStudent">التسجيل للدورة</button>
                                        </div>
                                    </form>
                                </div>
                            
                                <script>
                                    $(document).ready(function(){
                                    // Function to update the courses <select> element
                                    function updateCoursesSelect(courses) {
                                        var $courseSelect = $('#course');
                                        $courseSelect.empty(); // Clear existing options

                                        courses.forEach(function(course) {
                                            var option = $('<option>', {
                                                value: course.id,
                                                text: course.id // Adjust based on how you want to display course options
                                            });
                                            $courseSelect.append(option);
                                        });
                                    }

                                    $('#submitsubject').on('click', function() {
                                        var subject = $('#subject').val();
                                        $.ajax({
                                            url: 'fetch_courses.php',
                                            type: 'POST',
                                            data: { subject: subject },
                                            success: function(response) {
                                                console.log("Response from fetch_courses.php: ", response);
                                                try {
                                                    if (typeof response === 'string') {
                                                        response = response.trim(); // Trim whitespace
                                                        var courses = JSON.parse(response);
                                                    } else {
                                                        var courses = response; // Assuming it's already parsed
                                                    }
                                                    // Update the courses select options
                                                    updateCoursesSelect(courses);

                                                    // Save courses to global variable (if needed)
                                                    window.coursestoregist = courses;
                                                } catch (e) {
                                                    console.error("Error parsing JSON: ", e);
                                                    console.error("Response: ", response);
                                                    alert("An error occurred: " + response);
                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                console.error("AJAX Error: ", status, error);
                                            }
                                        });
                                    });

                                    // Event listener for course selection change
                                    $('#course').on('change', function() {
                                        var selectedCourseId = this.value;
                                        var courses = window.coursestoregist || []; // Use global variable

                                        var selectedCourse = courses.find(function(course) {
                                            return course.id == selectedCourseId;
                                        });

                                        if (selectedCourse) {
                                            document.getElementById('teacher').value = selectedCourse['teacher_name'];
                                            document.getElementById('coursegrade').value = selectedCourse['grade'];
                                            document.getElementById('sunday-time').value = selectedCourse['sunday_time'];
                                            document.getElementById('monday-time').value = selectedCourse['monday_time'];
                                            document.getElementById('tuesday-time').value = selectedCourse['tuesday_time'];
                                            document.getElementById('wednesday-time').value = selectedCourse['wednesday_time'];
                                            document.getElementById('thursday-time').value = selectedCourse['thursday_time'];
                                            document.getElementById('friday-time').value = selectedCourse['friday_time'];
                                            document.getElementById('saturday-time').value = selectedCourse['saturday_time'];
                                        }
                                    });
                                });

                                </script>

                            </div>

                    <?php
                            }
                        }
                    ?>
                    
                    <div id="messages" class="content-section">
                        <!-- Content for 'الرسائل' -->
                        <h1>الرسائل</h1>
<!-- ------------------------------------ MESSAGE ------------------------------------ -->
                        <div class="message-content">
                            <div class="message-content-right">
                                <ul class="contact-user">
                                    <li class="">
                                        <a href="#" class="contact-link" data-id="admin" data-name="Admin Admin" >
                                            <i class='bx bxs-user'></i>
                                            <span class="text">
                                                <h3>Admin Admin</h3>
                                            </span>
                                        </a>
                                    </li>
                                    <!-- Displaying student contacts -->
                                    <?php if(isset($_SESSION["type"]) && $_SESSION["type"] == 'student'): ?>
                                        <?php foreach ($studentcontacts as $contact): ?>
                                            <li class="">
                                                <a href="#" class="contact-link" data-id="<?php echo $contact['id']; ?>" data-name="<?php echo htmlspecialchars($contact['teacher_full_name']); ?>">
                                                    <i class='bx bxs-user-circle'></i>
                                                    <span class="text">
                                                        <h3><?php echo htmlspecialchars($contact['teacher_full_name']); ?></h3>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Displaying teacher contacts -->
                                    <?php if(isset($_SESSION["type"]) && $_SESSION["type"] == 'teacher'): ?>
                                        <?php foreach ($teachercontacts as $contact): ?>
                                            <li class="">
                                                <a href="#" class="contact-link" data-id="<?php echo $contact['student_id']; ?>" data-name="<?php echo htmlspecialchars($contact['student_full_name']); ?>" >
                                                    <i class='bx bxs-user-circle'></i>
                                                    <span class="text">
                                                        <h3><?php echo htmlspecialchars($contact['student_full_name']); ?></h3>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                                <input type="hidden" name="receiverIdInput" id="receiverIdInput">
                                <script>
                                $(document).ready(function() {
                                    $('.contact-link').click(function(e) {
                                        e.preventDefault();

                                        var $parentLi = $(this).closest('li');

                                        if ($parentLi.hasClass('active')) {
                                            // If already active, remove the active class
                                            $parentLi.removeClass('active');
                                            $('#message-header i').removeClass('bx bxs-user bxs-user-circle'); // Remove all classes
                                            $('#message-header h3').text(''); // Clear header
                                            $('.messagechatbox').html(''); // Clear message chatbox
                                        } else {
                                            // If not active, make this active and load messages
                                            $('.contact-user li').removeClass('active'); // Remove active class from all <li>
                                            $parentLi.addClass('active'); // Add active class to the clicked <li>

                                            // Get data attributes
                                            var fullName = $(this).data('name');
                                            var studentId = $(this).data('id');
                                            var iconClass = $(this).find('i').attr('class');

                                            // Update message header
                                            $('#message-header i').attr('class', iconClass);
                                            $('#message-header h3').text(fullName);

                                            // Update hidden input for receiver ID
                                            $('#receiverIdInput').val(studentId);

                                            // Load messages for selected user
                                            loadMessages(studentId);
                                        }
                                    });

                                    function loadMessages(studentId) {
                                        $.ajax({
                                            url: 'fetch_messages.php',
                                            method: 'POST',
                                            data: { studentId: studentId },
                                            success: function(response) {
                                                $('.messagechatbox').html(response); // Update messagechatbox with fetched messages
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error loading messages:', error);
                                            }
                                        });
                                    }
                                });

                                </script>

                            </div>
                            <div class="message-content-left">
                                <div id="message-header">
                                    <span>
                                        <i></i>
                                        <h3></h3>
                                    </span> 
                                </div>
                                <div id="message-container" >
                                    <div class="messagechatbox" >
                                    
                                    </div>
                                    
                                    <form id="messageForm" class="typing-area">
                                        <button type="submit" name="send" class="messagebtn"><i class='bx bx-send'></i></button>
                                        <input type="text" name="messagetext" class="input-field" placeholder="أدخل رسالة..." autocomplete="off">
                                    </form>
                                </div>
                                <script>
                                $(document).ready(function() {
                                    // Function to load messages
                                    function loadMessages(studentId) {
                                        // AJAX request to fetch messages
                                        $.ajax({
                                            url: 'fetch_messages.php',
                                            method: 'POST',
                                            data: { studentId: studentId },
                                            success: function(response) {
                                                $('.messagechatbox').html(response); // Update messagechatbox with fetched messages
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error loading messages:', error);
                                            }
                                        });
                                    }

                                    // Event handler for form submission
                                    $('#messageForm').submit(function(e) {
                                        e.preventDefault(); // Prevent default form submission

                                        // Get form data
                                        var formData = $(this).serialize();

                                        // Perform AJAX request to send message
                                        $.ajax({
                                            url: 'send_message.php', 
                                            type: 'POST',
                                            data: formData,
                                            success: function(response) {
                                                console.log('Message sent successfully:', response);
                                                
                                                // Optionally, clear the input field after successful submission
                                                $('#messagetext').val('');
                                                
                                                // Reload messages or update the chatbox as needed
                                                var receiverId = $('#receiverIdInput').val();
                                                loadMessages(receiverId); // Call your function to reload messages
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error sending message:', error);
                                            }
                                        });
                                    });

                                    // Initial load of messages when page loads
                                    var initialReceiverId = $('#receiverIdInput').val();
                                    if (initialReceiverId) {
                                        loadMessages(initialReceiverId);
                                    }
                                });
                                </script>

                            </div>
                        </div>




<!-- ------------------------------------ MESSAGE ------------------------------------ -->
                        </div>
                    <div id="edit-details" class="content-section">
                        <!-- Content for 'تعديل التفاصيل الشخصية' -->
                        <h1>تعديل التفاصيل الشخصية</h1>
<!-- ------------------------------------ EDIT STUDENT DETAILS ------------------------------------ -->
                        <?php
                        if(isset($_SESSION["type"])){
                            if($_SESSION["type"] == 'student' ){
                        ?>
                            <div class="profilecontainer">
                                <form action="" method="post" >
                                    <div class="profilecontant">
                                        <div class="profile-input-box">
                                        <label for="name">الاسم الشخصي</label>
                                            <input type="text" placeholder="الاسم الشخصي" name="Firstname" id="Firstname" value="<?php echo isset($_SESSION["firstname"]) ? htmlspecialchars($_SESSION["firstname"]) : ''; ?>" >
                                        </div>
                                        <div class="profile-input-box">
                                            <label for="name">العائلة</label>
                                            <input type="text" placeholder="اسم العائلة" name="Lastname" id="Lastname" value="<?php echo isset($_SESSION["lastname"]) ? htmlspecialchars($_SESSION["lastname"]) : ''; ?>" > 
                                        </div>
                                        <div class="profile-input-box">
                                            <label for="phone">رقم الهاتف</label>
                                            <input type="tel" placeholder="رقم الهاتف" name="phone" id="phone" value="<?php echo isset($_SESSION["userphone"]) ? htmlspecialchars($_SESSION["userphone"]) : ''; ?>" disabled>
                                        </div>
                                        
                                        <div class="profile-input-box">
                                            <label for="grade">الصف:</label>
                                            <select class="profileselect" name="grade" id="grade">
                                                <option value="" disabled selected>اختر الصف</option>
                                                <option value="Grade 7" <?php echo ($_SESSION["usergrade"] == 7) ? "selected" : ""; ?> >السابع</option>
                                                <option value="Grade 8" <?php echo ($_SESSION["usergrade"] == 8) ? "selected" : ""; ?> >الثامن</option>
                                                <option value="Grade 9" <?php echo ($_SESSION["usergrade"] == 9) ? "selected" : ""; ?> >التاسع</option>
                                                <option value="Grade 10" <?php echo ($_SESSION["usergrade"] == 10) ? "selected" : ""; ?> >العاشر</option>
                                                <option value="Grade 11" <?php echo ($_SESSION["usergrade"] == 11) ? "selected" : ""; ?> >الحادي عشر</option>
                                                <option value="Grade 12" <?php echo ($_SESSION["usergrade"] == 12) ? "selected" : ""; ?> >الثاني عشر</option>
                                            </select>
                                        </div>

                                        <div class="profile-input-box">
                                            <label for="email">البريد الالكتروني</label>
                                            <input type="text" placeholder="البريد الالكتروني" name="email" id="email" value="<?php echo isset($_SESSION["useremail"]) ? htmlspecialchars($_SESSION["useremail"]) : ''; ?>" disabled>
                                        </div>

                                        
                                        <div class="profile-input-box">
                                            <label for="school">المدرسة:</label>
                                            <select class="profileselect" name="school" id="school">
                                                <option value="" disabled selected>اختر المدرسة</option>
                                                <option value="مدرسة البيان" <?php echo ($_SESSION["userschool"] == "مدرسة البيان") ? "selected" : ""; ?> >مدرسة البيان</option>
                                                <option value="مدرسة الخورزمي" <?php echo ($_SESSION["userschool"] == "مدرسة الخورزمي") ? "selected" : ""; ?> >مدرسة الخورزمي</option>
                                                <option value="مدرسة الدكتور هشام" <?php echo ($_SESSION["userschool"] == "مدرسة الدكتور هشام") ? "selected" : ""; ?> >مدرسة الدكتور هشام</option>
                                                <option value="مدرسة الفرابي" <?php echo ($_SESSION["userschool"] == "مدرسة الفرابي" ) ? "selected" : ""; ?> >مدرسة الفرابي</option>
                                                <option value="مدرسة ابن خلدون" <?php echo ($_SESSION["userschool"] == "مدرسة ابن خلدون") ? "selected" : ""; ?> >مدرسة ابن خلدون</option>
                                                <option value="مدرسة المتنبي" <?php echo ($_SESSION["userschool"] == "مدرسة المتنبي") ? "selected" : ""; ?> >مدرسة المتنبي</option>
                                                <option value="مدرسة الحكمة" <?php echo ($_SESSION["userschool"] == "مدرسة الحكمة") ? "selected" : ""; ?> >مدرسة الحكمة</option>
                                            </select>
                                        </div>

                                        <div class="profile-input-box">
                                            <label for="password">كلمة السر</label>
                                            <input type="password" placeholder="كلمة السر" name="password" id="password" value="<?php echo isset($_SESSION["userpassword"]) ? htmlspecialchars($_SESSION["userpassword"]) : ''; ?>" >
                                            <div class="profile-password-button">
                                                <button type="button" class="profile-toggle-password">عرض</button>
                                            </div>
                                        </div>

                                        <div class="profile-input-box"> <!-- empty div -->
                                        </div>
                                    </div>    
                                    
                                    <div class="profile-button-container">
                                        <button name="submitStudent" type="submitStudent">تعديل التفاصيل</button>
                                    </div>

                                </form>
                            </div>

                            <script>
                                document.querySelector('.profile-toggle-password').addEventListener('click', function () {
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
                        <?php
                            }
                        }
                        ?>
<!-- ------------------------------------ EDIT TEACHER DETAILS ------------------------------------ -->
                        <?php
                        if(isset($_SESSION["type"])){
                            if($_SESSION["type"] == 'teacher'){
                        ?>
                            <div class="profilecontainer">
                                <form action="" method="post">
                                    <div class="profilecontant">
                                        <div class="profile-input-box">
                                            <label for="firstname">الاسم الشخصي</label>
                                            <input type="text" placeholder="ادخل الاسم الشخصي" name="firstname" id="firstname" value="<?php echo isset($_SESSION["firstname"]) ? htmlspecialchars($_SESSION["firstname"]) : ''; ?>" >
                                        </div>
                                        <div class="profile-input-box">
                                            <label for="lastname">اسم العائلة</label>
                                            <input type="text" placeholder="ادخل اسم العائلة" name="lastname" id="lastname" value="<?php echo isset($_SESSION["lastname"]) ? htmlspecialchars($_SESSION["lastname"]) : ''; ?>" >
                                        </div>
                                        <div class="profile-input-box">
                                            <label for="phone">رقم الهاتف</label>
                                            <input type="tel" placeholder="ادخل رقم الهاتف" name="phone" id="phone" value="<?php echo isset($_SESSION["userphone"]) ? htmlspecialchars($_SESSION["userphone"]) : ''; ?>" disabled>
                                        </div>
                                        <div class="profile-input-box">
                                            <label for="email">البريد الالكتروني</label>
                                            <input type="text" placeholder="ادخل البريد الالكتروني" name="email" id="email" value="<?php echo isset($_SESSION["useremail"]) ? htmlspecialchars($_SESSION["useremail"]) : ''; ?>" disabled>
                                        </div>
                                        <div class="profile-input-box">
                                            <label for="password">كلمة المرور</label>
                                            <input type="password" placeholder="ادخل كلمة المرور" name="password" id="password" value="<?php echo isset($_SESSION["userpassword"]) ? htmlspecialchars($_SESSION["userpassword"]) : ''; ?>" >
                                            <div class="profile-password-button">
                                                <button type="button" class="profile-toggle-password">عرض</button>
                                            </div>
                                        </div>
                                        

                                        <span class="subject-title">الموضوع:</span>
                                        <div class="profile-subject-category">
                                            <input type="hidden" name="math" value='0'>
                                            <input type="checkbox" id="math" name="math" value='1' value="1" <?php echo (isset($_SESSION["math"]) && $_SESSION["math"] == 1) ? 'checked' : ''; ?> >
                                            <label for="math"> رياضيات</label>
                                            <input type="hidden" name="physics" value='0'>
                                            <input type="checkbox" id="physics" name="physics" value='1' value="1" <?php echo (isset($_SESSION["physics"]) && $_SESSION["physics"] == 1) ? 'checked' : ''; ?> >
                                            <label for="physics"> فيزياء</label>
                                            <input type="hidden" name="chemistry" value='0'>
                                            <input type="checkbox" id="chemistry" name="chemistry" value='1' value="1" <?php echo (isset($_SESSION["chemistry"]) && $_SESSION["chemistry"] == 1) ? 'checked' : ''; ?> >
                                            <label for="chemistry"> كيمياء</label>
                                            <input type="hidden" name="biology" value='0'>
                                            <input type="checkbox" id="biology" name="biology" value='1' value="1" <?php echo (isset($_SESSION["biology"]) && $_SESSION["biology"] == 1) ? 'checked' : ''; ?> >
                                            <label for="biology"> بيولوجيا</label>
                                            <input type="hidden" name="arts" value='0'>
                                            <input type="checkbox" id="arts" name="arts" value='1' value="1" <?php echo (isset($_SESSION["arts"]) && $_SESSION["arts"] == 1) ? 'checked' : ''; ?> >
                                            <label for="arts"> فنون</label>
                                        </div>
                                    </div>

                                    <div class="profile-button-container">
                                        <button name="submitTeacher" type="submitTeacher">تعديل التفاصيل</button>
                                    </div>

                                    <script>
                                        document.querySelector('.profile-toggle-password').addEventListener('click', function () {
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
                                
                                </form>
                            </div>
                            
                        <?php
                            }
                        }
                        ?>

                    </div>
                    <div id="AI-features" class="content-section">
                        <!-- Content for 'الذكاء الاصطناعي' -->
                        <h1>الذكاء الاصطناعي</h1>
                        <div class="aicontainer"> 
                            <?php include_once 'ChatBot.php'; ?>
                        </div>
                    </div>
                </div>
                
            </main>
    <?php
        }
    }
    ?>

    
    
    <!-- MAIN -->





</section>





<!-- ------------ CONTENT ------------ -->




</body>
</html>