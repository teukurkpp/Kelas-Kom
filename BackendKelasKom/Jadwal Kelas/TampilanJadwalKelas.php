<?php include 'DbJadwalKelas.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>KelasKom - Jadwal Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/JadwalKelas.css">
</head>

<body>
  <header>
    <div class="logo-section">
      <img src="../PIC/logo.png" alt="Logo Fasilkom" />
      <div class="logo-text">
        <span>KelasKom</span>
        <span>Fakultas Ilmu Komputer</span>
      </div>
    </div>

    <div class="user-dropdown">
      <input type="checkbox" id="toggle-dropdown">
      <label for="toggle-dropdown" class="user-label">
        <div class="user-label-content">
          <div class="user-info">
            <strong>FIRYAL KHOIRUNNISA ZULFA</strong><br />
            Mahasiswa (SI)
          </div>
          <span class="arrow">&#9662;</span>
        </div>
      </label>

      <div class="dropdown-content">
        <div class="dropdown-header">Selamat Datang!</div>
        <a href="#">📅 Jadwal</a>
        <a href="#">↩️ Keluar</a>
      </div>
    </div>
  </header>

  <!-- ⬇️ Ini bagian konten utama -->
  <div class="container mt-5">
    <h2 class="mb-4">Data Jadwal Perkuliahan</h2>
    <a href="TambahJadwalKelas.php" class="btn btn-primary mb-3">+ Tambah Data</a>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Hari</th>
          <th>Jam</th>
          <th>Kode Matkul</th>
          <th>Mata Kuliah</th>
          <th>SKS</th>
          <th>Dosen</th>
          <th>Kelas</th>
          <th>Ruangan</th>
          <th>Prodi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $result = mysqli_query($conn, "SELECT * FROM jadwal_matkul");
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
                  <td>$no</td>
                  <td>{$row['hari']}</td>
                  <td>{$row['jam']}</td>
                  <td>{$row['kode_matkul']}</td>
                  <td>{$row['mata_kuliah']}</td>
                  <td>{$row['sks']}</td>
                  <td>{$row['dosen']}</td>
                  <td>{$row['kelas']}</td>
                  <td>{$row['ruangan']}</td>
                  <td>{$row['prodi']}</td>
                  <td>
                    <a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='hapus.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                  </td>
                </tr>";
          $no++;
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>