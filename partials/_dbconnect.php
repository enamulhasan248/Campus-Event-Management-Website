<?php
        $servername = 'localhost';
        $username ='root';
        $password = '';
        $database = "users_cse479";
        $conn = mysqli_connect($servername,$username,$password,$database);

        if(!$conn){
            echo"Not conneted to database";
        }

?>