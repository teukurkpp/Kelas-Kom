<?php
$conn = mysqli_connect("localhost", "root", "", "KelasKom");

if (!$conn) {
    die("konseki gagal: " . mysqli_connect_error());
}

?>