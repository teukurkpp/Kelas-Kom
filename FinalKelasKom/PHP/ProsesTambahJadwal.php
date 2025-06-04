<?php
session_start();
include 'DbKelasKom.php';

// Pastikan user sudah login dan merupakan Admin
if (!isset($_SESSION['username'])) {
    header("Location: LoginUser.php");
    exit;
}

$username = $_SESSION['username'];
$query_admin = mysqli_query($conn, "SELECT username FROM admin WHERE username = '$username'");
if (mysqli_num_rows($query_admin) == 0) {
    header("Location: PilihHari.php");
    exit;
}

// Proses tambah data jika form disubmit via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan sanitasi input dari form
    $hari         = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam          = mysqli_real_escape_string($conn, $_POST['jam']);
    $mata_kuliah  = mysqli_real_escape_string($conn, $_POST['mata_kuliah']);
    $sks          = (int) $_POST['sks'];
    $dosen        = mysqli_real_escape_string($conn, $_POST['dosen']);
    $kelas        = mysqli_real_escape_string($conn, $_POST['kelas']);
    $ruangan      = mysqli_real_escape_string($conn, $_POST['ruangan']);
    $prodi        = mysqli_real_escape_string($conn, $_POST['prodi']);
    $kode_matkul  = 'KM-' . time(); // Atau kamu bisa sesuaikan sendiri

    // Validasi sederhana
    if (empty($hari) || empty($jam) || empty($mata_kuliah) || empty($sks) || empty($dosen) || empty($kelas) || empty($ruangan) || empty($prodi)) {
        echo "<script>alert('Semua field wajib diisi!'); history.back();</script>";
        exit;
    }

    // Cek apakah jadwal untuk kelas yang sama dan waktu yang sama sudah ada
    $cek_duplikat = mysqli_query($conn, "SELECT * FROM jadwal_matkul WHERE hari='$hari' AND jam='$jam' AND kelas='$kelas'");
    if (mysqli_num_rows($cek_duplikat) > 0) {
        echo "<script>alert('Jadwal untuk kelas ini pada waktu tersebut sudah ada!'); history.back();</script>";
        exit;
    }

    // Simpan data ke database
    $query = "INSERT INTO jadwal_matkul (hari, jam, kode_matkul, mata_kuliah, sks, dosen, kelas, ruangan, prodi)
              VALUES ('$hari', '$jam', '$kode_matkul', '$mata_kuliah', $sks, '$dosen', '$kelas', '$ruangan', '$prodi')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Jadwal berhasil ditambahkan!'); window.location.href='TampilanJadwalKelas.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan jadwal!'); history.back();</script>";
    }

} else {
    header("Location: TambahJadwalKelas.php");
    exit;
}
?>
