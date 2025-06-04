<?php
session_start();
include 'DbKelasKom.php';

header('Content-Type: application/json');

// Validasi sesi PJ Kelas
if (!isset($_SESSION['npm'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$npm = mysqli_real_escape_string($conn, $_SESSION['npm']);
$query_pj = mysqli_query($conn, "SELECT npm FROM pj_kelas WHERE npm = '$npm'");
if (!mysqli_fetch_assoc($query_pj)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Ambil dan sanitasi input POST
$hari = isset($_POST['hari']) ? mysqli_real_escape_string($conn, trim($_POST['hari'])) : '';
$jam = isset($_POST['jam']) ? mysqli_real_escape_string($conn, trim($_POST['jam'])) : '';
$ruangan = isset($_POST['ruangan']) ? mysqli_real_escape_string($conn, trim($_POST['ruangan'])) : '';
$created_by = isset($_POST['created_by']) ? mysqli_real_escape_string($conn, trim($_POST['created_by'])) : '';

// Ubah durasi jadi integer
$duration_minutes = isset($_POST['duration_minutes']) ? intval($_POST['duration_minutes']) : 0;

// Validasi input
if (empty($hari) || empty($jam) || empty($ruangan) || $duration_minutes < 30 || empty($created_by)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap atau durasi kurang dari 30 menit']);
    exit;
}

// Cek apakah ruangan ada di jadwal
$query_validate = mysqli_query($conn, "SELECT COUNT(*) as count FROM jadwal_matkul WHERE hari = '$hari' AND jam = '$jam' AND ruangan = '$ruangan'");
$validation = mysqli_fetch_assoc($query_validate);
if ($validation['count'] == 0) {
    echo json_encode(['success' => false, 'message' => 'Ruangan tidak memiliki jadwal pada waktu ini']);
    exit;
}

// Cek apakah sudah online
$query_status = mysqli_query($conn, "SELECT status FROM room_status WHERE hari = '$hari' AND jam = '$jam' AND ruangan = '$ruangan'");
$status_data = mysqli_fetch_assoc($query_status);
if ($status_data && $status_data['status'] === 'online') {
    echo json_encode(['success' => false, 'message' => 'Ruangan ini sudah Online']);
    exit;
}

// Hitung timer_end dengan timezone yang tepat
date_default_timezone_set("Asia/Jakarta");
$now = new DateTime();
$now->modify("+$duration_minutes minutes");
$timer_end = $now->format('Y-m-d H:i:s');

// Lakukan query insert/update
mysqli_begin_transaction($conn);

try {
    $stmt = $conn->prepare("
        INSERT INTO room_status (ruangan, hari, jam, status, timer_end, updated_by)
        VALUES (?, ?, ?, 'online', ?, ?)
        ON DUPLICATE KEY UPDATE 
            status = 'online', 
            timer_end = VALUES(timer_end), 
            updated_by = VALUES(updated_by), 
            updated_at = NOW()
    ");
    $stmt->bind_param("sssss", $ruangan, $hari, $jam, $timer_end, $created_by);

    if (!$stmt->execute()) {
        throw new Exception("Query gagal: " . $stmt->error);
    }

    mysqli_commit($conn);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
