<?php
require_once "../MIDDLEWARE/db_connect.php";
require_once "../MIDDLEWARE/role-state-management.php";
require_once '../chat-object.php';

    roleStateManagement("Student");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $conn = OpenCon();
    $sql = "SELECT DISTINCT chat.* FROM chat
            JOIN participant ON chat.id = participant.chat_id
            WHERE participant.user_id = '" . $_SESSION['user_id'] . "'";
    $result = $conn -> query($sql);
    $chats = []; 
    while ($row = $result -> fetch_assoc()) {
        $participantQuery = "SELECT users.* FROM users 
                            JOIN participant ON users.id = participant.user_id 
                            WHERE participant.chat_id = '" . $row['id'] . "'";
        $participantResult = $conn->query($participantQuery);
        $participants = [];
        while ($participant = $participantResult->fetch_assoc()) {
            $participants[] = $participant;
        }

        $adminQuery = "SELECT users.* FROM users 
                        JOIN participant ON users.id = participant.user_id 
                        WHERE participant.chat_id = '" . $row['id'] . "' 
                        AND participant.is_admin = 1";
        $adminResult = $conn->query($adminQuery);
        $admin = $adminResult->fetch_assoc();
        $chat = new Chat($row['id'], $row['chat_type'], $row['created_at'], $row['chat_name'], $participants, $admin);
        $chats[] = $chat; 
    }
    
    $current_chat = null;
    if (isset($_GET['chat_id'])) {
        foreach ($chats as $chat) {
            if ($chat->id == $_GET['chat_id']) {
                $current_chat = $chat;
                break;
            }
        }
    }
    
    if (isset($_POST['send_message']) && isset($_POST['chat_id']) && isset($_POST['message'])) {
        if ($current_chat->sendMessage($_POST['chat_id'], $_POST['message'])) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?chat_id=" . $_POST['chat_id']);
            exit;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/chat.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Chat</title>
</head>

<body>
    <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once '../MIDDLEWARE/db_connect.php';
        $connect = OpenCon();
    
        $sql = "SELECT * FROM project WHERE student_id = '" . $_SESSION['user_id'] . "'";
        $result = $connect -> query($sql);
        $rows = $result->fetch_assoc();
        
        if ($result->num_rows == 0) {
            include '../HEADER/student-partial-header.inc.php';
        }
        else if ($rows['supervisor_approval_status'] == 'Pending' || 
            $rows['supervisor_approval_status'] == 'Rejected' ||
            $rows['admin_approval_status'] == 'Pending' ||
            $rows['admin_approval_status'] == 'Rejected') {
            include '../HEADER/student-partial-header.inc.php';
        } else {
            include '../HEADER/student-header.inc.php';
        }
    ?>

    <div class="container">
        <div class="col-left">
            <div class="btn-container">
                <form action="" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search.." >
                    <button type="submit" id="search-btn"><i class="fa fa-search"></i></button>
                </form>
                <button type="button" id="add-btn" onclick="window.location.href='../add-chat.php'">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <div class="chat-list-container">
                <ul class="chat-list">
                    <?php
                        if (isset($_GET['search'])) {
                            $searchResults = !empty($_GET['search']) ? Chat::searchChat($_GET['search']) : $chats;
                            foreach ($searchResults as $chat) {
                                $chat->printChatName($chat->chatName, $chat->chatType);
                            }
                        } else {
                            foreach ($chats as $chat) {
                                $chat->printChatName($chat->chatName, $chat->chatType);
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>

        <?php
            if ($current_chat) {
                $current_chat->displayChatRoom($current_chat->id);
            }
        ?>
    </div>
</body>
</html>