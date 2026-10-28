<?php
//inisialisasi variabel set bernilai kosong
$errJumlah = "";
$jumlah = 0;
$tampil_form = false;
$semua_valid = false;
$errNama = $errNilai = $nama_mhsw = $nilai_akhir = array();
$nilai_huruf = $predikat = array();

//menerima data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // tahap 1: tombol Buat Form diklik
    if (isset($_POST['buat_form'])) {
        if (!is_numeric($_POST['jumlah']) || $_POST['jumlah'] < 1) {
            $errJumlah = "Jumlah harus berupa angka minimal 1!";
        } else {
            $jumlah = (int)$_POST['jumlah'];
            $tampil_form = true;
        }
    }

    // tahap 2: tombol Simpan dan Tampilkan diklik
    if (isset($_POST['simpan'])) {
        $jumlah = (int)$_POST['jumlah'];
        $tampil_form = true;
        $semua_valid = true;

        for ($i = 0; $i < $jumlah; $i++) {
            $errNama[$i] = $errNilai[$i] = $nama_mhsw[$i] = $nilai_akhir[$i] = "";
            $nilai_huruf[$i] = $predikat[$i] = "";
            $val_nama = $val_nilai = false;

            // cek apakah field nama kosong atau berisi
            if (trim($_POST["nama_mhsw"][$i]) === "") {
                //jika field nama kosong, set pesan error
                $errNama[$i] = "Nama Mahasiswa tidak boleh kosong!";
            } else {
                //jika field nama berisi, lanjut Sanitasi/ Bersihkan
                $nama_mhsw[$i] = sanitasi($_POST["nama_mhsw"][$i]);
                // lanjut proses validasi format field nama
                if (preg_match("/^[a-zA-Z-' ]*$/", $nama_mhsw[$i])) {
                    //jika format benar
                    $val_nama = true;
                } else {
                    $errNama[$i] = "Hanya huruf dan spasi yang diperbolehkan!";
                }
            }

            // cek apakah field nilai kosong atau berisi
            if (trim($_POST["nilai_akhir"][$i]) === "") {
                //jika field nilai kosong, set pesan error
                $errNilai[$i] = "Nilai Akhir tidak boleh kosong!";
            } else {
                //jika field nilai berisi, lanjut Sanitasi/ Bersihkan
                $nilai_akhir[$i] = sanitasi($_POST["nilai_akhir"][$i]);
                // lanjut proses validasi format field nilai
                if (preg_match("/^[0-9.]*$/", $nilai_akhir[$i])) {
                    //jika format benar
                    //cek range nilai antara 0 - 100
                    if ($nilai_akhir[$i] < 0 || $nilai_akhir[$i] > 100) {
                        $errNilai[$i] = "Nilai harus antara 0 - 100!";
                    } else {
                        //panggil konversi ke huruf
                        $nilai_huruf[$i] = konversi_nilai($nilai_akhir[$i]);
                        $val_nilai = true;
                        $predikat[$i] = ($nilai_huruf[$i] == "E") ? "Tidak Lulus" : "Lulus";
                    }
                } else {
                    $errNilai[$i] = "Hanya angka yang diperbolehkan!";
                }
            }

            if (!($val_nama && $val_nilai)) {
                $semua_valid = false;
            }
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
        <h3>Form Input Nilai Mahasiswa</h3>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="jumlah">Jumlah Mahasiswa:</label>
            <input type="text" name="jumlah" id="jumlah" size="5" value="<?php echo isset($_POST['jumlah']) ? htmlspecialchars($_POST['jumlah']) : ''; ?>">
            <input type="submit" name="buat_form" value="Buat Form">
            <span class="error"><?php echo $errJumlah; ?></span>
        </form>

        <?php if ($tampil_form) { ?>
        <h4>Masukkan Data <?php echo $jumlah; ?> Mahasiswa</h4>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="jumlah" value="<?php echo $jumlah; ?>">
            <?php for ($i = 0; $i < $jumlah; $i++) { ?>
            <fieldset>
                <legend>Data ke-<?php echo $i + 1; ?></legend>
                <label for="nama">Nama Mahasiswa</label><span class=error>*</span>
                <input type="text" name="nama_mhsw[]" value="<?php echo isset($_POST['nama_mhsw'][$i]) ? htmlspecialchars($_POST['nama_mhsw'][$i]) : ''; ?>"><span class="error"><?php echo isset($errNama[$i]) ? $errNama[$i] : ''; ?></span>
                <label for="nilai_akhir">Nilai Akhir:</label><span class=error>*</span>
                <input type="text" name="nilai_akhir[]" value="<?php echo isset($_POST['nilai_akhir'][$i]) ? htmlspecialchars($_POST['nilai_akhir'][$i]) : ''; ?>"><span class="error"><?php echo isset($errNilai[$i]) ? $errNilai[$i] : ''; ?></span>
            </fieldset>
            <?php } ?>
            <span class=error>* wajib diisi</span><br><br>
            <input type="submit" name="simpan" value="Simpan dan Tampilkan">
        </form>
        <?php } ?>

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
            } elseif ($nilai > 34.50 && $nilai < 50) {
                return "D";
            } elseif ($nilai >= 50 && $nilai < 65) {
                return "C";
            } elseif ($nilai >= 65 && $nilai < 80) {
                return "B";
            } elseif ($nilai >= 80 && $nilai <= 100) {
                return "A";
            } else {
                return "Invalid value!";
            }
        }

        ?>
        <?php
        //tampilkan dalam bentuk tabel
        if ($semua_valid) {
            echo "<h2>Data Nilai Mahasiswa dalam Tabel</h2>";
        ?>
        <table>
        <tr>
            <th>Nama Mahasiswa</th>
            <th>Nilai Akhir</th>
            <th>Grade</th>
        </tr>
        <?php for ($i = 0; $i < $jumlah; $i++) { ?>
        <tr>
            <td><?php echo $nama_mhsw[$i]; ?></td>
            <td><?php echo $nilai_akhir[$i]; ?></td>
            <td><?php echo $nilai_huruf[$i]; ?></td>
        </tr>
        <?php } ?>
        </table>
        <?php
            // tampilkan data tersebut
            for ($i = 0; $i < $jumlah; $i++) {
                echo "<h2>Data Nilai Mahasiswa ke-" . ($i + 1) . "</h2>";
                echo "Nama Mahasiswa: " . $nama_mhsw[$i] . "<br>";
                echo "Nilai Akhir: " . $nilai_akhir[$i] . "<br>";
                echo "Nilai Huruf: " . $nilai_huruf[$i] . "<br>";
                echo "Predikat: " . $predikat[$i] . "<br>";
            }
        }
        ?>
    </body>
</html>