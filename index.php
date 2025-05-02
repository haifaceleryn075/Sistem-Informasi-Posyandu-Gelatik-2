<?php
require_once 'config/database.php';
require_once 'function/auth_functions.php';

$pages = [
    'profil' => 'admin/profil.php',
    'login' => 'login.php',
    'register' => 'register.php',
    'logout' => 'logout.php',
    'dashboard' => 'admin/dashboard.php',
    'users' => 'admin/users.php',
    'data-anak' => 'admin/data-anak.php',
    'data-anak-detail' => 'admin/data-anak-detail.php',
    'data-ibu' => 'admin/data-ibu.php', 
    'data-ibu-detail' => 'admin/data-ibu-detail.php', 
    'jadwal-kegiatan' => 'admin/jadwal-kegiatan.php',
    'data-kader' => 'admin/data-kader.php',
    'laporan-anak' => 'admin/laporan-anak.php',
    'laporan-ibu' => 'admin/laporan-ibu.php' 
];

$page = isset($_GET['page']) ? $_GET['page'] : 'login';

if (!array_key_exists($page, $pages)){
    header("HTTP/1.0 404 Not Found");
    exit();
}

$admin_pages = ['dashboard', 'users', 'jadwal-kegiatan', 'data-kader']; // Menambahkan jadwal-kegiatan ke array admin_pages
if (in_array($page, $admin_pages)) {
    redirectIfNotLoggedIn();
    if ($page === 'users') redirectIfNotAdmin();
}

if (isLoggedIn() && ($page === 'login')){
    header("Location: ?page=dashboard");
    exit();
}

include $pages[$page];
?>
