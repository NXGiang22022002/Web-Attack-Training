<?php
session_start();
require __DIR__ . "/config.php";

function requireLogin(): void {
    if (!isset($_SESSION["user"])) {
        header("Location: /CHALLEGE1/login.php");
        exit;
    }
}

function requireTeacher(): void {
    requireLogin();
    if ((int)$_SESSION["user"]["isteacher"] !== 1) {
        header("Location: /CHALLEGE1/login.php");
        exit;
    }
}

function requireStudent(): void {
    requireLogin();
    if ((int)$_SESSION["user"]["isteacher"] !== 0) {
        header("Location: /CHALLEGE1/login.php");
        exit;
    }
}

function currentUserId(PDO $pdo): int {
    // nếu session chưa có id thì lấy theo tendangnhap
    if (isset($_SESSION["user"]["id"]) && (int)$_SESSION["user"]["id"] > 0) {
        return (int)$_SESSION["user"]["id"];
    }
    $u = $_SESSION["user"]["tendangnhap"] ?? "";
    $stmt = $pdo->prepare("SELECT id FROM `user` WHERE tendangnhap=? LIMIT 1");
    $stmt->execute([$u]);
    $row = $stmt->fetch();
    if (!$row) {
        session_destroy();
        header("Location: /CHALLEGE1/login.php");
        exit;
    }
    $_SESSION["user"]["id"] = (int)$row["id"];
    return (int)$row["id"];
}

function ensureUploadDirs(): array {
    $base = __DIR__ . "/uploads";
    $assignDir = $base . "/assignments";
    $subDir = $base . "/submissions";

    if (!is_dir($assignDir)) mkdir($assignDir, 0777, true);
    if (!is_dir($subDir)) mkdir($subDir, 0777, true);

    return [$assignDir, $subDir];
}

function randomStoredName(string $originalName): string {
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $ext = $ext ? "." . strtolower($ext) : "";
    return bin2hex(random_bytes(16)) . $ext;
}