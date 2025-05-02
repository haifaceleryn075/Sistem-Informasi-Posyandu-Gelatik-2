<?php
$page_title = "Profil Posyandu";
// Menyisipkan file header, navbar, dan fungsi-fungsi terkait user
require_once 'includes/header.php';
require_once 'includes/navbar.php';
$database = new Database();
$db = $database->getConnection();

// Ambil data profil
$stmt = $db->prepare("SELECT * FROM profil LIMIT 1");
$stmt->execute();
$profil = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_posyandu = $_POST['nama_posyandu'];
    $alamat = $_POST['alamat'];
    $kelurahan = $_POST['kelurahan'];
    $kecamatan = $_POST['kecamatan'];
    $kabupaten = $_POST['kabupaten'];
    $provinsi = $_POST['provinsi'];

    // Upload logo jika ada file baru
    $logo = $profil['logo'];
    if (!empty($_FILES['logo']['name'])) {
        $logo_name = time() . '-' . basename($_FILES['logo']['name']);
        $upload_dir = 'uploads/';
        move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $logo_name);
        $logo = $upload_dir . $logo_name;
    }

    // Update data
    $stmt = $db->prepare("UPDATE profil SET nama_posyandu = ?, alamat = ?, kelurahan = ?, kecamatan = ?, kabupaten = ?, provinsi = ?, logo = ? WHERE id = ?");
    $stmt->execute([
        $nama_posyandu,
        $alamat,
        $kelurahan,
        $kecamatan,
        $kabupaten,
        $provinsi,
        $logo,
        $profil['id']
    ]);

    header("Location: ?page=profil");
    exit;
}
?>

<div class="container py-4">
    <div class="card">
        <div class="card-header fw-bold">Profil Posyandu</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <?php if (!empty($profil['logo'])): ?>
                            <img src="<?= $profil['logo'] ?>" alt="Logo Posyandu" class="img-thumbnail" width="150">
                        <?php else: ?>
                            <img src="assets/img/placeholder.png" alt="Logo Posyandu" class="img-thumbnail" width="150">
                        <?php endif; ?>
                        <input type="file" name="logo" class="form-control mt-2">
                    </div>
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Posyandu</label>
                            <input type="text" name="nama_posyandu" class="form-control" value="<?= htmlspecialchars($profil['nama_posyandu']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($profil['alamat']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kelurahan/Desa</label>
                            <input type="text" name="kelurahan" class="form-control" value="<?= htmlspecialchars($profil['kelurahan']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="<?= htmlspecialchars($profil['kecamatan']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control" value="<?= htmlspecialchars($profil['kabupaten']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" value="<?= htmlspecialchars($profil['provinsi']) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
