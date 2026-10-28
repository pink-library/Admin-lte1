<?php
//koneksi ke db\
require_once __DIR__ .  "/koneksi.php";

// SQL to delete a record
$sql = "DELETE FROM MyGuest WHERE id = $_GET[id]";

if (mysqli_query($conn, $sql)) {
  echo "<script>alert('Record deleted successfully');<script>";
} else {

//jika pengapusan gagal
  echo "Error deleting record: " . mysqli_error($conn);
}

//kembali ke tampilan data guest (file bacadata.php)
header("Location: bacadata.php");
//mysqli_close($conn);
?>

