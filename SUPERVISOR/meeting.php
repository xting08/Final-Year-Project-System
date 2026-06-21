<?php
require_once '../MIDDLEWARE/db_connect.php';
require_once '../GET/func-meeting-supervisor.php';
require_once "../MIDDLEWARE/role-state-management.php";
roleStateManagement("Supervisor");


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$currentWeek = isset($_GET['week']) ? $_GET['week'] : 1;

if(isset($_GET['location']) && isset($_GET['start_time']) && isset($_GET['end_time']) && 
   isset($_GET['day']) && isset($_GET['week']) && isset($_GET['meetingType'])) {
    
    $meetingLink = isset($_GET['meetingLink']) ? $_GET['meetingLink'] : '';
    addMeeting($_GET['week'], $_GET['location'], $_GET['start_time'], $_GET['end_time'], 
            $_GET['day'], $meetingLink);

    header("Location: meeting.php?week=" . $_GET['week']);
    exit();
} 

if (isset($_POST['delete_meeting']) && isset($_POST['meeting_id'])) {
    deleteMeeting($_POST['meeting_id']);
    header("Location: " . $_SERVER['PHP_SELF'] . "?week=" . $currentWeek);
    exit();
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
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Meeting Page</title>
</head>
<body>
    <?php include '../HEADER/supervisor-header.inc.php'; ?>

    <div >
        <form method="GET" action="meeting.php">
            <fieldset>
                <input type="hidden" name="week" value="<?php echo $currentWeek; ?>">
                <label for="location">Location: </label>
                <input type="text" name="location" placeholder="MMU" required/>
                
                <label for="start_time">Start Time: </label>
                <input type="time" name="start_time" required/>

                <label for="end_time">End Time: </label>
                <input type="time" name="end_time" required/>

                <label for="day">Day: </label>
                <select name="day" id="day" required>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                </select>
      
                <div class="meeting-type">
                    <label>Meeting Type:&nbsp;&nbsp;</label>
                        <input type="radio" name="meetingType" value="physical" id="physical" checked onclick="toggleMeetingLink()">
                        <label for="physical">Physical</label>
                        <input type="radio" name="meetingType" value="virtual" id="virtual" onclick="toggleMeetingLink()">
                        <label for="virtual">Virtual</label>
                </div>
                

                <div id="meetingLinkDiv" style="display: none;">
                    <label for="meetingLink">Meeting Link: &nbsp;&nbsp;</label>
                    <input type="text" name="meetingLink" id="linkInput" placeholder="Enter meeting link">
                </div>
                <input type="submit" value="ADD" />
            </fieldset>
        </form>
    </div>

    <section class="meeting-information">
    <div class="month">
        <ul>
            <?php 
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
						echo "</p>";
						echo "<div class='button-group'>";
						if (!empty($meeting['attachment'])) {
                            echo "<a href='../GET/download-meeting.php?meeting_id=" . $meeting['id'] . "' class='view-btn' target='_blank'>VIEW FILE</a>";
                        }   
                        
						echo "<form method='POST'>";
                        echo "<input type='hidden' name='meeting_id' value='" . $meeting['id'] . "'>";
                        echo "<button type='submit' name='delete_meeting' class='dlt-btn' onclick='return confirm(\"Are you sure you want to delete this meeting?\")'>DELETE</button>";
                        echo "</form>";
						echo "</div>";
						echo "</div>";
					}
					echo "</td>";
				}
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
    </section>

    

    <script>
    function toggleMeetingLink() {
        const virtualRadio = document.getElementById('virtual');
        const meetingLinkDiv = document.getElementById('meetingLinkDiv');
        meetingLinkDiv.style.display = virtualRadio.checked ? 'block' : 'none';
    }
    </script>
</body>
</html>