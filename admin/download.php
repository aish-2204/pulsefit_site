<?php
session_start();

define('SESSION_TIMEOUT', 300); // 5 minutes

// ── Session guard ────────────────────────────────────────────────────────
if (empty($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}

// ── Timeout check ────────────────────────────────────────────────────────
if (isset($_SESSION['last_activity']) &&
    (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    session_unset();
    session_destroy();
    header('Location: ../login.php?reason=timeout');
    exit;
}

$_SESSION['last_activity'] = time();

// ── Stream the CSV file as a download ────────────────────────────────────
$file = __DIR__ . '/../data/users.csv';

if (!file_exists($file)) {
    http_response_code(404);
    echo 'User list file not found.';
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="pulsefit_users.csv"');
header('Content-Length: ' . filesize($file));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

readfile($file);
exit;
