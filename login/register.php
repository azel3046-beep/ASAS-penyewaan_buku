<?php
include '../koneksi.php';

if (isset($_POST['register'])) {
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $email   = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi Password
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);

    // Cek apakah email sudah terdaftar
    $cek_email = mysqli_query($koneksi, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar! Silakan gunakan email lain.');</script>";
    } else {
        // Simpan data ke database dengan role otomatis 'member'
        $query = "INSERT INTO users (nama_lengkap, email, password, no_telp, role) 
                  VALUES ('$nama', '$email', '$password', '$no_telp', 'member')";
                  
        if (mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Pendaftaran Berhasil! Silakan Login.'); 
                    window.location='loginuser.php';
                  </script>";
        } else {
            echo "<script>alert('Gagal mendaftar!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Member Baru - BookRent</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #2c3e50; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reg-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 380px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; color: #666; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #28a745; border: none; color: white; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #218838; }
        .login-link { text-align: center; margin-top: 15px; font-size: 14px; }
        .login-link a { color: #007bff; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="reg-box">
        <h2>Pendaftaran Online</h2>
        <form action="" method="post">
            <div class="input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" placeholder="Nama asli Anda" required>
            </div>
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="contoh@gmail.com" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password akun" required>
            </div>
            <div class="input-group">
                <label>No. Telepon</label>
                <input type="text" name="no_telp" placeholder="08xxxxxxxxxx" required>
            </div>
            <button type="submit" name="register" class="btn">Daftar Sekarang</button>
        </form>
        <div class="login-link">Sudah punya akun? <a href="loginuser.php">Login di sini</a></div>
    </div>
</body>
</html>