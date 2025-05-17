<?php
include 'DbJadwalKelas.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM jadwal_matkul WHERE id = $id");
header("Location: TampilanJadwalKelas.php");
?>