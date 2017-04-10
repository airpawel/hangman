<?php
    $letters = array();
    $letters[0] = "A";
    $letters[1] = "Ą";
    $letters[2] = "B";
    $letters[3] = "C";
    $letters[4] = "Ć";
    $letters[5] = "D";
    $letters[6] = "E";
    $letters[7] = "Ę";
    $letters[8] = "F";
    $letters[9] = "G";
    $letters[10] = "H";
    $letters[11] = "I";
    $letters[12] = "J";
    $letters[13] = "K";
    $letters[14] = "L";
    $letters[15] = "Ł";
    $letters[16] = "M";
    $letters[17] = "N";
    $letters[18] = "Ń";
    $letters[19] = "O";
    $letters[20] = "Ó";
    $letters[21] = "P";
    $letters[22] = "Q";
    $letters[23] = "R";
    $letters[24] = "S";
    $letters[25] = "Ś";
    $letters[26] = "T";
    $letters[27] = "U";
    $letters[28] = "V";
    $letters[29] = "W";
    $letters[30] = "X";
    $letters[31] = "Y";
    $letters[32] = "Z";
    $letters[33] = "Ż";
    $letters[34] = "Ź";

//    include_once(realpath(dirname(__FILE__) . "dbconnect.php"));
    include($_SERVER['DOCUMENT_ROOT'] . '/dbconnect_local.php');

    $num = $_POST['num'];

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

        $query = sprintf("SELECT * FROM passwords WHERE id=%d", $id);
        if($result = @mysqli_query($link, $query)) {
            $info = $info ."\nSuccess when performing query!";
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            $password = $row['password'];

        } else {
            $info = $info ."\nFailed when performing query!";
        }

//        check if letters exist in the
        $exists = 0;
        $word = strtoupper($password);
        $newhidden = $hidden;
        for($i = 0; $i < strlen($word); $i++) {
            if(strcmp(substr($word, $i, 1), $letters[$num]) === 0) {
                $exists = 1;
                $newhidden[$i] = $letters[$num];
            }
        }

//        update actual password in database
        if(strcmp($newhidden, $hidden) !== 0) {
            $query = sprintf("UPDATE choosen_passwords SET actual_password='%s' WHERE choosen_passwords.id = 1",
                             mysqli_real_escape_string($link, $newhidden) );

            if(@mysqli_query($link, $query)) {
                $info = $info ."\nSuccessfully updated" . $newhidden;
            } else {
                $info = $info ."\nFailed to update. ERROR: ". mysqli_connect_errno() ."\n". mysqli_connect_error();
            }
        } else {
            $info = $info ."\nNo password updating (no letter matches!)";
        }

        mysqli_close($link);
    }

    echo json_encode(array("exists"   => $exists,
                           "info"     => $info) );

//    echo json_encode(array("exists"   => $exists,
//                           "password" => $newhidden,
//                           "info"     => $info) );

//    echo json_encode(array("letter"   => $letters[$num],
//                           "id"       => $id,
//                           "password" => $newhidden,
//                           "info"     => $info) );

?>

