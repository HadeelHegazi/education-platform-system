<?php
error_reporting(-1);
require_once "db.php"; // Ensure db.php contains your database connection settings

session_start();

if(isset($_POST['submit'])) {
    // Database connection
    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Form data
    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $phonenumber = $_POST['phone'];
    $email = $_POST['email'];

    // Check if email already exists
    $query = "SELECT * FROM teachercvfile WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        die('MySQL prepare error: ' . htmlspecialchars($mysqli->error));
    }

    // Bind parameter
    $stmt->bind_param("s", $email);

    // Execute statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If any record with the same email exists, display an error message
        $_SESSION['main_success_message'] = "خطأ في المعطيات";
        $_SESSION['success_message'] = "ان البريد الإلكتروني قد تم استخدامه، يرجى استخدام بريد آخر.";
        include_once 'PopUpAlert.php';
    } else {
        // Configuration for file upload
        $target_dir = "uploads/"; // Directory where uploaded files will be stored
        $target_file = $target_dir . basename($_FILES["cv"]["name"]); // Full path of the uploaded file
        $uploadOk = 1; // Flag to indicate if file upload is successful
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // File extension

        // Check file details (size, type, existence)
        if (file_exists($target_file)) {
            $_SESSION['main_success_message'] = "خطأ في المعطيات";
            $_SESSION['success_message'] = "الملف موجود بالفعل، يرجى استخدام ملف آخر.";
            include_once 'PopUpAlert.php';
            $uploadOk = 0;
        }

        if ($_FILES["cv"]["size"] > 5 * 1024 * 1024) { // 5MB limit
            $_SESSION['main_success_message'] = "خطأ في المعطيات";
            $_SESSION['success_message'] = "الملف كبير جدًا، يرجى استخدام ملف آخر.";
            include_once 'PopUpAlert.php';
            $uploadOk = 0;
        }

        if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
            $_SESSION['main_success_message'] = "خطأ في المعطيات";
            $_SESSION['success_message'] = "صيغة الملف غير مدعومة، يرجى استخدام ملف PDF أو DOC أو DOCX.";
            include_once 'PopUpAlert.php';
            $uploadOk = 0;
        }

        // Attempt to upload file
        if ($uploadOk == 1 && move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
            // File upload success
            // echo "تم تحميل الملف " . htmlspecialchars(basename($_FILES["cv"]["name"])) . " بنجاح.";

            // Save file details to database
            $filename = basename($_FILES["cv"]["name"]);
            $filepath = $target_file;

            // Insert file details into database
            $sql = "INSERT INTO `teachercvfile` (`first_name`, `last_name`, `phone_number`, `email`, `file_name`, `file_path`) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                die('MySQL prepare error: ' . htmlspecialchars($mysqli->error));
            }

            // Bind parameters
            $stmt->bind_param("ssssss", $firstname, $lastname, $phonenumber, $email, $filename, $filepath);

            // Execute statement
            if ($stmt->execute()) {
                $_SESSION['main_success_message'] = "تمت الإضافة بنجاح";
                $_SESSION['success_message'] = "تمت إضافة وحفظ الملف بنجاح.";
                include_once 'PopUpAlert.php';
            } else {
                $_SESSION['main_success_message'] = "خطأ في المعطيات";
                $_SESSION['success_message'] = "حدث خطأ أثناء تحميل الملف، يرجى المحاولة مرة أخرى.";
                include_once 'PopUpAlert.php';
            }

            $stmt->close();
        } else {
            // File upload failure
            $_SESSION['main_success_message'] = "خطأ في المعطيات";
            $_SESSION['success_message'] = "حدث خطأ أثناء تحميل الملف، يرجى المحاولة مرة أخرى.";
            include_once 'PopUpAlert.php';
        }
    }

    // Close database connection
    $mysqli->close();
}
?>





<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style2.css">
    <title>Upload CV</title>
</head>
<body>
<header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <h2>تقديم السيرة الذاتية كمعلم</h2>
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
                    <label for="cv">السيرة الذاتية</label>
                    <input type="file" name="cv" accept=".pdf,.doc,.docx" required>
                </div>
                
                

            </div>
            <div class="alert">
                <p>بالنقر على 'إضافة طالب'، سيتم إرسال بريد إلكتروني إلى بريد الطالب، يحتوي على بريده الإلكتروني وكلمة المرور الخاصة به.</p>
            </div>
            <div class="button-container">
                <button name="submit" type="submit">ارسال</button>
            </div>
        </form>
    </div>


</body>
</html>
