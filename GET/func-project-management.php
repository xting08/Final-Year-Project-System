<style>   
    #delete-project {
        background-color: #7e3c3e; 
        color: white; 
        font-weight: bold;
        border-radius: 0.3em; 
        border: black 0.15em solid;
        padding: 0.5em 1em;
        cursor: pointer;
        transition-duration: 0.15s;
    }

    #delete-project:hover {
        background-color: #a63c3e;  
        transform: scale(1.05);
    }
</style>

<?php
    require_once '../MIDDLEWARE/db_connect.php';

    function displaySupervisorProject() {
        $connect = OpenCon();

        $sql = "SELECT * FROM title_proposed WHERE supervisor_id = '" . $_SESSION['user_id'] . "'";
        $result = mysqli_query($connect,$sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $taken = $row['is_taken'];
                $studentId = $row['student_id'];

                if (!empty($studentId)) {
                    $studentNameSql = "SELECT full_name FROM student WHERE user_id = " . $studentId;
                    $studentNameResult = mysqli_query($connect, $studentNameSql);
                    $studentNameRow = mysqli_fetch_array($studentNameResult, MYSQLI_ASSOC);
                    $studentName = $studentNameRow["full_name"];
                } else {
                    $studentName = "N/A";
                }

                echo '<tr>';
                    echo '<td>' . $row['title'] . '</td>';
                    echo '<td>' . $row['project_type'] . '</td>';
                    if ($taken == '1') {
                        echo '<td>' . $studentName . '</td>';
                        echo '<td>' . $studentId . '</td>';
                    } else {
                        echo '<td colspan="2">Available</td>';
                        echo '<td><button id="delete-project" onclick="window.location.href=\'../POST/delete-project.php?id=' . $row['id'] . '\'">DELETE</button></td>';
                    }   
                echo '</tr>';
            }
        } else {
            echo '<tr>';
                echo '<td colspan="5">NO PROJECT AVAILABLE</td>';
            echo '</tr>';
        }
    }

    function displayProjectApproval() {
        $connect = OpenCon();
        $sql = "SELECT id, student_id, title, propose, description FROM project WHERE supervisor_id = '" . $_SESSION['user_id'] . "' AND supervisor_approval_status = 'Pending'";
        $result = mysqli_query($connect, $sql);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (count($row) > 0) {
            foreach ($row as $value) {
                $studentSql = "SELECT full_name FROM student WHERE user_id = '" . $value['student_id'] . "'";
                $studentNameResult = mysqli_query($connect, $studentSql);
                $studentNameRow = mysqli_fetch_array( $studentNameResult, MYSQLI_ASSOC);
                $studentName = $studentNameRow['full_name'];
                
                echo '<div class="project-card">
                        <div class="student">
                            <h3 style="text-align: center; font-size: 1em;">' . $studentName . '</h3>
                            <h3>' . $value['student_id'] . '</h3>
                            <h5>' . $value['propose'] . '</h5>
                        </div>
                        <div class="project-details">
                            <h3>' . $value['title'] . '</h3>
                            <p>' . $value['description'] . '</p>
                        </div>
                        <div class="view-btn">
                            <button id="view-project" onclick="window.location.href=\'status.php?id=' . $value['id'] . '\'">VIEW</button>
                        </div>
                      </div>';
            }
        } else {
            echo '<div class="project-card">
                    <h3>NO PROJECT AVAILABLE</h3>
                  </div>';
        }
    }

    function displayProjectApprovalAdmin() {
        $connect = OpenCon();
        $sql = "SELECT id, student_id, title, propose, description FROM project WHERE admin_approval_status = 'Pending' AND supervisor_approval_status = 'Approved'";
        $result = mysqli_query($connect, $sql);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (count($row) > 0) {
            foreach ($row as $value) {
                $studentSql = "SELECT full_name FROM student WHERE user_id = '" . $value['student_id'] . "'";
                $studentNameResult = mysqli_query($connect, $studentSql);
                $studentNameRow = mysqli_fetch_array( $studentNameResult, MYSQLI_ASSOC);
                $studentName = $studentNameRow['full_name'];
                
                echo '<div class="project-card">
                        <div class="student">
                            <h3 style="text-align: center; font-size: 1em;">' . $studentName . '</h3>
                            <h3>' . $value['student_id'] . '</h3>
                            <h5>' . $value['propose'] . '</h5>
                        </div>
                        <div class="project-details">
                            <h3>' . $value['title'] . '</h3>
                            <p>' . $value['description'] . '</p>
                        </div>
                        <div class="view-btn">
                            <button id="view-project" onclick="window.location.href=\'status.php?id=' . $value['id'] . '\'">VIEW</button>
                        </div>
                      </div>';
            }
        } else {
            echo '<div class="project-card">
                    <h3>NO PROJECT AVAILABLE</h3>
                  </div>';
        }
    }

    function tableHeader() {
        $connect = OpenCon();
        $sql = "SELECT * FROM title_proposed WHERE supervisor_id = '" . $_SESSION['user_id'] . "'";
        $result = mysqli_query($connect,$sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if ($rows['is_taken'] = 1) {
            echo '<tr>
                    <th>Project Title</th>
                    <th>Project Type</th>
                    <th>Student In-Charge</th>
                    <th>Student ID</th>
                  </tr>';
        } else {
            echo '<tr>
                    <th>Project Title</th>
                    <th>Project Type</th>
                    <th>Student In-Charge</th>
                    <th>Student ID</th>
                    <th>Delete</th>
                  </tr>';
        }
    }
?>