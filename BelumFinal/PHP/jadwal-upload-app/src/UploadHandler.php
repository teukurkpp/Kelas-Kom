<?php

namespace App;

use PhpOffice\PhpSpreadsheet\IOFactory;
use mysqli;

class UploadHandler
{
    private $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function validateFileType($file)
    {
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        return in_array($fileExtension, ['csv', 'xlsx', 'xls']);
    }

    public function processFile($fileTmpPath)
    {
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet = $spreadsheet->getActiveSheet();
        return $sheet->toArray();
    }

    public function insertData(array $rows)
    {
        $successCount = 0;
        $errorMessages = [];
        $isFirstRow = true;

        foreach ($rows as $row) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue; // Skip header row
            }

            $hari = mysqli_real_escape_string($this->conn, $row[0] ?? '');
            $jam = mysqli_real_escape_string($this->conn, $row[1] ?? '');
            $kode_matkul = mysqli_real_escape_string($this->conn, $row[2] ?? '');
            $mata_kuliah = mysqli_real_escape_string($this->conn, $row[3] ?? '');
            $sks = mysqli_real_escape_string($this->conn, $row[4] ?? '');
            $dosen = mysqli_real_escape_string($this->conn, $row[5] ?? '');
            $kelas = mysqli_real_escape_string($this->conn, $row[6] ?? '');
            $ruangan = mysqli_real_escape_string($this->conn, $row[7] ?? '');
            $prodi = mysqli_real_escape_string($this->conn, $row[8] ?? '');

            if (empty($hari) || empty($jam) || empty($kode_matkul) || empty($mata_kuliah) || 
                empty($sks) || empty($dosen) || empty($kelas) || empty($ruangan) || empty($prodi)) {
                $errorMessages[] = "Baris dengan kode matkul '$kode_matkul' memiliki data kosong.";
                continue;
            }

            if (!in_array($prodi, ['Sistem Informasi', 'Informatika'])) {
                $errorMessages[] = "Baris dengan kode matkul '$kode_matkul' memiliki prodi tidak valid: '$prodi'.";
                continue;
            }

            $query = "INSERT INTO jadwal_matkul (hari, jam, kode_matkul, mata_kuliah, sks, dosen, kelas, ruangan, prodi) 
                      VALUES ('$hari', '$jam', '$kode_matkul', '$mata_kuliah', '$sks', '$dosen', '$kelas', '$ruangan', '$prodi')";
            if (mysqli_query($this->conn, $query)) {
                $successCount++;
            } else {
                $errorMessages[] = "Gagal menyimpan baris dengan kode matkul '$kode_matkul': " . mysqli_error($this->conn);
            }
        }

        return ['successCount' => $successCount, 'errorMessages' => $errorMessages];
    }
}