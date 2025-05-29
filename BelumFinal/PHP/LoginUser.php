<?php
session_start();
include 'DbKelasKom.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);

    // Check in dosmhs table
    $query_mhs = mysqli_query($conn, "SELECT * FROM dosmhs WHERE username = '$username'");
    if ($data_mhs = mysqli_fetch_assoc($query_mhs)) {
        if (password_verify($password, $data_mhs['password'])) {
            // Login successful, store username in session
            $_SESSION['username'] = $username;
            header("Location: PilihHari.php");
            exit;
        }
    }

    // If not found
    $error = "Username atau password salah.";
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

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan Username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan Password" required>

        <button type="submit" class="btn-lanjut">Masuk</button>

        <a href="../PHP/SignupDM.php" class="link">Belum punya akun? Sign Up</a>
        <a href="../PHP/LoginPJ.php" class="link">Masuk sebagai PJ Kelas</a>
        <a href="../PHP/LoginAdmin.php" class="link">Masuk sebagai Admin</a>
      </form>
    </div>
  </div>    
</body>
</html>
