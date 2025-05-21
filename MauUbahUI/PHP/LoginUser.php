<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KelasKom - Login Mahasiswa</title>
  <link rel="stylesheet" href="../CSS/Login.css">
</head>
<body>
  <div class="form-login">
    <div class="login-card">
      <form class="login-form">
        <h2>Login</h2>
        <label>Username / NPM</label>
        <input type="username" placeholder="Masukkan Username atau NPM" required>

        <label>Kata Sandi</label>
        <input type="password" placeholder="Masukkan Sandi Anda" required>

        <a href="../HTML/PilihHari.html" class="btn-lanjut">Masuk</a>

        <a href="../PHP/SignUp.php" class="link">Belum punya akun? Daftar</a>
        <a href="../PHP/LoginAdmin.php" class="link">Masuk sebagai Admin</a>
      </form>
    </div>
  </div>    
</body>
</html>