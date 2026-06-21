<?php
require_once("../MIDDLEWARE/db_connect.php");

class Project
{
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

    /**
     * progression should get from getProgression method 
     * where it will query then find the last attached pdf then the progression, WHILE LOOP, 
     *  while i < 6
     * if current attachment == null then return i + 1; 
     * else continue;
     */
    public function __construct($studentId)
    {
        $row = self::getStudentNProject();
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

    private static function getStudentNProject()
    {
        $connect = OpenCon();
        $sql = "SELECT student.*, project.*
            FROM student 
            INNER JOIN project on student.user_id = project.student_id
            WHERE student.user_id = " . $_SESSION['user_id'] . " AND supervisor_approval_status = 'Approved' AND admin_approval_status = 'Approved'";
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
                echo '<a href="download-project.php?attachment=' . $i . '&student_id=' . $this->studentId . '" target="_blank">Download Chapter ' . $i . '</a>';
                echo '<form method="POST" enctype="multipart/form-data">';
                echo '<input type="file" id="chapter' . $i . '" name="chapter' . $i . '" accept=".doc,.docx,.pdf" style="display: none;">';
                echo '<button type="button" onclick="document.getElementById(\'chapter' . $i . '\').click()" class="edit-button" style="display: inline-block !important;">Edit</button>';
                echo '</form>';
                echo '<script>
                    document.getElementById("chapter' . $i . '").onchange = function() {
                        if(confirm("Are you sure you want to update this file?")) {
                            this.parentElement.submit();
                        }
                    };
                </script>';
            } else {
                echo '<form method="POST" enctype="multipart/form-data">';
                echo '<input type="file" id="chapter' . $i . '" name="chapter' . $i . '" accept=".doc,.docx,.pdf">';
                echo '<button type="submit" name="upload" value="' . $i . '" class="submit-button" style="display: inline-block !important;">Submit</button>';
                echo '</form>';     
            }
            echo "</li>";
        }
        echo "</ul>";
    }

    public function editAttachment($chapterId) {
        $connect = OpenCon();
        
        if ($_FILES["chapter" . $chapterId]["error"] == 0) {
            $fileName = $_FILES["chapter" . $chapterId]["name"];
            $fileType = $_FILES["chapter" . $chapterId]["type"];
            $fileTmpName = $_FILES["chapter" . $chapterId]["tmp_name"];
            
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

            $fileContent = $connect->real_escape_string($fileContent);
            $sql = "UPDATE project 
                   SET submission_attachment_" . $chapterId . " = '" . $fileContent . "'
                   WHERE student_id = '" . $this->studentId . "'";
            
            if ($connect->query($sql)) {
                $property = "attachment" . $chapterId;
                $this->$property = $fileContent;
                echo "<script>
                    alert('File updated successfully!');
                    window.location.href = 'project-student.php';
                </script>";
                
            } else {
                echo "<script>alert('Error updating database: " . $connect->error . "')</script>";
            }
        }
        $connect->close();
    }

    public function uploadFile($chapterId) {
        $connect = OpenCon();
        
        if ($_FILES["chapter" . $chapterId]["error"] == 0) {
            $fileName = $_FILES["chapter" . $chapterId]["name"];
            $fileType = $_FILES["chapter" . $chapterId]["type"];
            $fileTmpName = $_FILES["chapter" . $chapterId]["tmp_name"];
            
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

            $fileContent = $connect->real_escape_string($fileContent);
            $sql = "UPDATE project 
                   SET progression = $chapterId,
                   submission_attachment_" . $chapterId . " = '" . $fileContent . "'
                   WHERE student_id = '" . $this->studentId . "'";
            
            if ($connect->query($sql)) {
                $property = "attachment" . $chapterId;
                $this->$property = $fileContent;
                echo "<script>
                    alert('File uploaded successfully!');
                    window.location.href = 'project-student.php';
                </script>";
            } else {
                echo "<script>alert('Error uploading to database: " . $connect->error . "')</script>";
            }
        }
        $connect->close();
    }

    public function printProgressionBar()
    {
        $value = $this->progression * 10;
        echo '<progress id="progression" value="' . $value . '" max="60" title="' . $value . '%">' . $value . '</progress>';
    }

    /**
     * displaayTodo
     * to display the todo list
     * display based on the week
     * if APPROVED then clickable 
     * else if PENDING then not clickable
     * else if REJECTED then not clickable + EDIT button to modify and submit for APPROVAL
     * @return 
     */
    public function displayTodo(){
        $connect = OpenCon();
        $sql = "SELECT it.id AS item_todo_id, it.title, it.is_complete, t.is_approve, t.week, t.student_id
                FROM item_todo it
                JOIN todo_joint tj ON it.id = tj.item_todo_id
                JOIN todo t ON tj.todo_id = t.id
                WHERE t.student_id = " . $_SESSION['user_id'] . "
                ORDER BY t.week ASC";
        $result = $connect->query($sql);

        echo '<script>
            function deleteTodoWeek(week) {
                if(confirm("Are you sure you want to delete all todos for Week " + week + "?")) {
                    window.location.href = "project-student.php?action=delete&week=" + week;
                }
            }
        </script>';

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
            $isDisable = $is_approve != 1 ? "disabled" : " ";

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
                        <input type='checkbox' id='w" . $week . "plan-" . $item_todo_id . "' " . $is_complete . " " . $isDisable . "
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
            echo '<input type="checkbox" id="delete"> <label for="delete" onclick="deleteTodoWeek(' . $week . ')">DELETE</label>';
        } else if ($is_approve == 1) {
            echo "<p id='week" . $week . "-status' class='status approve'>Approved</p>";
        } else {
            echo "<p id='week" . $week . "-status' class='status pending'>Pending</p>";
        }
    }

    public function deleteTodoWeek($week) {
        $conn = OpenCon();
        $studentId = $_SESSION["user_id"];

        $todoSql = "SELECT id FROM todo WHERE week = $week AND student_id = '$studentId'";
        $result = $conn->query($todoSql);
        $todoId = $result->fetch_assoc()['id'];

        // Delete from todo_joint and item_todo
        $deleteJointSql = "DELETE tj, it FROM todo_joint tj 
                           JOIN item_todo it ON tj.item_todo_id = it.id 
                           WHERE tj.todo_id = $todoId";
        $conn->query($deleteJointSql);

        // Delete from todo table
        $deleteTodoSql = "DELETE FROM todo WHERE id = $todoId";
        $conn->query($deleteTodoSql);

        $conn->close();

        echo "<script>
                alert('Week $week todos deleted successfully!');
                window.location.href = 'project-student.php';
              </script>";
    }

    // add plan 
    public function createTodo() {
        $conn = OpenCon();
        $studentId = $_SESSION["user_id"];

        if (isset($_POST["submit"])) {
            $week = $_POST["add-week"];
            $title = $_POST["add-title"];

            if ($week > 0 && !empty($title)) {
                $checkTodoSql = "SELECT id FROM todo WHERE week = $week AND student_id = '$studentId'";
                $result = $conn->query($checkTodoSql);
                
                $itemSql = "INSERT INTO item_todo (title, is_complete) VALUES ('$title', 0)";
                $itemRes = $conn->query($itemSql);
                
                if ($itemRes) {
                    $itemFk = $conn->insert_id;
                    
                    if ($result->num_rows > 0) {
                        $todoFk = $result->fetch_assoc()['id'];
                    } else {
                        $todoSql = "INSERT INTO todo (is_approve, week, student_id) VALUES (2, $week, '$studentId')";
                        $conn->query($todoSql);
                        $todoFk = $conn->insert_id;
                    }


                    $jointSql = "INSERT INTO todo_joint (todo_id, item_todo_id) VALUES ($todoFk, $itemFk)";
                    $jointRes = $conn->query($jointSql);
                    
                    if ($jointRes) {
                        echo "<script>
                                alert('Todo added successfully!');
                                window.location.href = 'project-student.php';
                              </script>";
                    } else {
                        echo "Error: " . $conn->error;
                    }
                } else {
                    echo "Error: " . $conn->error;
                }
            } else {
                echo "<script>alert('Invalid input. Please fill in all fields.');</script>";
            }
        }

        $conn->close();
    }
}