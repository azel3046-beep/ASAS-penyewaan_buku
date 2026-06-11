<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login/loginuser.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

// Jika Admin, bisa lihat semua data peminjaman online. Jika member, hanya melihat miliknya sendiri.
if ($role === 'admin') {
    $query = "SELECT peminjaman.*, users.nama_lengkap, buku.judul_buku FROM peminjaman 
              JOIN users ON peminjaman.id_user = users.id_user 
              JOIN buku ON peminjaman.id_buku = buku.id_buku ORDER BY id_peminjaman DESC";
} else {
    $query = "SELECT peminjaman.*, users.nama_lengkap, buku.judul_buku FROM peminjaman 
              JOIN users ON peminjaman.id_user = users.id_user 
              JOIN buku ON peminjaman.id_buku = buku.id_buku WHERE peminjaman.id_user = '$id_user' ORDER BY id_peminjaman DESC";
}

$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sirkulasi Peminjaman Buku</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #94ABCE; margin: 0; padding: 20px; }
        .navbar { background: #343a40; padding: 15px; color: white; display: flex; justify-content: space-between; border-radius: 5px; margin-bottom: 30px; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.05); margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #343A40; color: white; }
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; display: inline-block; }
        .badge.dipesan { background: #ffc107; color: #333; }
        .badge.dipinjam { background: #17a2b8; color: white; }
        .badge.dikembalikan { background: #28a745; color: white; }
        .badge.dibatalkan { background: #dc3545; color: white; }
        .action-btn { padding: 5px 10px; color: white; text-decoration: none; border-radius: 3px; font-size: 13px; margin-right: 5px; }
        .btn-setuju { background: #28a745; }
        .btn-batal { background: #dc3545; }
        .btn-kembali { background: #007bff; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>Login sebagai: <b><?= $_SESSION['nama']; ?> (<?= ucfirst($role); ?>)</b></div>
        <div>
            <?php if ($role === 'admin') : ?>
                <a href="../admin/index.php">Dashboard Admin</a>
                <a href="index.php">Data Peminjaman</a>
                <a href="../users/index.php">Data Anggota</a>
            <?php else : ?>
                <a href="../buku/katalog.php">Katalog Buku</a>
                <a href="index.php">Riwayat Pinjam</a>
            <?php endif; ?>
            <a href="../logout.php" style="color: #ffc107;">Logout</a>
        </div>
    </div>

    <h2>Data Antrean & Riwayat Peminjaman Buku Online</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penyewa</th>
                <th>Judul Buku</th>
                <th>Tgl Booking</th>
                <th>Tgl Pinjam </th>
                <th>Tgl Dikembalikan</th>
                <th>Status</th>
                <?php if ($role === 'admin') : ?><th>Aksi Admin</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_lengkap']; ?></td>
                    <td><?= $row['judul_buku']; ?></td>
                    <td><?= $row['tgl_booking']; ?></td>
                    <td><?= $row['tgl_sewa'] ?? '-'; ?></td>
                    <td><?= $row['tgl_kembali_aktual'] ?? '-'; ?></td>
                    <td><span class="badge <?= $row['status_buku']; ?>"><?= $row['status_buku']; ?></span></td>
                    <?php if ($role === 'admin') : ?>
                        <td>
                            <?php if ($row['status_buku'] === 'dipesan') : ?>
                                <a href="setujui.php?id=<?= $row['id_peminjaman']; ?>" class="action-btn btn-setuju">Setujui Ambil</a>
                                <a href="batalkan.php?id=<?= $row['id_peminjaman']; ?>&id_buku=<?= $row['id_buku']; ?>" class="action-btn btn-batal" onclick="return confirm('Batalkan booking ini?')">Batalkan</a>
                            <?php elseif ($row['status_buku'] === 'dipinjam') : ?>
                                <a href="kembali.php?id=<?= $row['id_peminjaman']; ?>&id_buku=<?= $row['id_buku']; ?>&tgl_harus=<?= $row['tgl_kembali_seharusnya']; ?>" class="action-btn btn-kembali" onclick="return confirm('Konfirmasi pengembalian buku fisik?')">Selesai/Kembali</a>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html><?php
include '../koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../login/loginuser.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

// Jika Admin, bisa lihat semua data peminjaman online. Jika member, hanya melihat miliknya sendiri.
if ($role === 'admin') {
    $query = "SELECT peminjaman.*, users.nama_lengkap, buku.judul_buku FROM peminjaman 
              JOIN users ON peminjaman.id_user = users.id_user 
              JOIN buku ON peminjaman.id_buku = buku.id_buku ORDER BY id_peminjaman DESC";
} else {
    $query = "SELECT peminjaman.*, users.nama_lengkap, buku.judul_buku FROM peminjaman 
              JOIN users ON peminjaman.id_user = users.id_user 
              JOIN buku ON peminjaman.id_buku = buku.id_buku WHERE peminjaman.id_user = '$id_user' ORDER BY id_peminjaman DESC";
}

$result = mysqli_query($koneksi, $query);