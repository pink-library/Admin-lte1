<?php
if(isset($_POST["submit"])) {
    // $namafile= $_FILES["foto"]["name"];
    // $tipefile= $_FILES["foto"]["type"];
    $ukuranfile= $_FILES["foto"]["size"];
    $filesementara= $_FILES["foto"]["tmp_name"];
    $statusError= $_FILES["foto"]["error"];

    // //TAMPILKAN
    // echo "Nama File: ".$namafile."<br>";
    // echo "Tipe File: ".$tipefile."<br>";    
    // echo "Ukuran File: ".$ukuranfile."<br>";
    echo "File Sementara: ".$filesementara."<br>";
    // echo "Status Error: ".$statusError."<br>";

    //CHEKPOINT 1
    //PERIKSA IMAGE ATAU BUKAN
    $cek = getimagesize($filesementara);
    if ($cek !== false) {
        echo "file adalah gambar ASLI - " . $cek["mime"] . ".";
        $uploadOK = 1;

    }else {
        echo "file bukan gambar!";
        $uploadOK = 0;
    }

    //CHEKPOINT 2
    //PERIKSA ADA NAMA FILE YANG SAMA DI DIREKTORI
    $target_dir = "galeri/";
    $file_path = $target_dir . basename($_FILES["foto"]["name"]);

    $imageFileType = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    if (file_exists($file_path)) {
        echo "maaf, " . basename($_FILES['foto']['name']) . " sudah ada";
        $uploadOK = 0;
    }

    //CHEKPOINT 3 (Pembatas ukuran file)
    //MEMERIKSA UKRAN FILE YANG DI UNGGAH TIDAK MELEBIHI UKURAN YANG DI BATASI
    if ($ukuranfile > 1000000) {
        echo "maaf, ukuran file melebihi 1 MB";
        $uploadOK = 0;
    }

    //CHEKPOINT 4 
    //MEMEMERIKSA EKSTENSI FILE TERMASUK DALAM 'WHITELIST' YANG DI IJINKAN
    if (
        $imageFileType != "jpg" && $imageFileType != "png" &&
        $imageFileType !="jpeg" && $imageFileType !="gif"
    ) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diijinkan.";
        $uploadOK = 0;
    }

    //jika status upload OK
    if ($uploadOK == 1) {
        if (move_uploaded_file($filesementara, $file_path)) {
            echo "file ". (basename($_FILES["foto"]["name"])) . " berhasil diunggah.";
        // salin alamat file ke dalam tabe
        require_once __DIR__ . "/koneksi.php";
    $sql = "CREATE TABLE galeri (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                namafile VARCHAR(100) NOT NULL,
                tgl_unggah TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

            
      $sql = "INSERT INTO galeri (namafile)
                VALUE ('$file_path')";

                if (mysqli_query($conn, $sql)) {
                    echo "New record inserted succesfully";
                } else {
                    echo "Error inserted table: " . mysqli_error($conn);
                }

        mysqli_close($conn);
        }
    }
}
?>

