<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login/loginuser.php");
    exit;
}

$id_buku = $_GET['id_buku'];
$id_user = $_SESSION['id_user'];
$tgl_booking = date('Y-m-d');

// Cek apakah stok masih ada
$cek_buku = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id_buku = '$id_buku'");
$data_buku = mysqli_fetch_assoc($cek_buku);

if ($data_buku['stok'] > 0) {
    // 1. Masukkan data ke tabel peminjaman dengan status 'dipesan'
    $query_pinjam = "INSERT INTO peminjaman (id_user, id_buku, tgl_booking, status_buku) VALUES ('$id_user', '$id_buku', '$tgl_booking', 'dipesan')";
    
    if (mysqli_query($koneksi, $query_pinjam)) {
        // 2. Kurangi stok buku
        mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'");
        echo "<script>alert('Booking berhasil! Silakan ambil buku fisik ke perpustakaan maksimal dalam waktu 2 hari.'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Maaf, stok buku mendadak habis!'); window.location='../buku/katalog.php';</script>";
}
?>