<?php
$conn = mysqli_connect("localhost", "root", "", "kelaskom");

if (!$conn) {
    die("konseki gagal: " . mysqli_connect_error());
}

?>