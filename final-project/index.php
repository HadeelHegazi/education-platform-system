<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "db.php";
$message = ''; // Initialize here so it's always set



?>



<div class=""><!DOCTYPE html></div>
<html lang="ar" dir="rtl">
<head>
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
     <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,100,1,200" rel="stylesheet" />     
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
     -->
    

    
    <title>Home Page</title>
</head>
<body>
    <!-- <div class="bodydiv"> -->
    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>

    <section class="hero-section">
        <div class="content">
            <h1>قُلْ هَلْ يَسْتَوِي الَّذِينَ يَعْلَمُونَ وَالَّذِينَ لا يَعْلَمُونَ إِنَّمَا يَتَذَكَّرُ أُولُو الْأَلْبَابِ</h1>
            <p>[الزمر:9]</p>
        </div>
        <div class="insidecontent-container">
            <div class="insidecontent">
                <h2>معهد البراق نحو مستقبل مشرق بالعلم</h2>
                <button onclick="window.location.href='signin.php'">تسجيل الدخول</button>
            </div>
            <div class="insidecontent">
                <h2>اذا كنت معني للتقدم للعمل كاستاذ</h2>
                <button onclick="window.location.href='TeacherCvUpload.php'">ارسال السيرة الذاتية</button>
            </div>
        </div>
    </section>

    <!-- <section class="hero-section">
        <div class="content">
            <h1>قُلْ هَلْ يَسْتَوِي الَّذِينَ يَعْلَمُونَ وَالَّذِينَ لا يَعْلَمُونَ إِنَّمَا يَتَذَكَّرُ أُولُو الْأَلْبَابِ</h1> <p> [الزمر:9]</p>
    
            <h2>
            معهد البراق نحو مستقبل مشرق بالعلم
            </h2>
            <button>Start Your Way</button>
        </div>
    </section> -->

        
    <?php //include_once 'common/footer.php'; ?> 
    <!-- </div> -->
</body>
</html>



