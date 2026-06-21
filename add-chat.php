<?php
include("chat-object.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = OpenCon();
    
    $chatName = $_POST['chat_name'];
    $chatType = $_POST['chat_type'];
    $sql = "INSERT INTO chat (chat_type, chat_name, created_at) VALUES ('$chatType', '$chatName', NOW())";
    
    if ($conn->query($sql)) {
        $chatId = $conn->insert_id;
        
        $creatorId = $_SESSION['user_id'];
        $sql = "INSERT INTO participant (chat_id, user_id, is_admin) VALUES ('$chatId', '$creatorId', 1)";
        $conn->query($sql);
        
        if(isset($_POST['participants'])) {
            foreach($_POST['participants'] as $participantId) {
                $sql = "INSERT INTO participant (chat_id, user_id, is_admin) VALUES ('$chatId', '$participantId', 0)";
                $conn->query($sql);
            }
        }
        
        if ($_SESSION['role'] == "Student"){
            header("Location: ./STUDENT/chat.php");
        } else if ($_SESSION['role'] == "Supervisor"){
            header("Location: ./SUPERVISOR/chat.php");
        } else if ($_SESSION['role'] == "Admin"){
            header("Location: ./ADMIN/chat.php");
        }
        exit();
    }
}

$conn = OpenCon();
$sql = "SELECT * FROM users WHERE user_id != " . $_SESSION['user_id'];
$users = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/set.css">
    <link rel="stylesheet" href="./CSS/header.css">
    <link rel="stylesheet" href="./CSS/chat.css">
    <link rel="icon" href="./IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Add New Chat</title>
</head>
<body>
    <div class="container">
        <div class="col-right">
            <div class="chat-header">
                <h2>Create New Chat</h2>
                <?php
                $backUrl = '';
                if ($_SESSION['role'] == "Student") {
                    $backUrl = "./STUDENT/chat.php";
                } else if ($_SESSION['role'] == "Supervisor") {
                    $backUrl = "./SUPERVISOR/chat.php";
                } else if ($_SESSION['role'] == "Admin") {
                    $backUrl = "./ADMIN/chat.php";
                }
                ?>
                <a href="<?php echo $backUrl; ?>" class="back-btn">
                    <span class="back-icon"><i class="fas fa-arrow-left"></i></span>
                    <span class="back-text">Back</span>
                </a>
            </div>
            
            <form method="POST" action="" class="chat-container" style="padding: 2em;">
                <div style="margin-bottom: 2em;">
                    <label for="chat_name">Chat Name:</label>
                    <input type="text" name="chat_name" required>
                </div>
                
                <div style="margin-bottom: 2em;">
                    <label for="chat_type">Chat Type:</label>
                    <select name="chat_type" required style="width: 100%; padding: 10px; border: 1px solid #6d7045; border-radius: 5px; color: #6d7045; background-color: #fffcef;">
                        <option value="private">Private</option>
                        <option value="group">Group</option>
                    </select>
                </div>
                
                <div>
                    <label>Select Participants:</label>
                    <div class="chat-list-container">
                        <?php while($user = $users->fetch_assoc()): ?>
                            <input type="checkbox" class="checkbox-participant" id="<?php echo $user['full_name']; ?>" name="participants[]" value="<?php echo $user['user_id']; ?>" style="width: auto;" hidden>
                            <label class="chat" style="height: auto; padding: 1em 1em;" for="<?php echo $user['full_name']; ?>"><?php echo $user['full_name']; ?></label>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <div class="btn-container">
                    <button type="submit" class="submit-btn" style="width: 100%;">Create Chat</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('select[name="chat_type"]').addEventListener('change', function() {
            const participantList = document.querySelector('.participant-list');
            const checkboxes = participantList.querySelectorAll('input[type="checkbox"]');
            
            if (this.value === 'private') {
                let checkedCount = 0;
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            checkedCount++;
                            if (checkedCount > 1) {
                                this.checked = false;
                                checkedCount--;
                                alert('Private chat can only have one participant');
                            }
                        } else {
                            checkedCount--;
                        }
                    });
                });
            } else {
                checkboxes.forEach(checkbox => {
                    checkbox.onclick = null;
                });
            }
        });
    </script>
</body>
</html>
