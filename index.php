<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PulseFit App | Gym • Nutrition • Yoga • Pilates</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/nav.php'; ?>

  <main class="container page">
    <section class="hero">
      <div>
        <span class="eyebrow">All-in-one fitness platform</span>
        <h1>Train smarter with PulseFit.</h1>
        <p>
          PulseFit combines gym workouts, yoga & pilates classes, and nutrition coaching in one place.
          Build your plan, track your progress, and stay consistent—whether you’re at the studio or at home.
        </p>

        <div class="actions">
          <a class="btn" href="services.php">Explore Programs</a>
          <a class="btn btn-outline" href="contacts.php">Book a Free Intro</a>
        </div>

        <div class="badges">
          <span class="badge">Strength Training</span>
          <span class="badge">Yoga Flow</span>
          <span class="badge">Pilates Core</span>
          <span class="badge">Nutrition Plans</span>
          <span class="badge">Progress Tracking</span>
        </div>
      </div>

      <div class="panel">
        <h3 style="margin-top:0;">Today’s Quick Start</h3>
        <p class="muted">A simple plan to begin your week strong.</p>
        <ul class="feature-list">
          <li><strong>Workout:</strong> Full-Body Strength (30 min)</li>
          <li><strong>Mobility:</strong> 10 min hips + shoulders</li>
          <li><strong>Nutrition:</strong> High-protein meal guide</li>
          <li><strong>Mind:</strong> 5 min breathing reset</li>
        </ul>
        <p class="muted" style="margin-bottom:0;">New members get a free assessment + starter plan.</p>
      </div>
    </section>

    <section class="page-head">
      <h2 style="margin:0;">What you can do in PulseFit</h2>
      <p class="muted" style="margin:8px 0 0;">Everything you need to train, recover, and stay consistent.</p>
    </section>

    <section class="grid">
      <div class="card">
        <h3>Gym Facilities</h3>
        <p class="muted">Modern equipment, trainer-led sessions, and open gym access with tracking.</p>
      </div>
      <div class="card">
        <h3>Yoga & Pilates</h3>
        <p class="muted">Beginner to advanced classes: mobility, balance, posture, and core strength.</p>
      </div>
      <div class="card">
        <h3>Nutrition Coaching</h3>
        <p class="muted">Meal plans, calorie targets, macro guidance, and habit-based coaching.</p>
      </div>
      <div class="card">
        <h3>Progress Tracking</h3>
        <p class="muted">Track workouts, body stats, and consistency streaks across programs.</p>
      </div>
    </section>

    <section class="card" style="margin-top:18px;">
      <h2 style="margin-top:0;">Why PulseFit?</h2>
      <p class="muted">
        Most apps focus on one thing. PulseFit brings training + recovery + nutrition together, so you can follow
        one plan that fits your lifestyle.
      </p>
      <div class="actions">
        <a class="btn" href="contacts.php">Meet the Team</a>
        <a class="btn btn-outline" href="news.php">Latest Updates</a>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <span>© <?php echo date('Y'); ?> PulseFit App</span>
    </div>
  </footer>

  <script src="assets/app.js" defer></script>
</body>
</html>
