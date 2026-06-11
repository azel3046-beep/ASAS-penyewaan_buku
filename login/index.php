<!DOCTYPE html>
<html>
<head>
    <title>Halaman Login</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f2f5; margin: 0; }
        .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 320px; }
        h2 { text-align: center; color: #333; margin-top: 0; }
        input, select, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; cursor: pointer; font-size: 16px; margin-top: 15px; }
        button:hover { background: #0056b3; }
        .reg-text { text-align: center; margin-top: 15px; font-size: 14px; color: #555; }
        .reg-text a { color: #007bff; text-decoration: none; font-weight: bold; }
        .reg-text a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="box">
    <h2>Login Sistem</h2>
    <form action="proses.php" method="POST">
        <input type="email" name="email" placeholder="Masukkan Email Anda" required>
        <input type="password" name="password" placeholder="Masukkan Password" required>
        
        <select name="role" required>
            <option value="member">Member</option>
            <option value="admin">Admin</option>
        </select>
        
        <button type="submit">Log In</button>
    </form>

    <div class="reg-text">
        Belum punya akun? <a href="registrasi.php">Daftar di sini</a>
    </div>
</div>

</body>
</html>