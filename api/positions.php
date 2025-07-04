<?php
session_start();
header('Content-Type: application/json');

$host = 'localhost';
$db   = 'bpsa_jobs';
$user = 'uzivatel';
$pass = 'heslo';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Chyba připojení k databázi"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT id, nazev, lokalita, obrazek, LEFT(popis, 300) AS popis FROM positions ORDER BY datum_pridani DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(403);
    echo json_encode(["error" => "Nepovolený přístup"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazev = $_POST['nazev'] ?? '';
    $lokalita = $_POST['lokalita'] ?? '';
    $popis = $_POST['popis'] ?? '';
    $obrazek = $_POST['obrazek'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO positions (nazev, lokalita, popis, obrazek) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nazev, $lokalita, $popis, $obrazek]);
    echo json_encode(["success" => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Chybějící ID"]);
        exit;
    }
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM positions WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["success" => true]);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Nepodporovaná metoda"]);
