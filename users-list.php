<?php
// Fetch users from the API
$apiUrl = 'api/users.php';
$jsonData = @file_get_contents($apiUrl);
$response = json_decode($jsonData, true);

$users = [];
if ($response && $response['success']) {
    $users = $response['data'];
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Users | PulseFit</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>
    .users-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .users-table thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
    
    .users-table th {
      padding: 15px;
      text-align: left;
      font-weight: 600;
    }
    
    .users-table td {
      padding: 12px 15px;
      border-bottom: 1px solid #e0e0e0;
    }
    
    .users-table tbody tr:hover {
      background: #f9f9f9;
    }
    
    .users-table tbody tr:last-child td {
      border-bottom: none;
    }
    
    .membership-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    
    .badge-premium {
      background: #e3f2fd;
      color: #1565c0;
    }
    
    .badge-standard {
      background: #f3e5f5;
      color: #6a1b9a;
    }
  </style>
</head>
<body>
  <?php include 'partials/nav.php'; ?>

  <main class="container page">
    <header class="page-head">
      <p class="eyebrow">Community</p>
      <h1>PulseFit Members</h1>
      <p class="muted">Browse all registered members and their membership details.</p>
    </header>

    <?php if (empty($users)): ?>
      <div style="background: #f5f5f5; padding: 40px; border-radius: 8px; text-align: center;">
        <p class="muted">No users found. The API may be unavailable.</p>
      </div>
    <?php else: ?>
      <div style="overflow-x: auto;">
        <table class="users-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Membership</th>
              <th>Join Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><?php echo htmlspecialchars($user['id'], ENT_QUOTES); ?></td>
                <td>
                  <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name'], ENT_QUOTES); ?>
                </td>
                <td>
                  <a href="mailto:<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>">
                    <?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>
                  </a>
                </td>
                <td>
                  <span class="membership-badge badge-<?php echo strtolower(str_replace(' ', '-', htmlspecialchars($user['membership'], ENT_QUOTES))); ?>">
                    <?php echo htmlspecialchars($user['membership'], ENT_QUOTES); ?>
                  </span>
                </td>
                <td><?php echo htmlspecialchars($user['join_date'], ENT_QUOTES); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      
      <p class="muted" style="margin-top: 20px; text-align: center;">
        Total Members: <strong><?php echo count($users); ?></strong>
      </p>
    <?php endif; ?>

    <p style="margin-top: 24px;">
      <a class="btn btn-small" href="index.php">Back to Home</a>
    </p>
  </main>

  <footer class="footer">
    <div class="container">
      <span>© <?php echo date('Y'); ?> PulseFit App</span>
    </div>
  </footer>
  <script src="assets/app.js" defer></script>
</body>
</html>
