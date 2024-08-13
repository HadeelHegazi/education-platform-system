<?php
if (isset($_POST['addnote'])) {
    // Handle adding a note here
}

if (isset($_POST['pressent'])) {
    // Handle recording attendance here
}

function generate_calendar($month, $year) {
    $cal_html = '<table class="diarycalendar">';
    $cal_html .= '<caption>' . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . '</caption>';
    $cal_html .= '<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';

    $first_day = mktime(0, 0, 0, $month, 1, $year);
    $days_in_month = date('t', $first_day);
    $day_of_week = date('w', $first_day);

    $cal_html .= '<tr>';
    for ($i = 0; $i < $day_of_week; $i++) {
        $cal_html .= '<td></td>';
    }

    for ($day = 1; $day <= $days_in_month; $day++) {
        $date_string = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
        $cal_html .= '<td data-day="' . $date_string . '">' . $day . '</td>';
        if (($day + $day_of_week) % 7 == 0 && $day != $days_in_month) {
            $cal_html .= '</tr><tr>';
        }
    }

    $remaining_days = 7 - ($day_of_week + $days_in_month) % 7;
    if ($remaining_days < 7) {
        for ($i = 0; $i < $remaining_days; $i++) {
            $cal_html .= '<td></td>';
        }
    }

    $cal_html .= '</tr></table>';
    return $cal_html;
}

$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'prev') {
        $month--;
        if ($month < 1) {
            $month = 12;
            $year--;
        }
    } elseif ($_GET['action'] == 'next') {
        $month++;
        if ($month > 12) {
            $month = 1;
            $year++;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["note"]) && isset($_POST["date"])) {
    $note = $_POST["note"];
    $date = $_POST["date"];
    if (!isset($_SESSION['notes'])) {
        $_SESSION['notes'] = [];
    }
    $_SESSION['notes'][$date][] = $note;
    header("Location: " . $_SERVER['PHP_SELF'] . "?month=$month&year=$year&selected_date=$date");
    exit();
}

echo '<div class="diary-nav-buttons">';
echo '<form method="get" style="display:inline;"><button type="submit" name="action" value="prev">&lt; Prev</button>';
echo '<input type="hidden" name="month" value="' . $month . '">';
echo '<input type="hidden" name="year" value="' . $year . '"></form>';
echo '<form method="get" style="display:inline;"><button type="submit" name="action" value="next">Next &gt;</button>';
echo '<input type="hidden" name="month" value="' . $month . '">';
echo '<input type="hidden" name="year" value="' . $year . '"></form>';
echo '</div>';
echo generate_calendar($month, $year);

$selected_date = isset($_GET['selected_date']) ? $_GET['selected_date'] : '';
?>

<div class="note-section">
    <div class="note-display">
        <h2>برنامج دروس اليوم:</h2>
        <div id="course-list" class="diary-note-list">
            <ul>
                <!-- Populate with courses for the chosen day/date. -->
                <!-- This will be populated dynamically -->
            </ul>
        </div>
    </div>

    <div class="note-display">
        <h2>ملاحظات الدرس</h2>
        <div id="note-list" class="diary-note-list"></div>
    </div>
    <input type="hidden" id="date" name="date" value="<?php echo $selected_date; ?>">
</div>

<script>
var days = document.querySelectorAll('.diarycalendar td[data-day]');
var courseList = document.getElementById('course-list');
var noteList = document.getElementById('note-list');
var dateInput = document.getElementById('date');
var selectedDate = '<?php echo $selected_date; ?>';

if (selectedDate) {
    fetchCourses(selectedDate);
   // fetchNotes(selectedDate);
}

days.forEach(function(day) {
    day.addEventListener('click', function() {
        days.forEach(function(d) {
            d.classList.remove('selected');
        });
        day.classList.add('selected');
        selectedDate = day.getAttribute('data-day');

        if (dateInput) {
            dateInput.value = selectedDate;
        } else {
            console.error('dateInput element not found');
        }

        fetchCourses(selectedDate);
        // fetchNotes(selectedDate);
    });
});

