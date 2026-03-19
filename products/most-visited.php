<?php
require_once __DIR__ . '/bootstrap.php';

$navBase = '../';
$mostVisited = pulsefit_get_most_visited_products();
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Most Visited Products | PulseFit</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/../partials/nav.php'; ?>

  <main class="container page">
    <header class="page-head">
      <p class="eyebrow">Popular</p>
      <h1>Top 5 Most Visited Products</h1>
      <p class="muted">These are the most frequently visited products based on your browsing activity. The numbers show how many times each product has been viewed.</p>
    </header>

    <?php if (empty($mostVisited)): ?>
      <p class="muted">You have not visited any product pages yet. Browse the Products &amp; Services to start building your visit history.</p>
    <?php else: ?>
      <section class="grid">
        <?php foreach ($mostVisited as $item): ?>
          <article class="card">
            <img src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>" class="card-image" />
            <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?></h3>
            <?php if (!empty($item['tagline'])): ?>
              <p class="muted"><?php echo htmlspecialchars($item['tagline'], ENT_QUOTES); ?></p>
            <?php endif; ?>
            <p class="muted small">
              <strong>Visits:</strong> <?php echo htmlspecialchars($item['visit_count'], ENT_QUOTES); ?>
            </p>
            <p>
              <a class="btn btn-small" href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES); ?>">Open product page</a>
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
