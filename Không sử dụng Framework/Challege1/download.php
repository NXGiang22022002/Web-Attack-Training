<?php
require __DIR__ . "/auth.php";
requireLogin();

[$assignDir, $subDir] = ensureUploadDirs();

$type = $_GET["type"] ?? "";
$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) { http_response_code(400); exit("Bad request"); }

if ($type === "assignment") {
    $stmt = $pdo->prepare("SELECT file_original_name, file_stored_name FROM assignments WHERE id=? LIMIT 1");
    $stmt->execute([$id]);
    $f = $stmt->fetch();
    if (!$f) { http_response_code(404); exit("Not found"); }

    $path = $assignDir . "/" . $f["file_stored_name"];
    $downloadName = $f["file_original_name"];

} elseif ($type === "submission") {
    // chỉ giáo viên mới tải bài làm
    if ((int)$_SESSION["user"]["isteacher"] !== 1) {
        http_response_code(403);
        exit("Forbidden");
    }
    $stmt = $pdo->prepare("SELECT file_original_name, file_stored_name FROM submissions WHERE id=? LIMIT 1");
    $stmt->execute([$id]);
    $f = $stmt->fetch();
    if (!$f) { http_response_code(404); exit("Not found"); }

    $path = $subDir . "/" . $f["file_stored_name"];
    $downloadName = $f["file_original_name"];

} else {
    http_response_code(400);
    exit("Bad request");
}

if (!is_file($path)) { http_response_code(404); exit("File missing"); }

header("Content-Type: application/octet-stream");
header('Content-Disposition: attachment; filename="' . basename($downloadName) . '"');
header("Content-Length: " . filesize($path));
readfile($path);
exit;