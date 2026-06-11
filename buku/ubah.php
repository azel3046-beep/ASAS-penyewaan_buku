<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

// Ambil ID Buku dan amankan dari SQL Injection
$id_buku = mysqli_real_escape_string($koneksi, $_GET['id']);
$result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
$buku = mysqli_fetch_assoc($result);

if (isset($_POST['ubah'])) {
    // PERBAIKAN: Amankan semua input teks dari tanda petik (') agar SQL tidak crash
    $judul     = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit  = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun     = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);
    $stok      = mysqli_real_escape_string($koneksi, $_POST['stok']);
    
    $foto      = $_FILES['foto_cover']['name'];
    $tmp_name  = $_FILES['foto_cover']['tmp_name'];

    if (!empty($foto)) {
        // Jika admin memilih file gambar baru, upload yang baru
        $ekstensi = pathinfo($foto, PATHINFO_EXTENSION);
        $nama_baru = time() . '_' . rand(100, 999) . '.' . $ekstensi;
        
        // PERBAIKAN: Pastikan folder tujuan tersedia
        $dir_tujuan = '../gambar/cover/';
        if (!is_dir($dir_tujuan)) {
            mkdir($dir_tujuan, 0777, true);
        }

        move_uploaded_file($tmp_name, $dir_tujuan . $nama_baru);
        
        // Hapus foto lama agar server tidak penuh (hanya jika file lamanya ada dan bukan string kosong)
        if (!empty($buku['foto_cover']) && file_exists($dir_tujuan . $buku['foto_cover'])) {
            unlink($dir_tujuan . $buku['foto_cover']);
        }
    } else {
        // Jika tidak upload gambar baru, pakai nama gambar lama
        $nama_baru = $buku['foto_cover'];
    }

    // Query sekarang aman walaupun input form menggunakan tanda petik
    $query = "UPDATE buku SET 
              judul_buku='$judul', 
              pengarang='$pengarang', 
              penerbit='$penerbit', 
              tahun_terbit='$tahun', 
              stok='$stok', 
              foto_cover='$nama_baru' 
              WHERE id_buku='$id_buku'";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data buku berhasil diperbarui!'); window.location='katalog.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Buku</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .form-box { background: white; max-width: 500px; margin: 30px auto; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-top: 0; text-align: center; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; color: #666; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #007bff; border: none; color: white; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #0056b3; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Edit Data Buku</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" value="<?= $buku['judul_buku']; ?>" required>
            </div>
            <div class="input-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" value="<?= $buku['pengarang']; ?>" required>
            </div>
            <div class="input-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" value="<?= $buku['penerbit']; ?>" required>
            </div>
            <div class="input-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="<?= $buku['tahun_terbit']; ?>" required>
            </div>
            <div class="input-group">
                <label>Jumlah Stok</label>
                <input type="number" name="stok" value="<?= $buku['stok']; ?>" required>
            </div>
            <div class="input-group">
                <label>Ganti Cover Buku *(Kosongkan jika tidak ingin diganti)</label>
                <input type="file" name="foto_cover" accept="image/*">
            </div>
            <button type="submit" name="ubah" class="btn">Update Buku</button>
        </form>
        <a href="katalog.php" class="back-link">&larr; Batal & Kembali</a>
    </div>
</body>
</html>