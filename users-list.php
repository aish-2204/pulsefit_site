<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/api/user-management.php';

$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$users = get_all_users($limit + 1, $offset); // Get one extra to check if there's a next page
$has_more = count($users) > $limit;
if ($has_more) {
    array_pop($users);
}

// Get total count (for demo purposes, we'll show available data)
$all_users = get_all_users(10000, 0);
$total = count($all_users);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Users | PulseFit</title>
    <link rel="stylesheet" href="/assets/style.css" />
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

    .badge-active {
      background-color: #d4edda;
      color: #155724;
    }

    .badge-inactive {
      background-color: #e2e3e5;
      color: #383d41;
    }

    .badge-suspended {
      background-color: #f8d7da;
      color: #721c24;
    }

    .actions {
      display: flex;
      gap: 0.5rem;
    }

    .btn-edit {
      padding: 4px 8px;
      background-color: #17a2b8;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      font-size: 0.85rem;
    }

    .btn-edit:hover {
      background-color: #138496;
    }

    .pagination {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 2rem;
    }

    .pagination a,
    .pagination span {
      padding: 0.5rem 0.75rem;
      border: 1px solid #ddd;
      border-radius: 4px;
      text-decoration: none;
      color: var(--color-primary);
      display: inline-block;
    }

    .pagination a:hover {
      background-color: #f8f9fa;
    }

    .pagination .active {
      background-color: var(--color-primary);
      color: white;
      border-color: var(--color-primary);
    }

    .pagination .disabled {
      color: #999;
      cursor: not-allowed;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      border-left: 4px solid var(--color-primary);
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stat-card h3 {
      margin: 0 0 0.5rem 0;
      color: #666;
      font-size: 0.9rem;
      text-transform: uppercase;
    }

    .stat-card .value {
      font-size: 2rem;
      font-weight: bold;
      color: var(--color-text);
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

    <!-- Stats -->
    <div class="stats">
      <div class="stat-card">
        <h3>Total Members</h3>
        <div class="value"><?php echo $total; ?></div>
      </div>
      <div class="stat-card">
        <h3>Current Page</h3>
        <div class="value"><?php echo $page; ?></div>
      </div>
    </div>

    <?php if (empty($users)): ?>
      <div style="background: #f5f5f5; padding: 40px; border-radius: 8px; text-align: center;">
        <p class="muted">No members found. <a href="users-create.php">Add the first member</a>.</p>
      </div>
    <?php else: ?>
      <div style="overflow-x: auto;">
        <table class="users-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Status</th>
              <th>Join Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td>
                  <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name'], ENT_QUOTES); ?></strong>
                </td>
                <td>
                  <a href="mailto:<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>">
                    <?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>
                  </a>
                </td>
                <td>
                  <?php if ($user['cell_phone']): ?>
                    <a href="tel:<?php echo htmlspecialchars($user['cell_phone'], ENT_QUOTES); ?>">
                      <?php echo htmlspecialchars($user['cell_phone'], ENT_QUOTES); ?>
                    </a>
                  <?php else: ?>
                    <span style="color: #999;">N/A</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="membership-badge badge-<?php echo strtolower($user['membership_status'] ?? 'active'); ?>">
                    <?php echo htmlspecialchars($user['membership_status'] ?? 'Active', ENT_QUOTES); ?>
                  </span>
                </td>
                <td><?php echo date('M d, Y', strtotime($user['join_date'])); ?></td>
                <td>
                  <div class="actions">
                    <a href="/users-edit.php?id=<?php echo $user['user_id']; ?>" class="btn-edit">Edit</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="users-list.php?page=1">« First</a>
          <a href="users-list.php?page=<?php echo $page - 1; ?>">‹ Prev</a>
        <?php else: ?>
          <span class="disabled">« First</span>
          <span class="disabled">‹ Prev</span>
        <?php endif; ?>

        <span class="active"><?php echo $page; ?></span>

        <?php if ($has_more): ?>
          <a href="users-list.php?page=<?php echo $page + 1; ?>">Next ›</a>
        <?php else: ?>
          <span class="disabled">Next ›</span>
        <?php endif; ?>
      </div>
      
      <p class="muted" style="margin-top: 20px; text-align: center;">
        Total Members: <strong><?php echo count($users); ?></strong>
      </p>
    <?php endif; ?>

    <p style="margin-top: 24px;">
      <a class="btn btn-small" href="users.php">Back to User Management</a>
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
