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

//tombol untuk tambah data mahasiswa
echo "<a href='form_nilaimhsw.html'><button>Add Nilai Mahasiswa</button></a>"; 

// Query SELECT untuk tabel nilaimhsw
$sql = "SELECT nim, nama_lengkap, nilai_akhir, nilai_huruf, predikat, reg_date FROM nilaimhsw";  
// Execute the SQL query
$result = $conn->query($sql);

// Process the result set
if ($result->num_rows > 0) {
  // Output tabel dengan border 2 lapisan
  echo "<table border='1' style='border-collapse:collapse'>";
  echo "<caption><strong>List Nilai Mahasiswa</strong></caption>";  
  echo "<tr>
          <th>NIM</th>
          <th>Nama Lengkap</th>
          <th>Nilai Akhir</th>
          <th>Nilai Huruf</th>
          <th>Predikat</th>
          <th>Tgl Entry</th>
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
    echo "<td>" . 
    "<a href='form_editnilaimhsw.php?nim=" . $row["nim"] . "'><button>Edit</button></a> |
    <a href='hapusnilaimhsw.php?nim=" . $row["nim"] . "' onclick='return confirm(\"Apakah anda yakin ingin menghapus data ini?\")'>
    <button>Delete</button></a>" . 
    "</td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "0 results - Data not found";
}

$conn->close();
?>