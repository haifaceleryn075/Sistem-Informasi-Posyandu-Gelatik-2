<?php
ob_start();
session_start();
//Memulai sesi untuk menyimpan data login (seperti user_id, role)

//AUNTETIKASI
function isloggedIn(){
    return isset($_SESSION['user_id']); // Cek apakah user sudah login
}

function redirectIfNotLoggedIn() {
    if (!isloggedIn()) {
        header("Location: ?page=login");
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header("Location: ?pagehome");
        exit();
    }
}
?>

