<?php
include 'DbKoneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nip = $_POST['nip'];
    $prodi = $_POST['prodi'];
    $password = $_POST['password'];

    // Cek NPM apakah sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM dosen WHERE nip = '$nip'");
    if (mysqli_num_rows($cek) > 0) {
        $error = 'NIP sudah terdaftar!';
    } else {
        $query = "INSERT INTO dosen (username, nip, prodi, password) 
                  VALUES ('$username', '$nip', '$prodi', '$password')";

        
        if (mysqli_query($conn, $query)) {
            $success = 'Pendaftaran berhasil!';
            // Redirect ke login kalau mau otomatis
            header("Location: LoginUser.php"); exit;
        } else {
            $error = 'Gagal mendaftar: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KelasKom - Daftar Mahasiswa</title>
  <link rel="stylesheet" href="../CSS/Login.css">
</head>
<body>
  <div class="form-login">
    <div class="login-card">
      <form class="login-form" method="post" action="">
        <h2>Sign Up</h2>

        <?php if ($error): ?>
          <p style="color: red;"><?= $error ?></p>
        <?php elseif ($success): ?>
          <p style="color: green;"><?= $success ?></p>
        <?php endif; ?>

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan Nama Lengkap Anda" required>

        <label>NIP</label>
        <input type="text" name="nip" placeholder="Masukkan NIP" required>

        <label>Program Studi</label>
        <input type="text" name="prodi" placeholder="Masukkan Prodi Anda" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Buat Kata Sandi" required>

        <button type="submit" class="btn-lanjut">Daftar</button>
        <a href="SignUp.php" class="link">Daftar sebagai Mahasiwa</a>
        <a href="LoginUser.php" class="link">Kembali ke Login</a>


      </form>
    </div>
  </div>    
</body>
</html>
