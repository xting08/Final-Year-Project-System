<?php
require_once '../MIDDLEWARE/db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function displayMeeting($week){
    $conn = OpenCon();
    $sql = "SELECT * FROM meeting_slot 
            WHERE supervisor_id = " . $_SESSION['user_id'] . "
            AND week = " . $week . "
            ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), 
            start_time ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addMeeting($week, $location, $start_time, $end_time, $day, $meetingLink = '') {
    $conn = OpenCon();    

    $sql = "INSERT INTO meeting_slot (week, day, start_time, end_time, location, meet_link, supervisor_id) 
            VALUES ($week, '$day', '$start_time', '$end_time', '$location', '$meetingLink', " . $_SESSION['user_id'] . ")";

    $conn->query($sql);
    $conn->close();
}

function deleteMeeting($meeting_id){
    $conn = OpenCon();
    $sql = "DELETE FROM meeting_slot WHERE id = " . $meeting_id;
    $conn->query($sql);
    $conn->close();
}


function getMeetingsForWeek($week){
    $conn = OpenCon();
    $sql = "SELECT * FROM meeting_slot 
            WHERE supervisor_id = " . $_SESSION['user_id'] . "
            AND week = " . $week . "
            ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), 
            start_time ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>