<?php
$page_title = "Data Bayi Dan Balita";
// Menyisipkan file header, navbar, dan fungsi-fungsi terkait user
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'function/data-anak_functions.php';
require_once 'function/data-anak-detail_functions.php';
$id_detail=$_GET['id_detail'] ?? '';
// Mendapatkan parameter dari URL
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';
$search_query = $_POST['search_query'] ?? '';
$active_tab = $_GET['tab'] ?? 'data-dasar';


// Handle aksi hapus user
if ($action === 'delete' && $id) {
    deletedataanak($id, $db);
}
if ($action === 'detail' && $id) {
    detaildataanak($id, $db);
}

// Ambil data user untuk form edit (jika ada)
$current_dataanak = ($action === 'edit' && $id) ? getdataanakById($id, $db) : null;

// Ambil data semua user atau hasil pencarian
if ($search_query) {
    $dataanak = searchdataanak($search_query, $db);
} else {
    $dataanak = getAlldataanakdetail($id_detail, $db);
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
            <?php foreach ($dataanak as $row): ?>
                <h2 class="text-center">Detail Data Bayi dan Balita <?= $row['nama'] ?></h2>
            <?php endforeach; ?>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab === 'data-dasar' ? 'active' : '' ?>" id="data-dasar-tab" data-bs-toggle="tab" data-bs-target="#data-dasar" type="button" role="tab">Data Dasar</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab === 'pengukuran' ? 'active' : '' ?>" id="pengukuran-tab" data-bs-toggle="tab" data-bs-target="#pengukuran" type="button" role="tab">Pengukuran</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab === 'tumbuh_kembang' ? 'active' : '' ?>" id="tumbuh-kembang-tab" data-bs-toggle="tab" data-bs-target="#tumbuh_kembang" type="button" role="tab">Tumbuh Kembang</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab === 'imunisasi' ? 'active' : '' ?>" id="imunisasi-tab" data-bs-toggle="tab" data-bs-target="#imunisasi" type="button" role="tab">Imunisasi</button>
    </li>
</ul>


    <!-- Tabs Content -->
    <div class="tab-content border border-top-0 p-4 bg-light rounded-bottom" id="myTabContent">
        <!-- Data Dasar -->
        <div class="tab-pane fade <?= $active_tab === 'data-dasar' ? 'show active' : '' ?>" id="data-dasar" role="tabpanel">
            <?php if (count($dataanak) > 0): ?>
                <?php foreach ($dataanak as $row): ?>
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Nama Lengkap:</strong> <?= $row['nama'] ?></div>
                        <div class="col-md-6"><strong>Tanggal Lahir:</strong> <?= $row['ttl'] ?></div>
                        <div class="col-md-6"><strong>Jenis Kelamin:</strong> <?= $row['jenis_kelamin'] ?></div>
                        <div class="col-md-6"><strong>Berat Lahir:</strong> <?= $row['berat_lahir'] ?> kg</div>
                        <div class="col-md-6"><strong>Panjang Lahir:</strong> <?= $row['panjang_lahir'] ?> cm</div>
                        <div class="col-md-6"><strong>Nama Ibu:</strong> <?= $row['nama_ibu'] ?></div>
                        <div class="col-md-6"><strong>Nama Ayah:</strong> <?= $row['nama_ayah'] ?></div>
                        <div class="col-md-6"><strong>No Telepon:</strong> <?= $row['no_tlp'] ?></div>
                        <div class="col-md-12"><strong>Alamat:</strong> <?= $row['alamat'] ?></div>
                        <div class="col-md-6"><strong>NIK:</strong> <?= $row['nik'] ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">Data Anak Tidak Tersedia</div>
            <?php endif; ?>
        </div>

        <!-- Pengukuran -->
        <div class="tab-pane fade <?= $active_tab === 'pengukuran' ? 'show active' : '' ?>" id="pengukuran" role="tabpanel">
            <?php require_once 'pengukuran-anak.php'; ?>
        </div>

        <!-- Tumbuh Kembang -->
        <div class="tab-pane fade <?= $active_tab === 'tumbuh_kembang' ? 'show active' : '' ?>" id="tumbuh_kembang" role="tabpanel">
            <?php require_once 'tumbuh-kembang.php'; ?>
        </div>

        <!-- Imunisasi -->
        <div class="tab-pane fade <?= $active_tab === 'imunisasi' ? 'show active' : '' ?>" id="imunisasi" role="tabpanel">
            <div class="row g-3">
            <?php require_once 'imunisasi.php'; ?>
              <!--
                <div class="col-md-4"><strong>BCG:</strong> Sudah</div>
                <div class="col-md-4"><strong>DPT:</strong> Lengkap</div>
                <div class="col-md-4"><strong>Polio:</strong> Lengkap</div>
              -->
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
