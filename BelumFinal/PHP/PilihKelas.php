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

// Jika tetap gagal deteksi user
if (empty($userType)) {
    header("Location: LoginUser.php");
    exit;
}

$hari = isset($_GET['hari']) ? mysqli_real_escape_string($conn, $_GET['hari']) : '';
$jam = isset($_GET['jam']) ? mysqli_real_escape_string($conn, $_GET['jam']) : '';

$query_kelas = mysqli_query($conn, "SELECT DISTINCT ruangan FROM jadwal_matkul");
$ruangan_semua = [];
while ($row = mysqli_fetch_assoc($query_kelas)) {
    $ruangan_semua[] = trim($row['ruangan']);
}

$query_terisi = mysqli_query($conn, "SELECT ruangan, mata_kuliah, kelas FROM jadwal_matkul WHERE hari = '$hari' AND jam = '$jam'");
$isi_kelas = [];
while ($row = mysqli_fetch_assoc($query_terisi)) {
    $ruangan_key = trim($row['ruangan']);
    $isi_kelas[$ruangan_key] = [
        'mata_kuliah' => $row['mata_kuliah'],
        'kelas' => $row['kelas']
    ];
}

function tampilkan_ruangan($ruangan_semua, $isi_kelas, $filter_callback, $hapus_prefix = '') {
    global $hari, $jam, $userType;
    foreach (array_unique($ruangan_semua) as $ruangan) {
        if (!$filter_callback($ruangan)) continue;

        $terisi = array_key_exists($ruangan, $isi_kelas);
        $status = $terisi ? 'terisi' : 'kosong';
        $info = $terisi ? $isi_kelas[$ruangan]['kelas'] . ' - ' . $isi_kelas[$ruangan]['mata_kuliah'] : 'Kosong';

        $ruangan_display = $hapus_prefix ? trim(preg_replace('/^' . preg_quote($hapus_prefix, '/') . '/i', '', $ruangan)) : $ruangan;

        // Only generate link for Admin and PJ Kelas
        if ($userType === 'Admin' || $userType === 'PJ Kelas') {
            echo '<a href="FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '">
                    <div class="kelas bulat ' . $status . '">
                        <div class="judul-ruangan">' . htmlspecialchars($ruangan_display) . '</div>
                        <span>' . htmlspecialchars($info) . '</span>
                    </div>
                  </a>';
        } else {
            // For Mahasiswa and Dosen, display without link
            echo '<div class="kelas bulat ' . $status . '">
                    <div class="judul-ruangan">' . htmlspecialchars($ruangan_display) . '</div>
                    <span>' . htmlspecialchars($info) . '</span>
                  </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KelasKom - Pilih Kelas</title>
  <link rel="stylesheet" href="../CSS/PilihKelas.css">
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
        <a href="LoginUser.php">↩️ Keluar</a>
      </div>
    </div>
  </header>

  <main>
    <h3>Pilih Kelas untuk Hari: <?= htmlspecialchars($hari) ?> | Jam: <?= htmlspecialchars($jam) ?></h3>

    <div class="lantai">Lantai 4 (Ruang Kelas)</div>
    <div class="kelas-grid">
      <?php
      tampilkan_ruangan($ruangan_semua, $isi_kelas, function($r) {
          return stripos($r, 'Kelas') === 0;
      }, 'Kelas');
      ?>
    </div>

    <div class="lantai">Lantai 3 (Laboratorium)</div>
    <div class="kelas-grid">
      <?php
      tampilkan_ruangan($ruangan_semua, $isi_kelas, function($r) {
          return stripos($r, 'LAB') === 0;
      });
      ?>
    </div>
  </main>

  <div class="color-mean">
    <p class="text-mean">🔴 Offline</p>
    <p class="text-mean">🟡 Online</p>
    <p class="text-mean">⚪ Kosong</p>
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
