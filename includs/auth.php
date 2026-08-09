<?php

function databaseConnection(): mysqli|false
{
  require_once __DIR__ . '/config.php';
  return mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
}

function startSession(): void
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
}

function sanitizeInput(string $value): string
{
  $value = trim($value);
  $value = strip_tags($value);
  $value = stripslashes($value);

  return $value;
}

function validateUsername(string $value): array
{
  $cleanValue = sanitizeInput($value);

  if ($cleanValue === '' || strlen($cleanValue) < 3 || strlen($cleanValue) > 20) {
    return ['valid' => false, 'value' => '', 'error' => 'Username must be 3-20 characters long.'];
  }

  if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $cleanValue)) {
    return ['valid' => false, 'value' => '', 'error' => 'Username can only contain letters, numbers, and underscores.'];
  }

  return ['valid' => true, 'value' => $cleanValue, 'error' => ''];
}

function validatePassword(string $value): array
{
  $cleanValue = sanitizeInput($value);

  if ($cleanValue === '' || strlen($cleanValue) < 8) {
    return ['valid' => false, 'value' => '', 'error' => 'Password must be at least 8 characters.'];
  }

  if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $cleanValue)) {
    return ['valid' => false, 'value' => '', 'error' => 'Password must be 8+ characters with uppercase, lowercase, a number & special character.'];
  }

  return ['valid' => true, 'value' => $cleanValue, 'error' => ''];
}

function validateTitle(string $value): array
{
  $cleanValue = sanitizeInput($value);

  if ($cleanValue === '' || strlen($cleanValue) > 50) {
    return ['valid' => false, 'value' => '', 'error' => 'Title is required and must be less than 50 characters.'];
  }

  return ['valid' => true, 'value' => $cleanValue, 'error' => ''];
}

function validateBody(string $value): array
{
  $cleanValue = sanitizeInput($value);

  if ($cleanValue === '' || strlen($cleanValue) > 1000) {
    return ['valid' => false, 'value' => '', 'error' => 'Body is required and must be less than 1000 characters.'];
  }

  return ['valid' => true, 'value' => $cleanValue, 'error' => ''];
}

function getUserNotes(): array
{
  $conn = databaseConnection();
  if (!$conn) {
    return [];
  }

  if (!isset($_SESSION['user']['id'])) {
    mysqli_close($conn);
    return [];
  }

  $userId = $_SESSION['user']['id'];

  $stmt = mysqli_prepare($conn, "SELECT id, title, body, created_at FROM notes WHERE user_id = ?");
  if (!$stmt) {
    mysqli_close($conn);
    return [];
  }
  mysqli_stmt_bind_param($stmt, 'i', $userId);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return [];
  }
  $result = mysqli_stmt_get_result($stmt);
  if (!$result) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return [];
  }
  if (mysqli_num_rows($result) === 0) {
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return [];
  }
  $userNotes = [];

  while ($note = mysqli_fetch_assoc($result)) {
    $userNotes[] = $note;
  }
  mysqli_free_result($result);
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
  return $userNotes;
}

function addUserNote(string $title, string $body): void
{
  $conn = databaseConnection();
  if (!$conn) {
    return;
  }

  if (!isset($_SESSION['user']['id'])) {
    mysqli_close($conn);
    return;
  }

  $userId = $_SESSION['user']['id'];

  $stmt = mysqli_prepare($conn, "INSERT INTO notes (title, body, user_id) VALUES (?, ?, ?)");
  if (!$stmt) {
    mysqli_close($conn);
    return;
  }
  mysqli_stmt_bind_param($stmt, 'ssi', $title, $body, $userId);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return;
  }
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}

function deleteUserNote(int $noteId): void
{
  $conn = databaseConnection();
  if (!$conn) {
    return;
  }

  if (!isset($_SESSION['user']['id'])) {
    mysqli_close($conn);
    return;
  }

  $userId = $_SESSION['user']['id'];

  $stmt = mysqli_prepare($conn, "DELETE FROM notes WHERE id = ? AND user_id = ?");
  if (!$stmt) {
    mysqli_close($conn);
    return;
  }
  mysqli_stmt_bind_param($stmt, 'ii', $noteId, $userId);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return;
  }
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}

function setFlash(string $type, string $message): void
{
  $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
  if (!isset($_SESSION['flash'])) {
    return null;
  }

  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
  return $flash;
}

function isLoggedIn(): bool
{
  return isset($_SESSION['user']['username']);
}

function requireLogin(): void
{
  if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
  }
}

function registerUser(string $username, string $password): array
{
  if (empty($username) || empty($password)) {
    return ['success' => false, 'message' => 'Enter the username and password'];
  }

  $conn = databaseConnection();
  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed'];
  }

  $stmt = mysqli_prepare($conn, "SELECT id, username, created_at FROM users WHERE username = ?");
  if (!$stmt) {
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Database error'];
  }
  mysqli_stmt_bind_param($stmt, 's', $username);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Database query failed'];
  }

  $result = mysqli_stmt_get_result($stmt);

  if (!$result) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Database result retrieval failed'];
  }

  if (mysqli_num_rows($result) > 0) {
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'That username already exists.'];
  }
  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
  if (!$stmt) {
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Database error'];
  }
  mysqli_stmt_bind_param($stmt, 'ss', $username, $hashedPassword);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Account creation failed'];
  }
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
  return ['success' => true, 'message' => 'Account created successfully'];
}

function loginUser(string $username, string $password): array
{
  if (empty($username) || empty($password)) {
    return ['success' => false, 'message' => 'Enter the username and password'];
  }

  $conn = databaseConnection();
  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed'];
  }

  $stmt = mysqli_prepare($conn, "SELECT id, username, password, created_at FROM users WHERE username = ?");
  if (!$stmt) {
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Database error'];
  }
  mysqli_stmt_bind_param($stmt, 's', $username);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Database query failed'];
  }
  $result = mysqli_stmt_get_result($stmt);

  if (!$result) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Database result retrieval failed'];
  }

  if (mysqli_num_rows($result) === 0) {
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Invalid username'];
  }

  $user = mysqli_fetch_assoc($result);
  mysqli_free_result($result);

  if (!password_verify($password, $user['password'])) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Invalid password'];
  }

  session_regenerate_id(true);
  $_SESSION['user'] = [
    'id' => $user['id'],
    'username' => $user['username'],
    'created_at' => $user['created_at']
  ];
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
  return ['success' => true, 'message' => 'Welcome back'];
}

function logoutUser(): void
{
  session_unset();
  session_destroy();
}