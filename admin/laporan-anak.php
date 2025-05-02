<?php
$page_title = "Laporan Anak";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$database = new Database();
$db = $database->getConnection();

$query = "
    SELECT 
        a.nama,
        DATE_FORMAT(a.ttl, '%d %b %Y') as tanggal_lahir,
        u.bb as berat,
        u.tb as tinggi,
        CASE 
            WHEN u.bb / POWER(u.tb/100, 2) < 14 THEN 'Gizi Kurang'
            ELSE 'Gizi Baik'
        END as status_gizi,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM imunisasi_anak i 
                WHERE i.id_anak = a.id_anak AND i.keterangan = 'belum'
            ) THEN 'Belum Lengkap'
            ELSE 'Lengkap'
        END as imunisasi,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM perkembangan_anak p 
                WHERE p.id_anak = a.id_anak
            ) THEN
                (SELECT 
                    CASE 
                        WHEN p.kesehatan = 'sehat' THEN 'Pertumbuhan baik'
                        ELSE 'Perlu pemantauan'
                    END
                 FROM perkembangan_anak p 
                 WHERE p.id_anak = a.id_anak 
                 ORDER BY p.tgl_entry DESC LIMIT 1)
            ELSE 'Tidak ada catatan'
        END as catatan
    FROM data_anak a
    LEFT JOIN (
        SELECT * FROM pengukuran_anak 
        WHERE (id_ukur) IN (
            SELECT MAX(id_ukur) FROM pengukuran_anak GROUP BY id_anak
        )
    ) u ON a.id_anak = u.id_anak
";

$stmt = $db->prepare($query);
$stmt->execute();
$data_kesehatan = $stmt->fetchAll();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Data Kesehatan Detil</h2>
    </div>

    <table id="data-kesehatan" class="table table-striped datatables-default">
        <thead class="table-success">
            <tr>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Berat (kg)</th>
                <th>Tinggi (cm)</th>
                <th>Status Gizi</th>
                <th>Imunisasi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($data_kesehatan) > 0): ?>
                <?php foreach ($data_kesehatan as $data): ?>
                    <tr>
                        <td><?= htmlspecialchars($data['nama']) ?></td>
                        <td><?= htmlspecialchars($data['tanggal_lahir']) ?></td>
                        <td><?= htmlspecialchars($data['berat']) ?></td>
                        <td><?= htmlspecialchars($data['tinggi']) ?></td>
                        <td><?= htmlspecialchars($data['status_gizi']) ?></td>
                        <td><?= htmlspecialchars($data['imunisasi']) ?></td>
                        <td><?= htmlspecialchars($data['catatan']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Tidak ada data kesehatan tersedia.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>