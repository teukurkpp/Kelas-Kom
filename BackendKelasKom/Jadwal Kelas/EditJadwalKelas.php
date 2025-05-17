<?php include "DbJadwalKelas.php" ; ?>

<?php
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM jadwal_matkul WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);


?>



<!DOCTYPE html>
<html>

<head>
    <title>KelasKom - Edit Jadwal Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2 class="mb-4">Edit Data Jadwal Perkuliahan</h2>
    <form method="POST">
  <div class="row">
    <div class="col-md-6 mb-3">
      <label>No</label>
      <input type="text" name="nomor" value="<?= $row['nomor'] ?>" class="form-control" required>
    </div>
    
    <div class="col-md-6 mb-3">
      <label>Hari</label>
      <input type="text" name="hari" value="<?= $row['hari'] ?>" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
      <label>Jam</label>
      <input type="text" name="jam" value="<?= $row['jam'] ?>" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Kode Matkul</label>
      <input type="text" name="kode_matkul" value="<?= $row['kode_matkul'] ?>" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
      <label>Mata Kuliah</label>
      <input type="text" name="mata_kuliah" value="<?= $row['mata_kuliah'] ?>" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>SKS</label>
      <input type="text" name="sks" value="<?= $row['sks'] ?>" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
      <label>Dosen</label>
      <input type="text" name="dosen" value="<?= $row['dosen'] ?>" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Kelas</label>
      <input type="text" name="kelas" value="<?= $row['kelas'] ?>" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
      <label>Ruangan</label>
      <input type="text" name="ruangan" value="<?= $row['ruangan'] ?>" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Prodi</label>
      <input type="text" name="prodi" value="<?= $row['prodi'] ?>" class="form-control" required>
    </div>
  </div>

  <button type="submit" name="update" class="btn btn-primary">Update</button>
  <a href="index.php" class="btn btn-secondary">Kembali</a>
</form>


    <?php
        if (isset($_POST['update'])) {
            $hari = $_POST['hari'];
            $jam = $_POST['jam'];
            $kode_matkul = $_POST['kode_matkul'];
            $mata_kuliah = $_POST['mata_kuliah'];
            $sks = $_POST['sks'];
            $dosen = $_POST['dosen'];
            $kelas = $_POST['kelas'];
            $ruangan = $_POST['ruangan'];
            $prodi = $_POST['prodi'];

            mysqli_query($conn, "UPDATE jadwal_matkul SET hari = '$hari', jam = '$jam', kode_matkul = '$kode_matkul', mata_kuliah = '$mata_kuliah', sks = '$sks', dosen = '$dosen', kelas = '$kelas', ruangan = '$ruangan', prodi = '$prodi'  WHERE  id = '$id'");
            echo "Data berhasil dirubah";
        }
    
    ?>


</body>

</html>