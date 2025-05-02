<?php
$database = new Database();
$db = $database->getConnection();

function validateUserInput($username, $password, $confirm_password, $action, $id, $db) {
    $errors = [];
    if ($password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak cocok";
    }
    $stmt = $db->prepare("SELECT id FROM users WHERE username = :username AND id != :id");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors[] = "Username sudah digunakan";
    }
    return $errors;
}

function saveUser($action, $id, $username, $password, $role, $db) {
    if ($action === 'edit' && $id) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET username=?, password=?, role=? WHERE id=?");
            $stmt->execute([$username, $hashed_password, $role, $id]);
        } else {
            $stmt = $db->prepare("UPDATE users SET username=?, role=? WHERE id=?");
            $stmt->execute([$username, $role, $id]);
        }
        $_SESSION['success'] = "User berhasil diperbarui!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $role]);
        $_SESSION['success'] = "User berhasil ditambahkan!";
    }
    header("Location: ?page=users");
    exit();
}

function deleteUser($id, $db) {
    if ($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "Anda tidak dapat menghapus akun sendiri!";
    } else {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "User berhasil dihapus!";
    }
    header("Location: ?page=users");
    exit();
}

function getUserById($id, $db) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAllUsers($db) {
    $query = "SELECT * FROM users ORDER BY username ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function searchUsers($search, $db) {
    $query = "SELECT * FROM users";
    if ($search) {
        $query .= " WHERE username LIKE ? OR role LIKE ?";
    }
    $query .= " ORDER BY username ASC";
    $stmt = $db->prepare($query);
    if ($search) {
        $search_param = "%$search%";
        $stmt->execute([$search_param, $search_param]);
    } else {
        $stmt->execute();
    }
    return $stmt->fetchAll();
}
