<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/api/user-management.php';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $user_data = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'home_address' => trim($_POST['home_address'] ?? ''),
        'home_phone' => trim($_POST['home_phone'] ?? ''),
        'cell_phone' => trim($_POST['cell_phone'] ?? ''),
        'date_of_birth' => trim($_POST['date_of_birth'] ?? ''),
        'gender' => trim($_POST['gender'] ?? ''),
        'emergency_contact_name' => trim($_POST['emergency_contact_name'] ?? ''),
        'emergency_contact_phone' => trim($_POST['emergency_contact_phone'] ?? ''),
        'membership_status' => trim($_POST['membership_status'] ?? 'Active'),
        'membership_type' => trim($_POST['membership_type'] ?? 'Basic'),
        'notes' => trim($_POST['notes'] ?? '')
    ];

    $result = create_user($user_data);
    
    if ($result['success']) {
        $message = $result['message'] . " (User ID: " . $result['user_id'] . ")";
        $message_type = 'success';
        // Reset form
        $user_data = array_fill_keys(array_keys($user_data), '');
    } else {
        $message = $result['message'];
        $message_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create User | PulseFit</title>
    <link rel="stylesheet" href="/assets/style.css" />
    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full {
            grid-column: 1 / -1;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--color-text);
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .message {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .form-actions button {
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
        .form-actions button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(79, 70, 229, 0.28);
        }
        .btn-submit {
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
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(79, 70, 229, 0.28);
        }
        .btn-submit:active {
            transform: none;
        }
        .btn-reset {
            background-color: #6c757d;
            color: white;
        }
        .btn-reset:hover {
            opacity: 0.9;
        }
        .section-title {
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--color-primary);
            color: var(--color-text);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="container page">
        <section class="page-head">
            <h1>Create New User</h1>
            <p>Fill in the form below to add a new member to PulseFit.</p>
        </section>

        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="user-form">
            <!-- Basic Information -->
            <h2 class="section-title">Basic Information</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name" class="required">First Name</label>
                    <input type="text" id="first_name" name="first_name" required 
                           value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="last_name" class="required">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required 
                           value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>">
                </div>
                <div class="form-group full">
                    <label for="email" class="required">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>">
                </div>
            </div>

            <!-- Contact Information -->
            <h2 class="section-title">Contact Information</h2>
            <div class="form-grid">
                <div class="form-group full">
                    <label for="home_address">Home Address</label>
                    <input type="text" id="home_address" name="home_address" 
                           value="<?php echo htmlspecialchars($user_data['home_address'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="home_phone">Home Phone</label>
                    <input type="tel" id="home_phone" name="home_phone" 
                           value="<?php echo htmlspecialchars($user_data['home_phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="cell_phone" class="required">Cell Phone</label>
                    <input type="tel" id="cell_phone" name="cell_phone" required 
                           value="<?php echo htmlspecialchars($user_data['cell_phone'] ?? ''); ?>">
                </div>
            </div>

            <!-- Personal Information -->
            <h2 class="section-title">Personal Information</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" 
                           value="<?php echo htmlspecialchars($user_data['date_of_birth'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select...</option>
                        <option value="Male" <?php echo ($user_data['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($user_data['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($user_data['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                        <option value="Prefer not to say" <?php echo ($user_data['gender'] ?? '') === 'Prefer not to say' ? 'selected' : ''; ?>>Prefer not to say</option>
                    </select>
                </div>
            </div>

            <!-- Emergency Contact -->
            <h2 class="section-title">Emergency Contact</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="emergency_contact_name">Emergency Contact Name</label>
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" 
                           value="<?php echo htmlspecialchars($user_data['emergency_contact_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="emergency_contact_phone">Emergency Contact Phone</label>
                    <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" 
                           value="<?php echo htmlspecialchars($user_data['emergency_contact_phone'] ?? ''); ?>">
                </div>
            </div>

            <!-- Membership Information -->
            <h2 class="section-title">Membership Information</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="membership_status">Membership Status</label>
                    <select id="membership_status" name="membership_status">
                        <option value="Active" <?php echo ($user_data['membership_status'] ?? 'Active') === 'Active' ? 'selected' : ''; ?>>Active</option>
                        <option value="Inactive" <?php echo ($user_data['membership_status'] ?? '') === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="Suspended" <?php echo ($user_data['membership_status'] ?? '') === 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="membership_type">Membership Type</label>
                    <input type="text" id="membership_type" name="membership_type" placeholder="e.g., Premium, Basic, Trial"
                           value="<?php echo htmlspecialchars($user_data['membership_type'] ?? 'Basic'); ?>">
                </div>
            </div>

            <!-- Additional Notes -->
            <h2 class="section-title">Additional Information</h2>
            <div class="form-grid">
                <div class="form-group full">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" placeholder="Any special requirements, allergies, or notes..."><?php echo htmlspecialchars($user_data['notes'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">Submit</button>
                <button type="reset" class="btn-reset">Clear Form</button>
                <a href="/users.php" class="btn" style="text-decoration: none; display: inline-flex; align-items: center;">Back to Users</a>
            </div>
        </form>
    </main>
</body>
</html>
