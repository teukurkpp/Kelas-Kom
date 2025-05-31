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

if ($userType === 'Sistem Informasi' || $userType === 'Informatika' || $userType === 'Dosen') {
    header("Location: PilihHari.php");
    exit;
}

$hari = isset($_GET['hari']) ? urldecode($_GET['hari']) : '';
$jam = isset($_GET['jam']) ? urldecode($_GET['jam']) : '';
$ruangan = isset($_GET['ruangan']) ? urldecode($_GET['ruangan']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'kosong';

$current_class = null;
if ($status === 'online' || $status === 'temporary') {
    $query_current = mysqli_query($conn, "SELECT kelas, mata_kuliah, prodi FROM " . ($status === 'temporary' ? 'temporary_changes' : 'jadwal_matkul') . " WHERE hari = '$hari' AND jam = '$jam' AND ruangan = '$ruangan'");
    if ($row = mysqli_fetch_assoc($query_current)) {
        $current_class = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KelasKom - Formulir Perubahan Ruang Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/FormPerubahanJadwal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <main class="main-content py-5 px-3 px-md-5">
        <a href="PilihKelas.php?hari=<?= urlencode($hari) ?>&jam=<?= urlencode($jam) ?>" class="back-button">
            <i class="fas fa-chevron-left"></i>
        </a>

        <div class="form-container bg-white rounded shadow p-4 mx-auto" style="max-width: 800px;">
            <h1 class="form-title text-center mb-4">Formulir Perubahan Ruang Kelas</h1>
            <?php if ($current_class): ?>
                <div class="alert alert-warning">
                    Ruangan ini sedang digunakan oleh: <?= htmlspecialchars($current_class['kelas']) ?> - <?= htmlspecialchars($current_class['mata_kuliah']) ?> (<?= htmlspecialchars($current_class['prodi']) ?>)
                </div>
            <?php endif; ?>

            <form id="kelasForm" method="POST" action="ProsesPerubahanJadwal.php">
                <input type="hidden" name="hari" value="<?= htmlspecialchars($hari) ?>">
                <input type="hidden" name="jam" value="<?= htmlspecialchars($jam) ?>">
                <input type="hidden" name="ruangan" value="<?= htmlspecialchars($ruangan) ?>">
                <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
                <input type="hidden" name="created_by" value="<?= htmlspecialchars($userIdentifier) ?>">

                <div class="mb-3">
                    <label for="hari_display" class="form-label">Hari</label>
                    <input type="text" id="hari_display" class="form-control" value="<?= htmlspecialchars($hari) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="jam_display" class="form-label">Jam</label>
                    <input type="text" id="jam_display" class="form-control" value="<?= htmlspecialchars($jam) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="lantai" class="form-label">Lantai</label>
                    <select id="lantai" class="form-select" required>
                        <option value="" disabled selected>Pilih Lantai</option>
                        <option value="4">Lantai 4</option>
                        <option value="3">Lantai 3</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ruangan_display" class="form-label">Ruangan</label>
                    <input type="text" id="ruangan_display" class="form-control" value="<?= htmlspecialchars($ruangan) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <select id="kelas" name="kelas" class="form-select" required>
                        <option value="" disabled selected>Pilih Kelas</option>
                        <?php
                        $classes = ['2A', '2B', '2C', '2D', '2E', '2F', '4A', '4B', '4C', '4D', '4E', '4F', '6A', '6B', '6C', '6D', '6E', '6F', '6 Pilihan', '6 Pilihan A', '6 Pilihan B'];
                        foreach ($classes as $class) {
                            foreach (['Sistem Informasi', 'Informatika'] as $prodi) {
                                echo "<option value='$class - $prodi'>$class - $prodi</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah SKS</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sks" value="2" id="sks2">
                        <label class="form-check-label" for="sks2">2 SKS</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sks" value="3" id="sks3" checked>
                        <label class="form-check-label" for="sks3">3 SKS</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="mataKuliah" class="form-label">Mata Kuliah</label>
                    <input type="text" id="mataKuliah" name="mata_kuliah" class="form-control" placeholder="Contoh: Data Warehouse" required>
                </div>

                <?php if ($status === 'online' || $status === 'temporary'): ?>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Durasi (Menit)</label>
                        <input type="number" id="duration" name="duration_minutes" class="form-control" min="30" max="180" required>
                    </div>
                <?php endif; ?>

                <div class="d-flex gap-2 mt-4">
                    <?php if ($status === 'kosong'): ?>
                        <button type="submit" name="action" value="sementara" id="btnSementara" class="btn btn-success w-50" disabled>Ubah Sementara</button>
                        <button type="submit" name="action" value="permanen" id="btnPermanen" class="btn btn-danger w-50" disabled>Ubah Permanen</button>
                    <?php else: ?>
                        <button type="submit" name="action" value="sementara" id="btnSementara" class="btn btn-success w-100" disabled>Ubah Sementara</button>
                    <?php endif; ?>
                </div>
                <div class="alert mt-3 d-none" id="formFeedback" role="alert"></div>
            </form>
        </main>

    <script>
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.user-dropdown');
            const toggle = document.querySelector('#toggle-dropdown');
            if (!dropdown.contains(event.target)) {
                toggle.checked = false;
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/FormPerubahanJadwal.js"></script>
</body>
</html>