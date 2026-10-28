<?php

// baca data dari kiriman form: form_guest.html
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];

require "koneksi.php";

// sql to create table
$sql = "INSERT INTO MyGuest (firstname, lastname, email)
VALUE ('$fname', '$lname', '$email')";

if (mysqli_query($conn, $sql)) {
    echo "New record inserted succesfully";
} else {
    echo "Error inserted table: " . mysqli_error($sconn);
 }

 //kembali ke tampilan data guest (file bacadata.php)
header("Location: bacadata.php");
//mysqli_close($conn);
 ?>