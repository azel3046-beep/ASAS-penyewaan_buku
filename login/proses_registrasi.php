<?php
include '../koneksi.php';

$nama_lengkap = $_POST['nama_lengkap'];
$email        = $_POST['email'];
$password     = $_POST['password'];
$no_telp      = $_POST['no_telp'];
$role         = $_POST['role'];

// Cek apakah email sudah terdaftar sebelumnya
$cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
if (mysqli_num_rows($cek_email) > 0) {
    echo "<script>alert('Email sudah terdaftar! Gunakan email lain.'); window.location='registrasi.php';</script>";
} else {
    // Memasukkan data ke tabel users sesuai struktur kolom di gambar database kamu
    $query = "INSERT INTO users (nama_lengkap, email, password, no_telp, role) 
              VALUES ('$nama_lengkap', '$email', '$password', '$no_telp', '$role')";
              
    $simpan = mysqli_query($koneksi, $query);

    if ($simpan) {
        echo "<script>alert('Registrasi Berhasil! Silakan login.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mendaftar, coba lagi!'); window.location='registrasi.php';</script>";
    }
}
?>