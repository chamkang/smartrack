<?php
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$lang = current_language();
$pdo = db();
$stmt = $pdo->prepare('SELECT string_key, value FROM translations WHERE lang = :lang');
$stmt->execute([':lang' => $lang]);
$result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
