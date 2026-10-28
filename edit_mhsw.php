<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db5d";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Mengambil NIM dari URL
$nim = $_GET['nim'];

// Ambil data mahasiswa berdasarkan NIM
$sql = "SELECT * FROM Nilaimhsw WHERE nim='$nim'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
?>

<h2>Form Edit Nilai Mahasiswa</h2>

<form action="update_mhsw.php" method="post">
  <!-- NIM dibuat readonly agar tidak bisa diubah karena merupakan Primary Key -->
  NIM:<br>
  <input type="text" name="nim" value="<?php echo $data['nim']; ?>" readonly><br><br>

  Nama Lengkap:<br>
  <input type="text" name="nama_lengkap" value="<?php echo $data['nama_lengkap']; ?>"><br><br>

  Nilai Akhir:<br>
  <input type="text" name="nilai_akhir" value="<?php echo $data['nilai_akhir']; ?>"><br><br>

  Nilai Huruf:<br>
  <input type="text" name="nilai_huruf" value="<?php echo $data['nilai_huruf']; ?>"><br><br>

  Predikat:<br>
  <input type="text" name="predikat" value="<?php echo $data['predikat']; ?>"><br><br>

  <input type="submit" value="Simpan Perubahan">
</form>

<?php $conn->close(); ?>