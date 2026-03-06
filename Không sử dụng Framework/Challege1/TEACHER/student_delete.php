<?php
session_start();
require __DIR__ . "/../config.php";

if (!isset($_SESSION["user"]) || (int)$_SESSION["user"]["isteacher"] !== 1) {
    header("Location: /CHALLEGE1/login.php");
    exit;
}

$id = (int)($_GET["id"] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM `user` WHERE id = ? AND isteacher = 0");
    $stmt->execute([$id]);
}
header("Location: students.php");
exit;