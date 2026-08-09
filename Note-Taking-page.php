<?php
require_once __DIR__ . '/includs/auth.php';
startSession();
requireLogin();

$flash = getFlash();
$username = $_SESSION['user']['username'];
$notes = getUserNotes();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-save'])) {

  $titleValidation = validateTitle($_POST['title'] ?? '');
  $bodyValidation = validateBody($_POST['body'] ?? '');

  if (!$titleValidation['valid'] || !$bodyValidation['valid']) {
    setFlash('error', $titleValidation['error'] ?: $bodyValidation['error']);
    header('Location: Note-Taking-page.php');
    exit;
  }

  addUserNote($titleValidation['value'], $bodyValidation['value']);
  setFlash('success', 'Note saved successfully.');
  header('Location: Note-Taking-page.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-note'])) {

  $noteId = (int) sanitizeInput($_POST['note-id'] ?? '');

  deleteUserNote($noteId);
  setFlash('success', 'Note deleted successfully.');
  header('Location: Note-Taking-page.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NoteKeeper – My Notes</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="Style/variables.css">
  <link rel="stylesheet" href="Style/global.css">
  <link rel="stylesheet" href="Style/components.css">
  <link rel="stylesheet" href="Style/Note-Taking-page.css">
</head>

<body>

  <nav>
    <div class="nav-logo">
      <span class="logo-text">NoteKeeper</span>
    </div>
    <div class="nav-user">
      <span class="username-badge"><i class="fa-solid fa-user fa-xs"></i>
        <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>
      <form action="logout.php" method="POST">
        <button type="submit" class="btn-logout">Logout</button>
      </form>
    </div>
  </nav>

  <main class="container">
    <section class="add-note-section">
      <h2 class="section-title"><i class="fa-solid fa-pen"></i> New Note</h2>
      <form action="Note-Taking-page.php" class="note-form" method="POST">
        <input type="text" class="note-title-input" name="title" placeholder="Note title...">
        <textarea class="note-body-input" name="body" placeholder="Write your note here..." rows="4"
          maxlength="1000"></textarea>
        <div class="control-area">
          <div class="msg">
            <?php if ($flash): ?>
              <p class="flash <?php echo htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8'); ?>
              </p>
            <?php endif; ?>
            <p class="error-msg"></p>
          </div>
          <button type="submit" class="btn-save prim-btn" name="btn-save">Save Note</button>
        </div>
      </form>
    </section>

    <section class="notes-section">
      <h2 class="section-title">My Notes <span class="notes-count"><?php echo count($notes); ?></span></h2>
      <div class="notes-grid">
        <?php foreach ($notes as $note): ?>
          <div class="note-card">
            <div class="card-header">
              <div class="card-title"><?php echo htmlspecialchars($note['title'], ENT_QUOTES, 'UTF-8'); ?></div>
              <form action="Note-Taking-page.php" method="POST" style="display: inline;">
                <input type="hidden" name="note-id"
                  value="<?php echo htmlspecialchars($note['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="btn-delete" name="delete-note" title="delete"><i
                    class="fa-solid fa-trash"></i></button>
              </form>
            </div>
            <div class="card-body"><?php echo htmlspecialchars($note['body'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="card-footer">
              <span class="card-date"><i class="fa-solid fa-calendar"></i>
                <?php echo htmlspecialchars($note['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
  <script src="Script/Note-Taking-page.js"></script>
</body>

</html>