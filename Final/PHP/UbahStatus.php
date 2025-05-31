<?php
session_start();
include 'DbKelasKom.php';
date_default_timezone_set("Asia/Jakarta");

if (!isset($_SESSION['npm'])) {
    error_log("UbahStatus.php: No NPM in session");
    header("Location: LoginUser.php");
    exit;
}

$npm = mysqli_real_escape_string($conn, $_SESSION['npm']);
$query_pj = mysqli_query($conn, "SELECT npm FROM pj_kelas WHERE npm = '$npm'");
if (!$data = mysqli_fetch_assoc($query_pj)) {
    error_log("UbahStatus.php: Invalid NPM $npm for PJ Kelas");
    header("Location: LoginUser.php");
    exit;
}

$userType = 'PJ Kelas';
$userIdentifier = $npm;

$hari = isset($_GET['hari']) ? mysqli_real_escape_string($conn, urldecode($_GET['hari'])) : '';
$jam = isset($_GET['jam']) ? mysqli_real_escape_string($conn, urldecode($_GET['jam'])) : '';
$ruangan = isset($_GET['ruangan']) ? mysqli_real_escape_string($conn, urldecode($_GET['ruangan'])) : '';

if (empty($hari) || empty($jam) || empty($ruangan)) {
    error_log("UbahStatus.php: Missing parameters - hari=$hari, jam=$jam, ruangan=$ruangan");
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Parameter hari, jam, atau ruangan tidak lengkap'];
    header("Location: PilihHari.php?error=missing_parameters");
    exit;
}

$query_schedule = mysqli_query($conn, "SELECT kelas, mata_kuliah, prodi FROM jadwal_matkul WHERE hari = '$hari' AND jam = '$jam' AND ruangan = '$ruangan'");
if (!$schedule = mysqli_fetch_assoc($query_schedule)) {
    error_log("UbahStatus.php: No schedule found for ruangan=$ruangan, hari=$hari, jam=$jam");
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Tidak ada jadwal untuk ruangan ini pada waktu yang dipilih'];
    header("Location: PilihHari.php?error=invalid_schedule");
    exit;
}

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

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>">
                <?php echo $_SESSION['message']['text']; ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div id="alert-message" class="alert d-none"></div>

        <?php if ($current_status === 'online'): ?>
            <div class="alert alert-info">
                Kelas ini sudah berstatus: <strong>Online</strong>
                <p><a href="PilihKelas.php?hari=<?= urlencode($hari) ?>&jam=<?= urlencode($jam) ?>" class="btn btn-primary">Kembali ke Pilih Kelas</a></p>
            </div>
        <?php else: ?>
            <div class="card p-3 mb-3">
                <h5>Ubah kelas <?= htmlspecialchars($ruangan) ?> menjadi Online</h5>
                <p>pukul <?= htmlspecialchars($jam) ?> untuk kelas <?= htmlspecialchars($schedule['kelas']) ?> - <?= htmlspecialchars($schedule['mata_kuliah']) ?></p>
                <form id="status-form" method="POST" action="ProsesUbahStatus.php">
                    <input type="hidden" name="hari" value="<?= htmlspecialchars($hari) ?>">
                    <input type="hidden" name="jam" value="<?= htmlspecialchars($jam) ?>">
                    <input type="hidden" name="ruangan" value="<?= htmlspecialchars($ruangan) ?>">
                    <input type="hidden" name="created_by" value="<?= htmlspecialchars($userIdentifier) ?>">
                    <input type="hidden" name="kelas" value="<?= htmlspecialchars($schedule['kelas']) ?>">
                    <input type="hidden" name="mata_kuliah" value="<?= htmlspecialchars($schedule['mata_kuliah']) ?>">
                    <input type="hidden" name="sks" value="3">
                    <input type="hidden" name="status" value="<?= htmlspecialchars($current_status) ?>">
                    <input type="hidden" name="action" value="sementara">

                    <div class="mb-3">
                        <label for="duration" class="form-label">Durasi (Menit)</label>
                        <input type="number" id="duration" name="duration_minutes" class="form-control" min="30" max="180" required>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="PilihKelas.php?hari=<?= urlencode($hari) ?>&jam=<?= urlencode($jam) ?>" class="btn btn-secondary w-50">Kembali</a>
                        <button type="submit" class="btn btn-success w-50">Ubah ke Online</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <script>
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.user-dropdown');
            const toggle = document.querySelector('#toggle-dropdown');
            if (!dropdown.contains(event.target)) {
                toggle.checked = false;
            }
        });

        document.getElementById('status-form')?.addEventListener('submit', function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const alertMessage = document.getElementById('alert-message');

            fetch('ProsesUbahStatus.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alertMessage.classList.remove('d-none');
                if (data.success) {
                    alertMessage.classList.remove('alert-danger');
                    alertMessage.classList.add('alert-success');
                    alertMessage.innerHTML = 'Status kelas berhasil diubah menjadi Online';
                    setTimeout(() => {
                        window.location.href = 'PilihKelas.php?hari=<?= urlencode($hari) ?>&jam=<?= urlencode($jam) ?>';
                    }, 1500);
                } else {
                    alertMessage.classList.remove('alert-success');
                    alertMessage.classList.add('alert-danger');
                    alertMessage.innerHTML = data.message || 'Terjadi kesalahan saat mengubah status';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertMessage.classList.remove('d-none');
                alertMessage.classList.remove('alert-success');
                alertMessage.classList.add('alert-danger');
                alertMessage.innerHTML = 'Terjadi kesalahan saat mengirim data';
            });
        });
    </script>
</body>
</html>
