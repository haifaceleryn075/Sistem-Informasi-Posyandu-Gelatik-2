<?php
$page_title = "Data Bayi Dan Balita";
// Menyisipkan file header, navbar, dan fungsi-fungsi terkait user
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'function/data-anak_functions.php';

// Mendapatkan parameter dari URL
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';
$search_query = $_POST['search_query'] ?? '';

// Handle pengiriman form tambah/edit user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$search_query) {
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $berat_lahir = $_POST['berat_lahir'];
    $panjang_lahir = $_POST['panjang_lahir'];
    $nama_ibu = $_POST['nama_ibu'];
    $nama_ayah = $_POST['nama_ayah'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $nik = $_POST['nik'];

        // Simpan user (tambah/edit)
        savedataanak($action, $id, $nama, $ttl, $jenis_kelamin, $berat_lahir, $panjang_lahir, $nama_ibu, $nama_ayah, $alamat, $no_tlp, $nik, $db);
}

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
    $dataanak = getAlldataanak($db);
}
?>

<div class="container py-4">
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
    <!-- Judul dan tombol -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            Data Bayi Dan Balita
        </h2>
        <button class="btn btn-primary" onclick="toggleUserForm()">
            <i class="fas fa-plus me-1"></i><?= $current_dataanak ? 'Edit Data' : 'Tambah Data' ?>
        </button>
    </div>

    <!-- Form Tambah/Edit -->
    <div id="userForm" style="display: <?= ($action === 'add' || $action === 'edit') ? 'block' : 'none' ?>;">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="mb-3">
                    <i class="fas fa-edit me-2"></i><?= $current_dataanak ? 'Edit Data Bayi dan Balita' : 'Tambah Data Baru' ?>
                </h4>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><i class="fas fa-exclamation-circle me-1"></i><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="?page=data-anak<?= $current_dataanak ? '&action=edit&id=' . $current_dataanak['id_anak'] : '&action=add' ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required value="<?= $current_dataanak['nama'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="ttl" class="form-control" required value="<?= $current_dataanak['ttl'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-Laki" <?= ($current_dataanak['jenis_kelamin'] ?? '') === 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                                <option value="Perempuan" <?= ($current_dataanak['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Berat Lahir (kg)</label>
                            <input type="text" name="berat_lahir" class="form-control" required value="<?= $current_dataanak['berat_lahir'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Panjang Lahir (cm)</label>
                            <input type="text" name="panjang_lahir" class="form-control" required value="<?= $current_dataanak['panjang_lahir'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" required value="<?= $current_dataanak['nama_ibu'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" required value="<?= $current_dataanak['nama_ayah'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="no_tlp" class="form-control" required value="<?= $current_dataanak['no_tlp'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" required value="<?= $current_dataanak['nik'] ?? '' ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?= $current_dataanak['alamat'] ?? '' ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                    <a href="?page=data-anak" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Batal
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel-anak" class="datatables-default table table-bordered table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>ID Anak</th>
                            <th>Nama Anak</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Nama Orang Tua</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dataanak) > 0): ?>
                            <?php foreach ($dataanak as $row): ?>
                                <tr>
                                    <td><?= $row['id_anak'] ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= $row['ttl'] ?></td>
                                    <td><?= $row['jenis_kelamin'] ?></td>
                                    <td><?= $row['nama_ayah'] ?> - <?= $row['nama_ibu'] ?></td>
                                    <td><?= $row['alamat'] ?></td>
                                    <td>
                                        <a href="?page=data-anak&action=detail&id=<?= $row['id_anak'] ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                        <?php if (isAdmin()): ?>
                                            <a href="?page=data-anak&action=edit&id=<?= $row['id_anak'] ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <a href="?page=data-anak&action=delete&id=<?= $row['id_anak'] ?>" onclick="return confirm('Apakah Anda yakin?')" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt me-1"></i>Hapus
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data bayi dan balita</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>
