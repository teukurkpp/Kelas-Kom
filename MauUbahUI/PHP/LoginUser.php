<?php
include 'DbKoneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = $_POST['username'];
    $passwordInput = $_POST['password'];

    // Cek di tabel mahasiswa
    $query_mhs = mysqli_query($conn, "SELECT * FROM mahasiswa 
        WHERE username = '$usernameInput' OR npm = '$usernameInput'");

    if ($data_mhs = mysqli_fetch_assoc($query_mhs)) {
        if ($passwordInput === $data_mhs['password']) {
            // Login Mahasiswa berhasil
            header("Location: PilihHariMahasiswa.php");
            exit;
        }
    }

    // Cek di tabel dosen
    $query_dosen = mysqli_query($conn, "SELECT * FROM dosen 
        WHERE username = '$usernameInput' OR nip = '$usernameInput'");

    if ($data_dosen = mysqli_fetch_assoc($query_dosen)) {
        if ($passwordInput === $data_dosen['password']) {
            // Login Dosen berhasil
            header("Location: PilihHari.php");
            exit;
        }
    }

    // Jika tidak ditemukan
    $error = "Username/NPM/NIP atau password salah.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KelasKom - Login</title>
  <link rel="stylesheet" href="../CSS/Login.css">
</head>
<body>
  <div class="form-login">
    <div class="login-card">
      <form class="login-form" method="post" action="">
        <h2>Login</h2>

        <?php if ($error): ?>
          <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <label>Username / NPM / NIP</label>
        <input type="text" name="username" placeholder="Masukkan Username atau NPM/NIP" required>

        <label>Kata Sandi</label>
        <input type="password" name="password" placeholder="Masukkan Sandi Anda" required>

        <button type="submit" class="btn-lanjut">Masuk</button>

        <a href="SignupUser.php" class="link">Belum punya akun? Daftar Mahasiswa</a>
        <a href="SignupDosen.php" class="link">Daftar sebagai Dosen</a>
        <a href="LoginAdmin.php" class="link">Masuk sebagai Admin</a>
      </form>
    </div>
  </div>    
</body>
</html>
