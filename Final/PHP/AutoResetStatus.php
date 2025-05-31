<?php
include 'DbKelasKom.php';
date_default_timezone_set("Asia/Jakarta");

$now = date('Y-m-d H:i:s');

$query = "SELECT * FROM room_status WHERE status = 'online' AND timer_end <= '$now'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $ruangan = $row['ruangan'];
    $hari = $row['hari'];
    $jam = $row['jam'];

    $update = mysqli_query($conn, "
        UPDATE room_status 
        SET status = 'offline', timer_end = NULL, updated_at = NOW() 
        WHERE ruangan = '$ruangan' AND hari = '$hari' AND jam = '$jam'
    ");
}

mysqli_close($conn);
