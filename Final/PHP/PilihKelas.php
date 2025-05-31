<?php
session_start();
include 'DbKelasKom.php';

// Nonaktifkan cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Cek login
if (!isset($_SESSION['username']) && !isset($_SESSION['npm'])) {
    error_log("PilihKelas.php: No user session"); // Logging
    header("Location: LoginUser.php");
    exit;
}

$userType = '';
$userIdentifier = '';

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
} elseif (isset($_SESSION['npm'])) {
    $npm = mysqli_real_escape_string($conn, $_SESSION['npm']);
    $query_pj = mysqli_query($conn, "SELECT npm FROM pj_kelas WHERE npm = '$npm'");
    if ($data = mysqli_fetch_assoc($query_pj)) {
        $userType = 'PJ Kelas';
        $userIdentifier = $data['npm'];
    }
}

if (empty($userType)) {
    error_log("PilihKelas.php: User type not detected for npm=$npm or username=$username"); // Logging
    header("Location: LoginUser.php");
    exit;
}

$hari = isset($_GET['hari']) ? mysqli_real_escape_string($conn, urldecode($_GET['hari'])) : '';
$jam = isset($_GET['jam']) ? mysqli_real_escape_string($conn, urldecode($_GET['jam'])) : '';

if (empty($hari) || empty($jam)) {
    error_log("PilihKelas.php: Missing hari or jam - hari=$hari, jam=$jam"); // Logging
    header("Location: PilihHari.php?error=missing_parameters");
    exit;
}

$query_validate = mysqli_query($conn, "SELECT COUNT(*) as count FROM jadwal_matkul WHERE hari = '$hari' AND jam = '$jam'");
$validation = mysqli_fetch_assoc($query_validate);
if ($validation['count'] == 0) {
    error_log("PilihKelas.php: Invalid hari or jam - hari=$hari, jam=$jam"); // Logging
    header("Location: PilihHari.php?error=invalid_hari_jam");
    exit;
}

$query_kelas = mysqli_query($conn, "SELECT DISTINCT ruangan FROM jadwal_matkul");
$ruangan_semua = [];
while ($row = mysqli_fetch_assoc($query_kelas)) {
    $ruangan_semua[] = trim($row['ruangan']);
}

$query_status = mysqli_query($conn, "SELECT ruangan, status, timer_end FROM room_status WHERE hari = '$hari' AND jam = '$jam'");
$room_status = [];
while ($row = mysqli_fetch_assoc($query_status)) {
    $room_status[$row['ruangan']] = [
        'status' => $row['status'],
        'timer_end' => $row['timer_end'] ? strtotime($row['timer_end']) : null
    ];
    error_log("PilihKelas.php: Room={$row['ruangan']}, Status={$row['status']}, Timer End={$row['timer_end']}"); // Logging
}

$query_temp = mysqli_query($conn, "SELECT ruangan, kelas, mata_kuliah, prodi FROM temporary_changes WHERE hari = '$hari' AND jam = '$jam' AND timer_end > NOW()");
$temp_changes = [];
while ($row = mysqli_fetch_assoc($query_temp)) {
    $temp_changes[$row['ruangan']] = [
        'kelas' => $row['kelas'],
        'mata_kuliah' => $row['mata_kuliah'],
        'prodi' => $row['prodi']
    ];
}

$query_terisi = mysqli_query($conn, "SELECT ruangan, mata_kuliah, kelas, prodi FROM jadwal_matkul WHERE hari = '$hari' AND jam = '$jam'");
$isi_kelas = [];
while ($row = mysqli_fetch_assoc($query_terisi)) {
    $ruangan_key = trim($row['ruangan']);
    $isi_kelas[$ruangan_key] = [
        'mata_kuliah' => $row['mata_kuliah'],
        'kelas' => $row['kelas'],
        'prodi' => $row['prodi']
    ];
    if (!in_array($row['ruangan'], $ruangan_semua)) {
        $ruangan_semua[] = $row['ruangan'];
    }
}

