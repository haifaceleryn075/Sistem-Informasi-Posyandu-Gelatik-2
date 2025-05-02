<?php
$page_title = "Laporan Anak";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$database = new Database();
$db = $database->getConnection();

// Ambil data usia kehamilan untuk distribusi trimester
$stmtTrimester = $db->prepare("SELECT usia_kehamilan FROM data_ibu");
$stmtTrimester->execute();
$dataTrimester = $stmtTrimester->fetchAll(PDO::FETCH_ASSOC);

$trimester = [1 => 0, 2 => 0, 3 => 0];
foreach ($dataTrimester as $row) {
    $usia = $row['usia_kehamilan'];
    if ($usia >= 1 && $usia <= 3) {
        $trimester[1]++;
    } elseif ($usia >= 4 && $usia <= 6) {
        $trimester[2]++;
    } elseif ($usia >= 7 && $usia <= 9) {
        $trimester[3]++;
    }
}
$total = array_sum($trimester);

// Ambil data usia kehamilan untuk risiko kehamilan
$stmtRisiko = $db->prepare("SELECT usia_kehamilan FROM data_ibu");
$stmtRisiko->execute();
$dataRisiko = $stmtRisiko->fetchAll(PDO::FETCH_ASSOC);

$risiko = ['rendah' => 0, 'tinggi' => 0];
foreach ($dataRisiko as $row) {
    $usia = $row['usia_kehamilan'];
    $risiko[$usia < 8 ? 'rendah' : 'tinggi']++;
}
$totalRisiko = array_sum($risiko);



// Ambil parameter filter
$tgl_dari = $_GET['tgl_dari'] ?? '';
$tgl_sampai = $_GET['tgl_sampai'] ?? '';
$filter_trimester = $_GET['trimester'] ?? '';

// Query data ibu berdasarkan filter
$whereClauses = [];
$params = [];

// Filter tanggal
if (!empty($tgl_dari) && !empty($tgl_sampai)) {
    $whereClauses[] = "tgl_periksaakhir BETWEEN :tgl_dari AND :tgl_sampai";
    $params[':tgl_dari'] = $tgl_dari;
    $params[':tgl_sampai'] = $tgl_sampai;
}

// Filter trimester
if (!empty($filter_trimester)) {
    if ($filter_trimester == '1') {
        $whereClauses[] = "usia_kehamilan BETWEEN 1 AND 3";
    } elseif ($filter_trimester == '2') {
        $whereClauses[] = "usia_kehamilan BETWEEN 4 AND 6";
    } elseif ($filter_trimester == '3') {
        $whereClauses[] = "usia_kehamilan BETWEEN 7 AND 9";
    }
}

$whereSql = '';
if (!empty($whereClauses)) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

$sql = "SELECT * FROM data_ibu $whereSql ORDER BY tgl_periksaakhir DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$dataIbu = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
<h2 class="fw-bold">Laporan Ibu Hamil</h2>
<form class="row g-3 mb-4" method="GET" action="">
    <input type="hidden" name="page" value="laporan-ibu">
        <div class="col-md-3">
            <label for="tgl_dari" class="form-label">Dari Tanggal</label>
            <input type="date" class="form-control" id="tgl_dari" name="tgl_dari" value="<?= htmlspecialchars($tgl_dari) ?>">
        </div>
        <div class="col-md-3">
            <label for="tgl_sampai" class="form-label">Sampai Tanggal</label>
            <input type="date" class="form-control" id="tgl_sampai" name="tgl_sampai" value="<?= htmlspecialchars($tgl_sampai) ?>">
        </div>
        <div class="col-md-3">
            <label for="trimester" class="form-label">Trimester</label>
            <select class="form-select" id="trimester" name="trimester">
                <option value="" <?= $filter_trimester == '' ? 'selected' : '' ?>>Semua</option>
                <option value="1" <?= $filter_trimester == '1' ? 'selected' : '' ?>>Trimester 1 (1-3 bln)</option>
                <option value="2" <?= $filter_trimester == '2' ? 'selected' : '' ?>>Trimester 2 (4-6 bln)</option>
                <option value="3" <?= $filter_trimester == '3' ? 'selected' : '' ?>>Trimester 3 (7-9 bln)</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
        </div>
    </form>

    <?php if (!empty($dataIbu)) : ?>
        <div class="table-responsive">
            <table class="datatables-default table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Nama Ibu</th>
                        <th>Usia Kehamilan (bln)</th>
                        <th>Tanggal Pemeriksaan Terakhir</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataIbu as $i => $ibu): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($ibu['nama']) ?></td>
                            <td><?= $ibu['usia_kehamilan'] ?></td>
                            <td><?= date('d-m-Y', strtotime($ibu['tgl_periksaakhir'])) ?></td>
                            <td><?= htmlspecialchars($ibu['alamat']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="alert alert-warning">Tidak ada data ditemukan.</div>
    <?php endif; ?>

    <div class="row">
        <!-- Grafik Distribusi Trimester -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Distribusi Usia Kehamilan</h5>
                </div>
                <div class="card-body">
                    <canvas id="kehamilanChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Risiko Kehamilan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Status Risiko Kehamilan</h5>
                </div>
                <div class="card-body">
                    <canvas id="risikoChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Chart -->
<script>
    const ctx1 = document.getElementById('kehamilanChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Trimester 1', 'Trimester 2', 'Trimester 3'],
            datasets: [{
                label: 'Jumlah Ibu',
                data: [<?= $trimester[1] ?>, <?= $trimester[2] ?>, <?= $trimester[3] ?>],
                backgroundColor: ['#007bff', '#28a745', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let jumlah = context.raw;
                            let persen = ((jumlah / <?= $total ?>) * 100).toFixed(0);
                            return `${jumlah} ibu (${persen}%)`;
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const ctx2 = document.getElementById('risikoChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Risiko Rendah', 'Risiko Tinggi'],
            datasets: [{
                label: 'Jumlah Ibu',
                data: [<?= $risiko['rendah'] ?>, <?= $risiko['tinggi'] ?>],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let jumlah = context.raw;
                            let persen = ((jumlah / <?= $totalRisiko ?>) * 100).toFixed(0);
                            return `${jumlah} ibu (${persen}%)`;
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
