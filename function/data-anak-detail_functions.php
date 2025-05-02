<?php
$database = new Database();
$db = $database->getConnection();

function getAlldataanakdetail($id_detail, $db) {
    $query = "SELECT * FROM data_anak WHERE id_anak = ? ORDER BY id_anak ASC";
    $stmt = $db->prepare($query);
    $stmt->execute([$id_detail]);
    return $stmt->fetchAll();
}

function getAlldatapengukuran($id_detail, $db) {
    $query = "SELECT * FROM pengukuran_anak WHERE id_anak = ? ORDER BY id_anak ASC";
    $stmt = $db->prepare($query);
    $stmt->execute([$id_detail]);
    return $stmt->fetchAll();
}

function getdatapengukuranById($id_ukur, $db) {
    $stmt = $db->prepare("SELECT * FROM pengukuran_anak WHERE id_ukur = ?");
    $stmt->execute([$id_ukur]);
    return $stmt->fetch();
}

function savedatapengukuran($active_tab, $action, $id_detail, $id_ukur, $bb, $tb, $lk, $ll, $usia, $bulan, $db) {
    if ($action === 'edit') {
            $stmt = $db->prepare("UPDATE pengukuran_anak SET bb=?, tb=?, lk=?, ll=?, usia=?, bulan=? WHERE id_ukur=?");
            $stmt->execute([$bb, $tb, $lk, $ll, $usia, $bulan, $id_ukur]);
        $_SESSION['success'] = "Data Pengukuran berhasil diperbarui!";
    } else {
        $stmt = $db->prepare("INSERT INTO pengukuran_anak (id_anak, bb, tb, lk, ll, usia, bulan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_detail, $bb, $tb, $lk, $ll, $usia, $bulan]);
        $_SESSION['success'] = "Data Pengukuran berhasil ditambahkan!";
    }
    header("Location: ?page=data-anak-detail&id_detail=" . $id_detail . "&tab=" . $active_tab);
    exit();
}

function deletedatapengukuran($active_tab, $id_detail, $id_ukur, $db) {
    $stmt = $db->prepare("DELETE FROM pengukuran_anak WHERE id_ukur = ?");
    $stmt->execute([$id_ukur]);
    $_SESSION['success'] = "Data Pengukuran berhasil dihapus!";
    header("Location: ?page=data-anak-detail&id_detail=" . $id_detail . "&tab=" . $active_tab);
exit();
}

//tumbuh kembang//
function getAlldatatumbuhkembang($id_detail, $db) {
    $query = "SELECT * FROM perkembangan_anak WHERE id_anak = ? ORDER BY id_anak ASC";
    $stmt = $db->prepare($query);
    $stmt->execute([$id_detail]);
    return $stmt->fetchAll();
}

function getdatatumbuhkembangById($id_kembang, $db) {
    $stmt = $db->prepare("SELECT * FROM perkembangan_anak WHERE id_kembang= ?");
    $stmt->execute([$id_kembang]);
    return $stmt->fetch();
}

function savedatatumbuhkembang($active_tab, $action, $id_detail, $id_kembang, $usia, $motorik, $sosial, $kesehatan, $db) {
    if ($action === 'edit') {
            $stmt = $db->prepare("UPDATE perkembangan_anak SET usia=?, motorik=?, sosial=?, kesehatan=? WHERE id_kembang=?");
            $stmt->execute([$usia, $motorik, $sosial, $kesehatan, $id_kembang]);
        $_SESSION['success'] = "Data Tumbuh Kembang berhasil diperbarui!";
    } else {
        $stmt = $db->prepare("INSERT INTO perkembangan_anak (id_anak, usia, motorik, sosial, kesehatan) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_detail, $usia, $motorik, $sosial, $kesehatan]);
        $_SESSION['success'] = "Data Tumbuh Kembang berhasil ditambahkan!";
    }
    header("Location: ?page=data-anak-detail&id_detail=" . $id_detail . "&tab=" . $active_tab);
    exit();
}

function deletedatatumbuhkembang($active_tab, $id_detail, $id_kembang, $db) {
    $stmt = $db->prepare("DELETE FROM perkembangan_anak WHERE id_kembang = ?");
    $stmt->execute([$id_kembang]);
    $_SESSION['success'] = "Data Tumbuh Kembang berhasil dihapus!";
    header("Location: ?page=data-anak-detail&id_detail=" . $id_detail . "&tab=" . $active_tab);
exit();
}

//imunisasi
function getAlldataimunisasi($id_detail, $db) {
    $query = "SELECT * FROM imunisasi_anak WHERE id_anak = ? ORDER BY id_imunisasi ASC";
    $stmt = $db->prepare($query);
    $stmt->execute([$id_detail]);
    return $stmt->fetchAll();
}

function getdataimunisasiById($id_imunisasi, $db) {
    $stmt = $db->prepare("SELECT * FROM imunisasi_anak WHERE id_imunisasi = ?");
    $stmt->execute([$id_imunisasi]);
    return $stmt->fetch();
}

function savedataimunisasi($active_tab, $action, $id_detail, $id_imunisasi, $jenis_imunisasi, $tgl_beri, $usia, $petugas, $keterangan, $db) {
    if ($action === 'edit') {
        $stmt = $db->prepare("UPDATE imunisasi_anak SET jenis_imunisasi=?, tgl_beri=?, usia=?, petugas=?, keterangan=? WHERE id_imunisasi=?");
        $stmt->execute([$jenis_imunisasi, $tgl_beri, $usia, $petugas, $keterangan, $id_imunisasi]);
        $_SESSION['success'] = "Data Imunisasi berhasil diperbarui!";
    } else {
        $stmt = $db->prepare("INSERT INTO imunisasi_anak (id_anak, jenis_imunisasi, tgl_beri, usia, petugas, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_detail, $jenis_imunisasi, $tgl_beri, $usia, $petugas, $keterangan]);
        $_SESSION['success'] = "Data Imunisasi berhasil ditambahkan!";
    }
    header("Location: ?page=data-anak-detail&id_detail=" . $id_detail . "&tab=" . $active_tab);
    exit();
}

function deletedataimunisasi($active_tab, $id_detail, $id_imunisasi, $db) {
    $stmt = $db->prepare("DELETE FROM imunisasi_anak WHERE id_imunisasi = ?");
    $stmt->execute([$id_imunisasi]);
    $_SESSION['success'] = "Data Imunisasi berhasil dihapus!";
    header("Location: ?page=data-anak-detail&id_detail=" . $id_detail . "&tab=" . $active_tab);
    exit();
}


