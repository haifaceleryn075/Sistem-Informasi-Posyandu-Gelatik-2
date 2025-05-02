<?php
$id_kembang = $_GET['id_kembang'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$search_query && $_POST["tabname"] == "tumbuh-kembang") {
    $usia = $_POST['usia'];
    $motorik = $_POST['motorik'];
    $sosial = $_POST['sosial'];
    $kesehatan = $_POST['kesehatan'];

    // Simpan user (tambah/edit)
    savedatatumbuhkembang($active_tab, $action, $id_detail, $id_kembang, $usia, $motorik, $sosial, $kesehatan, $db);
}

if ($action === 'delete' && $id_kembang) {
    deletedatatumbuhkembang($active_tab, $id_detail, $id_kembang, $db);
}

$tumbuhkembang = getAlldatatumbuhkembang($id_detail, $db);
$current_tumbuhkembang = ($action === 'edit' && $id_kembang) ? getdatatumbuhkembangById($id_kembang, $db) : null;
?>

<div class="container mt-4">
    <!-- Pesan Sukses -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Pesan Error -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Judul dan Tombol -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Tumbuh Kembang</h2>
        <button class="btn btn-primary" onclick="toggleKembangForm()">
            <?= $current_tumbuhkembang ? 'Edit Data Tumbuh Kembang' : 'Tambah Data Perkembangan' ?>
        </button>
    </div>

    <!-- Form Tambah/Edit -->
    <div id="kembangForm" style="display: <?= ($action === 'add' || $action === 'edit') ? 'block' : 'none' ?>;">
        <div class="card mb-4">
            <div class="card-header">
                <?= $current_tumbuhkembang ? 'Edit Data Tumbuh Kembang' : 'Tambah Data Tumbuh Kembang Baru' ?>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="?page=data-anak-detail&id_detail=<?= $id_detail ?><?= $current_tumbuhkembang ? '&action=edit&id_kembang=' . $current_tumbuhkembang['id_kembang'] : '&action=add' ?>&tab=tumbuh_kembang">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usia</label>
                            <input type="text" name="usia" class="form-control" required value="<?= $current_tumbuhkembang['usia'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Motorik</label>
                            <input type="text" name="motorik" class="form-control" required value="<?= $current_tumbuhkembang['motorik'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Perkembangan Sosial</label>
                            <input type="text" name="sosial" class="form-control" required value="<?= $current_tumbuhkembang['sosial'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kesehatan</label>
                            <select name="kesehatan" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="sehat" <?= ($current_tumbuhkembang['kesehatan'] ?? '') === 'sehat' ? 'selected' : '' ?>>sehat</option>
                                <option value="tidak sehat" <?= ($current_tumbuhkembang['kesehatan'] ?? '') === 'tidak sehat' ? 'selected' : '' ?>>tidak sehat</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="tabname" value="tumbuh-kembang">
                    <input type="hidden" name="id_kembang" value="<?= $id_kembang ?>">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=data-anak" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Tumbuh Kembang -->
    <div class="card">
        <div class="card-header">Daftar Data Tumbuh Kembang</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="datatables-default table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID Tumbuh Kembang</th>
                            <th>ID Anak</th>
                            <th>Usia</th>
                            <th>Motorik</th>
                            <th>Perkembangan Sosial</th>
                            <th>Kesehatan</th>
                            <th>Tanggal Entry</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($tumbuhkembang) > 0): ?>
                            <?php foreach ($tumbuhkembang as $row): ?>
                                <tr>
                                    <td><?= $row['id_kembang'] ?></td>
                                    <td><?= $row['id_anak'] ?></td>
                                    <td><?= $row['usia'] ?></td>
                                    <td><?= $row['motorik'] ?></td>
                                    <td><?= $row['sosial'] ?></td>
                                    <td><?= $row['kesehatan'] ?></td>
                                    <td><?= $row['tgl_entry'] ?></td>
                                    <td>
                                        <a href="?page=data-anak-detail&id_detail=<?= $row['id_anak'] ?>&action=edit&id_kembang=<?= $row['id_kembang'] ?>&tab=tumbuh_kembang" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?page=data-anak-detail&id_detail=<?= $row['id_anak'] ?>&action=delete&id_kembang=<?= $row['id_kembang'] ?>&tab=tumbuh_kembang" onclick="return confirm('Apakah Anda yakin?')" class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada Data Tumbuh Kembang</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
