<?php
require_once '../MIDDLEWARE/db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function getMeetingsForWeek($week){
    $conn = OpenCon();
    $studentSql = "SELECT * FROM student WHERE user_id = " . $_SESSION['user_id'] . ";";
    $studentResult = $conn->query($studentSql);
    $student = $studentResult->fetch_assoc();

    if (!$student || !isset($student['supervisor_id'])) {
        return array(); 
    }

    $sql = "SELECT * FROM meeting_slot 
            WHERE supervisor_id = '" . $student['supervisor_id'] . "'
            AND week = '" . $week . "'
            ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), 
            start_time ASC";

    $result = $conn->query($sql);
    if (!$result) {
        return array(); 
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}   

function bookMeeting($meeting_id){
    $conn = OpenCon();
    $sql = "UPDATE meeting_slot SET is_taken = 1, student_id = '" . $_SESSION['user_id'] . "' 
            WHERE id = " . $meeting_id;
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

function deleteMeeting($meeting_id){
    $conn = OpenCon();
    $sql = "UPDATE meeting_slot SET is_taken = 0, student_id = NULL 
            WHERE id = " . $meeting_id;
    $conn->query($sql);
    $conn->close();
}


function uploadDocument($meeting_id, $file){
    $conn = OpenCon();
    $sql = "UPDATE meeting_slot SET file = '" . $file . "' 
            WHERE id = " . $meeting_id;
    $conn->query($sql);
    $conn->close();
}

function uploadFile($meetingId, $fileData) {
    $conn = OpenCon();
    
    if ($fileData["error"] == 0) {
        $fileType = $fileData["type"];
        $fileTmpName = $fileData["tmp_name"];
        
        if ($fileType != "application/pdf" && 
            $fileType != "application/msword" && 
            $fileType != "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
            echo "<script>alert('Only PDF and Word documents are allowed!')</script>";
            return false;
        }

        $fileContent = file_get_contents($fileTmpName);
        if ($fileContent === false) {
            echo "<script>alert('Error reading file!')</script>";
            return false;
        }

        $fileContent = $conn->real_escape_string($fileContent);
        $sql = "UPDATE meeting_slot 
                SET attachment = '" . $fileContent . "'
                WHERE id = " . $meetingId;
        
        if ($conn->query($sql)) {
            echo "<script>
                alert('File uploaded successfully!');
                window.location.href = 'meeting.php?week=" . $_GET['week'] . "';
            </script>";
            return true;
        } else {
            echo "<script>alert('Error uploading to database: " . $conn->error . "')</script>";
            return false;
        }
    }
    $conn->close();
    return false;
}



?>