<?php
require_once __DIR__ . "/koneksi.php"; // ← BARU (pakai koneksi yang sama dengan upload.php)

$sql = "CREATE TABLE IF NOT EXISTS galeri ( /* BARU: IF NOT EXISTS agar tidak error kalau tabel sudah ada */
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            namafile VARCHAR(100) NOT NULL,
            tgl_unggah TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

if (mysqli_query($conn, $sql)) { // ← BARU (INI yang menjalankan query)
    echo "Tabel galeri berhasil dibuat.";
} else {
    echo "Error membuat tabel: " . mysqli_error($conn);
}

mysqli_close($conn);
?>