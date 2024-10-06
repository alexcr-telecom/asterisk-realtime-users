<?php
// index.php

require_once 'functions.php';
require_once 'templates/header.php';

$endpoints = get_all_endpoints();
?>

<h1>User Management</h1>
<a href="users/add.php" class="btn btn-primary mb-3">Add New User</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Transport</th>
            <th>Allowed Codecs</th>
            <th>WebRTC</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($endpoints as $endpoint): ?>
        <tr>
            <td><?= htmlspecialchars($endpoint['id']); ?></td>
            <td><?= htmlspecialchars($endpoint['transport']); ?></td>
            <td><?= htmlspecialchars($endpoint['allow']); ?></td>
            <td><?= htmlspecialchars($endpoint['webrtc']); ?></td>
            <td>
                <a href="users/edit.php?id=<?= urlencode($endpoint['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="users/delete.php?id=<?= urlencode($endpoint['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'templates/footer.php'; ?>

