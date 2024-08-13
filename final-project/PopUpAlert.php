<?php
$redirectUrl = isset($_SESSION["type"]) ? 'profile.php' : 'index.php';

$mainsuccessMessage = isset($_SESSION['main_success_message']) ? $_SESSION['main_success_message'] : null;
unset($_SESSION['main_success_message']); // Clear the session variable after using it

$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
unset($_SESSION['success_message']); // Clear the session variable after using it

?>


<head>

    <style>
        /* Importing Google font - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .popup {
            width: 400px;
            background: #dae9f7; /* Background color for the entire popup */
            border-radius: 6px;
            position: absolute;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%) scale(0.1);
            text-align: center;
            padding: 20px;
            color: #333; /* Text color */
            visibility: hidden;
            transition: transform 0.3s ease, top 0.3s ease; /* Add transition for smooth effect */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Optional: add a box shadow for depth */
            display: flex; /* Use flexbox for centering */
            flex-direction: column; /* Stack elements vertically */
            justify-content: center; /* Center vertically */
            align-items: center; /* Center horizontally */
            z-index: 9999; /* Ensure the popup is above other content */
        }

        .popup h2 {
            font-size: 32px; /* Adjust the font size */
            font-weight: 600; /* Increase font weight for emphasis */
            margin-bottom: 20px; /* Add space below the heading */
            color: #555; /* Optional: adjust text color */
        }

        .popup p {
            font-size: 18px; /* Font size for paragraph */
            margin-bottom: 20px; /* Space below paragraph */
        }

        .popup button {
            width: 150px; /* Adjust button width */
            padding: 10px 0; /* Vertical padding */
            background-color: #778ca4; /* Button background color */
            color: #fff; /* Button text color */
            border: none; /* Remove border */
            outline: none; /* Remove outline */
            font-size: 16px; /* Font size */
            border-radius: 4px; /* Button border radius */
            cursor: pointer; /* Pointer cursor */
            transition: background-color 0.3s ease; /* Smooth background color transition */
        }

        .popup button:hover {
            background-color: #556677; /* Darker background color on hover */
        }

        .open-popup {
            visibility: visible;
            top: 50%;
            transform: translate(-50%,-50%) scale(1);
        }
    </style>

    <script>
        function openPopup() {
            let popup = document.getElementById('popup');
            popup.classList.add('open-popup');
        }
        
        function closePopup() {
            let popup = document.getElementById('popup');
            popup.classList.remove('open-popup');
            window.location.href = '<?php echo $redirectUrl; ?>';
        }

        window.onload = function() {
            <?php if ($mainsuccessMessage): ?>
                openPopup();
            <?php endif; ?>
            <?php if ($successMessage): ?>
                openPopup();
            <?php endif; ?>
        }
    </script>

    
</head>
<body>

    <div class="popup open-popup" id="popup">
        <h2><?php echo htmlspecialchars($mainsuccessMessage); ?></h2>
        <p><?php echo htmlspecialchars($successMessage); ?></p>
        <button type="button" onclick="closePopup()">OK</button>
    </div>

</body>

<!-- </html> -->
