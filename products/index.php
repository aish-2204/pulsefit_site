<?php
require_once __DIR__ . '/bootstrap.php';

$navBase = '../';
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Products &amp; Services | PulseFit App</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/../partials/nav.php'; ?>

  <main class="container page">
    <header class="page-head">
      <p class="eyebrow">Products &amp; Services</p>
      <h1>Everything you need to train, recover, and eat better.</h1>
      <p class="muted">Explore PulseFit memberships, coaching options, and app features. Each product has a dedicated page where you can learn more and see what is included.</p>

      <p>
        <a class="link-inline" href="recent.php">View your last 5 viewed products</a>
        &nbsp;|&nbsp;
        <a class="link-inline" href="most-visited.php">View 5 most visited products</a>
      </p>
    </header>

    <section class="grid">
      <?php foreach ($products as $slug => $product): ?>
        <article class="card">
          <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>" class="card-image" />
          <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></h3>
          <?php if (!empty($product['tagline'])): ?>
            <p class="muted"><?php echo htmlspecialchars($product['tagline'], ENT_QUOTES); ?></p>
          <?php endif; ?>
          <p class="muted small">Learn more about what is included and how it works.</p>
          <p>
            <a class="btn btn-small" href="<?php echo htmlspecialchars(pulsefit_product_url($slug), ENT_QUOTES); ?>">View details</a>
          </p>
        </article>
      <?php endforeach; ?>
    </section>

    <section class="recent-inline">
      <h2>Recently visited products</h2>
      <?php $recent = pulsefit_get_recent_products(); ?>
      <?php if (empty($recent)): ?>
        <p class="muted">You have not viewed any products yet. Browse the list above to get started.</p>
      <?php else: ?>
        <ul class="recent-list">
          <?php foreach ($recent as $item): ?>
            <li>
              <a href="<?php echo htmlspecialchars(pulsefit_product_url($item['slug']), ENT_QUOTES); ?>">
                <?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <span>© <?php echo date('Y'); ?> PulseFit App</span>
    </div>
  </footer>
  <script src="../assets/app.js" defer></script>
</body>
</html>
