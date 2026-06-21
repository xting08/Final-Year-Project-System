<?php
require_once "MIDDLEWARE/db_connect.php";

    class Chat {
        private $id;
        private $chatType;
        private $createdAt; 
        private $chatName;
        private $participants;
        private $admin;
        
        public function __construct($id, $chatType, $createdAt, $chatName, $participants, $admin) {
            $this->id = $id;
            $this->chatType = $chatType;
            $this->createdAt = $createdAt;
            $this->chatName = $chatName;
            $this->participants = $participants;
            $this->admin = $admin;
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
        
        public function printChatName($chatName, $chatType){
            echo "<li><button type='button' class='chat' onclick='window.location.href=\"?chat_id=" . $this->id . "\"'>" . $chatName . " ( " . $chatType . " )</button></li>";
        }

        public function displayChatRoom($chatId){
            $this->displayChatHeader();
            $this->displayMessages($chatId);
            $this->displayChatInput();
        }

        private function displayChatHeader(){
            echo '<div class="col-right">
                    <div class="chat-header">
                        <h2>' . $this->chatName . '</h2>
                    </div>
                    <div class="chat-container">';
        }

        private function displayChatInput() {
            echo '<div class="chat-input">
                <form method="POST" action="" id="chat-input-form" enctype="multipart/form-data">
                    <div class="attachment-container">
                        <label for="attachment" class="attachment-btn"><i class="fa fa-paperclip"></i></label>
                        <input type="file" name="attachment" id="attachment" accept=".pdf,.docx" hidden />
                        <span id="selected-file-name"></span>
                    </div>
                    <input type="text" name="message" placeholder="Type a message.." required>
                    <input type="hidden" name="chat_id" value="' . $this->id . '">
                    <button type="submit" name="send_message" class="submit-btn">
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </form>
            </div>
            <script>
                document.getElementById("attachment").addEventListener("change", function() {
                    const fileName = this.files[0] ? this.files[0].name : "";
                    document.getElementById("selected-file-name").textContent = fileName;
                });

                document.getElementById("chat-input-form").addEventListener("submit", function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    
                    fetch("", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        this.reset();
                        document.getElementById("selected-file-name").textContent = "";
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Error sending message");
                    });
                });
            </script>';
        }

        private function displayMessages($chatId) {
            $messages = $this->getMessages($chatId);
            
            if ($messages) {
                foreach ($messages as $message) {
                    $messageClass = ($message['sender'] == $_SESSION['user_id']) ? 'right-chat' : 'left-chat';
                    echo '<div class="' . $messageClass . '">
                            <span class="sender-name">' . $message['sender_name'] . '</span>
                            <div class="message-content">';
                    
                    if (!empty($message['content'])) {
                        echo '<p>' . $message['content'] . '</p>';
                    }
                    
                    if (!empty($message['attachment'])) {
                        echo '<div class="attachment">
                                <i class="fa fa-file"></i>
                                <a href="../GET/download-chat-attachment.php?message_id=' . $message['id'] . '">Download Attachment</a>
                              </div>';
                    }
                    
                    echo '<br>
                        </div>
                      </div>';
                }
            } else {
                echo '<div class="no-messages">No messages yet</div>';
            }
        }
        
        private function getMessages($chatId) {
            try {
                $conn = OpenCon();
                $sql = "SELECT m.id, m.sender, m.content, m.attachment, m.send_at, u.full_name as sender_name 
                        FROM message m 
                        JOIN users u ON m.sender = u.user_id
                        WHERE m.chat_id = $chatId 
                        ORDER BY m.send_at ASC";
                
                $result = $conn->query($sql);
                return $result->fetch_all(MYSQLI_ASSOC);
            } catch (Exception $e) {
                error_log("Error fetching messages: " . $e->getMessage());
                return array();
            }
        }

        public function sendMessage($chatId, $message) {
            try {
                $conn = OpenCon();
                $sql = "INSERT INTO message (chat_id, sender, content) 
                        VALUES ($chatId, '{$_SESSION['user_id']}', '$message')";
                return $conn->query($sql);
            } catch (Exception $e) {
                error_log("Error sending message: " . $e->getMessage());
                return false;
            }
        }

        function uploadFile($chatId, $fileData, $message) {
            try {
                $conn = OpenCon();
                
                if ($fileData["error"] != 0) {
                    throw new Exception("File upload error");
                }

                $fileContent = $conn->real_escape_string(file_get_contents($fileData["tmp_name"]));
                
                $sql = "INSERT INTO message (chat_id, sender, content, attachment) 
                        VALUES ($chatId, '{$_SESSION['user_id']}', '$message', '$fileContent')";
                
                $result = $conn->query($sql);
                if (!$result) {
                    throw new Exception("Error uploading to database: " . $conn->error);
                }

                $conn->close();
                return true;

            } catch (Exception $e) {
                error_log("Error uploading file: " . $e->getMessage());
                if (isset($conn)) {
                    $conn->close();
                }
                return false;
            }
        }

        public static function addChat($chatName, $chatType){
            $conn = OpenCon();
            $sql = "INSERT INTO chat (chat_name, chat_type) VALUES ('$chatName', '$chatType')";
            return $conn->query($sql);
        }

        public static function searchChat($chatName){
            $conn = OpenCon();
            $sql = "SELECT * FROM chat WHERE chat_name LIKE '%$chatName%'";
            $result = $conn->query($sql);
            
            $chats = [];
            while ($row = $result->fetch_assoc()) {
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
                
                $chats[] = new Chat($row['id'], $row['chat_type'], $row['created_at'], $row['chat_name'], $participants, $admin);
            }
            return $chats;
        }
    }
?>