function tampilkan_ruangan($ruangan_semua, $isi_kelas, $room_status, $temp_changes, $filter_callback, $hapus_prefix = '') {
    global $hari, $jam, $userType, $userIdentifier;
    $current_time = time();
    foreach (array_unique($ruangan_semua) as $ruangan) {
        if (!$filter_callback($ruangan)) continue;

        $status = 'kosong';
        $info = 'Kosong';
        $link = '';
        $class = 'kosong';
        $extra_info = '';

        if (isset($isi_kelas[$ruangan])) {
            $status = isset($room_status[$ruangan]) && $room_status[$ruangan]['status'] === 'online' &&
                    (!isset($room_status[$ruangan]['timer_end']) || $room_status[$ruangan]['timer_end'] > $current_time)
                    ? 'online' : 'offline';
            $class = $status;
            $info = $isi_kelas[$ruangan]['kelas'] . ' - ' . $isi_kelas[$ruangan]['mata_kuliah'] . ' (' . $isi_kelas[$ruangan]['prodi'] . ')';

            if ($status === 'online' && ($userType === 'Admin' || $userType === 'PJ Kelas')) {
                $link = 'FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '&status=online';
            } elseif ($userType === 'PJ Kelas' && $status === 'offline') {
                $link = 'UbahStatus.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan);
            }
        }

        // Tambahkan keterangan jika ruangan juga sedang digunakan sementara
        if (isset($temp_changes[$ruangan])) {
            $class = 'temporary'; // ubah warna jadi biru
            $used_kelas = htmlspecialchars($temp_changes[$ruangan]['kelas']);
            $used_prodi = htmlspecialchars($temp_changes[$ruangan]['prodi']);
            $extra_info = '<div class="keterangan-bawah">Digunakan sementara oleh ' . $used_kelas . ' (' . $used_prodi . ')</div>';

            // Link ke form perubahan status tetap temporary
            if ($userType === 'Admin' || $userType === 'PJ Kelas') {
                $link = 'FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '&status=temporary';
            }
        


        } elseif (isset($isi_kelas[$ruangan])) {
            $status = isset($room_status[$ruangan]) && $room_status[$ruangan]['status'] === 'online' && (!isset($room_status[$ruangan]['timer_end']) || $room_status[$ruangan]['timer_end'] > $current_time) ? 'online' : 'offline';
            $class = $status;
            $info = $isi_kelas[$ruangan]['kelas'] . ' - ' . $isi_kelas[$ruangan]['mata_kuliah'] . ' (' . $isi_kelas[$ruangan]['prodi'] . ')';
            if ($status === 'online' && ($userType === 'Admin' || $userType === 'PJ Kelas')) {
                $link = 'FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '&status=online';
            } elseif ($userType === 'PJ Kelas' && $status === 'offline') {
                $link = 'UbahStatus.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan);
            }
        } else {
            if ($userType === 'Admin' || $userType === 'PJ Kelas') {
                $link = 'FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '&status=kosong';
            }
        }

        $ruangan_display = $hapus_prefix ? trim(preg_replace('/^' . preg_quote($hapus_prefix, '/') . '/i', '', $ruangan)) : $ruangan;

        if ($link && ($userType === 'Admin' || $userType === 'PJ Kelas')) {
            echo '<a href="' . $link . '">
                    <div class="kelas ' . $class . '">
                        <div class="judul-ruangan">' . htmlspecialchars($ruangan_display) . '</div>
                        <span>' . htmlspecialchars($info) . '</span>
                        ' . $extra_info . '
                    </div>
                  </a>';
        } else {
            echo '<div class="kelas ' . $class . '">
                    <div class="judul-ruangan">' . htmlspecialchars($ruangan_display) . '</div>
                    <span>' . htmlspecialchars($info) . '</span>
                    ' . $extra_info . '
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="container mt-3">
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='alert alert-{$_SESSION['message']['type']}'>{$_SESSION['message']['text']}</div>";
                unset($_SESSION['message']);
            }
            ?>
        </div>
        <h3>Pilih Kelas untuk Hari: <?= htmlspecialchars($hari) ?> | Jam: <?= htmlspecialchars($jam) ?></h3>

        <div class="lantai">Lantai 4 (Ruang Kelas)</div>
        <div class="kelas-grid">
            <?php
            tampilkan_ruangan($ruangan_semua, $isi_kelas, $room_status, $temp_changes, function($r) {
                return stripos($r, 'Kelas') === 0;
            }, 'Kelas');
            ?>
        </div>

        <div class="lantai">Lantai 3 (Laboratorium)</div>
        <div class="kelas-grid">
            <?php
            tampilkan_ruangan($ruangan_semua, $isi_kelas, $room_status, $temp_changes, function($r) {
                return stripos($r, 'LAB') === 0;
            });
            ?>
        </div>
    </main>

    <div class="color-mean">
        <span class="legend-item offline">Offline</span>
        <span class="legend-item online">Online</span>
        <span class="legend-item kosong">Kosong</span>
        <span class="legend-item temporary">Digunakan Sementara</span>
    </div>

    <script>
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