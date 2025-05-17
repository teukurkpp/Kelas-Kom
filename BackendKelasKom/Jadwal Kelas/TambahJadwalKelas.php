<?php include 'DbJadwalKelas.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>KelasKom - Tambah Jadwal Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2 class="mb-4">Jadwal Mata Kuliah Fasilkom Unsika</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Hari</label>
            <input type="text" name="hari" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jam</label>
            <input type="text" name="jam" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kode Matkul</label>
            <input type="text" name="kode_matkul" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mata Kuliah</label>
            <input type="text" name="mata_kuliah" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>SKS</label>
            <input type="text" name="sks" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Dosen</label>
            <input type="text" name="dosen" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kelas</label>
            <input type="text" name="kelas" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ruangan</label>
            <input type="text" name="ruangan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Prodi</label>
            <input type="text" name="prodi" class="form-control" required>
        </div>


        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>

        <a href="TampilanJadwalKelas.php" class="btn btn-secondary">Kembali</a>

    </form>

    <?php
        if (isset($_POST['simpan'])) {
            $hari = $_POST['hari'];
            $jam = $_POST['jam'];
            $kode_matkul = $_POST['kode_matkul'];
            $mata_kuliah = $_POST['mata_kuliah'];
            $sks = $_POST['sks'];
            $dosen = $_POST['dosen'];
            $kelas = $_POST['kelas'];
            $ruangan = $_POST['ruangan'];
            $prodi = $_POST['prodi'];


            mysqli_query($conn, "INSERT INTO jadwal_matkul (hari, jam, kode_matkul, mata_kuliah, sks, dosen, kelas, ruangan, prodi) VALUES ('$hari', '$jam', '$kode_matkul', '$mata_kuliah', '$sks', '$dosen', '$kelas', '$ruangan', '$prodi') ");
            echo "Data berhasil ditambahkan";
        }
    
    ?>

</body>

</html>