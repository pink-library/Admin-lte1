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

//tombol untuk tambah data guest
echo "<a href='form_guest.html'><button>Add My Guest</button></a>";

// Query SELECT untuk tabel MyGuest
$sql = "SELECT id, firstname, lastname, email, reg_date FROM MyGuest";
// Execute the SQL query
$result = $conn->query($sql);

// Process the result set
if ($result->num_rows > 0) {
  // Output tabel dengan border 2 lapisan
  echo "<table border='1' style='border-collapse:collapse'>";
  echo "<caption><strong>My Guest List</strong></caption>";
  echo "<tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Tgl Entry</th>
          <th>Actions</th>
        </tr>";

  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . $row["firstname"]. " ".$row["lastname"]."</td>";
    echo "<td>" . $row["email"] . "</td>";
    echo "<td>" . $row["reg_date"] . "</td>";
    echo "<td>" . 
    "<a href='form_editguest.php?id=" . $row["id"] . "'><button>Edit</button></a> |
    <a href='hapusguest.php?id=" . $row["id"] . "' onclick='return confirm(\"Apakah anda yakin ingin menghapus data ini?\")'>
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