<?php
$database = new Database();
$db = $database->getConnection();

function savejadwal($action, $id, $tgl, $waktu, $kegiatan, $lokasi, $id_kader, $status, $db) {
    if ($action === 'edit' && $id) {
        $stmt = $db->prepare("UPDATE jadwal_kegiatan SET tgl=?, waktu=?, kegiatan=?, lokasi=?, id_kader=?, status=? WHERE id_kegiatan=?");
        $stmt->execute([$tgl, $waktu, $kegiatan, $lokasi, $id_kader, $status, $id]);
        $_SESSION['success'] = "Jadwal berhasil diperbarui!";
    } else {
        $stmt = $db->prepare("INSERT INTO jadwal_kegiatan (tgl, waktu, kegiatan, lokasi, id_kader, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$tgl, $waktu, $kegiatan, $lokasi, $id_kader, $status]);
        $_SESSION['success'] = "Jadwal berhasil ditambahkan!";
    }
    header("Location: ?page=jadwal-kegiatan");
    exit();
}

function deletejadwal($id, $db) {
    $stmt = $db->prepare("DELETE FROM jadwal_kegiatan WHERE id_kegiatan = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "Jadwal berhasil dihapus!";
    header("Location: ?page=jadwal-kegiatan");
    exit();
}

function getjadwalById($id, $db) {
    $stmt = $db->prepare("SELECT * FROM jadwal_kegiatan WHERE id_kegiatan = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAlljadwal($db) {
    $query = "SELECT jk.*, dk.nama AS nama_kader 
              FROM jadwal_kegiatan jk
              LEFT JOIN data_kader dk ON jk.id_kader = dk.id_kader
              ORDER BY jk.tgl DESC, jk.waktu ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function searchjadwal($search, $db) {
    $query = "SELECT jk.*, dk.nama AS nama_kader 
              FROM jadwal_kegiatan jk
              LEFT JOIN data_kader dk ON jk.id_kader = dk.id_kader
              WHERE jk.kegiatan LIKE ? OR jk.lokasi LIKE ? OR dk.nama LIKE ?
              ORDER BY jk.tgl DESC, jk.waktu ASC";
    $stmt = $db->prepare($query);
    $search_param = "%$search%";
    $stmt->execute([$search_param, $search_param, $search_param]);
    return $stmt->fetchAll();
}

function getAlldatakader($db) {
    $query = "SELECT * FROM data_kader ORDER BY nama ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}
