<?php
session_start();

require_once __DIR__ . '/data/admin.php';

// If already logged in, go straight to admin
if (!empty($_SESSION['admin'])) {
    header('Location: admin/index.php');
    exit;
}

// Guard: ensure setup.php has been run
if (!defined('ADMIN_HASH') || ADMIN_HASH === '') {
    die('<p style="font-family:monospace;padding:40px;color:red;">
         Setup incomplete. Please run <strong>setup.php</strong> first, then delete it.</p>');
}

$error   = '';
$timeout = (isset($_GET['reason']) && $_GET['reason'] === 'timeout');
$logout  = (isset($_GET['reason']) && $_GET['reason'] === 'logout');

// ── Handle POST ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // SHA-256 Message Digest: hash(salt + password)
    $computed_hash = hash('sha256', ADMIN_SALT . $password);

    // Compare computed hash against stored hash
    if ($username === ADMIN_USER && $computed_hash === ADMIN_HASH) {
        $_SESSION['admin']         = true;
        $_SESSION['last_activity'] = time();

        header('Location: admin/index.php');
        exit;
    } else {
        // Generic message — never reveal which field was wrong
        $error = 'Invalid credentials. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | PulseFit</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/nav.php'; ?>

  <main class="login-wrap">
    <div class="login-card">
      <span class="eyebrow">Secure area</span>
      <h1>Admin Login</h1>
      <p class="muted" style="margin:0 0 24px;">Enter your credentials to access the admin section.</p>

      <?php if ($logout && !$error): ?>
        <div class="alert alert-info" role="alert">
          You have been logged out successfully.
        </div>
      <?php endif; ?>

      <?php if ($timeout && !$error): ?>
        <div class="alert alert-info" role="alert">
          Your session expired after 5 minutes of inactivity. Please log in again.
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="alert alert-error" role="alert">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php" novalidate>

        <div class="form-group">
          <label for="username">User ID</label>
          <input
            class="form-control"
            type="text"
            id="username"
            name="username"
            placeholder="Enter user ID"
            autocomplete="username"
            required
          >
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input
            class="form-control"
            type="password"
            id="password"
            name="password"
            placeholder="Enter password"
            autocomplete="current-password"
            required
          >
        </div>

        <button class="btn" type="submit" style="width:100%;margin-top:8px;">
          Log In
        </button>
      </form>
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <span>© <?php echo date('Y'); ?> PulseFit App</span>
    </div>
  </footer>

  <script src="assets/app.js" defer></script>
</body>
</html>
