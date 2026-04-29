<?php

/**
 * PulseFit User Management API
 * Handles all database operations for user management
 */

require_once __DIR__ . '/db.config.php';

/**
 * Create a new user
 * @param array $data User data with keys: first_name, last_name, email, home_address, home_phone, cell_phone, etc.
 * @return array Result array with 'success', 'message', and optional 'user_id'
 */
function create_user($data) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    // Validate required fields
    $required = ['first_name', 'last_name', 'email', 'cell_phone'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            close_db_connection($conn);
            return ['success' => false, 'message' => "Missing required field: $field"];
        }
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Invalid email format'];
    }

    // Check if email already exists
    $check_query = "SELECT user_id FROM users WHERE email = ?";
    $result = execute_query($conn, $check_query, [$data['email']], 's');
    if ($result && $result->num_rows > 0) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Email already exists'];
    }

    // Convert empty strings to NULL for optional fields
    $date_of_birth = !empty($data['date_of_birth']) ? $data['date_of_birth'] : null;
    $home_address = !empty($data['home_address']) ? $data['home_address'] : null;
    $home_phone = !empty($data['home_phone']) ? $data['home_phone'] : null;
    $gender = !empty($data['gender']) ? $data['gender'] : null;
    $emergency_contact_name = !empty($data['emergency_contact_name']) ? $data['emergency_contact_name'] : null;
    $emergency_contact_phone = !empty($data['emergency_contact_phone']) ? $data['emergency_contact_phone'] : null;
    $notes = !empty($data['notes']) ? $data['notes'] : null;

    // Insert new user
    $query = "INSERT INTO users (first_name, last_name, email, home_address, home_phone, cell_phone, date_of_birth, gender, emergency_contact_name, emergency_contact_phone, membership_status, membership_type, notes) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $params = [
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $home_address,
        $home_phone,
        $data['cell_phone'],
        $date_of_birth,
        $gender,
        $emergency_contact_name,
        $emergency_contact_phone,
        $data['membership_status'] ?? 'Active',
        $data['membership_type'] ?? 'Basic',
        $notes
    ];

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Prepare error: ' . $conn->error];
    }

    if (!$stmt->bind_param('sssssssssssss', ...$params)) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Bind error: ' . $stmt->error];
    }

    if (!$stmt->execute()) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Execute error: ' . $stmt->error];
    }

    $user_id = $conn->insert_id;
    $stmt->close();
    close_db_connection($conn);

    return [
        'success' => true,
        'message' => 'User created successfully',
        'user_id' => $user_id
    ];
}

/**
 * Search users by name, email, or phone
 * @param string $search_term Search term
 * @param string $search_type Type of search: 'all', 'name', 'email', 'phone'
 * @return array Array of matching users or empty array
 */
function search_users($search_term, $search_type = 'all') {
    $conn = get_db_connection();
    if (!$conn) {
        return [];
    }

    $search_term = trim($search_term);
    if (empty($search_term)) {
        close_db_connection($conn);
        return [];
    }

    $search_pattern = '%' . $search_term . '%';
    $users = [];

    if ($search_type === 'all') {
        // Search across all fields
        $query = "SELECT * FROM users WHERE 
                  CONCAT(first_name, ' ', last_name) LIKE ? 
                  OR email LIKE ? 
                  OR home_phone LIKE ? 
                  OR cell_phone LIKE ?
                  ORDER BY last_name, first_name LIMIT 50";
        $result = execute_query($conn, $query, [$search_pattern, $search_pattern, $search_pattern, $search_pattern], 'ssss');
    } elseif ($search_type === 'name') {
        // Search by name only
        $query = "SELECT * FROM users WHERE 
                  CONCAT(first_name, ' ', last_name) LIKE ? 
                  OR first_name LIKE ? 
                  OR last_name LIKE ?
                  ORDER BY last_name, first_name LIMIT 50";
        $result = execute_query($conn, $query, [$search_pattern, $search_pattern, $search_pattern], 'sss');
    } elseif ($search_type === 'email') {
        // Search by email
        $query = "SELECT * FROM users WHERE email LIKE ? ORDER BY email LIMIT 50";
        $result = execute_query($conn, $query, [$search_pattern], 's');
    } elseif ($search_type === 'phone') {
        // Search by phone
        $query = "SELECT * FROM users WHERE home_phone LIKE ? OR cell_phone LIKE ? ORDER BY last_name, first_name LIMIT 50";
        $result = execute_query($conn, $query, [$search_pattern, $search_pattern], 'ss');
    }

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    close_db_connection($conn);
    return $users;
}

/**
 * Get user by ID
 * @param int $user_id User ID
 * @return array User data or null
 */
function get_user($user_id) {
    $conn = get_db_connection();
    if (!$conn) {
        return null;
    }

    $query = "SELECT * FROM users WHERE user_id = ?";
    $result = execute_query($conn, $query, [$user_id], 'i');

    $user = null;
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }

    close_db_connection($conn);
    return $user;
}

/**
 * Update user
 * @param int $user_id User ID
 * @param array $data Fields to update
 * @return array Result array with 'success' and 'message'
 */
function update_user($user_id, $data) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    // Build dynamic UPDATE query
    $allowed_fields = ['first_name', 'last_name', 'email', 'home_address', 'home_phone', 'cell_phone', 
                      'date_of_birth', 'gender', 'emergency_contact_name', 'emergency_contact_phone', 
                      'membership_status', 'membership_type', 'notes'];
    
    // Fields that should be converted to NULL if empty
    $optional_fields = ['date_of_birth', 'home_address', 'home_phone', 'gender', 'emergency_contact_name', 'emergency_contact_phone', 'notes'];
    
    $updates = [];
    $params = [];
    $types = '';

    foreach ($data as $field => $value) {
        if (in_array($field, $allowed_fields)) {
            // Convert empty strings to NULL for optional fields
            if (in_array($field, $optional_fields) && $value === '') {
                $value = null;
            }
            $updates[] = "$field = ?";
            $params[] = $value;
            $types .= 's';
        }
    }

    if (empty($updates)) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'No valid fields to update'];
    }

    $query = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = ?";
    $params[] = $user_id;
    $types .= 'i';

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Prepare error: ' . $conn->error];
    }

    if (!$stmt->bind_param($types, ...$params)) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Bind error'];
    }

    if (!$stmt->execute()) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Execute error'];
    }

    $stmt->close();
    close_db_connection($conn);

    return ['success' => true, 'message' => 'User updated successfully'];
}

/**
 * Delete user
 * @param int $user_id User ID
 * @return array Result array with 'success' and 'message'
 */
function delete_user($user_id) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Prepare error'];
    }

    $stmt->bind_param('i', $user_id);
    if (!$stmt->execute()) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Delete failed'];
    }

    $stmt->close();
    close_db_connection($conn);

    return ['success' => true, 'message' => 'User deleted successfully'];
}

/**
 * Get all users (with pagination)
 * @param int $limit Number of records per page
 * @param int $offset Offset for pagination
 * @return array Array of users
 */
function get_all_users($limit = 20, $offset = 0) {
    $conn = get_db_connection();
    if (!$conn) {
        return [];
    }

    $query = "SELECT * FROM users ORDER BY last_name, first_name LIMIT ? OFFSET ?";
    $result = execute_query($conn, $query, [$limit, $offset], 'ii');

    $users = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    close_db_connection($conn);
    return $users;
}
