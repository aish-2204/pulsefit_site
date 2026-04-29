<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/api/user-management.php';

$search_term = '';
$search_type = 'all';
$search_results = [];
$has_searched = false;

// Handle search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_term = trim($_POST['search_term'] ?? '');
    $search_type = $_POST['search_type'] ?? 'all';
    
    if (!empty($search_term)) {
        $search_results = search_users($search_term, $search_type);
        $has_searched = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Users | PulseFit</title>
    <link rel="stylesheet" href="/assets/style.css" />
    <style>
        .search-container {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .search-form {
            display: grid;
            gap: 1rem;
            grid-template-columns: 1fr auto auto;
        }
        .search-form input,
        .search-form select {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
        }
        .search-form input:focus,
        .search-form select:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        .search-form button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 16px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #fff;
            border-radius: 12px;
            font-weight: 700;
            border: 0;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.22);
            font-size: 1rem;
        }
        .search-form button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(79, 70, 229, 0.28);
        }
        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
        }
        .results-info {
            margin-bottom: 1rem;
            font-size: 1rem;
            color: #666;
        }
        .user-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: grid;
            gap: 1rem;
        }
        .user-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .user-name {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--color-text);
        }
        .user-meta {
            font-size: 0.9rem;
            color: #888;
        }
        .user-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .user-detail {
            display: flex;
            flex-direction: column;
        }
        .user-detail-label {
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .user-detail-value {
            color: var(--color-text);
            margin-top: 0.25rem;
        }
        .no-results {
            text-align: center;
            padding: 2rem;
            color: #888;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
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
            margin-top: 1rem;
        }
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #17a2b8;
            color: white;
        }
        .btn-edit:hover {
            background-color: #138496;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="container page">
        <section class="page-head">
            <h1>Search Users</h1>
            <p>Find members by name, email, or phone number.</p>
            <a href="/users.php" class="btn" style="text-decoration: none; display: inline-flex; align-items: center; margin-top: 1rem;">Back to Users</a>
        </section>

        <!-- Search Form -->
        <div class="search-container">
            <form method="POST" class="search-form">
                <input type="text" name="search_term" placeholder="Enter name, email, or phone number..." 
                       value="<?php echo htmlspecialchars($search_term); ?>" required>
                <select name="search_type">
                    <option value="all" <?php echo $search_type === 'all' ? 'selected' : ''; ?>>All Fields</option>
                    <option value="name" <?php echo $search_type === 'name' ? 'selected' : ''; ?>>By Name</option>
                    <option value="email" <?php echo $search_type === 'email' ? 'selected' : ''; ?>>By Email</option>
                    <option value="phone" <?php echo $search_type === 'phone' ? 'selected' : ''; ?>>By Phone</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Search Results -->
        <?php if ($has_searched): ?>
            <div class="results-info">
                Found <strong><?php echo count($search_results); ?></strong> result<?php echo count($search_results) !== 1 ? 's' : ''; ?>
            </div>

            <?php if (empty($search_results)): ?>
                <div class="no-results">
                    <p>No users found matching your search criteria.</p>
                </div>
            <?php else: ?>
                <?php foreach ($search_results as $user): ?>
                    <div class="user-card">
                        <div class="user-card-header">
                            <div>
                                <div class="user-name">
                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                </div>
                                <div class="user-meta">
                                    User ID: <?php echo $user['user_id']; ?>
                                </div>
                            </div>
                            <span class="badge badge-<?php echo strtolower($user['membership_status'] ?? 'active'); ?>">
                                <?php echo htmlspecialchars($user['membership_status'] ?? 'Active'); ?>
                            </span>
                        </div>

                        <div class="user-details">
                            <div class="user-detail">
                                <span class="user-detail-label">Email</span>
                                <span class="user-detail-value">
                                    <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                                        <?php echo htmlspecialchars($user['email']); ?>
                                    </a>
                                </span>
                            </div>

                            <?php if ($user['home_phone']): ?>
                                <div class="user-detail">
                                    <span class="user-detail-label">Home Phone</span>
                                    <span class="user-detail-value">
                                        <a href="tel:<?php echo htmlspecialchars($user['home_phone']); ?>">
                                            <?php echo htmlspecialchars($user['home_phone']); ?>
                                        </a>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="user-detail">
                                <span class="user-detail-label">Cell Phone</span>
                                <span class="user-detail-value">
                                    <a href="tel:<?php echo htmlspecialchars($user['cell_phone']); ?>">
                                        <?php echo htmlspecialchars($user['cell_phone']); ?>
                                    </a>
                                </span>
                            </div>

                            <?php if ($user['home_address']): ?>
                                <div class="user-detail">
                                    <span class="user-detail-label">Address</span>
                                    <span class="user-detail-value">
                                        <?php echo htmlspecialchars($user['home_address']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ($user['date_of_birth']): ?>
                                <div class="user-detail">
                                    <span class="user-detail-label">Date of Birth</span>
                                    <span class="user-detail-value">
                                        <?php echo date('M d, Y', strtotime($user['date_of_birth'])); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ($user['gender']): ?>
                                <div class="user-detail">
                                    <span class="user-detail-label">Gender</span>
                                    <span class="user-detail-value">
                                        <?php echo htmlspecialchars($user['gender']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="user-detail">
                                <span class="user-detail-label">Membership Type</span>
                                <span class="user-detail-value">
                                    <?php echo htmlspecialchars($user['membership_type'] ?? 'Basic'); ?>
                                </span>
                            </div>

                            <div class="user-detail">
                                <span class="user-detail-label">Member Since</span>
                                <span class="user-detail-value">
                                    <?php echo date('M d, Y', strtotime($user['join_date'])); ?>
                                </span>
                            </div>

                            <?php if ($user['emergency_contact_name']): ?>
                                <div class="user-detail">
                                    <span class="user-detail-label">Emergency Contact</span>
                                    <span class="user-detail-value">
                                        <?php echo htmlspecialchars($user['emergency_contact_name']); ?>
                                        <?php if ($user['emergency_contact_phone']): ?>
                                            - <a href="tel:<?php echo htmlspecialchars($user['emergency_contact_phone']); ?>">
                                                <?php echo htmlspecialchars($user['emergency_contact_phone']); ?>
                                            </a>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($user['notes']): ?>
                            <div class="user-detail">
                                <span class="user-detail-label">Notes</span>
                                <span class="user-detail-value">
                                    <?php echo htmlspecialchars($user['notes']); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="actions">
                            <a href="/users-edit.php?id=<?php echo $user['user_id']; ?>" class="btn-small btn-edit">Edit</a>
                            <a href="users.php" class="btn-small" style="background-color: #6c757d; color: white; text-decoration: none;">Back to List</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-results">
                <p>Enter a search term above to find users.</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
