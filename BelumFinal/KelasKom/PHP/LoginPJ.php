<?php
session_start();
unset($_SESSION['username']); // buang jika sebelumnya login sebagai user
include 'DbKelasKom.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npm = $_POST['npm'];
    $password = $_POST['password'];

    // Sanitize input
    $npm = mysqli_real_escape_string($conn, $npm);
    $password = mysqli_real_escape_string($conn, $password);

    // Cek admin (cocok langsung tanpa hash)
    $query = mysqli_query($conn, "SELECT * FROM pj_kelas WHERE npm = '$npm' AND password = '$password'");
    if ($data = mysqli_fetch_assoc($query)) {
        $_SESSION['npm'] = $data['npm']; // Simpan username ke session
        header("Location: PilihHari.php");
        exit;
    } else {
        $error = "Npm atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KelasKom - Login Pj Kelas</title>
    <link rel="stylesheet" href="../CSS/Login.css">
</head>
<body>
    <div class="form-login">
        <div class="login-card">
            <form class="login-form" method="post" action="">
                <h2>Login PJ Kelas</h2>

                <?php if ($error): ?>
                    <p style="color: red;"><?= $error ?></p>
                <?php endif; ?>

                <label>NPM</label>
                <input type="text" name="npm" placeholder="Masukkan NPM Anda" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan Password Anda" required>

                <button type="submit" class="btn-lanjut">Masuk</button>

                <a href="../PHP/LoginUser.php" class="link">Keluar dari PJ Kelas</a>
            </form>
        </div>
    </div>
</body>
</html>
