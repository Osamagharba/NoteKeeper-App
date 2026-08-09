<?php
require_once __DIR__ . '/includs/auth.php';
startSession();

if (isLoggedIn()) {
  header('Location: Note-Taking-page.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $usernameValidation = validateUsername($_POST['username'] ?? '');
  $passwordValidation = validatePassword($_POST['password'] ?? '');

  if (!$usernameValidation['valid'] || !$passwordValidation['valid']) {
    setFlash('error', $usernameValidation['error'] ?: $passwordValidation['error']);
    header('Location: register.php');
    exit;
  }

  $result = registerUser($usernameValidation['value'] ?? '', $passwordValidation['value'] ?? '');
  if ($result['success']) {
    setFlash('success', $result['message']);
    header('Location: Note-Taking-page.php');
    exit;
  }

  setFlash('error', $result['message']);
  header('Location: register.php');
  exit;
}

$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Page</title>
  <link rel="stylesheet" href="Style/variables.css">
  <link rel="stylesheet" href="Style/global.css">
  <link rel="stylesheet" href="Style/components.css">
  <link rel="stylesheet" href="Style/register.css">
</head>

<body>
  <div class="login-card">
    <form action="register.php" method="POST">
      <h1>Create Account</h1>
      <div class="inputs-fields">
        <div class="input-raw">
          <label for="username">Username</label>
          <input type="text" class="input username" name="username" id="username" placeholder="Enter your username">
        </div>
        <div class="input-raw">
          <label for="password">Password</label>
          <input type="password" class="input password" name="password" id="password" placeholder="Enter your password">
        </div>
        <div class="input-raw">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" class="input confirm-password" name="confirm-password" id="confirm-password"
            placeholder="Confirm your password">
        </div>
        <div class="input-raw">
          <button type="submit" class="prim-btn" name="register">Register</button>
        </div>
        <div class="error">
          <?php if ($flash): ?>
            <p class="flash <?php echo htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?>">
              <?php echo htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
          <?php endif; ?>
          <p class="error-msg"></p>
        </div>
        <div>
          <p class="login">Already have an account? <a href="index.php">Login</a></p>
        </div>
      </div>
    </form>
  </div>
  <script src="Script/register.js"></script>
</body>

</html>