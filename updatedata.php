<?php
//koneksi
require_once __DIR__ . "/koneksi.php";

// data dari form editguest.php
$id = $_POST['id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];

// SQL to update a record
$sql = "UPDATE MyGuest
        SET firstname='$fname',
            lastname='$lname',
            email='$email'
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
  header('Location: bacadata.php'); 
  exit; 
} else {
  echo "Error updating record: " . mysqli_error($conn);
}
?>