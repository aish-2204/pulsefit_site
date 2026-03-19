<?php
require_once __DIR__ . '/bootstrap.php';

pulsefit_track_product_visit('onboarding');

$product = $products['onboarding'];
$navBase = '../';
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?> | PulseFit</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/../partials/nav.php'; ?>

  <main class="container page">
    <header class="page-head">
      <p class="eyebrow">Product</p>
      <h1><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></h1>
      <p class="muted"><?php echo htmlspecialchars($product['tagline'], ENT_QUOTES); ?></p>
    </header>

    <section class="stack">
      <img src="../assets/group_training.jpg" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>" class="card-image" />
      <article class="card">
        <h2>Your first three sessions</h2>
        <p class="muted"><?php echo htmlspecialchars($product['description'], ENT_QUOTES); ?></p>
        <ul class="feature-list">
          <li>Movement and posture screening</li>
          <li>Goal mapping and training recommendations</li>
          <li>Starter plan loaded into the PulseFit app</li>
        </ul>
      </article>

      <p>
        <a class="btn btn-small" href="index.php">Back to Products &amp; Services</a>
      </p>
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
