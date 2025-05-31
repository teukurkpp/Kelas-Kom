<?php
session_start();
include 'DbKelasKom.php';

if (!isset($_SESSION['npm'])) {
    header("Location: LoginUser.php");
    exit;
}

$npm = mysqli_real_escape_string($conn, $_SESSION['npm']);
$query_pj = mysqli_query($conn, "SELECT npm FROM pj_kelas WHERE npm = '$npm'");
if (!$data = mysqli_fetch_assoc($query_pj)) {
    header("Location: LoginUser.php");
    exit;
}

$userType = 'PJ Kelas';
$userIdentifier = $npm;

$hari = isset($_GET['hari']) ? urldecode($_GET['hari']) : '';
$jam = isset($_GET['jam']) ? urldecode($_GET['jam']) : '';
$ruangan = isset($_GET['ruangan']) ? urldecode($_GET['ruangan']) : '';

if (empty($hari) || empty($jam) || empty($ruangan)) {
    header("Location: PilihHari.php?error=missing_parameters");
    exit;
}

$query_schedule = mysqli_query($conn, "SELECT kelas, mata_kuliah, prodi FROM jadwal_matkul WHERE hari = '$hari' AND jam = '$jam' AND ruangan = '$ruangan'");
$schedule = mysqli_fetch_assoc($query_schedule);

$query_status = mysqli_query($conn, "SELECT status, timer_end FROM room_status WHERE hari = '$hari' AND jam = '$jam' AND ruangan = '$ruangan'");
$status_data = mysqli_fetch_assoc($query_status);
$current_status = $status_data ? $status_data['status'] : 'offline';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KelasKom - Ubah Status Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/UbahStatus.css">
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

    <main class="main-content py-5 px-3 px-md-5">
        <h2 class="text-center mb-4">Perubahan Status Kelas</h2>

        <div class="step-container active" id="step1">
            <div class="alert alert-info">
                Kelas ini sudah berstatus: <strong><?= ucfirst($current_status) ?></strong>
            </div>
            <div class="card p-3 mb-3">
                <h5>Ubah kelas <?= htmlspecialchars($ruangan) ?> menjadi...</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-warning w-50" onclick="goToStep(2)">Online</button>
                    <button class="btn btn-danger w-50" onclick="showOfflineMessage()">Offline</button>
                </div>
            </div>
        </div>

        <div class="step-container" id="step2">
            <div class="card p-3 mb-3">
                <h5>Apakah Anda yakin ingin merubah Kelas <?= htmlspecialchars($ruangan) ?> menjadi Online?</h5>
                <p>pukul <?= htmlspecialchars($jam) ?> untuk kelas <?= htmlspecialchars($schedule['kelas']) ?> - <?= htmlspecialchars($schedule['mata_kuliah']) ?></p>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary w-50" onclick="goToStep(1)">Kembali</button>
                    <button class="btn btn-primary w-50" onclick="goToStep(3)">Konfirmasi</button>
                </div>
            </div>
        </div>

        <div class="step-container" id="step3">
            <div class="card p-3 mb-3">
                <h5>Setting waktu Kelas Online...</h5>
                <form id="statusForm">
                    <input type="hidden" name="hari" value="<?= htmlspecialchars($hari) ?>">
                    <input type="hidden" name="jam" value="<?= htmlspecialchars($jam) ?>">
                    <input type="hidden" name="ruangan" value="<?= htmlspecialchars($ruangan) ?>">
                    <input type="hidden" name="created_by" value="<?= htmlspecialchars($userIdentifier) ?>">
                    <div class="mb-3">
                        <label for="duration" class="form-label">Durasi (Menit)</label>
                        <input type="number" id="duration" name="duration_minutes" class="form-control" min="30" max="180" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Selesai</button>
                </form>
            </div>
        </div>

        <div class="step-container" id="successMessage">
            <div class="alert alert-success">
                <h5>Status Kelas Berhasil Diubah</h5>
                <p>Kelas <?= htmlspecialchars($ruangan) ?> sekarang berstatus <span id="finalStatus">Online</span></p>
                <a href="PilihKelas.php?hari=<?= urlencode($hari) ?>&jam=<?= urlencode($jam) ?>" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>

        <div class="alert alert-info mt-3 d-none" id="offlineAlert">
            Status sudah Offline
        </div>
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
    <script src="../JS/UbahStatus.js"></script>
</body>
</html>