<?php
//    include_once(realpath(dirname(__FILE__) . '/dbconnect.php'));
    require($_SERVER['DOCUMENT_ROOT'] . '/dbconnect_local.php');

     $link = @mysqli_connect($host, $user, $dbpassword, $database);
     $info = "";

     if(!$link) {
         $info = "(draw) Debugging errno: " . mysqli_connect_errno();
     } else {
         $info = "Connection success!";
 //        encoding for strings coming from client (SET NAMES)
 //        encoding fro string coming from database (SET CHARSET)
         @mysqli_query($link, "SET CHARSET utf8");
         @mysqli_query($link, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
 //        draw random password
         $rand_id = rand(1, 3);

         $query = sprintf("SELECT * FROM passwords WHERE id=%d", $rand_id);
         if($result = @mysqli_query($link, $query)) {
             $info = $info ."\nSuccess when performing query!";
             $row = mysqli_fetch_assoc($result);
             mysqli_free_result($result);

             $password = $row['password'];
             $id = (int)$row['id'];

 //            alternate password to its hidden form
             $hidden = "";
             for($i = 0; $i < strlen($password); $i++) {
                 if(substr($password, $i, 1) == ' ') $hidden = $hidden .' ';
                 else $hidden = $hidden .'-';
             }

             $query = sprintf("UPDATE choosen_passwords SET password_num=%d, actual_password='%s' WHERE choosen_passwords.id = 1",
                              mysqli_real_escape_string($link, $rand_id),
                              mysqli_real_escape_string($link, $hidden) );

             if(@mysqli_query($link, $query)) {
                 $info = $info ."\nSuccessfully updated";
             } else {
                 $info = $info ."\nFailed to update. ERROR: ". mysqli_connect_errno() ."\n". mysqli_connect_error();
             }

         } else {
             $info = $info ."\nFailed when performing query!";
         }

         mysqli_close($link);
     }
     $row['info'] = $info;


    echo json_encode($row);
?>
