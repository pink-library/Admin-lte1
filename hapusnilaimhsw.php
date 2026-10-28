<?php
//koneksi ke db\
require_once __DIR__ .  "/koneksi.php";

// SQL to delete a record
$sql = "DELETE FROM nilaimhsw WHERE nim = '$_GET[nim]'";  

if (mysqli_query($conn, $sql)) {
  echo "<script>alert('Record deleted successfully');<script>";
} else {

//jika pengapusan gagal
  echo "Error deleting record: " . mysqli_error($conn);
}


if (mysqli_query($conn, $sql)) {
  header('Location: adminlte/dist/adminlte.php');  
  exit;
} else {
  echo "Error updating record: " . mysqli_error($conn);
}
?>