function fetchCourses(date) {
    console.log("Fetching courses for date: ", date); // Debug log
    var day = new Date(date).toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase(); // Get the day in lowercase
    console.log("Fetching courses for day: ", day); // Debug log

    var xhr = new XMLHttpRequest();
    console.log('The xhr is done... on the way to fetch_diary_courses page...');
    xhr.open('GET', 'fetch_diary_courses.php?date=' + date + '&day=' + day, true);
    console.log('Get back from the fetch_diary_courses page... :)');

    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Getting into the if function... :)');
            var response = JSON.parse(xhr.responseText);
            console.log("Response Text: ", xhr.responseText);

            if (response.courses) {
                displayCourses(response.courses, response.user_type);
            } else {
                console.error('Error fetching courses:', response.error);
            }
        } else {
            console.error('Error fetching courses:', xhr.status, xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Request error');
    };
    xhr.send();
}

function displayCourses(courses, userType) {
    courseList.innerHTML = '';
    if (courses.length === 0) {
        courseList.innerHTML = '<p>لا يوجد دورات لهذا اليوم</p>';
    } else {
        courses.forEach(function(course) {
            var courseItem = document.createElement('li');
            courseItem.classList.add('course-item');
            courseItem.dataset.courseId = course.course_id; // Store the course ID in a data attribute

            var subjectElement = document.createElement('div');
            subjectElement.classList.add('course-subject');
            subjectElement.textContent = course.subject;

            var timeElement = document.createElement('div');
            timeElement.classList.add('course-time');
            timeElement.textContent = 'الساعة: ' + course.time;

            var teacherElement = document.createElement('div');
            teacherElement.classList.add('course-teacher');
            teacherElement.textContent = 'المعلم: ' + course.teacher_name;

            courseItem.appendChild(subjectElement);
            courseItem.appendChild(timeElement);
            courseItem.appendChild(teacherElement);

            // Add buttons based on user type
            if (userType === 'teacher') {
                var presentButton = document.createElement('button');
                presentButton.classList.add('present-button');
                presentButton.textContent = 'تسجيل الحضور';
                presentButton.onclick = function() {
                    // Handle presenting logic here
                    presentCourse(course.id);
                    console.log('Presenting course ID:', course.id);
                };

                var addNoteButton = document.createElement('button');
                addNoteButton.classList.add('add-note-button');
                addNoteButton.textContent = 'اضافة ملاحظات الدرس';
                addNoteButton.onclick = function() {
                    // Handle adding note logic here
                    addNote(course.id,selectedDate);
                    console.log('Adding note to course ID:', course.id);
                };

                courseItem.appendChild(presentButton);
                courseItem.appendChild(addNoteButton);
            } else if (userType === 'student') {
                var presentButton = document.createElement('button');
                presentButton.classList.add('present-button');
                presentButton.textContent = 'تسجيل الحضور';
                presentButton.onclick = function() {
                    // Handle presenting logic here
                    presentCourse(course.id);
                    console.log('Presenting course ID:', course.id);
                };

                courseItem.appendChild(presentButton);
            }
            courseItem.onclick = function() {
                // Remove "active" class from all course items
                document.querySelectorAll('.course-item').forEach(function(item) {
                    item.classList.remove('active');
                });

                // Add "active" class to the clicked course item
                courseItem.classList.add('active');

                // Show notes for the selected course
                showNotes(course.id,selectedDate);
            };

            courseList.appendChild(courseItem);
        });
    }
}
function presentCourse(courseId) {
    console.log('Getting into the presentCourse() function... :)');
    console.log('Presenting course ID from the presentCourse():', courseId);
    const statusElement = document.getElementById('status');

    // Function to handle checking in
    function checkIn(latitude, longitude) {
        console.log('Getting into the checkIn() function... :)');

        const CLASSROOM_LAT = 32.8531968; // Example latitude
        const CLASSROOM_LON = 35.1895552; // Example longitude
        const RADIUS = 100; // Acceptable radius in meters

    // Function to calculate distance between two coordinates using Haversine formula
    function getDistance(lat1, lon1, lat2, lon2) {

        console.log('Getting into the getDistance() function... :)');

        const R = 6371e3; // Earth's radius in meters
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
            Math.cos(φ1) * Math.cos(φ2) *
            Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c; // Distance in meters
    }


        // Calculate distance to classroom
        const distance = getDistance(latitude, longitude, CLASSROOM_LAT, CLASSROOM_LON);

        // Check if within acceptable radius
        // Check if within acceptable radius
                // Check if within acceptable radius
                if (distance <= RADIUS) {
            console.log('Getting into the if for the distance... :)');

            // Create XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Prepare and send GET request
            xhr.open('GET', `pressent.php?courseId=` + courseId, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Request successful, parse response
                        const response = JSON.parse(xhr.responseText);
                        console.log('Response from present.php:', response);
                        // Handle response as needed
                        if (response.status === 'success') {
                            alert('تم تسجيل الحضور بنجاح.');
                        } else {
                            alert('لم يتم تسجيل الحضور بنجاح.');
                        }
                    } else {
                        // Error handling
                        console.error('Error presenting course:', xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.send();
        } else {
            statusElement.textContent = "أنت لست داخل منطقة الفصل.";
        }
    }

    // Get current location
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Call checkIn function with obtained coordinates
            checkIn(latitude, longitude);
        }, function(error) {
            // Handle errors
            statusElement.textContent = "خطأ في الحصول على الموقع: " + error.message;
        });
    } else {
        statusElement.textContent = "نظام تحديد المواقع غير مدعوم في هذا المتصفح.";
    }
}
function addNote(courseId, date) {
    console.log('The courseId id:', courseId);
    console.log('The date of the choosen day is:', date);

    var xhr = new XMLHttpRequest();
    console.log('The xhr is done... on the way to AddNoteToLesson page...');

    xhr.open('GET', 'AddNoteToLesson.php?courseId=' + courseId + '&date=' + date, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('Get back from the AddNoteToLesson page... :)');

                // Redirect to the AddNoteToLesson.php page
                window.location.href = 'AddNoteToLesson.php?courseId=' + courseId + '&date=' + date;
            } else {
                // Handle errors if needed
                console.error('Error:', xhr.status, xhr.statusText);
            }
        }
    };

    xhr.send();
}
function showNotes(courseId, date) {
    var noteList = document.getElementById('note-list');
    noteList.innerHTML = ''; // Clear existing notes

    // Make an AJAX request to fetch notes from fetch_notes.php
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_notes.php?courseId=' + courseId + '&date=' + date, true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            var response = JSON.parse(xhr.responseText);
            
            if (response.hasOwnProperty('notes')) {
                var notes = response.notes;

                if (notes.length === 0) {
                    noteList.innerHTML = '<p>لا يوجد ملاحظات لهذه الدورة</p>';
                } else {
                    notes.forEach(function(note) {
                        // Create a container for each note
                        var noteContainer = document.createElement('div');
                        noteContainer.classList.add('note-container');

                        // Create a div for the note text
                        var noteText = document.createElement('div');
                        noteText.classList.add('note-text');
                        noteText.textContent = note.note;

                        // Append note text to the container
                        noteContainer.appendChild(noteText);

                        // Append the note container to the note list
                        noteList.appendChild(noteContainer);
                    });
                }
            } else {
                noteList.innerHTML = '<p>Failed to fetch notes</p>';
            }
        } else {
            noteList.innerHTML = '<p>Error fetching notes</p>';
        }
    };

    xhr.onerror = function() {
        noteList.innerHTML = '<p>Network error occurred</p>';
    };

    xhr.send();
}

</script>
