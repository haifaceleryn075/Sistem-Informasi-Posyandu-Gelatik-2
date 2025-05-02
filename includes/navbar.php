<?php if (isLoggedIn()): ?>
<?php
$database = new Database();
$db = $database->getConnection();
// Ambil data profil dari database
$stmt = $db->prepare("SELECT nama_posyandu, logo, kelurahan FROM profil LIMIT 1");
$stmt->execute();
$profil = $stmt->fetch(PDO::FETCH_ASSOC);

// Default jika data tidak ditemukan
$namaPosyandu = $profil['nama_posyandu'] ?? 'SI-POSYANDU';
$logoPath = !empty($profil['logo']) ? '' . $profil['logo'] : 'default-logo.png';

?>
<?php $currentPage = $_GET['page'] ?? 'dashboard'; ?>
<nav class="sidebar bg-success text-white p-3">
<a href="<?php if (isAdmin()): ?> ?page=profil <?php endif; ?>" class="d-flex align-items-center text-white text-decoration-none">
    <div class="d-flex align-items-center mb-4">
        <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo" class="me-2 rounded-circle bg-white" style="width: 40px; height: 40px; object-fit: cover;">
        <div>
            <strong>SI-POSYANDU</strong><br><small><?= htmlspecialchars($namaPosyandu) ?></small>
        </div>
    </div>
</a>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'dashboard' ? 'active fw-bold' : '' ?>" href="?page=dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'data-anak' ? 'active fw-bold' : '' ?>" href="?page=data-anak">
                <i class="fas fa-baby me-2"></i>Data Bayi Dan Balita
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'data-ibu' ? 'active fw-bold' : '' ?>" href="?page=data-ibu">
                <i class="fas fa-female me-2"></i>Data Ibu Hamil
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'data-kader' ? 'active fw-bold' : '' ?>" href="?page=data-kader">
                <i class="fas fa-users me-2"></i>Data Kader Posyandu
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'jadwal-kegiatan' ? 'active fw-bold' : '' ?>" href="?page=jadwal-kegiatan">
                <i class="fas fa-calendar-alt me-2"></i>Jadwal Kegiatan
            </a>
        </li>
        <?php if (isAdmin()): ?>
        <li class="nav-item">
            <?php
            $laporanPages = ['laporan-anak', 'laporan-ibu'];
            $laporanExpanded = in_array($currentPage, $laporanPages);
            ?>
            <a class="nav-link text-white dropdown-toggle <?= $laporanExpanded ? '' : 'collapsed' ?>" data-bs-toggle="collapse" href="#dataLaporan" role="button" aria-expanded="<?= $laporanExpanded ? 'true' : 'false' ?>" aria-controls="dataLaporan">
                <i class="fas fa-file-alt me-2"></i>Laporan
            </a>
            <div class="collapse ps-3 <?= $laporanExpanded ? 'show' : '' ?>" id="dataLaporan">
                <a class="nav-link text-white small <?= $currentPage === 'laporan-anak' ? 'active fw-bold' : '' ?>" href="?page=laporan-anak">
                    <i class="fas fa-file-medical me-2"></i>Laporan Bayi & Balita
                </a>
                <a class="nav-link text-white small <?= $currentPage === 'laporan-ibu' ? 'active fw-bold' : '' ?>" href="?page=laporan-ibu">
                    <i class="fas fa-file-medical-alt me-2"></i>Laporan Ibu Hamil
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'users' ? 'active fw-bold' : '' ?>" href="?page=users">
                <i class="fas fa-user-cog me-2"></i>Manajemen User
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item mt-4">
            <a class="nav-link text-white small" href="?page=logout">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </li>
    </ul>
</nav>
<?php endif; ?>
