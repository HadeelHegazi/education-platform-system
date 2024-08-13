<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "db.php";


?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,100,1,200" rel="stylesheet" />     
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">



<!-----------------------------------------Primary Navigation Links---------------------------------------------------------->
<?php
if(!isset($_SESSION["type"]))
{
    ?>


    <header>
    <nav class="navbar">
        <a href="#" class="logo" >معهد البراق<span>.</span> </a>
        <ul class="menu-link">
            <span id="close-menu-btn" class="material-symbols-outlined">close</span>
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="SignIn.php">تسجيل الدخول</a></li>
            <li><a href="SignUp.php">إنشاء حساب</a></li> <!-- TBD --->
            <li><a href="AboutUs.php">معلومات عنا</a></li><!-- TBD --->
            <li><a href="ContactUs.php">اتصل بنا</a></li>    <!-- TBD --->     
            <li><a href="TeacherCvUpload.php">تقديم للعمل </a></li>    <!-- TBD --->        
        </ul>
        <span id="menu-btn" class="material-symbols-outlined">menu</span>
    </nav>
</header> 


<?php
}
?>
<!----------------------------------------------User Account Links----------------------------------------------------->
<?php
if(isset($_SESSION["type"])){
if($_SESSION["type"] == 'teacher' || $_SESSION["type"] == 'student' ){
    ?>
    <header>
        <nav class="navbar">
            <a href="#" class="logo" >معهد البراق<span>.</span> </a>
            <ul class="menu-link">
                <span id="close-menu-btn" class="material-symbols-outlined">close</span>
                <li><a href="Profile.php">الملف الشخصي</a></li> <!-- TBD --->
                <li><a href="SignOut.php">تسجيل الخروج</a></li>
                <li><a href="#">التسجيل في دورة جديدة</a></li> <!-- TBD --->
                
            </ul>
            <span id="menu-btn" class="material-symbols-outlined">menu</span>
        </nav>
    </header>

<?php
}
}
?>


<!------------------------------------------Admin Navigation Bar--------------------------------------------------------->
<?php
if(isset($_SESSION["type"]))
{

    if($_SESSION["type"] == 'admin'){
?>


    <header>
        <nav class="navbar">
            <a href="#" class="logo" >معهد البراق<span>.</span> </a>
            <ul class="menu-link">
                <span id="close-menu-btn" class="material-symbols-outlined">close</span>

                <li class="nav-link services">
                    <a href="#">إدارة المستخدمين
                        <span class="material-icons dropdown-icon">
                            arrow_drop_down
                        </span>
                    </a>
                    <ul class="drop-down">
                        <li><a href="AddStudent.php">إضافة طالب جديد</a></li>
                        <li><a href="EditStudent.php">تعديل تفاصيل طالب</a></li>
                        <li><a href="DeleteStudent.php">حذف طالب</a></li>
                        <li><a href="AddTeacher.php">إضافة معلم جديد</a></li>
                        <li><a href="EditTeacher.php">تعديل تفاصيل معلم</a></li>
                        <li><a href="DeleteTeacher.php">حذف معلم</a></li>
                    </ul>
                </li>

                <li class="nav-link services">
                    <a href="#">إدارة المحتوى
                        <span class="material-icons dropdown-icon">
                            arrow_drop_down
                        </span>
                    </a>
                    <ul class="drop-down">
                        <li><a href="AddCourse.php">إضافة دورة جديد</a></li>
                        <li><a href="EditCourse.php">تعديل تفاصيل دورة</a></li>
                        <li><a href="DeleteCourse.php">حذف دورة</a></li>
                        <li><a href="AddStudentToCourse.php">إضافة طالب إلى دورة</a></li>
                        <li><a href="DeleteStudentFromCourse.php">حذف طالب من دورة</a></li>
                    </ul>
                </li>

                <li><a href="Reports.php">التقارير</a></li> <!-- TBD --->
                <li><a href="Profile.php">الملف الشخصي</a></li> <!-- TBD --->
                <li><a href="SignOut.php">تسجيل الخروج</a></li>
                            
            </ul>
            <span id="menu-btn" class="material-symbols-outlined">menu</span>
        </nav>
    </header>
    
<?php
}
}
?>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdowns = document.querySelectorAll('.nav-link.services'); // Target specific dropdowns
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('mouseover', function() {
                    var dropdownContent = this.querySelector('.drop-down');
                    dropdownContent.style.display = 'block';
                });

                dropdown.addEventListener('mouseout', function() {
                    var dropdownContent = this.querySelector('.drop-down');
                    dropdownContent.style.display = 'none';
                });
            });
        });


        const header = document.querySelector("header");
        const menuBtn = document.querySelector("#menu-btn");
        const closeMenuBtn = document.querySelector("#close-menu-btn");

        // Toggle mobile menu on menu button click
        menuBtn.addEventListener("click", () => {
            header.classList.toggle("show-mobile-menu");
        });

        // Close mobile menu on close button click
        closeMenuBtn.addEventListener("click", () => {
            header.classList.remove("show-mobile-menu");
        });
    </script>

