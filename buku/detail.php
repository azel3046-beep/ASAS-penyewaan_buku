<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login/loginuser.php");
    exit;
}

$id_buku = $_GET['id'];
$result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
$buku = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Buku - <?= $buku['judul_buku']; ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .detail-box { background: white; max-width: 700px; margin: 40px auto; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; gap: 30px; }
        .detail-image img { width: 220px; height: 320px; object-fit: cover; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .detail-info { flex: 1; }
        .detail-info h2 { margin-top: 0; color: #333; font-size: 24px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .detail-info table { width: 100%; margin-top: 15px; border-collapse: collapse; }
        .detail-info table td { padding: 8px 0; font-size: 15px; color: #555; }
        .detail-info table td.label { font-weight: bold; width: 130px; color: #333; }
        .stok-status { font-weight: bold; margin-top: 15px; font-size: 16px; }
        .stok-status.ready { color: #28a745; }
        .stok-status.empty { color: #dc3545; }
        .action-area { margin-top: 30px; display: flex; gap: 15px; align-items: center; }
        .btn-booking { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-booking:hover { background: #0056b3; }
        .btn-disabled { background: #ccc; color: #666; padding: 10px 20px; text-decoration: none; border-radius: 5px; pointer-events: none; font-weight: bold; }
        .back-link { color: #666; text-decoration: none; font-size: 15px; }
        .back-link:hover { color: #333; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="detail-box">
        <div class="detail-image">
            <img src="../gambar/cover/<?= $buku['foto_cover']; ?>" alt="Cover Buku">
        </div>
        
        <div class="detail-info">
            <h2><?= $buku['judul_buku']; ?></h2>
            <table>
                <tr>
                    <td class="label">Nama Pengarang</td>
                    <td>: <?= $buku['pengarang']; ?></td>
                </tr>
                <tr>
                    <td class="label">Penerbit</td>
                    <td>: <?= $buku['penerbit']; ?></td>
                </tr>
                <tr>
                    <td class="label">Tahun Terbit</td>
                    <td>: <?= $buku['tahun_terbit']; ?></td>
                </tr>
                <tr>
                    <td class="label">Tersedia Sisa</td>
                    <td>: <?= $buku['stok']; ?> Buku</td>
                </tr>
            </table>

            <div class="action-area">
                <?php if ($buku['stok'] > 0) : ?>
                    <a href="../penyewaan/booking.php?id_buku=<?= $buku['id_buku']; ?>" class="btn-booking" onclick="return confirm('Booking buku ini sekarang?')">Ajukan Pinjam (Booking)</a>
                <?php else : ?>
                    <span class="btn-disabled">Stok Habis Kosong</span>
                <?php endif; ?>
                <a href="katalog.php" class="back-link">&larr; Kembali ke Katalog</a>
            </div>
        </div>
    </div>

</body>
</html>