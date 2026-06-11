<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

$id = $_GET['id'];

// Mengubah status pelanggaran menjadi 'selesai' setelah dikonfirmasi admin secara offline
$query = "UPDATE denda SET status_denda = 'selesai' WHERE id_denda = '$id'";

if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Sanksi akun member selesai dan diputihkan!'); window.location='index.php';</script>";
}
?>