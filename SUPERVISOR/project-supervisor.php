<?php 
require_once("../MIDDLEWARE/db_connect.php");

class ProjectSupervisor {
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

    public function displayTodo($studentId){
        $connect = OpenCon();
        $sql = "SELECT it.id AS item_todo_id, it.title, it.is_complete, t.is_approve, t.week, t.student_id
                FROM item_todo it
                JOIN todo_joint tj ON it.id = tj.item_todo_id
                JOIN todo t ON tj.todo_id = t.id
                WHERE t.student_id = " . $studentId . "
                ORDER BY t.week ASC";
        $result = $connect->query($sql);

        $weekArray = array();
        while ($row = $result->fetch_assoc()) {
            $week = $row['week'];
            $title = $row['title'];
            $is_complete = $row['is_complete'];
            $item_todo_id = $row['item_todo_id'];
            $is_approve = $row['is_approve'];

            if (!isset($weekArray[$week])) {
                $weekArray[$week] = array();
            }

            $weekArray[$week][] = [
                "item_todo_id"=> $item_todo_id,
                "title" => $title,
                "is_complete" => $is_complete,
                "is_approve" => $is_approve
            ];
        } 

        foreach ($weekArray as $week => $items) {
            $is_approve = $items[0]["is_approve"];

            echo "<details>
                    <summary>
                        <div id='week'>
                            <span>WEEK " . $week . "</span>";
            echo $this -> displayTodoheader($week, $is_approve);
            echo     "</div>
                        <i class='fa-solid fa-caret-down' for='add-plan'></i>
                    </summary>
                    <hr/>
                    <ul class='items'>";
    
            foreach ($items as $item) {
                $title = $item["title"];
                $is_complete = $item["is_complete"] ? "checked" : ""; 
                $item_todo_id = $item["item_todo_id"]; 
    
                echo "<li>
                        <input type='checkbox' id='w" . $week . "plan-" . $item_todo_id . "' " . $is_complete . " " . "disabled" . "
                            onchange='updateItemTodo(" . $item_todo_id . ", this.checked)'>
                        <label for='w" . $week . "plan-" . $item_todo_id . "'>" . $title . "</label>
                      </li>";
            }

            echo "</ul>
                    </details>";
        }
    }

    private function displayTodoheader($week, $is_approve){
        if ($is_approve == 0){
            echo "<p id='week" . $week . "-status' class='status reject'>Rejected</p>";
        } else if ($is_approve == 1) {
            echo "<p id='week" . $week . "-status' class='status approve'>Approved</p>";
        } else {
            echo "<p id='week" . $week . "-status' class='status pending'>Pending</p>";
            echo "<button id='approve-todo' onclick='window.location.href=\"?action=approve&week=" . $week . "&student_id=" . $this->studentId . "\"'>Approve</button>";
            echo "<button id='reject-todo' onclick='window.location.href=\"?action=reject&week=" . $week . "&student_id=" . $this->studentId . "\"'>Reject</button>";
        }
    }


    public function approveTodo($week, $studentId){
        $connect = OpenCon();
        $sql = "UPDATE todo SET is_approve = 1 WHERE week = " . $week;
        $connect->query($sql);
        echo "<script>
                alert('Todo approved successfully!');
                window.location.href = 'view-project.php?student_id=" . $studentId . "';
            </script>";
    }

    public function rejectTodo($week, $studentId){
        $connect = OpenCon();
        $sql = "UPDATE todo SET is_approve = 0 WHERE week = " . $week;
        $connect->query($sql);
        echo "<script>

                alert('Todo rejected successfully!');
                window.location.href = 'view-project.php?student_id=" . $studentId . "';
            </script>";
    }

    public static function displayAllProgressCard($supervisorId){
        $conn = OpenCon();

        $sql = "SELECT project.*, student.* 
                FROM project 
                JOIN student ON project.student_id = student.user_id
                WHERE project.supervisor_approval_status = 'Approved' AND project.admin_approval_status = 'Approved' ";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()){
            $progressionBar = $row['progression'] / 6 * 100;
            echo "<div class='progress-card'>";
            echo "<div class='student'>";
            echo "<h3>{$row['full_name']}</h3>";
            echo "<h3>{$row['student_id']}</h3>";
            echo "</div>";
            echo "<div class='progress-info'>";
            echo "<h3>{$row['title']}</h3>";
            echo "<div class='progress-bar-container'>";
            echo "<div class='progress-bar' style='width: {$progressionBar}%'></div>";
            echo "</div>";
            echo "<p>Progression: {$progressionBar}%</p>";
            echo "</div>";
            echo "<div class='view-btn'>";
            echo '<button id="view-project" onclick="window.location.href=\'view-project.php?student_id=' . $row['student_id'] . '\'">VIEW</button>';
            echo "</div>";
            echo "</div>";
        }
    }

    public function markProjectCompletion($studentId, $progression){
        $connect = OpenCon();
        if ($progression == 6){
            $sql = "UPDATE project SET is_complete = 1 WHERE student_id = " . $studentId;
            $connect->query($sql);
            echo "<script>
                    alert('Project completion marked successfully!');
                    window.location.href = 'view-project.php?student_id=" . $studentId . "';
                </script>";
        } else {
            echo "<script>
                    alert('Project completion not marked and not eligible for marksheet!');
                    window.location.href = 'view-project.php?student_id=" . $studentId . "';
                </script>";
        }
        
    }

    public static function displayProjects($projects) {
        foreach ($projects as $project) {
            $progressionBar = $project['progression'] / 6 * 100;
            echo "<div class='progress-card'>";
            echo "<div class='student'>";
            echo "<h3>{$project['full_name']}</h3>";
            echo "<h3>{$project['student_id']}</h3>";
            echo "</div>";
            echo "<div class='progress-info'>";
            echo "<h3>{$project['project_title']}</h3>";
            echo "<div class='progress-bar-container'>";
            echo "<div class='progress-bar' style='width: {$progressionBar}%'></div>";
            echo "</div>";
            echo "<p>Progression: {$progressionBar}%</p>";
            echo "</div>";
            echo "<div class='view-btn'>";
            echo '<button id="view-project" onclick="window.location.href=\'view-project.php?student_id=' . $project['student_id'] . '\'">VIEW</button>';
            echo "</div>";
            echo "</div>";
        }
    }

}
?>