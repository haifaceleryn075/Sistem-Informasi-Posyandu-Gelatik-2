<!-- Tampilkan pesan sukses jika ada -->
<?php
$id_imunisasi = $_GET['id_imunisasi'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$search_query && $_POST["tabname"] == "imunisasi") {
    $jenis_imunisasi = $_POST['jenis_imunisasi'];
    $tgl_beri = $_POST['tgl_beri'];
    $usia = $_POST['usia'];
    $petugas = $_POST['petugas'];
    $keterangan = $_POST['keterangan'];

    // Simpan data (tambah/edit)
    savedataimunisasi($active_tab, $action, $id_detail, $id_imunisasi, $jenis_imunisasi, $tgl_beri, $usia, $petugas, $keterangan, $db);
}
if ($action === 'delete' && $id_imunisasi) {
    deletedataimunisasi($active_tab, $id_detail, $id_imunisasi, $db);
}
$imunisasi = getAlldataimunisasi($id_detail, $db);
$current_imunisasi = ($action === 'edit' && $id_imunisasi) ? getdataimunisasiById($id_imunisasi, $db) : null;
?>

<div class="container mt-4">
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Imunisasi</h2>
        <button class="btn btn-primary" onclick="toggleImunisasiForm()">
            <?= $current_imunisasi ? 'Edit Data Imunisasi' : 'Tambah Data Imunisasi' ?>
        </button>
    </div>

    <div id="imunisasiForm" style="display: <?= ($action === 'add' || $action === 'edit') ? 'block' : 'none' ?>;">
        <div class="card mb-4">
            <div class="card-header">
                <?= $current_imunisasi ? 'Edit Data Imunisasi' : 'Tambah Data Imunisasi Baru' ?>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="?page=data-anak-detail&id_detail=<?= $id_detail ?><?= $current_imunisasi ? '&action=edit&id_imunisasi=' . $current_imunisasi['id_imunisasi'] : '&action=add' ?>&tab=imunisasi">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Imunisasi</label>
                            <input type="text" name="jenis_imunisasi" class="form-control" required value="<?= $current_imunisasi['jenis_imunisasi'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Diberikan</label>
                            <input type="date" name="tgl_beri" class="form-control" required value="<?= $current_imunisasi['tgl_beri'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usia</label>
                            <input type="text" name="usia" class="form-control" required value="<?= $current_imunisasi['usia'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Petugas</label>
                            <input type="text" name="petugas" class="form-control" required value="<?= $current_imunisasi['petugas'] ?? '' ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <select name="keterangan" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="sudah" <?= ($current_tumbuhkembang['keterangan'] ?? '') === 'sudah' ? 'selected' : '' ?>>sudah</option>
                                <option value="belum" <?= ($current_tumbuhkembang['keterangan'] ?? '') === 'belum' ? 'selected' : '' ?>>belum</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="tabname" value="imunisasi">
                    <input type="hidden" name="id_imunisasi" value="<?= $id_imunisasi ?>">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=data-anak" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Daftar Data Imunisasi</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="datatables-default table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID Imunisasi</th>
                            <th>ID Anak</th>
                            <th>Jenis Imunisasi</th>
                            <th>Tanggal Diberikan</th>
                            <th>Usia</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                            <th>Tanggal Entry</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($imunisasi) > 0): ?>
                            <?php foreach ($imunisasi as $row): ?>
                                <tr>
                                    <td><?= $row['id_imunisasi'] ?></td>
                                    <td><?= $row['id_anak'] ?></td>
                                    <td><?= $row['jenis_imunisasi'] ?></td>
                                    <td><?= $row['tgl_beri'] ?></td>
                                    <td><?= $row['usia'] ?></td>
                                    <td><?= $row['petugas'] ?></td>
                                    <td><?= $row['keterangan'] ?></td>
                                    <td><?= $row['tgl_entry'] ?></td>
                                    <td>
                                        <a href="?page=data-anak-detail&id_detail=<?= $row['id_anak'] ?>&action=edit&id_imunisasi=<?= $row['id_imunisasi'] ?>&tab=imunisasi" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?page=data-anak-detail&id_detail=<?= $row['id_anak'] ?>&action=delete&id_imunisasi=<?= $row['id_imunisasi'] ?>&tab=imunisasi" onclick="return confirm('Apakah Anda yakin?')" class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada Data Imunisasi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
