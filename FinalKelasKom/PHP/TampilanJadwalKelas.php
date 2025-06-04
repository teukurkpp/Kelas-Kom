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

// Filter berdasarkan prodi, hari, dan kelas
$prodi_filter = isset($_GET['prodi']) ? $_GET['prodi'] : '';
$hari_filter = isset($_GET['hari']) ? urldecode($_GET['hari']) : '';
$kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : '';

$query = "SELECT * FROM jadwal_matkul";
$conditions = [];
$params = [];
$types = '';

$valid_days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];

if ($prodi_filter && in_array($prodi_filter, ['Sistem Informasi', 'Informatika'])) {
    $conditions[] = "prodi = ?";
    $params[] = $prodi_filter;
    $types .= 's';
}
if ($hari_filter && in_array($hari_filter, $valid_days)) {
    $conditions[] = "hari = ?";
    $params[] = $hari_filter;
    $types .= 's';
}
if ($kelas_filter) {
    $conditions[] = "kelas = ?";
    $params[] = $kelas_filter;
    $types .= 's';
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Ambil data untuk dropdown Hari dan Kelas
$hari_options = mysqli_query($conn, "SELECT DISTINCT hari FROM jadwal_matkul ORDER BY FIELD(hari, 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU')");
$kelas_options = mysqli_query($conn, "SELECT DISTINCT kelas FROM jadwal_matkul ORDER BY kelas");
?>

<!DOCTYPE html>
<html>
<head>
    <title>KelasKom - Jadwal Kelas</title>
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
            <label for="toggle-dropdown" class="user-label">
                <div class="user-label-content">
                    <div class="user-info">
                        <strong><?php echo htmlspecialchars($userIdentifier); ?></strong><br />
                        <?php echo htmlspecialchars($userType); ?>
                    </div>
                    <span class="arrow">▾</span>
                </div>
            </label>
            <div class="dropdown-content">
                <div class="dropdown-header">Selamat Datang!</div>
                <a href="TampilanJadwalKelas.php">📋 Jadwal</a>
                <a href="PilihHari.php">📅 Pilih Hari</a>
                <a href="LoginUser.php">↩️ Keluar</a>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <h2 class="mb-4">Data Jadwal Perkuliahan</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-{$_SESSION['message']['type']}'>{$_SESSION['message']['text']}</div>";
            unset($_SESSION['message']);
        }
        ?>
        <div class="mb-3">
            <!-- Show "Tambah Data" button only for Admin -->
            <?php if ($userType === 'Admin'): ?>
                <a href="TambahJadwalKelas.php" class="btn btn-primary">+ Tambah Data</a>
            <?php endif; ?>
            <form method="GET" class="d-inline">
                <label for="prodi" class="me-2">Filter Prodi:</label>
                <select name="prodi" id="prodi" class="form-select d-inline w-auto" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="Sistem Informasi" <?= $prodi_filter == 'Sistem Informasi' ? 'selected' : '' ?>>Sistem Informasi</option>
                    <option value="Informatika" <?= $prodi_filter == 'Informatika' ? 'selected' : '' ?>>Informatika</option>
                </select>

                <label for="hari" class="me-2 ms-3">Filter Hari:</label>
                <select name="hari" id="hari" class="form-select d-inline w-auto" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <?php while ($hari_row = mysqli_fetch_assoc($hari_options)): ?>
                        <option value="<?= htmlspecialchars(urlencode($hari_row['hari'])) ?>" <?= $hari_filter === $hari_row['hari'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($hari_row['hari']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="kelas" class="me-2 ms-3">Filter Kelas:</label>
                <select name="kelas" id="kelas" class="form-select d-inline w-auto" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <?php while ($kelas_row = mysqli_fetch_assoc($kelas_options)): ?>
                        <option value="<?= htmlspecialchars($kelas_row['kelas']) ?>" <?= $kelas_filter == $kelas_row['kelas'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kelas_row['kelas']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <a href="TampilanJadwalKelas.php" class="btn btn-secondary ms-3">Reset</a>
            </form>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Kode Matkul</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Dosen</th>
                    <th>Kelas</th>
                    <th>Ruangan</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>$no</td>
                            <td>" . htmlspecialchars($row['hari']) . "</td>
                            <td>" . htmlspecialchars($row['jam']) . "</td>
                            <td>" . htmlspecialchars($row['kode_matkul']) . "</td>
                            <td>" . htmlspecialchars($row['mata_kuliah']) . "</td>
                            <td>" . htmlspecialchars($row['sks']) . "</td>
                            <td>" . htmlspecialchars($row['dosen']) . "</td>
                            <td>" . htmlspecialchars($row['kelas']) . "</td>
                            <td>" . htmlspecialchars($row['ruangan']) . "</td>
                            <td>" . htmlspecialchars($row['prodi']) . "</td>
                            <td>";
                        // Show Edit and Hapus buttons only for Admin
                        if ($userType === 'Admin') {
                            echo 
                                "<a href='EditJadwalKelas.php?id={$row['id']}' class='btn btn-warning btn-sm' onclick='return confirm(\"Yakin ingin mengedit?\")'>Edit</a> 
                                <a href='HapusJadwalKelas.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
                        } else {
                            echo "-"; // Placeholder for PJ Kelas, Mahasiswa, and Dosen
                        }
                        echo "</td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='11' class='text-center'>Tidak ada data jadwal yang ditemukan.</td></tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
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