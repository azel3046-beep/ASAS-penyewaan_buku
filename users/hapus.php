<?php
include '../koneksi.php';
session_start();

// Proteksi halaman: Hanya Admin yang boleh mengeksekusi penghapusan
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/loginadmin.php");
    exit;
}

// Cek apakah ada parameter ID di URL
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Jalankan query hapus data user dari database
    $query = "DELETE FROM users WHERE id_user = '$id_user' AND role = 'member'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Data member berhasil dihapus dari sistem!');
                window.location = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data member!');
                window.location = 'index.php';
              </script>";
    }
} else {
    // Jika tidak ada ID di URL, lempar kembali ke index
    header("Location: index.php");
    exit;
}
?>