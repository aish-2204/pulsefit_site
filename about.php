<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About | PulseFit App</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/nav.php'; ?>

  <main class="container">
    <h1>About PulseFit</h1>

    <div class="card">
      <p>
        PulseFit is a fitness platform that helps members train consistently using a simple system:
        <strong>strength</strong>, <strong>mobility</strong>, <strong>mind-body</strong>, and <strong>nutrition</strong>.
        Our programs are designed for busy students and professionals—at the gym or at home.
      </p>
      <p class="muted">
        Mission: Make fitness feel clear, supportive, and measurable for everyone.
      </p>
    </div>

    <div class="grid" id="team">
      <div class="card">
        <h3>Our Approach</h3>
        <p class="muted">Progressive training plans + recovery + nutrition habits that fit real schedules.</p>
      </div>
      <div class="card">
        <h3>Coaches</h3>
        <p class="muted">Certified trainers and nutrition coaches with class programs for all levels.</p>
      </div>
      <div class="card">
        <h3>Facilities</h3>
        <p class="muted">Strength equipment, functional training zones, and studio spaces for yoga/pilates.</p>
      </div>
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
