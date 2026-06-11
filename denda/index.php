<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

// Menampilkan data pelanggaran member yang telat mengembalikan buku
$query = "SELECT denda.*, users.nama_lengkap, buku.judul_buku, peminjaman.tgl_kembali_seharusnya, peminjaman.tgl_kembali_aktual 
          FROM denda 
          JOIN peminjaman ON denda.id_peminjaman = peminjaman.id_peminjaman
          JOIN users ON peminjaman.id_user = users.id_user
          JOIN buku ON peminjaman.id_buku = buku.id_buku ORDER BY id_denda DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sanksi Keterlambatan (Denda)</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .navbar { background: #343a40; padding: 15px; color: white; display: flex; justify-content: space-between; border-radius: 5px; margin-bottom: 30px; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #dc3545; color: white; }
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge.belum { background: #fff3cd; color: #856404; }
        .badge.selesai { background: #d4edda; color: #155724; }
        .btn-lunas { background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>Panel Admin: <b><?= $_SESSION['nama']; ?></b></div>
        <div>
            <a href="../admin/index.php">Dashboard</a>
            <a href="../penyewaan/index.php">Peminjaman</a>
            <a href="index.php">Sanksi/Denda</a>
            <a href="../logout.php" style="color: #ffc107;">Logout</a>
        </div>
    </div>

    <h2 style="color: #333;">Daftar Sanksi Keterlambatan Member</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggar</th>
                <th>Buku</th>
                <th>Tenggat Selesai</th>
                <th>Tanggal Balik</th>
                <th>Total Keterlambatan</th>
                <th>Status Kasus</th>
                <th>Aksi Admin</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_lengkap']; ?></td>
                    <td><?= $row['judul_buku']; ?></td>
                    <td><?= $row['tgl_kembali_seharusnya']; ?></td>
                    <td><?= $row['tgl_kembali_aktual']; ?></td>
                    <td style="color: red; font-weight: bold;"><?= $row['jumlah_hari_terlambat']; ?> Hari</td>
                    <td>
                        <span class="badge <?= $row['status_denda'] === 'belum_kembali' ? 'belum' : 'selesai'; ?>">
                            <?= $row['status_denda'] === 'belum_kembali' ? 'Belum Selesai' : 'Selesai Sanksi'; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['status_denda'] === 'belum_kembali') : ?>
                            <a href="lunas.php?id=<?= $row['id_denda']; ?>" class="btn-lunas" onclick="return confirm('Selesaikan sanksi pelanggaran akun ini?')">Selesaikan</a>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>