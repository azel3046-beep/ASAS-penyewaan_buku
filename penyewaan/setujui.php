<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

$id = $_GET['id'];
$tgl_sewa = date('Y-m-d');
$tgl_kembali_seharusnya = date('Y-m-d', strtotime('+7 days')); // Durasi pinjam online default 7 hari

// Ubah status dari dipesan menjadi dipinjam saat mengambil buku fisik
$query = "UPDATE peminjaman SET 
          tgl_sewa = '$tgl_sewa', 
          tgl_kembali_seharusnya = '$tgl_kembali_seharusnya', 
          status_buku = 'dipinjam' 
          WHERE id_peminjaman = '$id'";

if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Buku berhasil diserahkan ke member. Status aktif dipinjam!'); window.location='index.php';</script>";
}
?>