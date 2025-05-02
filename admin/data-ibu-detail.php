<?php
$page_title = "Detail Data Ibu Hamil";
// Menyisipkan file header, navbar, dan fungsi-fungsi terkait user
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'function/data-ibu-detail_functions.php';

$id_detail = $_GET['id_detail'] ?? '';  // Mengambil ID detail dari URL
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';
$search_query = $_POST['search_query'] ?? '';

// Handle aksi hapus user
if ($action === 'delete' && $id) {
    deletedataibu($id, $db);
}
if ($action === 'detail' && $id) {
    detaildataibu($id, $db);
}

// Ambil data ibu untuk form edit (jika ada)
$current_dataibu = ($action === 'edit' && $id) ? getdataibuById($id, $db) : null;

// Ambil data semua ibu atau hasil pencarian
if ($search_query) {
    $dataibu = searchdataibu($search_query, $db);
} else {
    $dataibu = getAlldataibudetail($id_detail, $db);
}
?>

<div class="container my-4">

<!-- Tampilkan pesan sukses jika ada -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<!-- Tampilkan pesan error jika ada -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
    <!-- Judul -->
    <div class="mb-4">
            <?php foreach ($dataibu as $row): ?>
                <h2 class="text-center">Detail Data Ibu <?= $row['nama'] ?></h2>
            <?php endforeach; ?>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="data-dasar-tab" data-bs-toggle="tab" data-bs-target="#data-dasar" type="button" role="tab">Data Dasar</button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content border border-top-0 p-4 bg-light rounded-bottom" id="myTabContent">
        <!-- Data Dasar -->
        <div class="tab-pane fade show active" id="data-dasar" role="tabpanel">
            <?php if (count($dataibu) > 0): ?>
                <?php foreach ($dataibu as $row): ?>
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Nama Lengkap:</strong> <?= $row['nama'] ?></div>
                        <div class="col-md-6"><strong>Tanggal Lahir:</strong> <?= $row['ttl'] ?></div>
                        <div class="col-md-6"><strong>Usia:</strong> <?= $row['usia'] ?> Tahun</div>
                        <div class="col-md-6"><strong>Alamat:</strong> <?= $row['alamat'] ?></div>
                        <div class="col-md-6"><strong>No Telepon:</strong> <?= $row['no_tlp'] ?></div>
                        <div class="col-md-6"><strong>NIK:</strong> <?= $row['nik'] ?></div>
                        <div class="col-md-6"><strong>Nama Suami:</strong> <?= $row['suami'] ?></div>
                        <div class="col-md-6"><strong>Usia Kehamilan:</strong> <?= $row['usia_kehamilan'] ?> Minggu</div>
                        <div class="col-md-6"><strong>HPHT:</strong> <?= $row['hpht'] ?></div>
                        <div class="col-md-6"><strong>Golongan Darah:</strong> <?= $row['goldar'] ?></div>
                        <div class="col-md-6"><strong>Kehamilan Ke:</strong> <?= $row['hamil_ke'] ?></div>
                        <div class="col-md-6"><strong>Petugas:</strong> <?= $row['petugas'] ?></div>
                        <div class="col-md-6"><strong>Tanggal Periksa Terakhir:</strong> <?= $row['tgl_periksaakhir'] ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">Data Ibu Hamil Tidak Tersedia</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>