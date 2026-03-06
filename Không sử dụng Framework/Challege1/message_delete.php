<?php
session_start();
require __DIR__ . "/config.php";

if (!isset($_SESSION["user"])) {
    header("Location: /CHALLEGE1/login.php");
    exit;
}

$me = $_SESSION["user"];
$myId = (int)($me["id"] ?? 0);
if ($myId <= 0) {
    $stmt = $pdo->prepare("SELECT id FROM `user` WHERE tendangnhap=? LIMIT 1");
    $stmt->execute([$me["tendangnhap"]]);
    $row = $stmt->fetch();
    if (!$row) { header("Location: /CHALLEGE1/login.php"); exit; }
    $myId = (int)$row["id"];
    $_SESSION["user"]["id"] = $myId;
}

$id = (int)($_GET["id"] ?? 0);
$to = (int)($_GET["to"] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id=? AND sender_id=?");
    $stmt->execute([$id, $myId]);
}

header("Location: /CHALLEGE1/user_detail.php?id=" . $to);
exit;