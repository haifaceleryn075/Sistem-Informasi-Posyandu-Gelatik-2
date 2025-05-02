<?php
$page_title = "Jadwal Kegiatan Posyandu";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'function/jadwal-kegiatan_functions.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$search_query = $_POST['search_query'] ?? '';

// Proses simpan (tambah/edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$search_query) {
    $tgl = $_POST['tgl'];
    $waktu = $_POST['waktu'];
    $kegiatan = $_POST['kegiatan'];
    $lokasi = $_POST['lokasi'];
    $id_kader = $_POST['id_kader'];
    $status = $_POST['status'];

    savejadwal($action, $id, $tgl, $waktu, $kegiatan, $lokasi, $id_kader, $status, $db);
}

if ($action === 'delete' && $id) {
    deletejadwal($id, $db);
}

$current_jadwal = ($action === 'edit' && $id) ? getjadwalById($id, $db) : null;
$datakader = getAlldatakader($db);

$jadwal_list = $search_query ? searchjadwal($search_query, $db) : getAlljadwal($db);
?>

<div class="container py-4">
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Jadwal Kegiatan Posyandu</h2>
        <?php if (isAdmin()): ?>
        <button class="btn btn-primary" onclick="toggleUserForm()">
            <?= $current_jadwal ? 'Edit Jadwal' : 'Tambah Jadwal' ?>
        </button>
        <?php endif; ?>
    </div>

    <div id="userForm" style="display: <?= ($action === 'add' || $action === 'edit') ? 'block' : 'none' ?>;">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="mb-3"><?= $current_jadwal ? 'Edit Jadwal' : 'Tambah Jadwal Baru' ?></h4>
                <form method="POST" action="?page=jadwal-kegiatan<?= $current_jadwal ? '&action=edit&id=' . $current_jadwal['id_kegiatan'] : '&action=add' ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tgl" class="form-control" required value="<?= $current_jadwal['tgl'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="time" name="waktu" class="form-control" required value="<?= $current_jadwal['waktu'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kegiatan</label>
                            <input type="text" name="kegiatan" class="form-control" required value="<?= $current_jadwal['kegiatan'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required value="<?= $current_jadwal['lokasi'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kader Penanggung Jawab</label>
                            <select name="id_kader" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($datakader as $k): ?>
                                    <option value="<?= $k['id_kader'] ?>" <?= ($current_jadwal['id_kader'] ?? '') == $k['id_kader'] ? 'selected' : '' ?>>
                                        <?= $k['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Akan Datang" <?= ($current_jadwal['status'] ?? '') === 'Akan Datang' ? 'selected' : '' ?>>Akan Datang</option>
                                <option value="Selesai" <?= ($current_jadwal['status'] ?? '') === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=jadwal-kegiatan" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="datatables-default table table-bordered table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Kegiatan</th>
                            <th>Lokasi</th>
                            <th>Penanggung Jawab</th>
                            <th>Status</th>
                            <?php if (isAdmin()): ?>
                            <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($jadwal_list) > 0): ?>
                            <?php foreach ($jadwal_list as $row): ?>
                                <tr>
                                    <td><?= $row['tgl'] ?></td>
                                    <td><?= $row['waktu'] ?></td>
                                    <td><?= $row['kegiatan'] ?></td>
                                    <td><?= $row['lokasi'] ?></td>
                                    <td><?= $row['nama_kader'] ?? '-' ?></td>
                                    <td><?= $row['status'] ?></td>
                                    <?php if (isAdmin()): ?> 
                                    <td>
                                        <a href="?page=jadwal-kegiatan&action=edit&id=<?= $row['id_kegiatan'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?page=jadwal-kegiatan&action=delete&id=<?= $row['id_kegiatan'] ?>" onclick="return confirm('Apakah Anda yakin?')" class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">Tidak ada data jadwal</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
