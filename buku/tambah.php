<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

if (isset($_POST['tambah'])) {
    $judul       = $_POST['judul_buku'];
    $pengarang   = $_POST['pengarang'];
    $penerbit    = $_POST['penerbit'];
    $tahun       = $_POST['tahun_terbit'];
    $stok        = $_POST['stok'];

    // Proses Upload Gambar Cover
    $foto        = $_FILES['foto_cover']['name'];
    $tmp_name    = $_FILES['foto_cover']['tmp_name'];
    
    // Beri nama acak unik agar file gambar tidak saling tertimpa
    $ekstensi    = pathinfo($foto, PATHINFO_EXTENSION);
    $nama_baru   = time() . '_' . rand(100, 999) . '.' . $ekstensi;
    
    // PERBAIKAN: Cek dan buat folder tujuan secara otomatis jika belum ada
    $dir_tujuan = '../gambar/cover/';
    if (!is_dir($dir_tujuan)) {
        mkdir($dir_tujuan, 0777, true);
    }

    $folder_tujuan = $dir_tujuan . $nama_baru;

    if (move_uploaded_file($tmp_name, $folder_tujuan)) {
        // PERBAIKAN: Gunakan mysqli_real_escape_string agar tidak error jika judul buku pakai tanda petik (')
        $judul_safe     = mysqli_real_escape_string($koneksi, $judul);
        $pengarang_safe = mysqli_real_escape_string($koneksi, $pengarang);
        $penerbit_safe  = mysqli_real_escape_string($koneksi, $penerbit);

        $query = "INSERT INTO buku (judul_buku, pengarang, penerbit, tahun_terbit, stok, foto_cover) 
                  VALUES ('$judul_safe', '$pengarang_safe', '$penerbit_safe', '$tahun', '$stok', '$nama_baru')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Buku baru berhasil ditambahkan!'); window.location='katalog.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan data ke database: " . mysqli_error($koneksi) . "');</script>";
        }
    } else {
        echo "<script>alert('Gagal mengupload cover buku! Periksa permission folder Anda.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku Baru</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .form-box { background: white; max-width: 500px; margin: 30px auto; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-top: 0; text-align: center; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; color: #666; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #28a745; border: none; color: white; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #218838; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Tambah Data Buku</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" required>
            </div>
            <div class="input-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" required>
            </div>
            <div class="input-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" required>
            </div>
            <div class="input-group">
                <label>Tahun Terbit (Format: YYYY)</label>
                <input type="number" name="tahun_terbit" min="1900" max="2100" required>
            </div>
            <div class="input-group">
                <label>Jumlah Stok</label>
                <input type="number" name="stok" min="0" required>
            </div>
            <div class="input-group">
                <label>File Cover Buku</label>
                <input type="file" name="foto_cover" accept="image/*" required>
            </div>
            <button type="submit" name="tambah" class="btn">Simpan Buku</button>
        </form>
        <a href="katalog.php" class="back-link">&larr; Kembali ke Katalog</a>
    </div>
</body>
</html>