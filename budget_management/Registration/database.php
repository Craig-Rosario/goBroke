<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "GoBroke";
    $port = 4306;

    $conn = new mysqli($server, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
