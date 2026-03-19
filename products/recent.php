<?php
require_once __DIR__ . '/bootstrap.php';

$navBase = '../';
$recent = pulsefit_get_recent_products();
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recently Viewed Products | PulseFit</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/../partials/nav.php'; ?>

  <main class="container page">
    <header class="page-head">
      <p class="eyebrow">History</p>
      <h1>Your last 5 viewed products</h1>
      <p class="muted">These pages are based on cookie data stored in your browser. Only the five most recently viewed unique products are shown.</p>
    </header>

    <?php if (empty($recent)): ?>
      <p class="muted">You have not viewed any product pages yet. Visit any product from the Products &amp; Services page to see it appear here.</p>
    <?php else: ?>
      <section class="grid">
        <?php foreach ($recent as $item): ?>
          <article class="card">
            <img src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>" class="card-image" />
            <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?></h3>
            <?php if (!empty($item['tagline'])): ?>
              <p class="muted"><?php echo htmlspecialchars($item['tagline'], ENT_QUOTES); ?></p>
            <?php endif; ?>
            <p>
              <a class="btn btn-small" href="<?php echo htmlspecialchars(pulsefit_product_url($item['slug']), ENT_QUOTES); ?>">Open product page</a>
            </p>
          </article>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>

    <p style="margin-top:24px;">
      <a class="btn btn-small" href="index.php">Back to Products &amp; Services</a>
    </p>
  </main>

  <footer class="footer">
    <div class="container">
      <span>© <?php echo date('Y'); ?> PulseFit App</span>
    </div>
  </footer>
  <script src="../assets/app.js" defer></script>
</body>
</html>
