<?php
    require_once("../MIDDLEWARE/db_connect.php");
    require_once("../MIDDLEWARE/role-state-management.php");
    roleStateManagement("Admin");
    $conn = OpenCon();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/announcement.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Announcement Page</title>
</head>
<body>
    <?php include '../HEADER/admin-header.inc.php'; ?>

    <div id="add-btn-container">
        <input type="checkbox" id="form-toggle">
        <label for="form-toggle" class="add-btn" onclick="clearForm()">Add Announcement</label> 
        <form id="popup-form" action="../POST/make-announcement.php" method="POST" enctype="multipart/form-data">
            <label for="form-toggle" class="close-btn">&times;</label>
            <h2 id="form-title">Create / Update Announcement</h2>
            <input type="hidden" name="id" id="announcement-id" value="">
            <p class="col-left">
                <label for="title">Title</label>
            </p>
            <p class="col-right">
                <input type="text" name="title" id="title" required />
            </p>
            <p class="col-left">
                <label for="description">Description</label>
            </p>
            <p class="col-right">
                <textarea name="description" id="description" cols="30" rows="5" required></textarea>
            </p>
            <p class="col-left">
                <label for="attachment">Attachment</label>
            </p>
            <p class="col-right">
                <input type="file" name="attachment" id="attachment" />
            </p>
            <p class="col-left"></p>
            <p class="col-right">
                <input type="submit" id="submit" name="submit" value="Submit" />
            </p>
        </form>
    </div>
    
    <div class="announcement-container">
        <ul class="announcement-list">
            <?php
                $sql = "SELECT * FROM announcement";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<li class='announcement-item'>";
                        echo "<div class='announcement-header'>";
                        echo "<div class='admin-info'>";
                        echo "<span>".$row['author']."</span> | <span>".$row['posted_date']."</span>";
                        echo "</div>";
                        echo "<div class='announcement-action'>";
                        echo "<button onclick=\"editAnnouncement('".$row['id']."', '".$row['title']."', '".$row['description']."')\">Edit</button>";
                        echo "<button onclick=\"confirmDelete(".$row['id'].")\">Delete</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='announcement-content'>";
                        echo "<h2>".$row['title']."</h2>";
                        if ($row['attachment']) {
                            $filePath = "../UPLOADS/".$row['attachment'];
                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                            if(in_array($fileExtension, $imageExtensions)) {
                                echo "<img src='".$filePath."' alt='Attachment' class='announcement-img'>";
                            }
                            else {
                                echo "<p><a href='".$filePath."' target='_blank'>Download Attachment</a></p>";
                            }
                        }
                        echo "<p>".$row['description']."</p>";
                        echo "</div>";
                        echo "</li>";
                    }
                } else {
                    echo "<li class='announcement-item'><p>No posted announcements</p></li>";
                }
            ?>
        </ul>
    </div>

    <script>
    function confirmDelete(id) {
        if (confirm('Confirm to delete this announcement?')) {
            window.location.href = 'delete-announcement.php?id=' + id;
        }
    }

    function editAnnouncement(id, title, description) {
        document.getElementById('announcement-id').value = id;
        document.getElementById('title').value = title;
        document.getElementById('description').value = description;
        document.getElementById('form-title').textContent = "Edit Announcement";
        document.getElementById('form-toggle').checked = true;
    }

    function clearForm() {
        document.getElementById('announcement-id').value = "";
        document.getElementById('title').value = "";
        document.getElementById('description').value = "";
        document.getElementById('form-title').textContent = "Create Announcement";
    }
    </script>
</body>
</html>
