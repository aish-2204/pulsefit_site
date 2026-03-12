<?php
session_start();
session_unset();
session_destroy();

// Redirect to login with a logout confirmation
header('Location: login.php?reason=logout');
exit;
