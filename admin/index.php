<?php
session_start();

define('SESSION_TIMEOUT', 300); // 5 minutes in seconds

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

// Refresh activity timestamp on every page load
$_SESSION['last_activity'] = time();

// ── Load user list from CSV ──────────────────────────────────────────────
$users    = [];
$csv_path = __DIR__ . '/../data/users.csv';

if (file_exists($csv_path)) {
    $lines = file($csv_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    array_shift($lines); // skip header row
    foreach ($lines as $line) {
        $cols = str_getcsv($line, ',', '"', '');
        if (count($cols) >= 6) {
            $users[] = [
                'id'         => $cols[0],
                'first_name' => $cols[1],
                'last_name'  => $cols[2],
                'email'      => $cols[3],
                'membership' => $cols[4],
                'join_date'  => $cols[5],
            ];
        }
    }
}

$seconds_remaining = SESSION_TIMEOUT - (time() - $_SESSION['last_activity']);

// Tell nav.php we are one level deep
$navBase = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin — User List | PulseFit</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/../partials/nav.php'; ?>

  <main class="container page">

    <div class="admin-header page-head">
      <div>
        <span class="eyebrow">Secure section</span>
        <h1 style="margin:4px 0 0;">PulseFit Members</h1>
      </div>
      <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <span class="muted" style="font-size:0.88rem;">
          Session expires in: <strong id="session-timer">5:00</strong>
        </span>
        <a class="btn btn-outline btn-small" href="../admin/download.php">
          Download CSV
        </a>
        <a class="btn btn-small" href="../logout.php">Logout</a>
      </div>
    </div>

    <div class="card" style="padding:0;overflow:hidden;">
      <?php if (empty($users)): ?>
        <p class="muted" style="padding:24px;">No users found in the data file.</p>
      <?php else: ?>
        <table class="user-table">
          <thead>
            <tr>
              <th>#</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Membership</th>
              <th>Join Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td><?php echo htmlspecialchars($u['id']); ?></td>
                <td><?php echo htmlspecialchars($u['first_name']); ?></td>
                <td><?php echo htmlspecialchars($u['last_name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td>
                  <?php if ($u['membership'] === 'Premium'): ?>
                    <span class="badge-premium">Premium</span>
                  <?php else: ?>
                    <span class="badge-standard">Standard</span>
                  <?php endif; ?>
                </td>
                <td class="muted"><?php echo htmlspecialchars($u['join_date']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <p class="muted" style="font-size:0.85rem;margin-top:12px;">
      <?php echo count($users); ?> member(s) listed. Document is available for download above.
    </p>

  </main>

  <footer class="footer">
    <div class="container">
      <span>© <?php echo date('Y'); ?> PulseFit App</span>
    </div>
  </footer>

  <script src="../assets/app.js" defer></script>
  <script>
    // Session countdown timer — auto-redirects on expiry
    (function () {
      var remaining = <?php echo (int)$seconds_remaining; ?>;
      var el = document.getElementById('session-timer');

      function tick() {
        if (!el) return;
        var m = Math.floor(remaining / 60);
        var s = remaining % 60;
        el.textContent = m + ':' + (s < 10 ? '0' + s : s);

        // Turn red in the last 60 seconds
        if (remaining <= 60) el.style.color = '#dc2626';

        if (remaining <= 0) {
          window.location.href = '../login.php?reason=timeout';
          return;
        }
        remaining--;
        setTimeout(tick, 1000);
      }

      tick();
    })();
  </script>
</body>
</html>
