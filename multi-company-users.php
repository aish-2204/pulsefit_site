<?php

// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/api/config.php';

// Company configuration
$companies = [
    [
        'name' => 'PulseFit',
        'source' => 'file',
        'url' => null
    ],
    [
        'name' => 'Decor Dreams',
        'source' => 'url',
        'url' => 'https://prajaktatech.com/api_users.php'
    ],
];

/**
 * Fetch users from external API using cURL
 */
function fetch_users_with_curl($url) {
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode !== 200 || !$response) {
            return null;
        }
        
        $data = json_decode($response, true);
        
        // Handle different response formats
        if (isset($data['users']) && is_array($data['users'])) {
            // Company B format: users array inside object
            return [
                'metadata' => [
                    'company' => $data['company'] ?? null,
                    'company_code' => $data['company_code'] ?? null,
                    'site' => $data['site'] ?? null
                ],
                'users' => $data['users']
            ];
        } elseif (isset($data['data']) && is_array($data['data'])) {
            return $data['data'];
        } elseif (is_array($data)) {
            return $data;
        }
        
        return null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get users for a company
 */
function get_company_users($source, $url = null) {
    if ($source === 'file') {
        return read_users_from_csv();
    } elseif ($source === 'url' && $url) {
        $result = fetch_users_with_curl($url);
        // If result has metadata (Company B format), return only users
        if (is_array($result) && isset($result['metadata']) && isset($result['users'])) {
            return $result['users'];
        }
        return $result;
    }
    return null;
}

/**
 * Get company metadata (for external APIs like Company B)
 */
function get_company_metadata($source, $url = null) {
    if ($source !== 'url' || !$url) {
        return null;
    }
    
    $result = fetch_users_with_curl($url);
    if (is_array($result) && isset($result['metadata'])) {
        return $result['metadata'];
    }
    return null;
}

/**
 * Get all unique field names from an array of records
 */
function get_all_fields($records) {
    if (empty($records)) {
        return [];
    }
    
    $fields = [];
    foreach ($records as $record) {
        if (is_array($record)) {
            $fields = array_unique(array_merge($fields, array_keys($record)));
        }
    }
    
    return array_values($fields);
}

