<?php
session_start();
include 'DbKelasKom.php';

// Cek login
if (!isset($_SESSION['username']) && !isset($_SESSION['npm'])) {
    header("Location: LoginUser.php");
    exit;
}

$userType = '';
$userIdentifier = '';

// === 1. Jika login sebagai ADMIN / DOSEN / MAHASISWA ===
if (isset($_SESSION['username'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);

    // Admin
    $query_admin = mysqli_query($conn, "SELECT username FROM admin WHERE username = '$username'");
    if (mysqli_num_rows($query_admin) > 0) {
        $userType = 'Admin';
        $userIdentifier = $username;
    } else {
        // Cek di dosmhs
        $query_dosmhs = mysqli_query($conn, "SELECT username, prodi FROM dosmhs WHERE username = '$username'");
        if ($data = mysqli_fetch_assoc($query_dosmhs)) {
            $prodi = $data['prodi'];

            // Cek apakah dosen
            $query_dosen = mysqli_query($conn, "SELECT dosen FROM jadwal_matkul WHERE dosen = '$username'");
            if (mysqli_num_rows($query_dosen) > 0) {
                $userType = 'Dosen';
                $userIdentifier = $username;
            } else {
                $userType = ($prodi === 'Sistem Informasi' ? 'Sistem Informasi' : 'Informatika');
                $userIdentifier = $data['username'];
            }
        }
    }
}

// === 2. Jika login sebagai PJ Kelas ===
elseif (isset($_SESSION['npm'])) {
    $npm = mysqli_real_escape_string($conn, $_SESSION['npm']);
    $query_pj = mysqli_query($conn, "SELECT npm FROM pj_kelas WHERE npm = '$npm'");
    if ($data = mysqli_fetch_assoc($query_pj)) {
        $userType = 'PJ Kelas';
        $userIdentifier = $data['npm'];
    }
}

// Jika tetap gagal deteksi user
if (empty($userType)) {
    header("Location: LoginUser.php");
    exit;
}

// Ambil daftar hari unik dari database
$query_hari = mysqli_query($conn, "SELECT DISTINCT hari FROM jadwal_matkul ORDER BY FIELD(hari, 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU')");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KelasKom - Pilih Hari</title>
  <link rel="stylesheet" href="../CSS/PilihHari.css">
</head>
<body>
  <header>
    <div class="logo-section">
      <img src="../PIC/logo.png" alt="Logo Fasilkom" />
      <div class="logo-text">
        <span>KelasKom</span>
        <span>Fakultas Ilmu Komputer</span>
      </div>
    </div>
    <div class="user-dropdown">
      <input type="checkbox" id="toggle-dropdown">
      <div class="user-label">
        <div class="user-label-content">
          <div class="user-info">
            <strong><?php echo htmlspecialchars($userIdentifier); ?></strong><br />
            <?php echo htmlspecialchars($userType); ?>
          </div>
          <label for="toggle-dropdown" class="arrow">▾</label>
        </div>
      </div>
      <div class="dropdown-content">
        <div class="dropdown-header">Selamat Datang!</div>
        <a href="TampilanJadwalKelas.php">📋 Jadwal</a>
        <a href="LoginUser.php">↩️ Keluar</a>
      </div>
    </div>
  </header>
  
  <main>
    <h3>Pilih Hari...</h3>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'missing_parameters'): ?>
      <p style="color: red;">Error: Hari atau jam tidak dipilih.</p>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'invalid_hari_jam'): ?>
      <p style="color: red;">Error: Hari atau jam tidak valid.</p>
    <?php endif; ?>
    <div class="day-grid">
      <?php while ($row = mysqli_fetch_assoc($query_hari)): ?>
        <a href="PilihJam.php?hari=<?= urlencode($row['hari']) ?>">
          <div class="day-card"><?= htmlspecialchars($row['hari']) ?></div>
        </a>
      <?php endwhile; ?>
    </div>
  </main>

  <script>
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      const dropdown = document.querySelector('.user-dropdown');
      const toggle = document.querySelector('#toggle-dropdown');
      if (!dropdown.contains(event.target)) {
        toggle.checked = false;
      }
    });
  </script>
</body>
</html>