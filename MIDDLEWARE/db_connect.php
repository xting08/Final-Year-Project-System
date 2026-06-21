<?php
    function OpenCon() {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "web_dev_assignment";

        $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        
        if(!$connect) {
            die("Connection Failed: " . mysqli_connect_error());
        }

        return $connect;
    }

    function CloseCon($connect) {
        $connect -> close();
    }
?>