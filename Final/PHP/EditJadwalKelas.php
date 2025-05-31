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
    $query_admin = mysqli_query($conn, "SELECT username FROM admin WHERE username = '$username'");
    if (mysqli_num_rows($query_admin) > 0) {
        $userType = 'Admin';
        $userIdentifier = $username;
    } else {
        $query_dosmhs = mysqli_query($conn, "SELECT username, prodi FROM dosmhs WHERE username = '$username'");
        if ($data = mysqli_fetch_assoc($query_dosmhs)) {
            $prodi = $data['prodi'];
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

// Restrict access to Admin only
if ($userType !== 'Admin') {
    header("Location: PilihHari.php");
    exit;
}

// Proceed with edit schedule form logic
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
// Example: Fetch schedule data (replace with actual logic)
$query = "SELECT * FROM jadwal_matkul WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KelasKom - Edit Jadwal Kelas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../CSS/JadwalKelas.css">
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
        <a href="PilihHari.php">📅 Pilih Hari</a>
        <a href="PilihKelas.php">📅 Pilih Kelas</a>
        <a href="LoginUser.php">↩️ Keluar</a>
      </div>
    </div>
  </header>

  <div class="container mt-5">
    <h2 class="mb-4">Edit Jadwal Perkuliahan</h2>
    <!-- Add your form fields here, pre-filled with $row data -->
    <form method="POST" action="">
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>

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