<?php

function loadEnv(string $filePath): void
{
  if (!file_exists($filePath)) {
    return;
  }

  $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) {
      continue;
    }

    if (strpos($line, '=') !== false) {
      list($name, $value) = explode('=', $line, 2);
      $name = trim($name);
      $value = trim(trim($value), "'\"");

      if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
      }
    }
  }
}

// Load environment variables from .env file
loadEnv(dirname(__DIR__) . '/.env');

// Read database configuration strictly from environment variables
define('DB_HOST', $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? '');
define('DB_USER', $_ENV['DB_USER'] ?? getenv('DB_USER') ?? '');
define('DB_PASS', $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? '');