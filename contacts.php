<?php
// contacts.php - Reads contacts from data/contacts.txt and displays them.

$contactsFile = __DIR__ . '/data/contacts.txt';
$contacts = [];
$errorMsg = '';

if (!file_exists($contactsFile)) {
  $errorMsg = "Contacts file not found. Expected: data/contacts.txt";
} else {
  $lines = file($contactsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  foreach ($lines as $line) {
    // Expected format: Name|Role|Email|Phone|Location
    $parts = array_map('trim', explode('|', $line));
    if (count($parts) < 5) continue;

    $contacts[] = [
      'name' => $parts[0],
      'role' => $parts[1],
      'email' => $parts[2],
      'phone' => $parts[3],
      'location' => $parts[4],
    ];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contacts | PulseFit App</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/nav.php'; ?>

  <main class="container">
    <h1>Contacts</h1>
    <p class="muted">For memberships, bookings, app support, or nutrition coaching.</p>

    <?php if ($errorMsg): ?>
      <div class="card error"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php elseif (count($contacts) === 0): ?>
      <div class="card">No contacts found.</div>
    <?php else: ?>
      <div class="grid">
        <?php foreach ($contacts as $c): ?>
          <div class="card">
            <h3><?php echo htmlspecialchars($c['name']); ?></h3>
            <p class="muted"><?php echo htmlspecialchars($c['role']); ?></p>
            <p>
              <strong>Email:</strong>
              <a href="mailto:<?php echo htmlspecialchars($c['email']); ?>">
                <?php echo htmlspecialchars($c['email']); ?>
              </a><br/>
              <strong>Phone:</strong>
              <a href="tel:<?php echo htmlspecialchars($c['phone']); ?>">
                <?php echo htmlspecialchars($c['phone']); ?>
              </a><br/>
              <strong>Location:</strong> <?php echo htmlspecialchars($c['location']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="card" style="margin-top:16px;">
      <h3 style="margin-top:0;">Business Hours</h3>
      <p class="muted" style="margin-bottom:0;">
        Mon–Fri: 6am–9pm • Sat: 8am–6pm • Sun: 9am–3pm
      </p>
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <span>© <?php echo date('Y'); ?> PulseFit App</span>
    </div>
  </footer>
  <script src="assets/app.js" defer></script>
</body>
</html>
