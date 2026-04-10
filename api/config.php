<?php

/**
 * Read users from CSV file
 * @return array Array of user records
 */
function read_users_from_csv(): array
{
    $csvFile = __DIR__ . '/../data/users.csv';
    
    if (!file_exists($csvFile)) {
        return [];
    }
    
    $users = [];
    $file = fopen($csvFile, 'r');
    
    if ($file === false) {
        return [];
    }
    
    // Skip header row
    $headers = fgetcsv($file, 0, ',', '"', '\\');
    
    if ($headers === false) {
        fclose($file);
        return [];
    }
    
    // Map headers to lowercase for consistency
    $headers = array_map('strtolower', $headers);
    $headers = array_map(function($h) {
        return str_replace(' ', '_', $h);
    }, $headers);
    
    // Read data rows
    while (($row = fgetcsv($file, 0, ',', '"', '\\')) !== false) {
        if (count($row) === count($headers)) {
            $user = array_combine($headers, $row);
            $users[] = $user;
        }
    }
    
    fclose($file);
    return $users;
}

/**
 * Set CORS headers for API access
 */
function set_cors_headers(): void
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json; charset=utf-8');
}

/**
 * Send JSON response
 */
function send_json_response(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}
