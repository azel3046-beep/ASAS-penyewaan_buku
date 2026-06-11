<?php
include '../koneksi.php';
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Cari user dengan email tersebut dan pastikan role-nya 'admin'
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email' AND role='admin'");
    
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        // Memeriksa kecocokan password terenkripsi
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['nama'] = $row['nama_lengkap'];
            $_SESSION['role'] = 'admin';
            
            header("Location: ../admin/index.php");
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
    <title>Login Admin - BookRent</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #2c3e50; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); width: 350px; }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 5px; }
        p.subtitle { text-align: center; color: #7f8c8d; font-size: 14px; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; color: #34495e; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #2c3e50; border: none; color: white; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #1a252f; }
        .error { color: #e74c3c; text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 14px; }
        .back-link { text-align: center; margin-top: 20px; font-size: 13px; }
        .back-link a { color: #34495e; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Admin</h2>
        <p class="subtitle">Sistem Informasi Perpustakaan</p>
        
        <?php if (isset($error)) : ?>
            <p class="error">Email atau password salah / Akun bukan Admin!</p>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="input-group">
                <label>Email Admin</label>
                <input type="email" name="email" placeholder="admin@gmail.com" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="******" required>
            </div>
            <button type="submit" name="login" class="btn">Masuk ke Dashboard</button>
        </form>
        
        <div class="back-link">
            <a href="loginuser.php">&larr; Kembali ke Login Member</a>
        </div>
    </div>
</body>
</html>