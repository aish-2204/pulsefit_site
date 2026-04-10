<?php

require_once __DIR__ . '/config.php';

set_cors_headers();

// Set REQUEST_METHOD to GET if running from CLI
if (php_sapi_name() === 'cli' && !isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    send_json_response([
        'success' => false,
        'error' => 'Method not allowed. Only GET requests are supported.'
    ], 405);
}

// Handle OPTIONS request (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $users = read_users_from_csv();
    
    if (empty($users)) {
        send_json_response([
            'success' => true,
            'data' => [],
            'count' => 0,
            'message' => 'No users found'
        ]);
    }
    
    send_json_response([
        'success' => true,
        'data' => $users,
        'count' => count($users),
        'timestamp' => date('c')
    ]);
    
} catch (Exception $e) {
    send_json_response([
        'success' => false,
        'error' => 'Error fetching users: ' . $e->getMessage()
    ], 500);
}
