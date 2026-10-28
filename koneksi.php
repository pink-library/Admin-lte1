<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db5d";

// create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<strong>Connected successfully</strong><br>";
?>