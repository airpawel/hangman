<?php
    require_once "dbconnect.php";

    $link = @mysqli_connect($host, $user, $dbpassword, $database);
    $info = "";
    $hidden = "";
    $id = 0;

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
            $hidden = $row['actual_password'];
            $id = $row['password_num'];

            mysqli_free_result($result);
        } else {
            $info = $info ."\nFailed when performing query!";
        }

        mysqli_close($link);
    }

    echo json_encode(array("id"       => $id,
                           "password" => $hidden,
                           "info"     => $info) );

//        $query = sprintf("SELECT * FROM passwords WHERE id=%d", $id);
//        if($result = @mysqli_query($link, $query)) {
//            $info = $info ."\nSuccess when performing query!";
//            $row = mysqli_fetch_assoc($result);
//            mysqli_free_result($result);
//            $password = $row['password'];
//
//        } else {
//            $info = $info ."\nFailed when performing query!";
//        }

//        check if letters exist in the
//        $exists = 0;
//        $newhidden = $hidden;
//        for($i = 0; $i < strlen($password); $i++) {
//            if(substr($password, $i, 1) == letters[$num]) {
//                $exists = 1;
//                $newhidden[$num] = letters[$num];
//            }
//        }

//        if(strcmp($newhidden, $hidden) === 0) {
//            $query = sprintf("UPDATE choosen_passwords SET actual_password='%s' WHERE choosen_passwords.id = 1",
//                             mysqli_real_escape_string($link, $newhidden) );
//
//            if(@mysqli_query($link, $query)) {
//                $info = $info ."\nSuccessfully updated";
//            } else {
//                $info = $info ."\nFailed to update. ERROR: ". mysqli_connect_errno() ."\n". mysqli_connect_error();
//            }
//        } else {
//            $info = $info ."\nNo password updating (no letter matches!)";
//        }

?>

