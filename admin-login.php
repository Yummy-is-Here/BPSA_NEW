<?php
session_start();

// Změň si na skutečné hodnoty
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'tajneheslo';

// Pokud je již přihlášen, přesměruj na admin rozhraní
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
  header('Location: admin-pozice.html');
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = $_POST['username'] ?? '';
  $pass = $_POST['password'] ?? '';

  if ($user === $ADMIN_USER && $pass === $ADMIN_PASS) {
    $_SESSION['admin'] = true;
    header('Location: admin-pozice.html');
    exit;
  } else {
    $error = 'Nesprávné přihlašovací údaje';
  }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Přihlášení | BPSA Admin</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: Inter, sans-serif; background: #f7f7f7; display: flex; justify-content: center; align-items: center; height: 100vh; }
    .login-box { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,0.1); max-width: 360px; width: 100%; }
    .login-box h1 { margin-bottom: 1.5rem; text-align: center; font-size: 1.5rem; }
    input[type=text], input[type=password] { width: 100%; padding: 0.6rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 6px; }
    button { width: 100%; padding: 0.6rem; background: #2196f3; color: white; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; }
    button:hover { background: #1976d2; }
    .error { color: #c62828; text-align: center; margin-bottom: 1rem; }
  </style>
</head>
<body>
  <form method="post" class="login-box">
    <h1>Admin přihlášení</h1>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Uživatelské jméno" required>
    <input type="password" name="password" placeholder="Heslo" required>
    <button type="submit">Přihlásit se</button>
  </form>
</body>
</html>