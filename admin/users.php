<?php
$page_title = "Manajemen User";
// Menyisipkan file header, navbar, dan fungsi-fungsi terkait user
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'function/users_functions.php';

// Mendapatkan parameter dari URL
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';
$search_query = $_POST['search_query'] ?? '';

// Handle pengiriman form tambah/edit user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$search_query) {
    $username = htmlspecialchars($_POST['username']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input user
    $errors = validateUserInput($username, $password, $confirm_password, $action, $id, $db);
    if (empty($errors)) {
        // Simpan user (tambah/edit)
        saveUser($action, $id, $username, $password, $role, $db);
    }
}

// Handle aksi hapus user
if ($action === 'delete' && $id) {
    deleteUser($id, $db);
}

// Ambil data user untuk form edit (jika ada)
$current_user = ($action === 'edit' && $id) ? getUserById($id, $db) : null;

// Ambil data semua user atau hasil pencarian
if ($search_query) {
    $users = searchUsers($search_query, $db);
} else {
    $users = getAllUsers($db);
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

    <!-- Header dan tombol tambah user -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen User</h2>
        <button class="btn btn-primary" onclick="toggleUserForm()">
            <?= $current_user ? 'Edit User' : 'Tambah User' ?>
        </button>
    </div>

    <!-- Form Tambah/Edit -->
    <div id="userForm" style="display: <?= ($action === 'add' || $action === 'edit') ? 'block' : 'none' ?>;">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="mb-3"><?= $current_user ? 'Edit User' : 'Tambah User Baru' ?></h4>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="?page=users<?= $current_user ? '&action=edit&id=' . $current_user['id'] : '&action=add' ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required value="<?= $current_user['username'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="user" <?= ($current_user['role'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= ($current_user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <?= $current_user ? '(Kosongkan jika tidak diubah)' : '' ?></label>
                            <input type="password" name="password" class="form-control" <?= $current_user ? '' : 'required' ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" <?= $current_user ? '' : 'required' ?>>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="?page=users" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel User -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="datatables-default table table-bordered table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $i => $user): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <a href="?page=users&action=edit&id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="?page=users&action=delete&id=<?= $user['id'] ?>" onclick="return confirm('Apakah Anda yakin?')" class="btn btn-sm btn-danger">Hapus</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data user</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
