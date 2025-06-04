<?php
session_start();
include 'DbKelasKom.php';

// Menginisialisasi sesi dan koneksi ke database.
//Juga menonaktifkan cache agar data yang ditampilkan selalu terbaru (misalnya status ruangan yang bisa berubah setiap menit).
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Mengecek apakah pengguna sudah login sebagai username (admin/dosen/mahasiswa) atau npm (pj kelas).
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
        //Jika username ditemukan di tabel admin, maka user dianggap Admin.
    } else {
        $query_dosmhs = mysqli_query($conn, "SELECT username, prodi FROM dosmhs WHERE username = '$username'");
        if ($data = mysqli_fetch_assoc($query_dosmhs)) {
            $prodi = $data['prodi'];
            //Jika ditemukan, ambil juga program studi (prodi) dari mahasiswa atau dosen.
            $query_dosen = mysqli_query($conn, "SELECT dosen FROM jadwal_matkul WHERE dosen = '$username'");
            if (mysqli_num_rows($query_dosen) > 0) {
                $userType = 'Dosen';
                $userIdentifier = $username;
                //Jika username ditemukan sebagai dosen di jadwal, berarti user adalah Dosen.
            } else {
                $userType = ($prodi === 'Sistem Informasi' ? 'Sistem Informasi' : 'Informatika');
                $userIdentifier = $data['username'];
                //Jika bukan dosen, berarti dia Mahasiswa (Informatika / Sistem Informasi)
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
    //Jika Login sebagai npm (PJ Kelas)
}

if (empty($userType)) {
    error_log("PilihKelas.php: User type not detected for npm=$npm or username=$username"); // Logging
    header("Location: LoginUser.php");
    exit;
    //Kalau semua pemeriksaan gagal, maka dianggap belum login dan user diarahkan ke halaman login.
}

$hari = isset($_GET['hari']) ? mysqli_real_escape_string($conn, urldecode($_GET['hari'])) : '';
$jam = isset($_GET['jam']) ? mysqli_real_escape_string($conn, urldecode($_GET['jam'])) : '';
//Ambil parameter hari dan jam dari URL (GET), 

if (empty($hari) || empty($jam)) {
    error_log("PilihKelas.php: Missing hari or jam - hari=$hari, jam=$jam"); // Logging
    header("Location: PilihHari.php?error=missing_parameters");
    exit;
    //Cek jika hari atau jam kosong
}

$query_validate = mysqli_query($conn, "SELECT COUNT(*) as count FROM jadwal_matkul WHERE hari = '$hari' AND jam = '$jam'");
$validation = mysqli_fetch_assoc($query_validate);
if ($validation['count'] == 0) {
    error_log("PilihKelas.php: Invalid hari or jam - hari=$hari, jam=$jam"); // Logging
    header("Location: PilihHari.php?error=invalid_hari_jam");
    exit;
    //Validasi apakah hari dan jam ada di tabel jadwal_matkul
}

$query_kelas = mysqli_query($conn, "SELECT DISTINCT ruangan FROM jadwal_matkul");
$ruangan_semua = [];
while ($row = mysqli_fetch_assoc($query_kelas)) {
    $ruangan_semua[] = trim($row['ruangan']);
}
// Ambil semua ruangan yang unik dari tabel jadwal_matkul.
// Disimpan ke array $ruangan_semua agar bisa ditampilkan satu per satu di UI.
// trim() memastikan tidak ada spasi di awal/akhir string ruangan.

$query_status = mysqli_query($conn, "SELECT ruangan, status, timer_end FROM room_status WHERE hari = '$hari' AND jam = '$jam'");
$room_status = [];
while ($row = mysqli_fetch_assoc($query_status)) {
    $room_status[$row['ruangan']] = [
        'status' => $row['status'],
        'timer_end' => $row['timer_end'] ? strtotime($row['timer_end']) : null
    ];
    error_log("PilihKelas.php: Room={$row['ruangan']}, Status={$row['status']}, Timer End={$row['timer_end']}"); // Logging
}
// Mengambil status online atau offline, dan waktu akhir (timer_end) tiap ruangan pada jam & hari tertentu.
// Diubah ke timestamp UNIX dengan strtotime agar mudah dibandingkan waktunya nanti.
// error_log() hanya untuk debugging (output ke file log server jika terjadi error).

$query_temp = mysqli_query($conn, "SELECT ruangan, kelas, mata_kuliah, prodi FROM temporary_changes WHERE hari = '$hari' AND jam = '$jam' AND timer_end > NOW()");
$temp_changes = [];
while ($row = mysqli_fetch_assoc($query_temp)) {
    $temp_changes[$row['ruangan']] = [
        'kelas' => $row['kelas'],
        'mata_kuliah' => $row['mata_kuliah'],
        'prodi' => $row['prodi']
    ];
}
// Ambil semua ruangan yang sedang digunakan sementara (ubah sementara) pada hari dan jam tertentu dan masih aktif (timer_end > NOW()).
// Simpan ke $temp_changes berdasarkan nama ruangan sebagai key.

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
// Ambil semua ruangan yang sudah ada isi jadwal normal pada hari & jam tersebut.
// Data disimpan ke $isi_kelas berdasarkan nama ruangan.

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
        //normal jadwal atau desault

        if (isset($isi_kelas[$ruangan])) {//Mengecek apakah ruangan ini memiliki kelas terjadwal pada jam dan hari tertentu.
                                            //Jika iya, maka artinya ruangan ini sedang digunakan secara reguler.
            $status = isset($room_status[$ruangan]) && $room_status[$ruangan]['status'] === 'online' &&
                    (!isset($room_status[$ruangan]['timer_end']) || $room_status[$ruangan]['timer_end'] > $current_time)
                    ? 'online' : 'offline';
// Cek apakah room_status memiliki entri untuk ruangan ini.
// Lalu cek apakah status-nya adalah 'online'.
// Kemudian cek apakah timer-nya belum habis (timer_end > waktu sekarang), atau bahkan belum diset sama sekali (!isset()).
// Jika semua kondisi di atas terpenuhi → status = 'online', jika tidak → 'offline'.
            $class = $status;
            // Menyimpan nilai 'online' atau 'offline' ke variabel $class.
            //Variabel ini akan dipakai untuk menentukan warna tampilan kotak ruangan (misalnya warna hijau untuk online, merah untuk offline).
            $info = $isi_kelas[$ruangan]['kelas'] . ' - ' . $isi_kelas[$ruangan]['mata_kuliah'] . ' (' . $isi_kelas[$ruangan]['prodi'] . ')';
//Menyusun teks informasi ruangan:
            if ($status === 'online' && ($userType === 'Admin' || $userType === 'PJ Kelas')) {//if ($status === 'online' && ($userType === 'Admin' || $userType === 'PJ Kelas')) {
                $link = 'FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '&status=online'; 
                //Maka tautan akan diarahkan ke halaman form perubahan jadwal, dengan status 'online'.
            } elseif (($userType === 'PJ Kelas' || $userType === 'Admin') && $status === 'offline') {
                $link = 'UbahStatus.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan);
            }

            //Jika user adalah PJ Kelas dan ruangan sudah offline (misalnya timer online habis), maka:
            // Akan diarahkan ke halaman UbahStatus.php, untuk mengaktifkan ulang status ruangan menjadi online kembali.
        }

        // Tambahkan keterangan jika ruangan juga sedang digunakan sementara
        if (isset($temp_changes[$ruangan])) {//Mengecek apakah ruangan ini termasuk dalam daftar perubahan sementara (temporary_changes) untuk hari dan jam tertentu.
                                            // Artinya, ruangan sedang dipakai oleh kelas lain secara sementara.
            $class = 'temporary'; // ubah warna jadi biru
            $used_kelas = htmlspecialchars($temp_changes[$ruangan]['kelas']); //ambil informasi
            $used_prodi = htmlspecialchars($temp_changes[$ruangan]['prodi']);
            $extra_info = '<div class="keterangan-bawah">Digunakan sementara oleh ' . $used_kelas . ' (' . $used_prodi . ')</div>';
            //Membuat informasi tambahan dalam bentuk HTML yang akan ditampilkan di bawah nama ruangan. 
            // Link ke form perubahan status tetap temporary
            if ($userType === 'Admin' || $userType === 'PJ Kelas') {
                $link = 'FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '&status=temporary';
            }// kalau login sebagai admin atau pj kelas maka disediakan tautan ke formperubahanjadwal
        


        } elseif (isset($isi_kelas[$ruangan])) {
            $status = isset($room_status[$ruangan]) && $room_status[$ruangan]['status'] === 'online' && (!isset($room_status[$ruangan]['timer_end']) || $room_status[$ruangan]['timer_end'] > $current_time) ? 'online' : 'offline';
            //Mengecek apakah ruangan ini:

            // Ada di room_status,

            // Statusnya online,

            // Belum melewati timer_end (artinya waktunya masih aktif).

            // Kalau semua syarat benar → status jadi 'online', kalau tidak → 'offline'.
            $class = $status;
            $info = $isi_kelas[$ruangan]['kelas'] . ' - ' . $isi_kelas[$ruangan]['mata_kuliah'] . ' (' . $isi_kelas[$ruangan]['prodi'] . ')';
            //$info berisi keterangan lengkap tentang:

            // Nama kelas,

            // Mata kuliah,

            // dan Prodi yang sedang menggunakan ruangan.
            if ($status === 'online' && ($userType === 'Admin' || $userType === 'PJ Kelas')) {
                $link = 'FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '&status=online';
            } elseif ($userType === 'PJ Kelas' && $status === 'offline') {
                $link = 'UbahStatus.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan);
            }// hak akses
        } else {
            if ($userType === 'Admin' || $userType === 'PJ Kelas') {
                $link = 'FormPerubahanJadwal.php?hari=' . urlencode($hari) . '&jam=' . urlencode($jam) . '&ruangan=' . urlencode($ruangan) . '&status=kosong';
            }
        }// Artinya: ruangan tidak digunakan secara tetap, dan tidak juga sedang digunakan sementara → benar-benar kosong.

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
    }// jika login sebagai admin atau pj kelas maka ruangannya akan menjadi href atau bisa di klik
    // namun jika bukan admin atau pj kelas maka ruangan akan menjadi info saja
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