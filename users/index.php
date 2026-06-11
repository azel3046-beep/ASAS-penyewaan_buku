<?php
include '../koneksi.php';
session_start();

// Proteksi halaman: Hanya Admin yang boleh masuk ke modul kelola user
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

// Ambil semua data user yang rolenya 'member'
$result = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'member' ORDER BY id_user DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Anggota (Member)</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .navbar { background: #343a40; padding: 15px; color: white; display: flex; justify-content: space-between; border-radius: 5px; margin-bottom: 30px; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        h2 { color: #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .btn-hapus { background: #dc3545; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; }
        .btn-hapus:hover { background: #c82333; }
        .no-data { text-align: center; color: #888; font-style: italic; padding: 20px; }
    </style>
</head>
<body>

    <div class="navbar">
        <div>Panel Admin: <b><?= $_SESSION['nama']; ?></b></div>
        <div>
            <a href="../admin/index.php">Dashboard</a>
            <a href="../penyewaan/index.php">Peminjaman</a>
            <a href="index.php">Data Anggota</a>
            <a href="../logout.php" style="color: #ffc107;">Logout</a>
        </div>
    </div>

    <h2>Daftar Anggota / Member Terdaftar</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>ID User</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th style="width: 100px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><b>#MBR-<?= $row['id_user']; ?></b></td>
                        <td><?= $row['nama_lengkap']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['no_telp'] ?? '-'; ?></td>
                        <td style="text-align: center;">
                            <a href="hapus.php?id=<?= $row['id_user']; ?>" class="btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus member <?= $row['nama_lengkap']; ?>? Semua riwayat pinjamannya juga akan ikut terhapus!')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="no-data">Belum ada member yang melakukan pendaftaran online.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>