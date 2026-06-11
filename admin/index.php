<?php
include '../koneksi.php';
session_start();

// Proteksi halaman: Kalau bukan admin, tendang ke login admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

// Mengambil data statistik untuk dashboard
$hitung_buku   = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
$total_buku    = mysqli_fetch_assoc($hitung_buku)['total'];

$hitung_member = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='member'");
$total_member  = mysqli_fetch_assoc($hitung_member)['total'];

$hitung_sewa   = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM peminjaman WHERE status_buku='dipesan' OR status_buku='dipinjam'");
$total_sewa    = mysqli_fetch_assoc($hitung_sewa)['total'];

$hitung_denda  = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM denda WHERE status_denda='belum_kembali'");
$total_denda   = mysqli_fetch_assoc($hitung_denda)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Perpus Online</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #94abce; margin: 0; padding: 20px; }
        .navbar { background: #343a40; padding: 15px; color: white; display: flex; justify-content: space-between; border-radius: 5px; margin-bottom: 30px; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        
        h2 { color: #333; }
        .welcome-msg { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; border-left: 5px solid #007bff; }
        
        /* Grid Statistik */
        .dashboard-grid { display: flex; gap: 20px; flex-wrap: wrap; }
        .stat-card { flex: 1; min-width: 220px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); text-align: center; color: white; }
        
        /* Warna-warni Card Statistik */
        .card-buku { background: #007bff; }
        .card-member { background: #28a745; }
        .card-sewa { background: #17a2b8; }
        .card-denda { background: #dc3545; }
        
        .stat-card h3 { margin: 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
        .stat-card p { margin: 10px 0 0 0; font-size: 36px; font-weight: bold; }
        
        .quick-links { margin-top: 40px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .quick-links h4 { margin-top: 0; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .btn-link { display: inline-block; background: #343a40; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; font-weight: bold; font-size: 14px; }
        .btn-link:hover { background: #23272b; }
    </style>
</head>
<body>

    <div class="navbar">
        <div>Panel Utama: <b><?= $_SESSION['nama']; ?></b> (Admin)</div>
        <div>
            <a href="index.php">Dashboard</a>
            <a href="../buku/katalog.php">Kelola Buku</a>
            <a href="../penyewaan/index.php">Peminjaman Online</a>
            <a href="../users/index.php">Data Anggota</a>
            <a href="../denda/index.php">Data Denda</a>
            <a href="../logout.php" style="color: #ffc107;">Logout</a>
        </div>
    </div>

    <div class="welcome-msg">
        <h2 style="margin: 0 0 5px 0;">Selamat Datang Kembali, <?= $_SESSION['nama']; ?>!</h2>
        <p style="margin: 0; color: #666;">Melalui panel ini, kamu bisa menyetujui pengambilan buku, memantau denda keterlambatan, dan mengelola akun member.</p>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card card-buku">
            <h3>Total Koleksi Buku</h3>
            <p><?= $total_buku; ?></p>
        </div>
        <div class="stat-card card-member">
            <h3>Member Terdaftar</h3>
            <p><?= $total_member; ?></p>
        </div>
        <div class="stat-card card-sewa">
            <h3>Transaksi Aktif</h3>
            <p><?= $total_sewa; ?></p>
        </div>
        <div class="stat-card card-denda">
            <h3>Kasus Denda Telat</h3>
            <p><?= $total_denda; ?></p>
        </div>
    </div>

    <div class="quick-links">
        <h4>Akses Cepat Manajemen</h4>
        <a href="../buku/tambah.php" class="btn-link" style="background:#28a745;">+ Tambah Buku Baru</a>
        <a href="../penyewaan/index.php" class="btn-link">Cek Antrean Booking</a>
        <a href="../users/index.php" class="btn-link">Lihat Daftar Member</a>
        <a href="../denda/index.php" class="btn-link" style="background:#dc3545;">Cek Pelanggaran</a>
        <a href="../logout.php" style="color: #ffc107;">Logout</a>
    </div>

</body>
</html>