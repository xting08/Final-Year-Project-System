<?php
require_once '../MIDDLEWARE/db_connect.php';

class ProjectAdmin {
    private $title;
    private $description;
    private $motivation;
    private $objectives;
    private $isCollab;
    private $supervisorApprovalStatus;
    private $adminApprovalStatus;
    private $studentId;
    private $studentName;
    private $supervisorId;
    private $progression;
    private $attachment1;
    private $attachment2;
    private $attachment3;
    private $attachment4;
    private $attachment5;
    private $attachment6;

    public function __construct($studentId)
    {
        $row = self::getStudentNProject($studentId);
        $this->title = $row["title"];
        $this->description = $row["description"];
        $this->motivation = $row["motivation"];
        $this->objectives = $row["objectives"];
        $this->isCollab = $row["is_collab"];
        $this->supervisorApprovalStatus = $row["supervisor_approval_status"];
        $this->adminApprovalStatus = $row["admin_approval_status"];
        $this->studentId = $row["student_id"];
        $this->studentName = $row["full_name"];
        $this->supervisorId = $row["supervisor_id"];
        $this->progression = $row["progression"];

        for ($i = 1; $i <= 6; $i++) {
            $property = "attachment" . $i;
            $this->$property = $row["submission_attachment_$i"];
        }
    }

    public function __get($variable)
    {
        if (property_exists($this, $variable)) {
            return $this->$variable;
        }
    }

    public function __set($variable, $value)
    {
        if (property_exists($this, $variable)) {
            $this->$variable = $value;
        }
    }

    private static function getStudentNProject($studentId)
    {
        $connect = OpenCon();
        $sql = "SELECT student.*, project.*
            FROM student 
            INNER JOIN project on student.user_id = project.student_id
            WHERE student.user_id = " . $studentId . " AND supervisor_approval_status = 'Approved' AND admin_approval_status = 'Approved'";
        $result = $connect->query($sql);
        return $result->num_rows > 0 ? $result->fetch_assoc() : array();
    }

    public function printChapters()
    {
        echo "<ul>";
        for ($i = 1; $i <= 6; $i++) {
            echo "<li>";
            echo "  <h3> Chapter $i </h3>";
            $submission = "attachment" . $i;
            if (!empty($this->$submission)) {
                echo '<a href="download.php?attachment=' . $i . '&student_id=' . $this->studentId . '" target="_blank">Download Chapter ' . $i . '</a>';
            } else {
                echo '<span style="color: #666;">In Progress</span>';
            }
            echo "</li>";
        }
        echo "</ul>";
    }

    public function printProgressionBar()
    {
        $value = $this->progression * 10;
        echo '<progress id="progression" value="' . $value . '" max="60" title="' . $value . '%">' . $value . '</progress>';
    }
}
?>
