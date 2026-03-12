<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Products & Services | PulseFit App</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/nav.php'; ?>

  <main class="container">
    <h1>Products & Services</h1>
    <p class="muted">Everything you need to train, recover, and eat better.</p>

    <div class="grid">
      <div class="card">
        <h3>PulseFit Gym Access</h3>
        <p class="muted">Open gym + equipment zones + workout tracking inside the app.</p>
        <ul class="feature-list">
          <li>Strength & functional areas</li>
          <li>Trainer onboarding session</li>
          <li>Workout logging</li>
        </ul>
      </div>

      <div class="card">
        <h3>Yoga Flow Studio</h3>
        <p class="muted">Flexibility, mobility, balance, and mindfulness.</p>
        <ul class="feature-list">
          <li>Beginner + intermediate flows</li>
          <li>Mobility & recovery sessions</li>
          <li>Breathing + stretch guides</li>
        </ul>
      </div>

      <div class="card">
        <h3>Pilates Core Program</h3>
        <p class="muted">Posture and core strength for daily life and sports.</p>
        <ul class="feature-list">
          <li>Mat & reformer sessions</li>
          <li>Core stability plans</li>
          <li>Low-impact training</li>
        </ul>
      </div>

      <div class="card">
        <h3>Nutrition Coaching</h3>
        <p class="muted">Personalized targets and meal templates.</p>
        <ul class="feature-list">
          <li>Macro + calorie guidance</li>
          <li>Meal plans & grocery lists</li>
          <li>Weekly check-ins</li>
        </ul>
      </div>

      <div class="card">
        <h3>Fitness App Features</h3>
        <p class="muted">Tools that keep you consistent.</p>
        <ul class="feature-list">
          <li>Workout & habit tracking</li>
          <li>Class booking</li>
          <li>Progress dashboard</li>
        </ul>
      </div>

      <div class="card">
        <h3>Corporate Wellness</h3>
        <p class="muted">Programs for teams and workplaces.</p>
        <ul class="feature-list">
          <li>Group classes</li>
          <li>Nutrition seminars</li>
          <li>Monthly progress reports</li>
        </ul>
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
