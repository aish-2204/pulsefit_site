<?php
// Start session if not already started (needed to check login state)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $navBase is set by the including page:
//   - root pages set nothing (defaults to '')
//   - admin/ pages set $navBase = '../'
$navBase = isset($navBase) ? $navBase : '';

$is_admin = !empty($_SESSION['admin']);
?>
<nav class="nav">
  <div class="container nav-inner">
    <a class="brand" href="<?php echo $navBase; ?>index.php">
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
      <a href="<?php echo $navBase; ?>index.php">Home</a>
      <a href="<?php echo $navBase; ?>about.php">About</a>
      <a href="<?php echo $navBase; ?>products/index.php">Products/Services</a>
      <a href="<?php echo $navBase; ?>news.php">News</a>
      <a href="<?php echo $navBase; ?>contacts.php">Contacts</a>
      <?php if ($is_admin): ?>
        <a href="<?php echo $navBase; ?>admin/index.php">Admin</a>
      <?php else: ?>
        <a href="<?php echo $navBase; ?>login.php">Admin Login</a>
      <?php endif; ?>
    </div>

    <?php if ($is_admin): ?>
      <a class="btn btn-small nav-cta" href="<?php echo $navBase; ?>logout.php">Logout</a>
    <?php else: ?>
      <a class="btn btn-small nav-cta" href="<?php echo $navBase; ?>contacts.php">Book a Free Intro</a>
    <?php endif; ?>
  </div>
</nav>
