<?php
//inisialisasi variabel set bernilai kosong
$errNama = $errNilai = $nama_mhsw = $nilai_akhir = "";
$val_nama = $val_nilai = false;

//menerima data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //jika tombol kirim sudah diklik
    $nama_mhsw = $_POST['nama_mhsw'];
    $nilai_akhir = $_POST['nilai_akhir'];

    //menampilkan data yang diterima
    //echo "<h2>Data yang diterima:</h2>";
    //echo "Nama Mahasiswa: " . $nama_mhsw . "<br>";
    //echo "Nilai Akhir: " . $nilai_akhir . "<br>";

    // cek apakah field nama kosong atau berisi
    if (empty($_POST["nama_mhsw"])) {
        //jika field nama kosong, set pesan error
        $errNama = "Nama Mahasiswa tidak boleh kosong!";
    } else {
        //jika field nama berisi, lanjut Sanitasi/ Bersihkan
        $nama_mhsw = sanitasi($_POST["nama_mhsw"]);
        //$val_nama = true;
        // lanjut proses validasi format field nama
        if (preg_match("/^[a-zA-Z-' ]*$/", $nama_mhsw)) {
            //jika format benar
            $val_nama = true;
        } else {
            $errNama = "Hanya huruf dan spasi yang diperbolehkan!";
        }


    }

        // cek apakah field nilai kosong atau berisi
    if (empty($_POST["nilai_akhir"])) {
        //jika field nilai kosong, set pesan error
        $errNilai = "Nilai Akhir tidak boleh kosong!";
    } else {
        //jika field nilai berisi, lanjut Sanitasi/ Bersihkan
        $nilai_akhir = sanitasi($_POST["nilai_akhir"]);
        $val_nilai = true;
        // lanjut proses validasi format field nilai
        if (preg_match("/^[0-9.]*$/", $nilai_akhir)) {
            //jika format benar
            //cek range nilai antara 0 - 100
            if ($nilai_akhir < 0 || $nilai_akhir > 100) {
                $errNilai = "Nilai harus antara 0 - 100!";
                $val_nilai = false;
            }
            //panggil konversi ke huruf
            $nilai_huruf = konversi_nilai($nilai_akhir);
            $val_nilai = true;
            $predikat = ($nilai_huruf == "E") ? "Tidak Lulus" : "Lulus";
        } else {
            $errNilai = "Hanya angka yang diperbolehkan!";
            
        }

        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Form Nilai Baru</title>
        <style>
            .error {
                color: red;
            }
            </style>
    </head>
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nama">Nama Mahasiswa</label><span class=error>*</span><br>
            <input type="text" name="nama_mhsw" value="<?php echo isset($_POST['nama_mhsw']) ? htmlspecialchars($_POST['nama_mhsw']) : ''; ?>"><span class="error"><?php echo $errNama; ?></span><br> <!-- ← UBAH -->
            <label for="nilai_akhir">Nilai Akhir:</label><span class=error>*</span><br>
            <input type="text" name="nilai_akhir" value="<?php echo isset($_POST['nilai_akhir']) ? htmlspecialchars($_POST['nilai_akhir']) : ''; ?>"><span class="error"><?php echo $errNilai; ?></span><br> <!-- ← UBAH -->
            <span class=error>* wajib diisi</span><br><br>
            <input type="submit" value="Kirim">
        </form>
        <?php

        //fungsi sanitasi input
        function sanitasi($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        

        //fungsi konversi ke huruf

        function konversi_nilai($nilai) {
            if ($nilai >= 0 && $nilai <= 34.50) {
                return "E";
            } elseif ($nilai >= 34.51 && $nilai <= 49.99) {
                return "D";
            } elseif ($nilai >= 50 && $nilai <= 64.99) {
                return "C";
            } elseif ($nilai >= 65 && $nilai <= 79.99) {
                return "B";
            } elseif ($nilai >= 80 && $nilai <= 100) {
                return "A";
            } else {
                return "Invalid value!";
            }
        }

        ?>
        <?php
        // tampilkan data tersebut
        //if (($val_nama == true) and ($val_nilai === true)) { 
        //    echo "<h2>Data Nilai Mahasiswa</h2>";
        //    echo "Nama Mahasiswa: " . $nama_mhsw . "<br>";
        //    echo "Nilai Akhir: " . $nilai_akhir . "<br>";
        //    echo "Grade: " . konversi_nilai($nilai_akhir) . "<br>";             
        //}

        //tampilkan dalam bentuk tabel
        if (($val_nama == true) and ($val_nilai === true)) { 
            echo "<h2>Data Nilai Mahasiswa dalam Tabel</h2>";
        }
        
        ?>
        <table>
        <tr>
            <th>Nama Mahasiswa</th>
            <th>Nilai Akhir</th>
            <th>Grade</th>
        </tr>
        <tr>
            <td><?php echo $nama_mhsw; ?></td>
            <td><?php echo $nilai_akhir; ?></td>
            <td><?php echo konversi_nilai($nilai_akhir); ?></td>
        </tr>
        <tr>
            <td><?php echo $nama_mhsw; ?></td>
            <td><?php echo $nilai_akhir; ?></td>
            <td><?php echo konversi_nilai($nilai_akhir); ?></td>
        
            </tr>
        </table>
        <?php
        // tampilkan data tersebut
        if (($val_nama == true) and ($val_nilai === true)) { 
            echo "<h2>Data Nilai Mahasiswa</h2>";
            echo "Nama Mahasiswa: " . $nama_mhsw . "<br>";
            echo "Nilai Akhir: " . $nilai_akhir . "<br>";
            echo "Nilai Huruf: " . $nilai_huruf . "<br>";
            echo "Predikat: " . $predikat . "<br>";             
        }
        ?>
    </body>
</html>