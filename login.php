<?php
$page_title = "Login | Posyandu Posyandu Gelatik 2"; // Menentukan judul halaman


// Proses login ketika form disubmit (method POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Dapatkan koneksi ke database
    $db = new Database(); // Membuat objek database
    $db = $db->getConnection(); // Mendapatkan koneksi database

    $username = trim($_POST['username']); // Mengambil dan menghilangkan spasi di username
    $password = trim($_POST['password']); // Mengambil dan menghilangkan spasi di password

    // Validasi input
    $errors = [];

    if (empty($username) || empty($password)) {
        $errors[] = "Username dan password harus diisi"; // Pesan error jika input kosong
    }

    if (empty($errors)) {
        // Mengecek apakah username ada di database
        $stmt = $db->prepare("SELECT * FROM users as u JOIN data_kader as dk ON u.id = dk.id_user WHERE u.username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(); // Mengambil data user berdasarkan username

        // Verifikasi password jika username ditemukan
        if ($user && password_verify($password, $user['password'])) {
            // Jika login sukses, simpan informasi user di session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['success_message'] = "Login sukses";

            header("Location: ?page=dashboard"); // Redirect ke halaman dashboard setelah login sukses
            exit(); // Hentikan eksekusi script setelah redirect
        } else {
            // Jika username atau password salah
            $errors[] = "Username atau password salah";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="asset/css/login.css">
</head>
<body>
    <div class="background-pattern">
        <div class="login-box">
            <h2 class="title">SISTEM INFORMASI POSYANDU</h2>
            <p class="subtitle"><b>POSYANDU GELATIK 2</b></p>

            <?php if (!empty($errors)): ?>
                <div class="alert">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <p class="footer">&copy; <?= date("Y") ?> POSYANDU GELATIK 2</p>
        </div>
    </div>
</body>
</html>