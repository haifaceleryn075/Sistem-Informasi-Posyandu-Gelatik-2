<?php
$database = new Database();
$db = $database->getConnection();

function savedatakader($action, $id, $id_user, $nama, $jabatan, $no_tlp, $alamat, $mulai_tugas, $status, $db) {
    if ($action === 'edit' && $id) {
        $stmt = $db->prepare("UPDATE data_kader SET nama=?, jabatan=?, no_tlp=?, alamat=?, mulai_tugas=?, status=? WHERE id_kader=?");
        $stmt->execute([$nama, $jabatan, $no_tlp, $alamat, $mulai_tugas, $status, $id]);
        $_SESSION['success'] = "Data kader berhasil diperbarui!";
    } else {
        $stmt = $db->prepare("INSERT INTO data_kader (id_user, nama, jabatan, no_tlp, alamat, mulai_tugas, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_user, $nama, $jabatan, $no_tlp, $alamat, $mulai_tugas, $status]);
        $_SESSION['success'] = "Data kader berhasil ditambahkan!";
    }
    header("Location: ?page=data-kader");
    exit();
}

function deletedatakader($id, $db) {
    $stmt = $db->prepare("DELETE FROM data_kader WHERE id_kader = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "Data kader berhasil dihapus!";
    header("Location: ?page=data-kader");
    exit();
}

function detaildatakader($id, $db) {
    header("Location: ?page=data-kader-detail&id_detail=" . $id);
    exit();
}

function getdatakaderById($id, $db) {
    $stmt = $db->prepare("SELECT * FROM data_kader WHERE id_kader = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAlldatakader($db) {
    $query = "SELECT * FROM data_kader ORDER BY id_kader ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAlldatauser($db) {
    $query = "SELECT * FROM users ORDER BY id ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function searchkader($search, $db) {
    $query = "SELECT * FROM data_kader";
    if ($search) {
        $query .= " WHERE nama LIKE ?";
    }
    $query .= " ORDER BY id_kader ASC";
    $stmt = $db->prepare($query);
    if ($search) {
        $search_param = "%$search%";
        $stmt->execute([$search_param]);
    } else {
        $stmt->execute();
    }
    return $stmt->fetchAll();
}
