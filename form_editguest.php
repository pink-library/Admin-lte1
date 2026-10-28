<?php
//baca data 1 record yang dipilih untuk diedt
// yang diambil dari tampilan bacadata.php pada posisi tombol edit diklik

require_once __DIR__ . "/koneksi.php";

$sql = "SELECT * FROM MyGuest WHERE id = $_GET[id]";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $fname = $row['firstname'];
    $lname = $row['lastname'];
    $email = $row['email'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Edit Guest</title>
</head>

<body>
    <h2>Form Edit MyGuest</h2>
    <form action="updatedata.php?id=<?php echo $id; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="fname">First name:</label><br>
        <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" required><br><br>

        <label for="lname">Last name:</label><br>
        <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br><br>
        <input type="reset" value="Cancel">
        <input type="submit" value="Simpan">
    </form>
</body>

</html>