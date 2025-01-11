<?php
// logout.php
require_once 'init.php';

// Clear session data
session_unset();
session_destroy();

// Redirect to homepage (or login page)
header("Location: index.php");
exit;
