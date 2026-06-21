<?php
    require_once '../MIDDLEWARE/db_connect.php';
    $connect = OpenCon();

    $supervisorQuery = 'SELECT * FROM supervisor';
    $supervisorResult = $connect -> query($supervisorQuery);
    $supervisorRows = $supervisorResult -> fetch_all(MYSQLI_ASSOC);
    
    $projectQuery = 'SELECT title, supervisor_id, project_type FROM title_proposed WHERE is_taken = 0';
    $projectResult = $connect -> query($projectQuery);  
    $projectRows = $projectResult -> fetch_all(MYSQLI_ASSOC);

    $collabStudentQuery = 'SELECT full_name, user_id FROM student WHERE is_collab = 1';
    $collabStudentResult = $connect -> query($collabStudentQuery);
    $collabStudentRows = $collabStudentResult -> fetch_all(MYSQLI_ASSOC);

    if (!function_exists('displaySupervisorByName')) {
        function displaySupervisorByName($supervisorRows) {
            foreach ($supervisorRows as $row) {
                echo '<option value="' . $row['full_name'] . '" supervisor-id="' . $row['user_id'] . '">' . $row['full_name'] . '</option>';
            }
        }
    }

    if (!function_exists('displaySupervisorById')) {
        function displaySupervisorById($supervisorRows) {
            foreach ($supervisorRows as $row) {
                echo '<option value="' . $row['user_id'] . '">' . $row['full_name'] . '</option>';
            }
        }
    }

    if (!function_exists('displayProject')) {
        function displayProject($projectRows) {
            foreach ($projectRows as $row) { 
                echo '<option value="' . $row['title'] . '" data-supervisor="' . $row['supervisor_id'] . '">';
                echo $row['title'];
                echo '</option>';
            }
        }
    }

    if (!function_exists('displayCollabStudentName')) {
        function displayCollabStudentName($collabStudentRows) {
            foreach ($collabStudentRows as $row) {
                echo '<option value="' . $row['full_name'] . '" data-student-id="' . $row['user_id'] . '">' . $row['full_name'] . '</option>';
            }
        }
    }

    if (!function_exists('displayCollabStudentId')) {
        function displayCollabStudentId($collabStudentRows) {
            foreach ($collabStudentRows as $row) {
                echo '<option value="' . $row['user_id'] . '">' . $row['user_id'] . '</option>';
            }
        }
    }
?>

<script>
        function filterProjects() {
            var supervisorSelect = document.getElementById('supervisor-id');
            var projectSelect = document.getElementById('title-name');
            var selectedSupervisor = supervisorSelect.value;

            projectSelect.selectedIndex = 0 ;

            for (var i = 0; i < projectSelect.options.length; i++) {
                var option = projectSelect.options[i];
                if (option.getAttribute('data-supervisor') === selectedSupervisor || option.value === "") {  // Show all options if supervisor is selected
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            }
        }

        document.getElementById('supervisor-id').addEventListener('change', filterProjects);
</script>