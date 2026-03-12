<?php
$news = [
  [
    'title' => 'PulseFit launches new Yoga + Mobility series',
    'date'  => '2026-02-18',
    'body'  => 'A 4-week plan focused on posture, hips, shoulders, and stress reduction — perfect for beginners.'
  ],
  [
    'title' => 'Nutrition: High-protein meal templates added',
    'date'  => '2026-02-02',
    'body'  => 'Members can now choose meal templates by goal: fat loss, maintenance, or muscle gain.'
  ],
  [
    'title' => 'Pilates Core: new beginner track',
    'date'  => '2026-01-20',
    'body'  => 'A low-impact program built for core stability, back support, and better movement quality.'
  ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>News | PulseFit App</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/nav.php'; ?>

  <main class="container">
    <h1>News</h1>
    <p class="muted">Latest updates about PulseFit programs and features.</p>

    <div class="stack">
      <?php foreach ($news as $n): ?>
        <div class="card">
          <h3><?php echo htmlspecialchars($n['title']); ?></h3>
          <p class="muted"><?php echo htmlspecialchars($n['date']); ?></p>
          <p><?php echo htmlspecialchars($n['body']); ?></p>
        </div>
      <?php endforeach; ?>
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
