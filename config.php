<?php 
    // $dbservername = "database";
    // $dbusername = "docker";
    // $dbpassword = "docker";
    // $dbname = "docker";

    $dbcon = mysqli_connect('database', 'docker', 'docker', 'docker');

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL : " . mysqli_connect_error();
    }

    // $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    //     // Check connection
    //     if ($conn->connect_error) {
    //         die("Connection failed: " . $conn->connect_error);
    //     }

?>