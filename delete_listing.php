<?php
// delete_listing.php
require_once 'init.php';
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: manage_listings.php");
    exit;
}

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// CSRF check
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    die("Invalid CSRF token.");
}

$user_id = $_SESSION['user_id'];
$home_id = $_POST['home_id'] ?? '';

if (empty($home_id)) {
    die("Invalid listing ID.");
}

// 1. Verify the listing belongs to the logged-in user
$stmt = $pdo->prepare("SELECT id FROM homes WHERE id = ? AND user_id = ?");
$stmt->execute([$home_id, $user_id]);
$listing = $stmt->fetch();

if (!$listing) {
    die("No matching listing found or you do not have permission to delete it.");
}

// 2. Perform the deletion
try {
    $del_stmt = $pdo->prepare("DELETE FROM homes WHERE id = ?");
    $del_stmt->execute([$home_id]);
    header("Location: manage_listings.php");
    exit;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
