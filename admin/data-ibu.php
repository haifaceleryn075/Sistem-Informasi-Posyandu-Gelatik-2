<?php
$page_title = "Data Ibu Hamil";
// Menyisipkan file header, navbar, dan fungsi-fungsi terkait user
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'function/data-ibu_functions.php';

// Mendapatkan parameter dari URL
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';
$search_query = $_POST['search_query'] ?? '';

// Handle pengiriman form tambah/edit user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$search_query) {
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl'];
    $usia = $_POST['usia'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $nik = $_POST['nik'];
    $suami = $_POST['suami'];
    $usia_kehamilan = $_POST['usia_kehamilan'];
    $hpht = $_POST['hpht'];
    $taksiran_lahir = $_POST['taksiran_lahir'];
    $goldar = $_POST['goldar'];
    $hamil_ke = $_POST['hamil_ke'];
    $petugas = $_POST['petugas'];
    $tgl_periksaakhir = $_POST['tgl_periksaakhir'];

    // Simpan data ibu hamil (tambah/edit)
    savedataibu($action, $id, $nama, $ttl, $usia, $alamat, $no_tlp, $nik, $suami, $usia_kehamilan, $hpht, $taksiran_lahir, $goldar, $hamil_ke, $petugas, $tgl_periksaakhir, $db);
}

// Handle aksi hapus user
if ($action === 'delete' && $id) {
    deletedataibu($id, $db);
}

if ($action === 'detail' && $id) {
    detaildataibu($id, $db);
}

// Ambil data ibu hamil untuk form edit (jika ada)
$current_dataibu = ($action === 'edit' && $id) ? getdataibuById($id, $db) : null;

// Ambil data semua ibu hamil atau hasil pencarian
if ($search_query) {
    $dataibu = searchdataibu($search_query, $db);
} else {
    $dataibu = getAlldataibu($db);
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
        <h2 class="fw-bold">Data Ibu Hamil</h2>
        <button class="btn btn-primary" onclick="toggleUserForm()">
            <?= $current_dataibu ? 'Edit Data' : 'Tambah Data' ?>
        </button>
    </div>

    <!-- Form Tambah/Edit -->
    <div id="userForm" style="display: <?= ($action === 'add' || $action === 'edit') ? 'block' : 'none' ?>;">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="mb-3"><?= $current_dataibu ? 'Edit Data Ibu Hamil' : 'Tambah Data Baru' ?></h4>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="?page=data-ibu<?= $current_dataibu ? '&action=edit&id=' . $current_dataibu['id_ibu'] : '&action=add' ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required value="<?= $current_dataibu['nama'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Tanggal Lahir</label>
                            <input type="date" name="ttl" class="form-control" required value="<?= $current_dataibu['ttl'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usia</label>
                            <input type="number" name="usia" class="form-control" required value="<?= $current_dataibu['usia'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required value="<?= $current_dataibu['alamat'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="no_tlp" class="form-control" required value="<?= $current_dataibu['no_tlp'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="number" name="nik" class="form-control" required value="<?= $current_dataibu['nik'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Suami</label>
                            <input type="text" name="suami" class="form-control" required value="<?= $current_dataibu['suami'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usia Kehamilan</label>
                            <input type="number" name="usia_kehamilan" class="form-control" required value="<?= $current_dataibu['usia_kehamilan'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">HPHT</label>
                            <input type="date" name="hpht" class="form-control" required value="<?= $current_dataibu['hpht'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Taksiran Lahir</label>
                            <input type="date" name="taksiran_lahir" class="form-control" required value="<?= $current_dataibu['taksiran_lahir'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Golongan Darah</label>
                            <select name="goldar" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="A" <?= ($current_dataibu['goldar'] ?? '') === 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= ($current_dataibu['goldar'] ?? '') === 'B' ? 'selected' : '' ?>>B</option>
                                <option value="O" <?= ($current_dataibu['goldar'] ?? '') === 'O' ? 'selected' : '' ?>>O</option>
                                <option value="AB" <?= ($current_dataibu['goldar'] ?? '') === 'AB' ? 'selected' : '' ?>>AB</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hamil ke</label>
                            <input type="number" name="hamil_ke" class="form-control" required value="<?= $current_dataibu['hamil_ke'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Petugas</label>
                            <input type="text" name="petugas" class="form-control" required value="Bidan Nurhayati">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Periksa Akhir</label>
                            <input type="date" name="tgl_periksaakhir" class="form-control" required value="<?= $current_dataibu['tgl_periksaakhir'] ?? '' ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=data-ibu" class="btn btn-secondary">Batal</a>
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
                            <th>ID Ibu</th>
                            <th>Nama Ibu</th>
                            <th>Tempat Tanggal Lahir</th>
                            <th>Usia</th>
                            <th>Nama Suami</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dataibu) > 0): ?>
                            <?php foreach ($dataibu as $row): ?>
                                <tr>
                                    <td><?= $row['id_ibu'] ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= $row['ttl'] ?></td>
                                    <td><?= $row['usia'] ?></td>
                                    <td><?= $row['suami'] ?></td>
                                    <td><?= $row['alamat'] ?></td>
                                    <td>
                                        <a href="?page=data-ibu&action=detail&id=<?= $row['id_ibu'] ?>" class="btn btn-sm btn-info">Detail</a>
                                        <?php if (isAdmin()): ?>
                                            <a href="?page=data-ibu&action=edit&id=<?= $row['id_ibu'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="?page=data-ibu&action=delete&id=<?= $row['id_ibu'] ?>" onclick="return confirm('Apakah Anda yakin?')" class="btn btn-sm btn-danger">Hapus</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data ibu hamil</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>
