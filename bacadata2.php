<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db5d";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query SELECT mengambil data mahasiswa termasuk reg_date
$sql = "SELECT nim, nama_lengkap, nilai_akhir, nilai_huruf, predikat, reg_date FROM Nilaimhsw";
$result = $conn->query($sql);

// Tombol Tambah Data
echo "<button onclick=\"window.location.href='form_nilai_mhsw.html'\">Tambah Data</button><br><br>";

// Judul Halaman di Tengah
echo "<h3>DATA MAHASISWA</h3>";

// Process the result set
if ($result->num_rows > 0) {
  // Tabel dengan border 2 lapisan
  echo "<table border='1' cellpadding='5'>";
  echo "<tr>
          <th>nim</th>
          <th>Name</th>
          <th>Nilai Akhir</th>
          <th>Nilai Huruf</th>
          <th>Predikat</th>
          <th>Tanggal Entri</th>
          <th>Aksi</th>
        </tr>";

  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["nim"] . "</td>";
    echo "<td>" . $row["nama_lengkap"] . "</td>";
    echo "<td>" . $row["nilai_akhir"] . "</td>";
    echo "<td>" . $row["nilai_huruf"] . "</td>";
    echo "<td>" . $row["predikat"] . "</td>";
    echo "<td>" . $row["reg_date"] . "</td>";
    // Kolom Aksi dengan Link Edit dan Hapus
    echo "<td>
            <a href='edit_mhsw.php?nim=" . $row["nim"] . "'>Edit</a> | 
            <a href='hapus_mhsw.php?nim=" . $row["nim"] . "' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
          </td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "<p>0 results - Data not found</p>";
}

$conn->close();
?>