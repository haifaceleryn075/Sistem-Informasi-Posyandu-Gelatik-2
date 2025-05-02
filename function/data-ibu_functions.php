<?php
$database = new Database();
$db = $database->getConnection();

function savedataibu($action, $id, $nama, $ttl, $usia, $alamat, $no_tlp, $nik, $suami, $usia_kehamilan, $hpht, $taksiran_lahir, $goldar, $hamil_ke, $petugas, $tgl_periksaakhir, $db) {
    if ($action === 'edit' && $id) {
        $stmt = $db->prepare("UPDATE data_ibu SET nama=?, ttl=?, usia=?, alamat=?, no_tlp=?, nik=?, suami=?, usia_kehamilan=?, hpht=?, taksiran_lahir=?, goldar=?, hamil_ke=?, petugas=?, tgl_periksaakhir=? WHERE id_ibu=?");
        $stmt->execute([$nama, $ttl, $usia, $alamat, $no_tlp, $nik, $suami, $usia_kehamilan, $hpht, $taksiran_lahir, $goldar, $hamil_ke, $petugas, $tgl_periksaakhir, $id]);
        $_SESSION['success'] = "Data Ibu Hamil berhasil diperbarui!";
    } else {
        $stmt = $db->prepare("INSERT INTO data_ibu (nama, ttl, usia, alamat, no_tlp, nik, suami, usia_kehamilan, hpht, taksiran_lahir, goldar, hamil_ke, petugas, tgl_periksaakhir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ $nama, $ttl, $usia, $alamat, $no_tlp, $nik, $suami, $usia_kehamilan, $hpht, $taksiran_lahir, $goldar, $hamil_ke, $petugas, $tgl_periksaakhir]);
        $_SESSION['success'] = "Data Ibu Hamil berhasil ditambahkan!";
    }
    header("Location: ?page=data-ibu");
    exit();
}

function deletedataibu($id, $db) {
    $stmt = $db->prepare("DELETE FROM data_ibu WHERE id_ibu = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "Data Ibu Hamil berhasil dihapus!";
    header("Location: ?page=data-ibu");
    exit();
}

function detaildataibu($id, $db) {
    header("Location: ?page=data-ibu-detail&id_detail=" . $id);
    exit();
}

function getdataibuById($id, $db) {
    $stmt = $db->prepare("SELECT * FROM data_ibu WHERE id_ibu = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAlldataibu($db) {
    $query = "SELECT * FROM data_ibu ORDER BY id_ibu ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function searchdataibu($search, $db) {
    $query = "SELECT * FROM data_ibu";
    if ($search) {
        $query .= " WHERE nama LIKE ? OR suami LIKE ?"; // Menambahkan pencarian berdasarkan nama ibu dan suami
    }
    $query .= " ORDER BY id_ibu ASC";
    $stmt = $db->prepare($query);
    if ($search) {
        $search_param = "%$search%";
        $stmt->execute([$search_param, $search_param]);
    } else {
        $stmt->execute();
    }
    return $stmt->fetchAll();
}
?>