// Fetch data for all companies
$companyData = [];
foreach ($companies as $company) {
    $company['users'] = get_company_users($company['source'], $company['url']);
    $company['metadata'] = get_company_metadata($company['source'], $company['url']);
    $company['fields'] = !empty($company['users']) ? get_all_fields($company['users']) : [];
    $companyData[] = $company;
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Multi-Company Users | PulseFit</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>
    .company-section {
      margin-bottom: 50px;
      background: white;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .company-header {
      border-bottom: 3px solid #667eea;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }

    .company-header h2 {
      margin: 0;
      color: #2c3e50;
      font-size: 1.8rem;
    }

    .company-count {
      display: inline-block;
      background: #667eea;
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.9rem;
      margin-left: 10px;
    }

    .company-meta {
      font-size: 0.9rem;
      color: #999;
      margin-top: 5px;
    }

    .company-meta code {
      background: #f5f5f5;
      padding: 2px 6px;
      border-radius: 3px;
      font-family: monospace;
    }

    .users-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      font-size: 0.95rem;
    }

    .users-table thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .users-table th {
      padding: 12px 10px;
      text-align: left;
      font-weight: 600;
      word-break: break-word;
    }

    .users-table td {
      padding: 10px;
      border-bottom: 1px solid #e0e0e0;
      word-break: break-word;
    }

    .users-table tbody tr:hover {
      background: #f9f9f9;
    }

    .users-table tbody tr:last-child td {
      border-bottom: none;
    }

    .placeholder-message {
      background: #fff3cd;
      border: 1px solid #ffc107;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      color: #856404;
    }

    .placeholder-message p {
      margin: 5px 0;
    }

    .placeholder-url {
      font-family: monospace;
      background: #fff;
      padding: 10px;
      border-radius: 4px;
      display: inline-block;
      margin-top: 10px;
      word-break: break-all;
      max-width: 500px;
    }

    .error-message {
      background: #f8d7da;
      border: 1px solid #f5c6cb;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      color: #721c24;
    }

    .scroll-hint {
      color: #999;
      font-size: 0.85rem;
      margin-top: 10px;
    }

    .table-wrapper {
      overflow-x: auto;
      border-radius: 8px;
    }

    .cell-value {
      max-width: 200px;
      word-wrap: break-word;
      overflow-wrap: break-word;
      white-space: normal;
    }

    .cell-truncated {
      max-height: 60px;
      overflow: hidden;
      position: relative;
    }

    .cell-truncated::after {
      content: '';
      position: absolute;
      bottom: 0;
      right: 0;
      width: 100%;
      height: 20px;
      background: linear-gradient(to bottom, transparent, white);
    }

    .value-badge {
      display: inline-block;
      background: #e3f2fd;
      color: #1565c0;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .json-object {
      display: inline-block;
      background: #f5f5f5;
      padding: 4px 6px;
      border-radius: 3px;
      font-family: monospace;
      font-size: 0.85rem;
      color: #666;
    }

    /* Card layout for Company B */
    .company-metadata {
      background: #f8f9fa;
      border-left: 4px solid #667eea;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }

    .company-metadata p {
      margin: 5px 0;
      font-size: 0.95rem;
      color: #666;
    }

    .company-metadata-label {
      font-weight: 600;
      color: #333;
    }

    .users-cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .user-card {
      background: white;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .user-card:hover {
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
      border-color: #667eea;
      transform: translateY(-2px);
    }

    .user-card-id {
      display: inline-block;
      background: #667eea;
      color: white;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .user-card-name {
      font-size: 1.1rem;
      font-weight: 600;
      color: #2c3e50;
      margin: 10px 0 5px 0;
    }

    .user-card-email {
      color: #667eea;
      text-decoration: none;
      word-break: break-all;
      font-size: 0.95rem;
    }

    .user-card-email:hover {
      text-decoration: underline;
    }

    .users-grid-info {
      color: #999;
      font-size: 0.85rem;
      margin-top: 15px;
      text-align: center;
    }
  </style>
</head>
<body>
  <?php include 'partials/nav.php'; ?>

  <main class="container page">
    <header class="page-head">
      <p class="eyebrow">Network</p>
      <h1>Multi-Company Users Directory</h1>
      <p class="muted">View users from multiple companies.</p>
    </header>

    <?php foreach ($companyData as $company): ?>
      <section class="company-section">
        <div class="company-header">
          <h2>
            <?php echo htmlspecialchars($company['name'], ENT_QUOTES); ?>
            <?php if ($company['users']): ?>
              <span class="company-count"><?php echo count($company['users']); ?> Users</span>
            <?php endif; ?>
          </h2>
          <?php if ($company['source'] === 'url'): ?>
            <div class="company-meta">
              API Endpoint: <code><?php echo htmlspecialchars($company['url'], ENT_QUOTES); ?></code>
            </div>
          <?php endif; ?>
        </div>

        <?php if ($company['source'] === 'file'): ?>
          <!-- PulseFit: CSV-based data with fixed fields -->
          <?php if (empty($company['users'])): ?>
            <div class="error-message">
              <p>Unable to load users from CSV file.</p>
            </div>
          <?php else: ?>
            <div class="table-wrapper">
              <table class="users-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Membership</th>
                    <th>Join Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($company['users'] as $user): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($user['id'] ?? '', ENT_QUOTES); ?></td>
                      <td><?php echo htmlspecialchars($user['first_name'] ?? '', ENT_QUOTES); ?></td>
                      <td><?php echo htmlspecialchars($user['last_name'] ?? '', ENT_QUOTES); ?></td>
                      <td>
                        <?php 
                          $email = $user['email'] ?? '';
                          if ($email) {
                            echo '<a href="mailto:' . htmlspecialchars($email, ENT_QUOTES) . '">' . htmlspecialchars($email, ENT_QUOTES) . '</a>';
                          }
                        ?>
                      </td>
                      <td><?php echo htmlspecialchars($user['membership'] ?? '', ENT_QUOTES); ?></td>
                      <td><?php echo htmlspecialchars($user['join_date'] ?? '', ENT_QUOTES); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

        <?php else: ?>
          <!-- URL-based data (Company B) with card layout -->
          <?php if ($company['users']): ?>
            <!-- Display company metadata if available -->
            <?php if ($company['metadata'] && array_filter($company['metadata'])): ?>
              <div class="company-metadata">
                <?php if ($company['metadata']['company']): ?>
                  <p><span class="company-metadata-label">Company Name:</span> <?php echo htmlspecialchars($company['metadata']['company'], ENT_QUOTES); ?></p>
                <?php endif; ?>
                <?php if ($company['metadata']['company_code']): ?>
                  <p><span class="company-metadata-label">Code:</span> <?php echo htmlspecialchars($company['metadata']['company_code'], ENT_QUOTES); ?></p>
                <?php endif; ?>
                <?php if ($company['metadata']['site']): ?>
                  <p><span class="company-metadata-label">Website:</span> <a href="<?php echo htmlspecialchars($company['metadata']['site'], ENT_QUOTES); ?>" target="_blank"><?php echo htmlspecialchars($company['metadata']['site'], ENT_QUOTES); ?></a></p>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <!-- Data loaded successfully from API - Display as cards -->
            <div class="users-cards">
              <?php foreach ($company['users'] as $user): ?>
                <div class="user-card">
                  <?php if (isset($user['id'])): ?>
                    <div class="user-card-id">ID: <?php echo htmlspecialchars($user['id'], ENT_QUOTES); ?></div>
                  <?php endif; ?>
                  
                  <?php if (isset($user['name'])): ?>
                    <div class="user-card-name"><?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?></div>
                  <?php endif; ?>
                  
                  <?php if (isset($user['email'])): ?>
                    <a href="mailto:<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>" class="user-card-email">
                      <?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>
                    </a>
                  <?php endif; ?>

                  <!-- Show any additional fields -->
                  <?php 
                    $hiddenFields = ['id', 'name', 'email'];
                    $additionalFields = array_diff_key($user, array_flip($hiddenFields));
                  ?>
                  <?php if (!empty($additionalFields)): ?>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #f0f0f0;">
                      <?php foreach ($additionalFields as $fieldName => $fieldValue): ?>
                        <p style="margin: 5px 0; font-size: 0.85rem;">
                          <span style="font-weight: 600; color: #666;"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $fieldName)), ENT_QUOTES); ?>:</span> 
                          <span style="color: #999;">
                            <?php 
                              if (is_array($fieldValue) || is_object($fieldValue)) {
                                echo '<code style="background: #f5f5f5; padding: 2px 4px; border-radius: 2px;">' . htmlspecialchars(json_encode($fieldValue), ENT_QUOTES) . '</code>';
                              } else {
                                echo htmlspecialchars($fieldValue, ENT_QUOTES);
                              }
                            ?>
                          </span>
                        </p>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
            <p class="users-grid-info">
              Showing <?php echo count($company['users']); ?> users from <?php echo htmlspecialchars($company['name'], ENT_QUOTES); ?>
            </p>
          <?php else: ?>
            <!-- Placeholder: URL not configured or no data -->
            <div class="placeholder-message">
              <p><strong>Unable to load data</strong></p>
              <p>The API endpoint did not return any user data.</p>
              <div class="placeholder-url">
                <?php echo htmlspecialchars($company['url'], ENT_QUOTES); ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </section>
    <?php endforeach; ?>

    <p style="margin-top: 40px;">
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
