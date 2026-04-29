<?php
// Start session if not already started (needed to check login state)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_admin = !empty($_SESSION['admin']);
?>
<nav class="nav">
  <div class="container nav-inner">
    <a class="brand" href="/">
      <span class="brand-mark" aria-hidden="true"></span>
      PulseFit
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav-links">
      <span class="sr-only">Toggle navigation</span>
      <span class="nav-toggle-bar" aria-hidden="true"></span>
      <span class="nav-toggle-bar" aria-hidden="true"></span>
      <span class="nav-toggle-bar" aria-hidden="true"></span>
    </button>

    <div class="links" id="nav-links">
      <a href="/">Home</a>
      <a href="/about.php">About</a>
      <a href="/products/index.php">Products/Services</a>
      <a href="/users.php">Users</a>
      <a href="/multi-company-users.php">Members Network</a>
      <a href="/news.php">News</a>
      <a href="/contacts.php">Contacts</a>
      <?php if ($is_admin): ?>
        <a href="/admin/index.php">Admin</a>
      <?php else: ?>
        <a href="/login.php">Admin Login</a>
      <?php endif; ?>
    </div>

    <?php if ($is_admin): ?>
      <a class="btn btn-small nav-cta" href="/logout.php">Logout</a>
    <?php else: ?>
      <a class="btn btn-small nav-cta" href="/contacts.php">Book a Free Intro</a>
    <?php endif; ?>
  </div>
</nav>
