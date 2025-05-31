<?php
session_start();
include 'DbKelasKom.php';

header('Content-Type: application/json');

if (!isset($_SESSION['npm'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$npm = mysqli_real_escape_string($conn, $_SESSION['npm']);
$query_pj = mysqli_query($conn, "SELECT npm FROM pj_kelas WHERE npm = '$npm'");
if (!$data = mysqli_fetch_assoc($query_pj)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$hari = isset($_POST['hari']) ? mysqli_real_escape_string($conn, $_POST['hari']) : '';
$jam = isset($_POST['jam']) ? mysqli_real_escape_string($conn, $_POST['jam']) : '';
$ruangan = isset($_POST['ruangan']) ? mysqli_real_escape_string($conn, $_POST['ruangan']) : '';
$duration_minutes = isset($_POST['duration_minutes']) ? (int)$_POST['duration_minutes'] : 0;
$created_by = mysqli_real_escape_string($conn, $_POST['created_by']);

if (empty($hari) || empty($jam) || empty($ruangan) || $duration_minutes < 30) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap atau durasi tidak valid']);
    exit;
}

$timer_end = date('Y-m-d H:i:s', strtotime("+$duration_minutes minutes"));

mysqli_begin_transaction($conn);

try {
    $stmt = $conn->prepare("INSERT INTO room_status (ruangan, hari, jam, status, timer_end, updated_by) VALUES (?, ?, ?, 'online', ?, ?) ON DUPLICATE KEY UPDATE status = 'online', timer_end = ?, updated_by = ?, updated_at = NOW()");
    $stmt->bind_param("sssssss", $ruangan, $hari, $jam, $timer_end, $created_by, $timer_end, $created_by);
    if (!$stmt->execute()) {
        throw new Exception("Gagal mengubah status");
    }
    $stmt->close();

    mysqli_commit($conn);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>