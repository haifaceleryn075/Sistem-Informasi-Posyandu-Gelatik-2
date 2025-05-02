<?php
$page_title = "Data Kader Posyandu";
// Menyisipkan file header, navbar, dan fungsi-fungsi terkait user
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'function/data-kader_functions.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';
$search_query = $_POST['search_query'] ?? '';

// Handle pengiriman form tambah/edit user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$search_query) {
    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $no_tlp = $_POST['no_tlp'];
    $alamat = $_POST['alamat'];
    $mulai_tugas = $_POST['mulai_tugas'];
    $status = $_POST['status'];

    // Simpan user (tambah/edit)
    savedatakader($action, $id, $id_user, $nama, $jabatan, $no_tlp, $alamat, $mulai_tugas, $status, $db);
}

// Handle aksi hapus user
if ($action === 'delete' && $id) {
    deletedatakader($id, $db);
}

// Ambil data user untuk form edit (jika ada)
$current_datakader = ($action === 'edit' && $id) ? getdatakaderById($id, $db) : null;
$datauser = getAlldatauser($db);

// Ambil data semua user atau hasil pencarian
if ($search_query) {
    $datakader = searchkader($search_query, $db);
} else {
    $datakader = getAlldatakader($db);
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
    <!-- Judul dan tombol tambah/edit -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Data Kader Posyandu</h2>
        <?php if (isAdmin()): ?>
        <button class="btn btn-primary" onclick="toggleUserForm()">
            <?= $current_datakader ? 'Edit Data Kader' : 'Tambah Data Kader' ?>
        </button>
        <?php endif; ?>
    </div>

    <!-- Form Tambah/Edit -->
    <div id="userForm" style="display: <?= ($action === 'add' || $action === 'edit') ? 'block' : 'none' ?>;">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="mb-3"><?= $current_datakader ? 'Edit Data Kader' : 'Tambah Data Kader Baru' ?></h4>

                <form method="POST" action="?page=data-kader<?= $current_datakader ? '&action=edit&id=' . $current_datakader['id_kader'] : '&action=add' ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih User</label>
                            <select name="id_user" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($datauser as $u): ?>
                                    <option value="<?= $u['id'] ?>" <?= ($current_datakader['id_user'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                                        <?= $u['username'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required value="<?= $current_datakader['nama'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" required value="<?= $current_datakader['jabatan'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="no_tlp" class="form-control" required value="<?= $current_datakader['no_tlp'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required value="<?= $current_datakader['alamat'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mulai Tugas</label>
                            <input type="date" name="mulai_tugas" class="form-control" required value="<?= $current_datakader['mulai_tugas'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Aktif" <?= ($current_datakader['status'] ?? '') === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="Tidak Aktif" <?= ($current_datakader['status'] ?? '') === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=data-kader" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="datatables-default table table-bordered table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>ID Kader</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>No Telepon</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <?php if (isAdmin()): ?>
                            <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($datakader) > 0): ?>
                            <?php foreach ($datakader as $row): ?>
                                <tr>
                                    <td><?= $row['id_kader'] ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= $row['jabatan'] ?></td>
                                    <td><?= $row['no_tlp'] ?></td>
                                    <td><?= $row['alamat'] ?></td>
                                    <td><?= $row['status'] ?></td>
                                    <?php if (isAdmin()): ?>
                                    <td>
                                        <a href="?page=data-kader&action=edit&id=<?= $row['id_kader'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?page=data-kader&action=delete&id=<?= $row['id_kader'] ?>" onclick="return confirm('Apakah Anda yakin?')" class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data kader</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>
