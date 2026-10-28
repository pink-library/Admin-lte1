<?php
//koneksi
require_once __DIR__ . "/koneksi.php";

// data dari form editnilaimhsw.php
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$nilai_akhir = $_POST['nilai_akhir'];

if ($nilai_akhir >= 80) {
  $nilai_huruf = "A";
  $predikat = "Sangat Memuaskan";
} elseif ($nilai_akhir >= 70) {
  $nilai_huruf = "B";
  $predikat = "Memuaskan";
} elseif ($nilai_akhir >= 60) {
  $nilai_huruf = "C";
  $predikat = "Cukup";
} elseif ($nilai_akhir >= 50) {
  $nilai_huruf = "D";
  $predikat = "Kurang";
} else {
  $nilai_huruf = "E";
  $predikat = "Sangat Kurang";
}

// SQL to update a record
$sql = "UPDATE nilaimhsw
        SET nama_lengkap='$nama',
            nilai_akhir='$nilai_akhir',
            nilai_huruf='$nilai_huruf',
            predikat='$predikat'
        WHERE nim='$nim'";

if (mysqli_query($conn, $sql)) {
  header('Location: adminlte/dist/adminlte.php');  
  exit;
} else {
  echo "Error updating record: " . mysqli_error($conn);
}
?>