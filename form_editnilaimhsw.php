<?php
//baca data 1 record yang dipilih untuk diedt
// yang diambil dari tampilan bacanilaimhsw.php pada posisi tombol edit diklik

require_once __DIR__ . "/koneksi.php";

$sql = "SELECT * FROM nilaimhsw WHERE nim = '$_GET[nim]'";  
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
    $nim = $row['nim']; 
    $nama = $row['nama_lengkap'];  
    $nilai_akhir = $row['nilai_akhir'];  
    } 
}  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Edit Nilai Mahasiswa</title>  
</head>

<body>
    <h2>Form Edit Nilai Mahasiswa</h2>  
    <form action="updatenilaimhsw.php?nim=<?php echo $nim; ?>" method="post"> 
        <input type="hidden" name="nim" value="<?php echo $nim; ?>"> 
        <label for="nama">Nama Lengkap:</label><br>  
        <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>" required><br><br> 
        <label for="nilai_akhir">Nilai Akhir:</label><br> 
        <input type="number" id="nilai_akhir" name="nilai_akhir" step="0.01" min="0" max="100" value="<?php echo $nilai_akhir; ?>" required><br><br>  
        <input type="reset" value="Cancel">
        <input type="submit" value="Simpan">
    </form>
</body>

</html>