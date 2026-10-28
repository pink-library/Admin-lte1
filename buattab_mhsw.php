<?php
require "koneksi.php";

// sql to create table
$sql = "CREATE TABLE Nilaimhsw (
nim CHAR(10) PRIMARY KEY,
nama_lengkap VARCHAR(100) NOT NULL,
nilai_akhir DECIMAL(3,2) NOT NULL,
nilai_huruf CHAR(1) NOT NULL,
predikat VARCHAR(20) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Table <strong>Nilaimhsw</strong> created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>