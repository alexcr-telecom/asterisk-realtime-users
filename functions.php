<?php
// functions.php

require_once 'config.php';

// Fetch all endpoints
function get_all_endpoints() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM ps_endpoints');
    return $stmt->fetchAll();
}

// Fetch a single endpoint by ID
function get_endpoint($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM ps_endpoints WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Add a new user (ps_aors, ps_auths, ps_endpoints)
function add_user($data) {
    global $pdo;
    try {
        $pdo->beginTransaction();

        // Insert into ps_aors
        $stmt = $pdo->prepare('INSERT INTO ps_aors (id, max_contacts, remove_existing, qualify_frequency) VALUES (?, ?, ?, ?)');
        $stmt->execute([$data['id'], $data['max_contacts'], $data['remove_existing'], $data['qualify_frequency']]);

        // Insert into ps_auths
        $stmt = $pdo->prepare('INSERT INTO ps_auths (id, auth_type, password, username) VALUES (?, ?, ?, ?)');
        $stmt->execute([$data['id'], 'userpass', $data['password'], $data['id']]);

        // Insert into ps_endpoints
        $stmt = $pdo->prepare('INSERT INTO ps_endpoints (
            id, transport, aors, auth, context, disallow, allow,
            force_rport, rtp_symmetric, rewrite_contact, webrtc
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['id'],
            $data['transport'],
            $data['id'],
            $data['id'],
            'from-internal',        // Default context
            'all',
            $data['allow'],
            'yes',                  // force_rport default
            'yes',                  // rtp_symmetric default
            'yes',                  // rewrite_contact default
            $data['webrtc']         // webrtc value from form
        ]);

        $pdo->commit();
        return true;
    } catch (\Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Update user information
function update_user($id, $data) {
    global $pdo;
    try {
        $pdo->beginTransaction();

        // Update ps_aors
        $stmt = $pdo->prepare('UPDATE ps_aors SET max_contacts = ?, remove_existing = ?, qualify_frequency = ? WHERE id = ?');
        $stmt->execute([$data['max_contacts'], $data['remove_existing'], $data['qualify_frequency'], $id]);

        // Update ps_auths
        $stmt = $pdo->prepare('UPDATE ps_auths SET password = ? WHERE id = ?');
        $stmt->execute([$data['password'], $id]);

        // Update ps_endpoints
        $stmt = $pdo->prepare('UPDATE ps_endpoints SET transport = ?, allow = ?, webrtc = ? WHERE id = ?');
        $stmt->execute([
            $data['transport'],
            $data['allow'],
            $data['webrtc'],
            $id
        ]);

        $pdo->commit();
        return true;
    } catch (\Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Delete a user
function delete_user($id) {
    global $pdo;
    try {
        $pdo->beginTransaction();

        // Delete from ps_aors
        $stmt = $pdo->prepare('DELETE FROM ps_aors WHERE id = ?');
        $stmt->execute([$id]);

        // Delete from ps_auths
        $stmt = $pdo->prepare('DELETE FROM ps_auths WHERE id = ?');
        $stmt->execute([$id]);

        // Delete from ps_endpoints
        $stmt = $pdo->prepare('DELETE FROM ps_endpoints WHERE id = ?');
        $stmt->execute([$id]);

        $pdo->commit();
        return true;
    } catch (\Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
