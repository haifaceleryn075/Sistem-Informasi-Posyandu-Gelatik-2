<!-- Tampilkan pesan sukses jika ada -->
<?php
$id_ukur=$_GET['id_ukur'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$search_query && $_POST["tabname"] == "pengukuran") {
    $bb = $_POST['bb'];
    $tb = $_POST['tb'];
    $lk = $_POST['lk'];
    $ll = $_POST['ll'];
    $usia = $_POST['usia'];
    $bulan = $_POST['bulan'];

        // Simpan user (tambah/edit)
        savedatapengukuran($active_tab, $action, $id_detail, $id_ukur, $bb, $tb, $lk, $ll, $usia, $bulan, $db);     
}
if ($action === 'delete' && $id_ukur) {
    deletedatapengukuran($active_tab, $id_detail, $id_ukur, $db);
}
 $pengukuran = getAlldatapengukuran($id_detail, $db); 
 $current_pengukuran = ($action === 'edit' && $id_ukur) ? getdatapengukuranById($id_ukur, $db) : null;


     // Ambil data pengukuran dari tabel pengukuran
     $stmt_chart = $db->prepare("SELECT usia, bb FROM pengukuran_anak WHERE id_anak = :id_anak ORDER BY tgl_entry DESC");
     $stmt_chart->bindParam(':id_anak', $id_detail, PDO::PARAM_INT); // Ganti $id_anak dengan id anak yang sesuai
     $stmt_chart->execute();
     $data_chart = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);
 
     $bulan_chart = [];
     $berat_badan_chart = [];
 
     foreach ($data_chart as $row_chart) {
         $bulan_chart[] = $row_chart['usia'];
         $berat_badan_chart[] = $row_chart['bb'];
     }

 ?>

<div class="container mt-4">
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

    <!-- Grafik Data Pengukuran -->
    <h2>Grafik Berat Badan per Bulan</h2>
    <canvas id="chart" width="600" height="300"></canvas>
    <script>
        const ctx = document.getElementById('chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($bulan_chart) ?>,  // Bulan
                datasets: [{
                    label: 'Berat Badan (kg)',   // Label untuk grafik
                    data: <?= json_encode($berat_badan_chart) ?>, // Data berat badan
                    fill: false,
                    borderColor: 'green',  // Warna garis grafik
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Berat Badan (kg)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Usia (Bulan)'
                        }
                    }
                }
            }
        });
    </script>


    <!-- Judul dan tombol tambah/edit -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Pengukuran</h2>
        <button class="btn btn-primary" onclick="toggleUkurForm()">
            <?= $current_pengukuran ? 'Edit Data Pengukuran' : 'Tambah Data Pengukuran' ?>
        </button>
    </div>

    <!-- Form Tambah/Edit -->
    <div id="ukurForm" style="display: <?= ($action === 'add' || $action === 'edit') ? 'block' : 'none' ?>;">
        <div class="card mb-4">
            <div class="card-header">
                <?= $current_pengukuran ? 'Edit Data Pengukuran' : 'Tambah Data Pengukuran Baru' ?>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="?page=data-anak-detail&id_detail=<?= $id_detail ?><?= $current_pengukuran ? '&action=edit&id_ukur=' . $current_pengukuran['id_ukur'] : '&action=add' ?>&tab=pengukuran">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Berat Badan</label>
                            <input type="text" name="bb" class="form-control" required value="<?= $current_pengukuran['bb'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tinggi Badan</label>
                            <input type="text" name="tb" class="form-control" required value="<?= $current_pengukuran['tb'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lingkar Kepala</label>
                            <input type="text" name="lk" class="form-control" required value="<?= $current_pengukuran['lk'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lingkar Lengan</label>
                            <input type="text" name="ll" class="form-control" required value="<?= $current_pengukuran['ll'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usia</label>
                            <input type="number" name="usia" class="form-control" required value="<?= $current_pengukuran['usia'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select" required>
                                <?php
                                $bulan_list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                foreach ($bulan_list as $bulan) {
                                    $selected = ($current_pengukuran['bulan'] ?? '') === $bulan ? 'selected' : '';
                                    echo "<option value='$bulan' $selected>$bulan</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="tabname" value="pengukuran">
                    <input type="hidden" name="id_ukur" value="<?= $id_ukur ?>">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=data-anak" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data Pengukuran -->
    <div class="card mt-4">
        <div class="card-header">Daftar Data Pengukuran</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="datatables-default table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID Ukur</th>
                            <th>ID Anak</th>
                            <th>Berat Badan</th>
                            <th>Tinggi Badan</th>
                            <th>Lingkar Kepala</th>
                            <th>Lingkar Lengan</th>
                            <th>Usia</th>
                            <th>Bulan</th>
                            <th>Tanggal Entry</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($pengukuran) > 0): ?>
                            <?php foreach ($pengukuran as $row): ?>
                                <tr>
                                    <td><?= $row['id_ukur'] ?></td>
                                    <td><?= $row['id_anak'] ?></td>
                                    <td><?= $row['bb'] ?></td>
                                    <td><?= $row['tb'] ?></td>
                                    <td><?= $row['lk'] ?></td>
                                    <td><?= $row['ll'] ?></td>
                                    <td><?= $row['usia'] ?> bln</td>
                                    <td><?= $row['bulan'] ?></td>
                                    <td><?= $row['tgl_entry'] ?></td>
                                    <td>
                                        <a href="?page=data-anak-detail&id_detail=<?= $row['id_anak'] ?>&action=edit&id_ukur=<?= $row['id_ukur'] ?>&tab=pengukuran" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="?page=data-anak-detail&id_detail=<?= $row['id_anak'] ?>&action=delete&id_ukur=<?= $row['id_ukur'] ?>&tab=pengukuran" onclick="return confirm('Apakah Anda yakin?')" class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada Data Pengukuran</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
