<?php
include 'DbKelasKom.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $prodi = $_POST['prodi'];
    $password = $_POST['password'];

    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $username);
    $prodi = mysqli_real_escape_string($conn, $prodi);

    // Validate inputs
    if (empty($username) || empty($prodi) || empty($password)) {
        $error = "Semua kolom harus diisi.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Check if username already exists
        $query_check = mysqli_query($conn, "SELECT * FROM dosmhs WHERE username = '$username'");
        if (mysqli_num_rows($query_check) > 0) {
            $error = "Username sudah digunakan.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into dosmhs table
            $query_insert = "INSERT INTO dosmhs (username, prodi, password) VALUES ('$username', '$prodi', '$hashed_password')";
            if (mysqli_query($conn, $query_insert)) {
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Pendaftaran gagal: " . mysqli_error($conn);
            }
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

        <label>Program Studi</label>
        <input type="text" name="prodi" placeholder="Masukkan Prodi Anda" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Buat Kata Sandi" required>

        <button type="submit" class="btn-lanjut">Daftar</button>
        <a href="LoginUser.php" class="link">Kembali ke Login</a>


      </form>
    </div>
  </div>    
</body>
</html>
