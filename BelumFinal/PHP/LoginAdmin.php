<?php
session_start();
include 'DbKelasKom.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Cek admin (cocok langsung tanpa hash)
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username' AND password = '$password'");
    if ($data = mysqli_fetch_assoc($query)) {
        $_SESSION['username'] = $data['username']; // Simpan username ke session
        header("Location: PilihHari.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KelasKom - Login Admin</title>
    <link rel="stylesheet" href="../CSS/Login.css">
</head>
<body>
    <div class="form-login">
        <div class="login-card">
            <form class="login-form" method="post" action="">
                <h2>Login Admin</h2>

                <?php if ($error): ?>
                    <p style="color: red;"><?= $error ?></p>
                <?php endif; ?>

                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan Username Anda" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan Password Anda" required>

                <button type="submit" class="btn-lanjut">Masuk</button>

                <a href="../PHP/LoginUser.php" class="link">Keluar dari Admin</a>
            </form>
        </div>
    </div>
</body>
</html>
