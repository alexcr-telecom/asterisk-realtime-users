<?php
require_once '../functions.php';
require_once '../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $_POST['id'],
        'max_contacts' => $_POST['max_contacts'],
        'remove_existing' => $_POST['remove_existing'],
        'qualify_frequency' => $_POST['qualify_frequency'],
        'password' => $_POST['password'],
        'transport' => $_POST['transport'],
        'allow' => $_POST['allow'],
        'webrtc' => isset($_POST['webrtc']) ? 'yes' : 'no' // Default to 'no' if not set
    ];

    try {
        add_user($data);
        header('Location: ../index.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    // Generate a 12-character password
    $generated_password = bin2hex(random_bytes(6)); // Generates 12 hex characters
}
?>

<h2>Add New User</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post">
    <!-- User ID -->
    <div class="form-group">
        <label for="id">User ID (Extension Number)</label>
        <input type="text" name="id" id="id" class="form-control" required>
    </div>
    <!-- Password -->
    <div class="form-group">
        <label for="password">Password</label>
        <input type="text" name="password" id="password" class="form-control" value="<?= htmlspecialchars($generated_password); ?>" required>
    </div>
    <!-- Transport -->
    <div class="form-group">
        <label for="transport">Transport</label>
        <select name="transport" id="transport" class="form-control">
            <option value="udp">UDP</option>
            <option value="tcp">TCP</option>
            <option value="wss">WSS</option>
        </select>
    </div>
    <!-- Allowed Codecs -->
    <div class="form-group">
        <label for="allow">Allowed Codecs</label>
        <input type="text" name="allow" id="allow" class="form-control" value="opus,ulaw,h264">
    </div>
    <!-- Max Contacts -->
    <div class="form-group">
        <label for="max_contacts">Max Contacts</label>
        <input type="number" name="max_contacts" id="max_contacts" class="form-control" value="1">
    </div>
    <!-- Remove Existing -->
    <div class="form-group">
        <label for="remove_existing">Remove Existing</label>
        <select name="remove_existing" id="remove_existing" class="form-control">
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>
    <!-- Qualify Frequency -->
    <div class="form-group">
        <label for="qualify_frequency">Qualify Frequency</label>
        <input type="number" name="qualify_frequency" id="qualify_frequency" class="form-control" value="30">
    </div>
    <!-- WebRTC Checkbox -->
    <div class="form-group">
        <label for="webrtc">WebRTC</label><br>
        <input type="checkbox" name="webrtc" id="webrtc" value="yes">
        <label for="webrtc">Enable WebRTC</label>
    </div>
    <button type="submit" class="btn btn-success">Add User</button>
    <a href="../index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once '../templates/footer.php'; ?>
