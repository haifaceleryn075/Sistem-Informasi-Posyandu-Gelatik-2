<?php
$database = new Database();
$db = $database->getConnection();

function savedataanak($action, $id, $nama, $ttl, $jenis_kelamin, $berat_lahir, $panjang_lahir, $nama_ibu, $nama_ayah, $alamat, $no_tlp, $nik, $db) {
    if ($action === 'edit' && $id) {
            $stmt = $db->prepare("UPDATE data_anak SET nama=?, ttl=?, jenis_kelamin=?, berat_lahir=?, panjang_lahir=?, nama_ibu=?, nama_ayah=?, alamat=?, no_tlp=?, nik=? WHERE id_anak=?");
            $stmt->execute([$nama, $ttl, $jenis_kelamin, $berat_lahir, $panjang_lahir, $nama_ibu, $nama_ayah, $alamat, $no_tlp, $nik, $id]);
        $_SESSION['success'] = "data bayi dan balita berhasil diperbarui!";
    } else {
        $stmt = $db->prepare("INSERT INTO data_anak (nama, ttl, jenis_kelamin, berat_lahir, panjang_lahir, nama_ibu, nama_ayah, alamat, no_tlp, nik) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $ttl, $jenis_kelamin, $berat_lahir, $panjang_lahir, $nama_ibu, $nama_ayah, $alamat, $no_tlp, $nik]);
        $_SESSION['success'] = "Data Bayi Dan Balita berhasil ditambahkan!";
    }
    header("Location: ?page=data-anak");
    exit();
}

function deletedataanak($id, $db) {
        $stmt = $db->prepare("DELETE FROM data_anak WHERE id_anak = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "data bayi dan balita berhasil dihapus!";
    header("Location: ?page=data-anak");
    exit();
}

function detaildataanak($id, $db) {
    header("Location: ?page=data-anak-detail&id_detail=" . $id);
        exit();
}

function getdataanakById($id, $db) {
    $stmt = $db->prepare("SELECT * FROM data_anak WHERE id_anak = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAlldataanak($db) {
    $query = "SELECT * FROM data_anak ORDER BY id_anak ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function searchanak($search, $db) {
    $query = "SELECT * FROM data_anak";
    if ($search) {
        $query .= " WHERE nama LIKE ?";
    }
    $query .= " ORDER BY id_anak ASC";
    $stmt = $db->prepare($query);
    if ($search) {
        $search_param = "%$search%";
        $stmt->execute([$search_param, $search_param]);
    } else {
        $stmt->execute();
    }
    return $stmt->fetchAll();
}
