<?php
$database = new Database();
$db = $database->getConnection();

function getAlldataibudetail($id_detail, $db) {
    $query = "SELECT * FROM data_ibu WHERE id_ibu = ? ORDER BY id_ibu ASC";
    $stmt = $db->prepare($query);
    $stmt->execute([$id_detail]);
    return $stmt->fetchAll();
}

function getdataibuById($id_detail, $db) {
    $query = "SELECT * FROM data_ibu WHERE id_ibu = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id_detail]);
    return $stmt->fetch();
}

function getAlldataibu($db) {
    $query = "SELECT * FROM data_ibu ORDER BY id_ibu ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function savedatadataibu($action, $id_detail, $nama, $ttl, $usia, $alamat, $no_tlp, $suami, $usia_kehamilan, $hpt, $taksiran_lahir, $goldar, $hamil_ke, $petugas, $tgl_periksaakhir, $db) {
    if ($action === 'edit') {
            $stmt = $db->prepare("UPDATE data_ibu SET nama=?, ttl=?, usia=?, alamat=?, no_tlp=?, suami=?, usia_kehamilan=?, hpt=?, taksiran_lahir=?, goldar=?, hamil_ke=?, petugas=?, tgl_periksaakhir=? WHERE id_ibu=?");
            $stmt->execute([$nama, $ttl, $usia, $alamat, $no_tlp, $suami, $usia_kehamilan, $hpt, $taksiran_lahir, $goldar, $hamil_ke, $petugas, $tgl_periksaakhir, $id_detail]);
        $_SESSION['success'] = "Data Ibu berhasil diperbarui!";
    } else {
        $stmt = $db->prepare("INSERT INTO data_ibu (nama, ttl, usia, alamat, no_tlp, suami, usia_kehamilan, hpt, taksiran_lahir, goldar, hamil_ke, petugas, tgl_periksaakhir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $ttl, $usia, $alamat, $no_tlp, $suami, $usia_kehamilan, $hpt, $taksiran_lahir, $goldar, $hamil_ke, $petugas, $tgl_periksaakhir]);
        $_SESSION['success'] = "Data Ibu berhasil ditambahkan!";
    }
    header("Location: ?page=data-ibu-detail&id_detail=" . $id_detail);
    exit();
}

function deletedataibu($id_detail, $db) {
    $stmt = $db->prepare("DELETE FROM data_ibu WHERE id_ibu = ?");
    $stmt->execute([$id_detail]);
    $_SESSION['success'] = "Data Ibu berhasil dihapus!";
    header("Location: ?page=data-ibu-detail&id_detail=" . $id_detail);
    exit();
}

function searchdataibu($search, $db) {
    $query = "SELECT * FROM data_ibu";
    if ($search) {
        $query .= " WHERE nama LIKE ? OR suami LIKE ?";
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
