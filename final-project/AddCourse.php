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

    $subject = $_POST['subject'];
    $teacher = $_POST['teacher'];

    if($_POST['grade'] == 'Grade 7') $coursegrade = 7;
    if($_POST['grade'] == 'Grade 8') $coursegrade = 8;
    if($_POST['grade'] == 'Grade 9') $coursegrade = 9;
    if($_POST['grade'] == 'Grade 10') $coursegrade = 10;
    if($_POST['grade'] == 'Grade 11') $coursegrade = 11;
    if($_POST['grade'] == 'Grade 12') $coursegrade = 12;

    if($_POST['sunday'] == '1')
    {
        $sunday=1;
        $sundaytime= $_POST['sunday-time'];
    } else {
        $sunday=0;
        $sundaytime= null;
    }

    if($_POST['monday'] == '1')
    {
        $monday=1;
        $mondaytime= $_POST['monday-time'];
    } else {
        $monday=0;
        $mondaytime= null;
    }

    if($_POST['tuesday'] == '1')
    {
        $tuesday=1;
        $tuesdaytime= $_POST['tuesday-time'];
    } else {
        $tuesday=0;
        $tuesdaytime= null;
    }

    if($_POST['wednesday'] == '1')
    {
        $wednesday=1;
        $wednesdaytime= $_POST['wednesday-time'];
    } else {
        $wednesday=0;
        $wednesdaytime= null;
    }

    if($_POST['thursday'] == '1')
    {
        $thursday=1;
        $thursdaytime= $_POST['thursday-time'];
    } else {
        $thursday=0;
        $thursdaytime= null;
    }

    if($_POST['friday'] == '1')
    {
        $friday=1;
        $fridaytime= $_POST['friday-time'];
    } else {
        $friday=0;
        $fridaytime= null;
    }

    if($_POST['saturday'] == '1')
    {
        $saturday=1;
        $saturdaytime= $_POST['saturday-time'];
    } else {
        $saturday=0;
        $saturdaytime= null;
    }

    //echo "subject: " . $subject . "- teacher: " . $teacher . "- sunday: " . $sunday . "- sundaytime: " . $sundaytime . "- monday: " . $monday . "- mondaytime: " . $mondaytime . "- tuesday: " . $tuesday . "- tuesdaytime: " . $tuesdaytime . "- wednesday: " . $wednesday . "- wednesdaytime: " . $wednesdaytime . "- thursday: " . $thursday . "- thursdaytime: " . $thursdaytime . "- friday: " . $friday . "- fridaytime: " . $fridaytime . "- saturday: " . $saturday . "- saturdaytime: " . $saturdaytime;
    
    $query = "INSERT INTO `course` (`subject`, `teacher_id`, `grade`, `sunday`, `sunday_time`, `monday`, `monday_time`, `tuesday`, `tuesday_time`, `wednesday`, `wednesday_time`, `thursday`, `thursday_time`, `friday`, `friday_time`, `saturday`, `saturday_time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    
    // Bind parameters
    $stmt->bind_param("sssssssssssssssss", $subject, $teacher, $coursegrade, $sunday, $sundaytime, $monday, $mondaytime, $tuesday, $tuesdaytime, $wednesday, $wednesdaytime, $thursday, $thursdaytime, $friday, $fridaytime, $saturday, $saturdaytime);
    
    // Execute statement
    $result = $stmt->execute();
    
    if ($result) {
        $_SESSION['main_success_message'] = "الاضافة تمت بنجاح";
        $_SESSION['success_message'] = "لقد تمت اضافة هذه الدورة بنجاح.";
        include_once 'PopUpAlert.php';
        
    } else {
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "لم تتم اضافة هذه الدورة, حاول مرة اخرى";
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
    
    <title>Add New Course</title>
</head>
<body>
    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post">
            <h2>انشاء دورة جديدة</h2>
            <div class="contant">
                <div class="input-box">
                    <label for="subject">الموضوع</label>
                    <select class="input-box" name="subject" id="subject">
                        <option value="math">رياضيات</option>
                        <option value="physics">فيزياء</option>
                        <option value="chemistry">كيمياء</option>
                        <option value="biology">بيولوجيا</option>
                        <option value="arts">فن</option>
                    </select>
                </div>

                <div class="input-box">
                    <label for="teacher">المعلم</label>
                    <select class="input-box" name="teacher" id="teacher">
                    <?php
                    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

                    $query = "SELECT * FROM `teacher`";
                    $stmt = $mysqli->prepare($query);

                    // Execute statement
                    $stmt->execute();

                    // Get result
                    $teacher = $stmt->get_result();  

                    // Check if the result set is not empty
                    if ($teacher->num_rows == 0) {
                        echo "The table is empty!";
                    }

                    while ($row = $teacher->fetch_assoc()) {
                    ?>
                    <option name="teacher" value="<?php echo $row['id'] ?>"> <?php echo $row['first_name'] . ' ' . $row['last_name'] ?> </option>
                    <?php
                        // echo "ID: "  $row['id'] " - full name: "  $row['full name'] " - usernames: "  $row['username'] "<br>";
                        }       
                    ?>
    


                        
                    </select>
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

                <div class="input-box"> <!-- empty div -->
                </div>

                <div class="input-box">
                    <label for="sunday">الأحد</label>
                    <input type="hidden" name="sunday" value='0'>
                    <input type="checkbox" id="sunday-checkbox" name="sunday" value='1'>
                </div>

                <div class="input-box" id="sunday-time-container">
                    <label for="sunday-time">الساعة</label>
                    <input type="time" placeholder="ادخل ساعة الدورة" id="sunday-time" name="sunday-time" disabled>
                </div>
                
                <script>
                    // Get the checkbox and time input elements
                    const sundayCheckbox = document.getElementById('sunday-checkbox');
                    const sundayTimeInput = document.getElementById('sunday-time');

                    // Add event listener to the checkbox
                    sundayCheckbox.addEventListener('change', function() {
                        // If checkbox is checked, enable the input
                        if (this.checked) {
                            sundayTimeInput.disabled = false;
                            mondayTimeInput.required = true; // Optionally, make it required if needed
                        } else {
                            // If checkbox is unchecked, disable the input
                            sundayTimeInput.disabled = true;
                            sundayTimeInput.required = false; // Optionally, remove the required attribute
                        }
                    });
                </script>

                <div class="input-box">
                    <label for="monday">الأثنين</label>
                    <input type="hidden" name="monday" value='0'>
                    <input type="checkbox" id="monday-checkbox" name="monday" value='1'>
                </div>

                <div class="input-box" id="monday-time-container">
                    <label for="monday-time">الساعة</label>
                    <input type="time" placeholder="ادخل ساعة الدورة" id="monday-time" name="monday-time" disabled>
                </div>

                <script>
                    // Get the checkbox and time input elements
                    const mondayCheckbox = document.getElementById('monday-checkbox');
                    const mondayTimeInput = document.getElementById('monday-time');

                    // Add event listener to the checkbox
                    mondayCheckbox.addEventListener('change', function() {
                        // If checkbox is checked, enable the input
                        if (this.checked) {
                            mondayTimeInput.disabled = false;
                            mondayTimeInput.required = true; // Optionally, make it required if needed
                        } else {
                            // If checkbox is unchecked, disable the input
                            mondayTimeInput.disabled = true;
                            mondayTimeInput.required = false; // Optionally, remove the required attribute
                        }
                    });
                </script>


                <div class="input-box" id="tuesday-time-container">
                    <label for="tuesday">الثلاثاء</label>
                    <input type="hidden" name="tuesday" value='0'>
                    <input type="checkbox" id="tuesday-checkbox" name="tuesday" value='1'>
                </div>

                <div class="input-box">
                    <label for="tuesday-time">الساعة</label>
                    <input type="time" placeholder="ادخل ساعة الدورة" id="tuesday-time" name="tuesday-time" disabled>
                </div>

                <script>
                    // Get the checkbox and time input elements
                    const tuesdayCheckbox = document.getElementById('tuesday-checkbox');
                    const tuesdayTimeInput = document.getElementById('tuesday-time');

                    // Add event listener to the checkbox
                    tuesdayCheckbox.addEventListener('change', function() {
                        // If checkbox is checked, enable the input
                        if (this.checked) {
                            tuesdayTimeInput.disabled = false;
                            tuesdayTimeInput.required = true; // Optionally, make it required if needed
                        } else {
                            // If checkbox is unchecked, disable the input
                            tuesdayTimeInput.disabled = true;
                            tuesdayTimeInput.required = false; // Optionally, remove the required attribute
                        }
                    });
                </script>

                <div class="input-box" id="wednesday-time-container">
                    <label for="wednesday">الأربعاء</label>
                    <input type="hidden" name="wednesday" value='0'>
                    <input type="checkbox" id="wednesday-checkbox" name="wednesday" value='1'>
                </div>

                <div class="input-box">
                    <label for="wednesday-time">الساعة</label>
                    <input type="time" placeholder="ادخل ساعة الدورة" id="wednesday-time" name="wednesday-time" disabled>
                </div>

                <script>
                    // Get the checkbox and time input elements
                    const wednesdayCheckbox = document.getElementById('wednesday-checkbox');
                    const wednesdayTimeInput = document.getElementById('wednesday-time');

                    // Add event listener to the checkbox
                    wednesdayCheckbox.addEventListener('change', function() {
                        // If checkbox is checked, enable the input
                        if (this.checked) {
                            wednesdayTimeInput.disabled = false;
                            wednesdayTimeInput.required = true; // Optionally, make it required if needed
                        } else {
                            // If checkbox is unchecked, disable the input
                            wednesdayTimeInput.disabled = true;
                            wednesdayTimeInput.required = false; // Optionally, remove the required attribute
                        }
                    });
                </script>

                <div class="input-box" id="thursday-time-container">
                    <label for="thursday">الخميس</label>
                    <input type="hidden" name="thursday" value='0'>
                    <input type="checkbox" id="thursday-checkbox" name="thursday" value='1'>
                </div>

                <div class="input-box">
                    <label for="thursday-time">الساعة</label>
                    <input type="time" placeholder="ادخل ساعة الدورة" id="thursday-time" name="thursday-time" disabled>
                </div>

                <script>
                    // Get the checkbox and time input elements
                    const thursdayCheckbox = document.getElementById('thursday-checkbox');
                    const thursdayTimeInput = document.getElementById('thursday-time');

                    // Add event listener to the checkbox
                    thursdayCheckbox.addEventListener('change', function() {
                        // If checkbox is checked, enable the input
                        if (this.checked) {
                            thursdayTimeInput.disabled = false;
                            thursdayTimeInput.required = true; // Optionally, make it required if needed
                        } else {
                            // If checkbox is unchecked, disable the input
                            thursdayTimeInput.disabled = true;
                            thursdayTimeInput.required = false; // Optionally, remove the required attribute
                        }
                    });
                </script>

                <div class="input-box" id="friday-time-container">
                    <label for="friday">الجمعه</label>
                    <input type="hidden" name="friday" value='0'>
                    <input type="checkbox" id="friday-checkbox" name="friday" value='1'>
                </div>

                <div class="input-box">
                    <label for="friday-time">الساعة</label>
                    <input type="time" placeholder="ادخل ساعة الدورة" id="friday-time" name="friday-time" disabled>
                </div>

                <script>
                    // Get the checkbox and time input elements
                    const fridayCheckbox = document.getElementById('friday-checkbox');
                    const fridayTimeInput = document.getElementById('friday-time');

                    // Add event listener to the checkbox
                    fridayCheckbox.addEventListener('change', function() {
                        // If checkbox is checked, enable the input
                        if (this.checked) {
                            fridayTimeInput.disabled = false;
                            fridayTimeInput.required = true; // Optionally, make it required if needed
                        } else {
                            // If checkbox is unchecked, disable the input
                            fridayTimeInput.disabled = true;
                            fridayTimeInput.required = false; // Optionally, remove the required attribute
                        }
                    });
                </script>

                <div class="input-box" id="saturday-time-container">
                    <label for="saturday">السبت</label>
                    <input type="hidden" name="saturday" value='0'>
                    <input type="checkbox" id="saturday-checkbox" name="saturday" value='1'>
                </div>

                <div class="input-box">
                    <label for="saturday-time">الساعة</label>
                    <input type="time" placeholder="ادخل ساعة الدورة" id="saturday-time" name="saturday-time" disabled>
                </div>

                <script>
                    // Get the checkbox and time input elements
                    const SaturdayCheckbox = document.getElementById('saturday-checkbox');
                    const SaturdayTimeInput = document.getElementById('saturday-time');

                    // Add event listener to the checkbox
                    saturdayCheckbox.addEventListener('change', function() {
                        // If checkbox is checked, enable the input
                        if (this.checked) {
                            saturdayTimeInput.disabled = false;
                            saturdayTimeInput.required = true; // Optionally, make it required if needed
                        } else {
                            // If checkbox is unchecked, disable the input
                            saturdayTimeInput.disabled = true;
                            saturdayTimeInput.required = false; // Optionally, remove the required attribute
                        }
                    });
                </script>

            </div>
            <div class="button-container">
                    <button name="submit" type="submit">انشاء دورة</button>
            </div>
        </form>



    </div>
    
</body>
</html>
