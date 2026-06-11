<?php
include '../koneksi.php';
session_start();

// Proteksi halaman: Kalau belum login, tendang ke halaman login user
if (!isset($_SESSION['login'])) {
    header("Location: ../login/loginuser.php");
    exit;
}

$role = $_SESSION['role']; // Mengambil role yang sedang login ('admin' atau 'member')

// Ambil semua data buku dari database
$result = mysqli_query($koneksi, "SELECT * FROM buku");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Buku Online</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #94abce; margin: 0; padding: 20px; }
        .navbar { background: #343a40; padding: 15px; color: white; display: flex; justify-content: space-between; border-radius: 5px; margin-bottom: 30px; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        
        /* Tombol Tambah Buku Khusus Admin */
        .btn-tambah { display: inline-block; background: #28a745; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-bottom: 20px; }
        .btn-tambah:hover { background: #218838; }

        .katalog-container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .card { background: white; width: 220px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 15px; }
        .card img { width: 100%; height: 280px; object-fit: cover; border-radius: 5px; cursor: pointer; }
        .card h3 { font-size: 16px; margin: 10px 0 5px 0; color: #333; }
        .card p { font-size: 14px; color: #777; margin: 0 0 10px 0; }
        .stok { font-weight: bold; color: #28a745; margin-bottom: 15px; }
        .stok.habis { color: red; }
        
        /* Tombol Aksi */
        .btn-pinjam { display: block; text-align: center; background: #007bff; color: white; padding: 8px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-pinjam:hover { background: #0056b3; }
        .btn-disabled { display: block; text-align: center; background: #ccc; color: #666; padding: 8px; border-radius: 5px; text-decoration: none; font-size: 14px; pointer-events: none; }
        
        /* Grid Aksi Admin */
        .admin-actions { display: flex; gap: 10px; margin-top: 10px; border-top: 1px dashed #ddd; padding-top: 10px; }
        .btn-edit { flex: 1; text-align: center; background: #ffc107; color: #333; padding: 6px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; }
        .btn-edit:hover { background: #e0a800; }
        .btn-hapus { flex: 1; text-align: center; background: #dc3545; color: white; padding: 6px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; }
        .btn-hapus:hover { background: #c82333; }
    </style>
</head>
<body>

    <div class="navbar">
        <div>Halo, <b><?= $_SESSION['nama']; ?></b> (<?= ucfirst($role); ?>)</div>
        <div>
            <?php if ($role === 'admin') : ?>
                <a href="../admin/index.php">Dashboard Admin</a>
                <a href="../penyewaan/index.php">Data Peminjaman</a>
                <a href="../users/index.php">Data Anggota</a>
            <?php else : ?>
                <a href="katalog.php">Katalog Buku</a>
                <a href="../penyewaan/index.php">Riwayat Pinjam</a>
            <?php endif; ?>
            <a href="../logout.php" style="color: #ffc107;">Logout</a>
        </div>
    </div>

    <h2 style="text-align: center; color: #333; margin-bottom: 10px;">Katalog Buku Online</h2>
    
    <?php if ($role === 'admin') : ?>
        <div style="text-align: center;">
            <a href="tambah.php" class="btn-tambah">+ Tambah Koleksi Buku Baru</a>
        </div>
    <?php endif; ?>

    <div class="katalog-container">
        <?php while ($buku = mysqli_fetch_assoc($result)) : ?>
            <div class="card">
                <div>
                    <a href="detail.php?id=<?= $buku['id_buku']; ?>">
                        <img src="../gambar/cover/<?= $buku['foto_cover']; ?>" alt="Cover">
                    </a>
                    <h3><?= $buku['judul_buku']; ?></h3>
                    <p>Oleh: <?= $buku['pengarang']; ?></p>
                </div>
                <div>
                    <?php if ($buku['stok'] > 0) : ?>
                        <div class="stok">Stok: <?= $buku['stok']; ?> Tersedia</div>
                        <?php if ($role === 'member') : ?>
                            <a href="../penyewaan/booking.php?id_buku=<?= $buku['id_buku']; ?>" class="btn-pinjam" onclick="return confirm('Booking buku ini sekarang secara online?')">Booking Buku</a>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="stok habis">Stok Habis</div>
                        <?php if ($role === 'member') : ?>
                            <a href="#" class="btn-disabled">Tidak Tersedia</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($role === 'admin') : ?>
                        <div class="admin-actions">
                            <a href="ubah.php?id=<?= $buku['id_buku']; ?>" class="btn-edit">Edit</a>
                            <a href="hapus.php?id=<?= $buku['id_buku']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
                            <a href="../login/logout.php" style="color: #ffc107;">Logout</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>