<?php
include '../koneksi.php';
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Cari user dengan email tersebut dan pastikan role-nya 'member'
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email' AND role='member'");
    
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        // Memeriksa kecocokan password terenkripsi
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['nama'] = $row['nama_lengkap'];
            $_SESSION['role'] = 'member';
            
            header("Location: ../buku/katalog.php");
            exit;
        }
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Member - BookRent</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #2c3e50; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; color: #666; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #007bff; border: none; color: white; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #0056b3; }
        .error { color: #dc3545; text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 14px; }
        .reg-link { text-align: center; margin-top: 20px; font-size: 14px; }
        .reg-link a { color: #007bff; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Member</h2>
        
        <?php if (isset($error)) : ?>
            <p class="error">Email atau password salah / Bukan akun member!</p>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Masukkan email Anda">
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="******">
            </div>
            <button type="submit" name="login" class="btn">Masuk</button>
        
        <div class="reg-link">Belum punya akun? <a href="register.php">Daftar Online</a></div>
        <div style="text-align: center; margin-top: 10px; font-size: 13px;">
    <a href="loginadmin.php" style="color: #e74c3c; text-decoration: none; font-weight: bold;">Akses Login Admin &rarr;</a>
        </div>
</form>
    
</body>
</html>