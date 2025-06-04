<?php
session_start();
include 'DbKelasKom.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username']) && !isset($_SESSION['npm'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
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
    }
} elseif (isset($_SESSION['npm'])) {
    $npm = mysqli_real_escape_string($conn, $_SESSION['npm']);
    $query_pj = mysqli_query($conn, "SELECT npm FROM pj_kelas WHERE npm = '$npm'");
    if (mysqli_fetch_assoc($query_pj)) {
        $userType = 'PJ Kelas';
        $userIdentifier = $npm;
    }
}

if ($userType !== 'Admin' && $userType !== 'PJ Kelas') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$hari = mysqli_real_escape_string($conn, $_POST['hari'] ?? '');
$jam = mysqli_real_escape_string($conn, $_POST['jam'] ?? '');
$ruangan = mysqli_real_escape_string($conn, $_POST['ruangan'] ?? '');
$status = $_POST['status'] ?? '';
$kelas = mysqli_real_escape_string($conn, $_POST['kelas'] ?? '');
$prodi = mysqli_real_escape_string($conn, $_POST['prodi'] ?? '');
$mata_kuliah = mysqli_real_escape_string($conn, $_POST['mata_kuliah'] ?? '');
$sks = (int) ($_POST['sks'] ?? 0);
$duration_minutes = (int) ($_POST['duration_minutes'] ?? 0);
$action = $_POST['action'] ?? '';
$created_by = mysqli_real_escape_string($conn, $_POST['created_by'] ?? '');

if (empty($hari) || empty($jam) || empty($ruangan) || empty($kelas) || empty($prodi) || empty($mata_kuliah) || empty($sks) || empty($action)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

if (!in_array($prodi, ['Sistem Informasi', 'Informatika'])) {
    echo json_encode(['success' => false, 'message' => 'Prodi tidak valid']);
    exit;
}

if (($status === 'online' || $status === 'temporary') && $action === 'permanen') {
    echo json_encode(['success' => false, 'message' => 'Hanya perubahan sementara diperbolehkan untuk ruangan online']);
    exit;
}

if (($status === 'online' || $status === 'temporary') && $duration_minutes < 30) {
    echo json_encode(['success' => false, 'message' => 'Durasi minimal 30 menit']);
    exit;
}

mysqli_begin_transaction($conn);

try {
    if ($action === 'sementara') {
        date_default_timezone_set('Asia/Jakarta');
        $timer_end = date('Y-m-d H:i:s', strtotime("+$duration_minutes minutes"));

        $stmt = $conn->prepare("INSERT INTO temporary_changes (hari, jam, ruangan, kelas, mata_kuliah, sks, prodi, duration_minutes, timer_end, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssisiss", $hari, $jam, $ruangan, $kelas, $mata_kuliah, $sks, $prodi, $duration_minutes, $timer_end, $created_by);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan perubahan sementara");
        }
        $stmt->close();
    } else {
        // Ambil dosen & kode matkul dari jadwal lama (apa pun harinya)
        $stmt_fetch = $conn->prepare("SELECT dosen, kode_matkul FROM jadwal_matkul WHERE kelas = ? LIMIT 1");
        $stmt_fetch->bind_param("s", $kelas);
        $stmt_fetch->execute();
        $result = $stmt_fetch->get_result();
        $row = $result->fetch_assoc();
        $stmt_fetch->close();

        $dosen = $row['dosen'] ?? 'TBA';
        $kode_matkul = $row['kode_matkul'] ?? 'TEMP' . time();

        // Hapus semua jadwal lama untuk kelas ini, kecuali yg identik dg yg akan dimasukkan
        $stmt_hapus = $conn->prepare("DELETE FROM jadwal_matkul WHERE kelas = ? AND NOT (hari = ? AND jam = ? AND ruangan = ?)");
        $stmt_hapus->bind_param("ssss", $kelas, $hari, $jam, $ruangan);

        if (!$stmt_hapus->execute()) {
            throw new Exception("Gagal menghapus jadwal lama");
        }
        $stmt_hapus->close();

        // Masukkan jadwal baru
        $stmt = $conn->prepare("INSERT INTO jadwal_matkul (hari, jam, kode_matkul, mata_kuliah, sks, dosen, kelas, ruangan, prodi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssissss", $hari, $jam, $kode_matkul, $mata_kuliah, $sks, $dosen, $kelas, $ruangan, $prodi);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan perubahan permanen");
        }
        $stmt->close();
    }

    mysqli_commit($conn);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
