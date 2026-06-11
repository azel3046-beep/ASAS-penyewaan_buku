<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

$id = $_GET['id'];
$id_buku = $_GET['id_buku'];

// Ubah status menjadi dibatalkan
$query = "UPDATE peminjaman SET status_buku = 'dibatalkan' WHERE id_peminjaman = '$id'";

if (mysqli_query($koneksi, $query)) {
    // Kembalikan stok buku yang sempat dikurangi saat dibooking
    mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");
    echo "<script>alert('Booking berhasil dibatalkan. Stok buku dikembalikan.'); window.location='index.php';</script>";
}
?>