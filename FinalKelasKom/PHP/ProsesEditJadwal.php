<?php
session_start();
include 'DbKelasKom.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $hari = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam = mysqli_real_escape_string($conn, $_POST['jam']);
    $mata_kuliah = mysqli_real_escape_string($conn, $_POST['mata_kuliah']);
    $sks = mysqli_real_escape_string($conn, $_POST['sks']);
    $dosen = mysqli_real_escape_string($conn, $_POST['dosen']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $ruangan = mysqli_real_escape_string($conn, $_POST['ruangan']);
    $prodi = mysqli_real_escape_string($conn, $_POST['prodi']);

    $query = "UPDATE jadwal_matkul SET 
        hari = '$hari',
        jam = '$jam',
        mata_kuliah = '$mata_kuliah',
        sks = '$sks',
        dosen = '$dosen',
        kelas = '$kelas',
        ruangan = '$ruangan',
        prodi = '$prodi'
        WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Jadwal berhasil diperbarui'); window.location.href='TampilanJadwalKelas.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui jadwal'); history.back();</script>";
    }
} else {
    header("Location: TampilanJadwalKelas.php");
    exit;
}
?>
