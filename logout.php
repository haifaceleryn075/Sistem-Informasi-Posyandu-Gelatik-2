<?php
require_once "function/auth_functions.php"; // Jika ada fungsi tambahan, misalnya pengecekan login

// Hapus semua data sesi (logout)
session_unset();       // Menghapus semua variabel sesi
session_destroy();     // Menghancurkan sesi pengguna

// Buat pesan sukses untuk ditampilkan di halaman login
$_SESSION['success_message'] = "Anda telah logout";

// Arahkan kembali ke halaman login
header("Location: ?page=login");
exit(); // Hentikan eksekusi script
?>

