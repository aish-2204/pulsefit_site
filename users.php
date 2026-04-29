<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/api/user-management.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Users | PulseFit</title>
    <link rel="stylesheet" href="/assets/style.css" />
    <style>
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }
        .card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .card h2 {
            margin: 1rem 0;
            color: var(--color-text);
        }
        .card p {
            color: #666;
            margin-bottom: 1.5rem;
        }
        .card .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: var(--color-primary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: opacity 0.3s;
        }
        .card .btn:hover {
            opacity: 0.9;
        }
        .features-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin: 2rem 0;
        }
        .features-section h2 {
            margin-top: 0;
        }
        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .feature-item {
            display: flex;
            gap: 1rem;
        }
        .feature-icon {
            font-size: 1.5rem;
            min-width: 30px;
        }
        .feature-text {
            display: flex;
            flex-direction: column;
        }
        .feature-text strong {
            color: var(--color-text);
        }
        .feature-text p {
            margin: 0.25rem 0 0 0;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="container page">
        <section class="page-head">
            <h1>User Management</h1>
            <p>Manage PulseFit members and their information.</p>
        </section>

        <!-- Main Action Cards -->
        <div class="users-grid">
            <!-- Create User Card -->
            <div class="card">
                <div>
                    <div class="card-icon">➕</div>
                    <h2>Create User</h2>
                    <p>Add a new member to the PulseFit community. Fill in their profile information including contact details and membership preferences.</p>
                </div>
                <a href="users-create.php" class="btn">Create New User</a>
            </div>

            <!-- Search Users Card -->
            <div class="card">
                <div>
                    <div class="card-icon">🔍</div>
                    <h2>Search Users</h2>
                    <p>Find members by their name, email address, or phone number. View detailed profile information for each member.</p>
                </div>
                <a href="users-search.php" class="btn">Search Users</a>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features-section">
            <h2>User Management Features</h2>
            <div class="features-list">
                <div class="feature-item">
                    <span class="feature-icon">📋</span>
                    <div class="feature-text">
                        <strong>Complete Profiles</strong>
                        <p>Store comprehensive member information including contact details, emergency contacts, and notes.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🔎</span>
                    <div class="feature-text">
                        <strong>Quick Search</strong>
                        <p>Search by name, email, or phone number to quickly find members.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">💾</span>
                    <div class="feature-text">
                        <strong>Data Management</strong>
                        <p>Securely store all member data in a MySQL database with automatic timestamps.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🎯</span>
                    <div class="feature-text">
                        <strong>Membership Tracking</strong>
                        <p>Track membership status (Active, Inactive, Suspended) and membership types.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🚨</span>
                    <div class="feature-text">
                        <strong>Emergency Contacts</strong>
                        <p>Store emergency contact information for safety and security.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📝</span>
                    <div class="feature-text">
                        <strong>Special Notes</strong>
                        <p>Record special requirements, allergies, or other important information.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
