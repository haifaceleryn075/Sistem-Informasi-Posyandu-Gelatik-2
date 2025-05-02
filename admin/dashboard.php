<?php
$page_title = "Dashboard";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$database = new Database();
$db = $database->getConnection();

// Hitung total anak
$stmt = $db->query("SELECT COUNT(*) FROM data_anak");
$total_anak = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM data_ibu");
$total_ibu = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM data_kader");
$total_kader = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM jadwal_kegiatan WHERE status = 'Akan Datang'");
$total_kegiatan = $stmt->fetchColumn();

// Ambil daftar jadwal kegiatan
$stmt_kegiatan = $db->query("SELECT jk.*, k.nama AS nama_kader
    FROM jadwal_kegiatan jk
    LEFT JOIN data_kader k ON jk.id_kader = k.id_kader
    WHERE jk.status = 'Akan Datang'
    ORDER BY jk.tgl ASC");
$jadwal_kegiatan = $stmt_kegiatan->fetchAll();

// Ambil daftar user dengan informasi kader dan status
$stmt_user_kader = $db->query("SELECT k.nama, u.username, k.status 
                               FROM users u 
                               LEFT JOIN data_kader k ON u.id = k.id_user
                               ORDER BY k.nama ASC LIMIT 5");
$user_kader = $stmt_user_kader->fetchAll();
?>

<!-- Main Content -->
<div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h2 class="text-success">SISTEM INFORMASI POSYANDU Gelatik 2</h2>
        <span class="text-muted"><?php echo $_SESSION['nama']; ?></span>
    </div>

    <h4 class="fw-bold text-success mt-4">Dashboard</h4>

    <div class="row mt-4">
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-success"><?= $total_anak ?></h3>
                    <p class="card-text">Bayi dan Balita</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-success"><?= $total_ibu ?></h3>
                    <p class="card-text">Ibu Hamil</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-success"><?= $total_kader ?></h3>
                    <p class="card-text">Kader Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-success"><?= $total_kegiatan ?></h3>
                    <p class="card-text">Jadwal akan datang</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Kegiatan -->
    <h4 class="fw-bold text-success mt-4">Jadwal Kegiatan akan Datang</h4> <!-- Menambahkan kelas text-success -->
    <table class="datatables-default table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Kegiatan</th>
                <th>Lokasi</th>
                <th>Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($jadwal_kegiatan)): ?>
                <?php foreach ($jadwal_kegiatan as $jadwal): ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($jadwal['tgl'])) ?></td>
                        <td><?= htmlspecialchars($jadwal['waktu']) ?></td>
                        <td><?= htmlspecialchars($jadwal['kegiatan']) ?></td>
                        <td><?= htmlspecialchars($jadwal['lokasi']) ?></td>
                        <td><?= htmlspecialchars($jadwal['nama_kader'] ?? 'Tidak ada') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">Tidak ada jadwal kegiatan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- User Kader -->
    <h4 class="fw-bold text-success mt-4">User Kader Terdaftar</h4> <!-- Menambahkan kelas text-success -->
    <table id="user-kader" class="table table-striped datatables-default">
        <thead class="table-success">
            <tr>
                <th>Nama User</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($user_kader) > 0): ?>
                <?php foreach ($user_kader as $kader): ?>
                    <tr>
                        <td><?= $kader['nama'] ?></td>
                        <td><?= $kader['username'] ?></td>
                        <td><?= $kader['status'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Tidak ada user kader yang terdaftar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
