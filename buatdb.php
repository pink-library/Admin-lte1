<?php
require "koneksi.php";

// create database
$sql = "CREATE DATABASE db5d";
if (mysqli_query($conn, $sql)) {
    echo "Database <strong>db5d</strong> created successfully";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

// close connection
mysqli_close($conn);
?>