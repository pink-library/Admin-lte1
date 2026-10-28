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

// Query SELECT untuk tabel nilaimhsw
$sql = "SELECT nim, nama_lengkap, nilai_akhir, nilai_huruf, predikat, reg_date FROM nilaimhsw";
// Execute the SQL query
$result = $conn->query($sql);
?>
<!DOCTYPE html>                                                     <!-- ← TAMBAH -->
<html lang="id">                                                    <!-- ← TAMBAH -->
<head>                                                              <!-- ← TAMBAH -->
  <meta charset="UTF-8">                                            <!-- ← TAMBAH -->
  <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- ← TAMBAH -->
  <title>List Nilai Mahasiswa</title>                               <!-- ← TAMBAH -->
  <link rel="stylesheet" href="adminlte/dist/css/adminlte.min.css"> <!-- ← TAMBAH (CSS AdminLTE) -->
</head>                                                             <!-- ← TAMBAH -->
<body class="bg-body-secondary">                                    <!-- ← TAMBAH -->
  <div class="container py-4">                                      <!-- ← TAMBAH -->
    <div class="card card-primary card-outline">                    <!-- ← TAMBAH -->
      <div class="card-header d-flex justify-content-between align-items-center">  <!-- ← TAMBAH -->
        <h3 class="card-title mb-0">List Nilai Mahasiswa</h3>       <!-- ← TAMBAH (pengganti <caption>) -->
        <!-- tombol untuk tambah data mahasiswa -->
        <a href="form_nilaimhsw.html" class="btn btn-primary btn-sm">+ Add Nilai Mahasiswa</a>  <!-- ← UBAH (dulu echo <button>) -->
      </div>                                                        <!-- ← TAMBAH -->
      <div class="card-body table-responsive p-0">                  <!-- ← TAMBAH -->
<?php
// Process the result set
if ($result->num_rows > 0) {
  echo "<table class='table table-striped table-hover mb-0'>";     // ← UBAH (dulu border='1' + style)
  echo "<thead>";                                                   // ← TAMBAH
  echo "<tr>
          <th>NIM</th>
          <th>Nama Lengkap</th>
          <th>Nilai Akhir</th>
          <th>Nilai Huruf</th>
          <th>Predikat</th>
          <th>Tgl Entry</th>
          <th>Aksi</th>
        </tr>";
  echo "</thead>";                                                  // ← TAMBAH
  echo "<tbody>";                                                   // ← TAMBAH

  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["nim"] . "</td>";
    echo "<td>" . $row["nama_lengkap"] . "</td>";
    echo "<td>" . $row["nilai_akhir"] . "</td>";
    echo "<td>" . $row["nilai_huruf"] . "</td>";
    echo "<td>" . $row["predikat"] . "</td>";
    echo "<td>" . $row["reg_date"] . "</td>";
    echo "<td>" .
    "<a href='form_editnilaimhsw.php?nim=" . $row["nim"] . "' class='btn btn-warning btn-sm'>Edit</a> " .   // ← UBAH (hapus <button> & tanda |, pakai class)
    "<a href='hapusnilaimhsw.php?nim=" . $row["nim"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah anda yakin ingin menghapus data ini?\")'>Delete</a>" .   // ← UBAH
    "</td>";
    echo "</tr>";
  }
  echo "</tbody>";                                                  // ← TAMBAH
  echo "</table>";
} else {
  echo "<div class='alert alert-warning m-3'>0 results - Data not found</div>";   // ← UBAH (dulu teks biasa)
}

$conn->close();
?>
      </div>                                                        <!-- ← TAMBAH -->
    </div>                                                          <!-- ← TAMBAH -->
  </div>                                                            <!-- ← TAMBAH -->
</body>                                                             <!-- ← TAMBAH -->
</html>                                                             <!-- ← TAMBAH -->