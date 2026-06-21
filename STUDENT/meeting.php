<?php
require_once '../MIDDLEWARE/db_connect.php';
require_once '../MIDDLEWARE/role-state-management.php';
require_once '../GET/func-meeting-student.php';
roleStateManagement("Student");

$currentWeek = isset($_GET['week']) ? $_GET['week'] : 1;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['book_meeting'])) {
    bookMeeting($_POST['meeting_id']);
    header("Location: meeting.php?week=$currentWeek");
    exit();
}

if (isset($_POST['delete_meeting']) && isset($_POST['meeting_id'])) {
    deleteMeeting($_POST['meeting_id']);
    header("Location: " . $_SERVER['PHP_SELF'] . "?week=" . $currentWeek);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_FILES as $key => $file) {
        if (strpos($key, 'file_') === 0) {
            $meetingId = substr($key, 5); 
            uploadFile($meetingId, $file);
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/meeting.css">
    <link rel="stylesheet" href="../CSS/main.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Meeting Page</title>
</head>

<body>
    <?php include '../HEADER/student-header.inc.php'; ?>

    <div class="month">
        <ul>
            <?php 
            $currentWeek = intval($currentWeek);
            $prevWeek = max(1, $currentWeek - 1);
            $nextWeek = min(14, $currentWeek + 1);
            ?>
            <li class="prev"><a href="?week=<?php echo $prevWeek; ?>">&#10094;</a></li>
            <li class="next"><a href="?week=<?php echo $nextWeek; ?>">&#10095;</a></li>
            <li><span style="font-size: 18px"><b>WEEK <?php echo $currentWeek; ?></b></span></li>
        </ul>
    </div>

    <table class="calendar">
		<thead>
			<tr>
				<th>Monday</th>
				<th>Tuesday</th>
				<th>Wednesday</th>
				<th>Thursday</th>
				<th>Friday</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$meetings = getMeetingsForWeek($currentWeek);
			$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
			
			$timeSlots = [];
			foreach ($meetings as $meeting) {
				if (!isset($timeSlots[$meeting['start_time']])) {
					$timeSlots[$meeting['start_time']] = array_fill(0, 5, null);
				}
				$dayIndex = array_search($meeting['day'], $days);
				$timeSlots[$meeting['start_time']][$dayIndex] = $meeting;
			}

			foreach ($timeSlots as $time => $slots) {
				echo "<tr>";
				foreach ($slots as $meeting) {
					echo "<td>";
					if ($meeting) {
						echo "<div class='time-slot'>";
						echo "<p>" . htmlspecialchars($meeting['start_time']) . " - " . htmlspecialchars($meeting['end_time']) . "</p>";
						echo "<p>" . htmlspecialchars($meeting['location']);
						if ($meeting['meet_link'] !== '') {
							echo " <a href='" . htmlspecialchars($meeting['meet_link']) . "' target='_blank'><i class='fas fa-video'></i></a>";
						}
						echo "<br>";
						if ($meeting['is_taken'] == 1) {
                            if ($meeting['student_id'] == $_SESSION['user_id']) {
                                echo "<div class='button-group'>";
                                echo "<form method='POST' enctype='multipart/form-data'>";
                                echo "<input type='hidden' name='meeting_id' value='" . $meeting['id'] . "'>";
                                echo "<input type='file' name='file_" . $meeting['id'] . "' id='file_" . $meeting['id'] . "' accept='.doc,.docx,.pdf' style='display: none;'>";
                                echo "<button type='button' class='upload-btn' onclick='document.getElementById(\"file_" . $meeting['id'] . "\").click()'>UPLOAD</button>";
                                echo "</form>";
                                
                                if (!empty($meeting['attachment'])) {
                                    echo "<a href='../GET/download-meeting.php?meeting_id=" . $meeting['id'] . "' class='view-btn' target='_blank'>VIEW FILE</a>";
                                }
                                
                                echo "<form method='POST'>";
                                echo "<input type='hidden' name='meeting_id' value='" . $meeting['id'] . "'>";
                                echo "<button type='submit' name='delete_meeting' class='dlt-btn' onclick='return confirm(\"Are you sure you want to delete this meeting?\")'>DELETE</button>";
                                echo "</form>";
                                echo "</div>";
                            } else {
                                echo "<p>Booked by: " . htmlspecialchars($meeting['student_id']) . "</p>";
                            }
                        } else {
                            echo "<form method='POST' style='display: inline;'>";
                            echo "<input type='hidden' name='meeting_id' value='" . $meeting['id'] . "'>";
                            echo "<button type='submit' name='book_meeting' class='book-btn'>BOOK</button>";
                            echo "</form>";
                        }

						echo "<input type='file' name='file' id='file' hidden />";
						echo "</p>";
						echo "</div>";

					}
					echo "</td>";
				}
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.files.length > 0) {
                if (confirm('Are you sure you want to upload this file?')) {
                        this.parentElement.submit();
                    }
                }
            });
        });
    });
    </script>
</body>


</html>

