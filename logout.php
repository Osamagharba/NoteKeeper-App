<?php
require_once __DIR__ . '/includs/auth.php';
startSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  logoutUser();
}

header('Location: index.php');
exit;
?>