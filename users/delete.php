<?php
require_once '../functions.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        delete_user($id);
    } catch (Exception $e) {
        // Handle exception if needed
    }
}

header('Location: ../index.php');
exit();
