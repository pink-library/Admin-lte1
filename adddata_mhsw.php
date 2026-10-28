<?php
require "koneksi.php";

$nim = $_POST['nim'];
$nama_lengkap = $_POST['NamaL'];
$nilai_akhir = $_POST['NilaiA'];

//olah nilai huruf dan predikat berdasarkan nilai akhir
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

$sql = "INSERT INTO Nilaimhsw (nim, nama_lengkap, nilai_akhir, nilai_huruf, predikat)
VALUES ('$nim', '$nama_lengkap', '$nilai_akhir', '$nilai_huruf', '$predikat')";

if (mysqli_query($conn, $sql)) {
  header('Location: adminlte/dist/adminlte.php');  
  exit;
} else {
  echo "Error updating record: " . mysqli_error($conn);
}
?>