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

$hari = isset($_POST['hari']) ? mysqli_real_escape_string($conn, $_POST['hari']) : '';
$jam = isset($_POST['jam']) ? mysqli_real_escape_string($conn, $_POST['jam']) : '';
$ruangan = isset($_POST['ruangan']) ? mysqli_real_escape_string($conn, $_POST['ruangan']) : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$kelas = isset($_POST['kelas']) ? mysqli_real_escape_string($conn, $_POST['kelas']) : '';
$mata_kuliah = isset($_POST['mata_kuliah']) ? mysqli_real_escape_string($conn, $_POST['mata_kuliah']) : '';
$sks = isset($_POST['sks']) ? (int)$_POST['sks'] : 0;
$duration_minutes = isset($_POST['duration_minutes']) ? (int)$_POST['duration_minutes'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$created_by = mysqli_real_escape_string($conn, $_POST['created_by']);

if (empty($hari) || empty($jam) || empty($ruangan) || empty($kelas) || empty($mata_kuliah) || empty($sks) || empty($action)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$prodi = explode(' - ', $kelas)[1] ?? '';
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
        $timer_end = date('Y-m-d H:i:s', strtotime("+$duration_minutes minutes"));
        $stmt = $conn->prepare("INSERT INTO temporary_changes (hari, jam, ruangan, kelas, mata_kuliah, sks, prodi, duration_minutes, timer_end, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssisiss", $hari, $jam, $ruangan, $kelas, $mata_kuliah, $sks, $prodi, $duration_minutes, $timer_end, $created_by);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan perubahan sementara");
        }
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO jadwal_matkul (hari, jam, kode_matkul, mata_kuliah, sks, dosen, kelas, ruangan, prodi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $kode_matkul = 'TEMP' . time();
        $dosen = 'TBA';
        $stmt->bind_param("ssssissss", $hari, $jam, $kode_matkul, $mata_kuliah, $sks, $dosen, $kelas, $ruangan, $prodi);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan perubahan permanen");
        }
        $stmt->close();

        $class_name = explode(' - ', $kelas)[0];
        $stmt = $conn->prepare("DELETE FROM jadwal_matkul WHERE kelas = ? AND hari != ? AND jam = ?");
        $stmt->bind_param("sss", $kelas, $hari, $jam);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menghapus jadwal lain");
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
?>