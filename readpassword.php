<?php
    require_once "dbconnect.php";

    $link = @mysqli_connect($host, $user, $dbpassword, $database);
    $info = "";

    if(!$link) {
        $info = "Debugging errno: " . mysqli_connect_errno();
    } else {
        $info = "Connection success!";
//        encoding for strings coming from client (SET NAMES)
//        encoding fro string coming from database (SET CHARSET)
        @mysqli_query($link, "SET CHARSET utf8");
        @mysqli_query($link, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

        $query = sprintf("SELECT * FROM choosen_passwords WHERE id=1");
        if($result = @mysqli_query($link, $query)) {
            $info = $info ."\nSuccess when performing query!";
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        } else {
            $info = $info ."\nFailed when performing query!";
        }

        mysqli_close($link);
    }

    echo json_encode(array("password" => $row['actual_password'],
                           "info"     => $info)                 );
?>