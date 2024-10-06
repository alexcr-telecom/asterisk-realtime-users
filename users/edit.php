<?php
require_once '../functions.php';
require_once '../templates/header.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ../index.php');
    exit();
}

$user = get_endpoint($id);

if (!$user) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'password' => $_POST['password'],
        'transport' => $_POST['transport'],
        'allow' => $_POST['allow'],
        'max_contacts' => $_POST['max_contacts'],
        'remove_existing' => $_POST['remove_existing'],
        'qualify_frequency' => $_POST['qualify_frequency'],
        'webrtc' => isset($_POST['webrtc']) ? 'yes' : 'no' // Default to 'no' if not set
    ];

    try {
        update_user($id, $data);
        header('Location: ../index.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<h2>Edit User <?= htmlspecialchars($id); ?></h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post">
    <!-- Password -->
    <div class="form-group">
        <label for="password">Password</label>
        <input type="text" name="password" id="password" class="form-control" value="" required>
    </div>
    <!-- Transport -->
    <div class="form-group">
        <label for="transport">Transport</label>
        <select name="transport" id="transport" class="form-control">
            <option value="udp" <?= $user['transport'] == 'udp' ? 'selected' : ''; ?>>UDP</option>
            <option value="tcp" <?= $user['transport'] == 'tcp' ? 'selected' : ''; ?>>TCP</option>
            <option value="wss" <?= $user['transport'] == 'wss' ? 'selected' : ''; ?>>WSS</option>
        </select>
    </div>
    <!-- Allowed Codecs -->
    <div class="form-group">
        <label for="allow">Allowed Codecs</label>
        <input type="text" name="allow" id="allow" class="form-control" value="<?= htmlspecialchars($user['allow']); ?>">
    </div>
    <!-- Max Contacts -->
    <div class="form-group">
        <label for="max_contacts">Max Contacts</label>
        <input type="number" name="max_contacts" id="max_contacts" class="form-control" value="<?= htmlspecialchars($user['max_contacts'] ?? '1'); ?>">
    </div>
    <!-- Remove Existing -->
    <div class="form-group">
        <label for="remove_existing">Remove Existing</label>
        <select name="remove_existing" id="remove_existing" class="form-control">
            <option value="yes" <?= $user['remove_existing'] == 'yes' ? 'selected' : ''; ?>>Yes</option>
            <option value="no" <?= $user['remove_existing'] == 'no' ? 'selected' : ''; ?>>No</option>
        </select>
    </div>
    <!-- Qualify Frequency -->
    <div class="form-group">
        <label for="qualify_frequency">Qualify Frequency</label>
        <input type="number" name="qualify_frequency" id="qualify_frequency" class="form-control" value="<?= htmlspecialchars($user['qualify_frequency'] ?? '30'); ?>">
    </div>
    <!-- WebRTC Checkbox -->
    <div class="form-group">
        <label for="webrtc">WebRTC</label><br>
        <input type="checkbox" name="webrtc" id="webrtc" value="yes" <?= $user['webrtc'] == 'yes' ? 'checked' : ''; ?>>
        <label for="webrtc">Enable WebRTC</label>
    </div>
    <button type="submit" class="btn btn-success">Update User</button>
    <a href="../index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once '../templates/footer.php'; ?>
