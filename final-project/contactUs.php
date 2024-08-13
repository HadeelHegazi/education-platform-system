<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMWJftG29aK0Buz3v1zib3B1hc9avSD5FIZdSWn" crossorigin="anonymous">
</head>
<body calss="bodycontactUs">

    <header>
        <!-- Your header content -->
        <?php include_once 'common/AdminHeader.php'; ?>
    </header>
    <div class="bodycontactUs">
    <div class="shining-bluecontactUs">تواصل معنا</div>
<div class="containercontactUs">
    <div class="left-sidecontactUs">
        <h2>معلومات الاتصال</h2>
        <div class="contact-detailcontactUs">
            <i class="fas fa-map-marker-alt"></i>
            <span>طمرة، شارع المركز، مبنى 3، الطابق 2</span>
        </div>
        <div class="contact-detailcontactUs">
            <i class="fas fa-phone-alt"></i>
            <span>04987987</span>
        </div>
        <div class="contact-detailcontactUs">
            <i class="fas fa-envelope"></i>
            <span>educationCenter@outlook.co.il</span>
        </div>
    </div>
    <div class="right-sidecontactUs">
        <h2>عملاؤنا الأعزاء</h2>
        <p>تواصلوا معنا، نحن هنا للإجابة على أسئلتكم.</p>
        <form id="contactForm" action="https://formsubmit.co/1e14d43c7f453c3620d82d6ddad016b7" method="POST">
            <label for="name">الاسم</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">الرسالة</label>
            <textarea id="message" name="message" rows="6" required></textarea>
            
            <input type="submit" value="إرسال">
        </form>
    </div>
</div>
</div>
</body>
</html>
