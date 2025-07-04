<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail pozice | BPSA s.r.o.</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <div class="logo">BPSA</div>
  <div class="menu-toggle" id="menu-toggle">☰</div>
  <nav id="nav-menu">
    <a href="index.html#o-nas">O nás</a>
    <a href="sluzby.html">Služby</a>
    <a href="nahradni-plneni.html">Náhradní plnění</a>
    <a href="index.html#klienti">Naši klienti</a>
    <a href="volne-pozice.html">Volné pozice</a>
    <a href="kontakt.html">Kontakty</a>
    <a class="phone" href="tel:+420734300442">+420 734 300 442</a>
  </nav>
</header>

<script>
  const toggle = document.getElementById('menu-toggle');
  const navMenu = document.getElementById('nav-menu');
  toggle.addEventListener('click', () => {
    navMenu.classList.toggle('responsive');
    toggle.classList.toggle('active');
  });
</script>

<main class="job-detail">
<?php
if (!isset($_GET['id'])) {
  echo "<p class='error'>Chyba: ID pozice nebylo zadáno.</p>";
  exit;
}

$id = (int) $_GET['id'];

$host = 'localhost';
$db   = 'bpsa_jobs';
$user = 'uzivatel';
$pass = 'heslo';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
  $stmt = $pdo->prepare("SELECT * FROM positions WHERE id = ? LIMIT 1");
  $stmt->execute([$id]);
  $pozice = $stmt->fetch();

  if (!$pozice) {
    echo "<p class='error'>Pozice nenalezena.</p>";
  } else {
    echo "<h1>{$pozice['nazev']}</h1>";
    echo "<img src='{$pozice['obrazek']}' alt='Obrázek pozice' class='job-img'>";
    echo "<p class='lokalita'><strong>Lokalita:</strong> {$pozice['lokalita']}</p>";
    echo "<div class='job-description'>{$pozice['popis']}</div>";
    echo "<a href='mailto:info@bpsa.cz?subject=Reakce na pozici {$pozice['nazev']}' class='btn'>Odpovědět</a>";
  }

} catch (PDOException $e) {
  echo "<p class='error'>Chyba při načítání pozice.</p>";
}
?>
</main>

<footer>
  &copy; 2025 BPSA s.r.o. | Všechna práva vyhrazena
</footer>
</body>
</html>
