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

// Restrict access to Admin only
if ($userType !== 'Admin') {
    header("Location: PilihHari.php");
    exit;
}

// Proceed with delete logic
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
if ($id) {
    $stmt = $conn->prepare("DELETE FROM jadwal_matkul WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Jadwal berhasil dihapus.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menghapus jadwal.'];
    }
    $stmt->close();
}
header("Location: TampilanJadwalKelas.php");
exit;
?>