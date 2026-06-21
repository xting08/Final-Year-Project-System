<?php require_once("../MIDDLEWARE/db_connect.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $conn = OpenCon();
        $item_todo_id = $_POST["item_todo_id"];
        $is_complete = $_POST["is_complete"];

        $sql = "UPDATE item_todo
                SET is_complete = $is_complete
                WHERE id = $item_todo_id";
                
        $result = $conn->query($sql);
        $conn ->close();
    }
    
?>