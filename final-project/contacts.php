<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "db.php";



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

        $stmt->close();
        $mysqli->close();
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

        $stmt->close();
        $mysqli->close();
    }
}

// FOR UPLODING THE MESSAGES
if (isset($_POST['contact_id'])) {
    $loggedInUserId = $_SESSION["id"];
    $selectedUserId = $_POST['contact_id'];

    // Query to retrieve messages between logged-in user and selected user
    $mysqli = new mysqli('localhost', 'root', '', '211900808_318609641');

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare and execute query
    $query = "SELECT * FROM messages 
              WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
              ORDER BY timestamp ASC"; // Adjust column names as per your table structure

    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Bind parameters and execute query
    $stmt->bind_param("iiii", $loggedInUserId, $selectedUserId, $selectedUserId, $loggedInUserId);
    if ($stmt->execute() === false) {
        die("Execute failed: " . $stmt->error);
    }

    // Fetch result
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    // Close statement and database connection
    $stmt->close();
    $mysqli->close();

    // Output messages as HTML
    foreach ($messages as $message) {
        echo htmlspecialchars($message['message']) . "<br>";
    }
} else {
    echo "Invalid request.";
}

?>

<ul class="contact-user">
    <li class="">
        <a href="#" class="contact-link" data-id="admin" data-name="Admin Admin" onclick="loadMessages(admin); ">
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
                <a href="#" class="contact-link" data-id="<?php echo $contact['teacher_id']; ?>" data-name="<?php echo htmlspecialchars($contact['teacher_full_name']); ?>" onclick="loadMessages(<?php echo $contact['teacher_id']; ?>);" >
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
                <a href="#" class="contact-link" data-id="<?php echo $contact['student_id']; ?>" data-name="<?php echo htmlspecialchars($contact['student_full_name']); ?>" onclick="loadMessages(<?php echo $contact['student_id']; ?>);" >
                    <i class='bx bxs-user-circle'></i>
                    <span class="text">
                        <h3><?php echo htmlspecialchars($contact['student_full_name']); ?></h3>
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>


<div id="message-container">
    <!-- Messages will be dynamically loaded here -->
</div>

<script>
    // JavaScript (jQuery) for handling click on contact links and loading messages
    function loadMessages(contactId) {
        $.ajax({
            url: 'load_messages.php', // Adjust the URL to your PHP script handling message retrieval
            method: 'POST',
            data: { contact_id: contactId },
            success: function(response) {
                $('#message-container').html(response); // Update message container with retrieved messages
            },
            error: function(xhr, status, error) {
                console.error('Error loading messages:', error);
            }
        });
    }
</script>


<script>
    $(document).ready(function() {
        $('.contact-link').click(function(e) {
            e.preventDefault();
            var $parentLi = $(this).closest('li');

            // Toggle active class
            if ($parentLi.hasClass('active')) {
                $parentLi.removeClass('active');
                $('#message-header i').removeClass('bx bxs-user bxs-user-circle'); // Remove all classes
                $('#message-header h3').text(''); // Clear header when deactivated
            } else {
                $('.contact-user li').removeClass('active'); // Remove active class from all <li>
                $parentLi.addClass('active'); // Add active class to the clicked <li>

                // Get the full name and icon class from data attributes
                var fullName = $(this).data('name');
                var iconClass = $(this).find('i').attr('class');
                console.log("Selected Name:", fullName);
                console.log("Selected Icon Class:", iconClass);

                // Update the header with the full name and icon
                $('#message-header i').attr('class', iconClass);
                $('#message-header h3').text(fullName);
            }





            // Example of storing the ID in a session variable (PHP example)
            <?php if(isset($_SESSION["type"]) && $_SESSION["type"] == 'student'): ?>
                var studentId = $(this).data('id');
                <?php $_SESSION["selected_student_id"] = "' + studentId + '"; ?>
            <?php endif; ?>

            // Example of storing the ID in JavaScript variable (for further use in JS)
            var selectedId = $(this).data('id');
            console.log("Selected ID:", selectedId);
        });
    });
</script>
