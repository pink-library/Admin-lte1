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
echo "<a href='form_galeri.html'><button>Tambah Foto</button></a>";

// Query SELECT untuk tabel galeri
$sql = "SELECT id, namafile,  tgl_unggah FROM galeri";  
// Execute the SQL query
$result = $conn->query($sql);

// Process the result set
if ($result->num_rows > 0) {
  // Output tabel dengan border 2 lapisan
  echo "<table border='1' style='border-collapse:collapse'>";
  echo "<caption><strong>My Photo Gallery</strong></caption>";  
  echo "<tr>
          <th>No</th>
          <th>Nama File</th>
          <th>Tgl Unggah</th>
          <th>Pratinjau</th>
        </tr>";  

  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>"; 
    echo "<td>" . $row["namafile"] . "</td>";  
    echo "<td>" . $row["tgl_unggah"] . "</td>";  
    echo "<td><img src='" . $row["namafile"] . "' width='100' height='auto'></td>";  
    echo "</tr>";
  }
  echo "</table>";

} else {
  echo "0 results - Data not found";
}
$conn->close();
?>