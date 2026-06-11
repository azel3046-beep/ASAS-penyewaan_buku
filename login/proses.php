<?php
session_start();
include '../koneksi.php'; 

$email    = $_POST['email'];
$password = $_POST['password'];
$role     = $_POST['role'];

$query  = "SELECT * FROM users WHERE email='$email' AND role='$role'";
$data   = mysqli_query($koneksi, $query);
$ketemu = mysqli_num_rows($data);

if ($ketemu > 0) {
    $user = mysqli_fetch_assoc($data);
    
    if ($password == $user['password']) {
        $_SESSION['nama']  = $user['nama_lengkap'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role']  = $user['role'];
        
        // LANGSUNG DIARAHKAN KE HALAMAN UTAMA KAMU DI LUAR FOLDER LOGIN
        // Sesuaikan '../index.php' dengan nama file halaman utama/dashboard asli kamu
        if ($user['role'] == "admin") {
            header("location: ../index.php"); 
        } else if ($user['role'] == "member") {
            header("location: ../index.php"); 
        }
    } else {
        echo "<script>alert('Password Salah!'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Email atau Role Tidak Ditemukan!'); window.location='index.php';</script>";
}
?>