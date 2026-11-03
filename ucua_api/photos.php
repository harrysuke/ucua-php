<?php
/**
 * /photos.php - Photo evidence for reports (BR-011, REQ-WF-005).
 *
 * POST (multipart/form-data): reportId, photoType=evidence|closed, file
 * GET  ?id=<reportId>        : list photo evidence with absolute URLs
 *
 * Storage matches the legacy convention: files live in `<webroot>/uploads` and
 * `ucuaphoto.evidenceImage` holds the relative path `uploads/<filename>`.
 */
require_once __DIR__ . '/config.php';

$user    = require_auth();
$pdo     = db_connect();
$staffId = $user['staffId'];
$level   = (int)$user['AccessLevel'];
$method  = $_SERVER['REQUEST_METHOD'];

function photo_type_label($type)
{
    switch (strtolower((string)$type)) {
        case 'closed':   return 'Closed Evidence';
        case 'violator': return 'Violator Evidence';
        default:         return 'Photo Evidence';
    }
}

function report_photos($pdo, $reportId)
{
    $stmt = $pdo->prepare(
        "SELECT id, evidenceImage, type FROM ucuaphoto
         WHERE ucuaform_Id = ? AND isdelete = 0 ORDER BY id ASC"
    );
    $stmt->execute([$reportId]);
    $photos = [];
    foreach ($stmt->fetchAll() as $row) {
        $photos[] = [
            'id'         => (int)$row['id'],
            'filename'   => basename((string)$row['evidenceImage']),
            'photo_type' => $row['type'],
            'url'        => photo_public_url($row['evidenceImage']),
        ];
    }
    return $photos;
}

try {
    if ($method === 'GET') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) respond(422, ['error' => 'Report id is required']);

        $stmt = $pdo->prepare("SELECT staffId FROM ucuaform WHERE Id = ? LIMIT 1");
        $stmt->execute([$id]);
        $report = $stmt->fetch();
        if (!$report) respond(404, ['error' => 'Report not found']);
        if ($level < 3 && $report['staffId'] !== $staffId) {
            respond(403, ['error' => 'Access denied']);
        }

        respond(200, ['photos' => report_photos($pdo, $id)]);
    }

    if ($method !== 'POST') {
        respond(405, ['error' => 'Method not allowed']);
    }

    $reportId  = isset($_POST['reportId']) ? (int)$_POST['reportId'] : 0;
    $photoType = isset($_POST['photoType']) ? trim($_POST['photoType']) : 'evidence';
    if ($reportId <= 0) respond(422, ['error' => 'reportId is required']);
    if (!isset($_FILES['file'])) respond(422, ['error' => 'No file uploaded']);
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        respond(422, ['error' => 'Upload failed with code ' . $_FILES['file']['error']]);
    }

    $stmt = $pdo->prepare("SELECT staffId FROM ucuaform WHERE Id = ? LIMIT 1");
    $stmt->execute([$reportId]);
    $report = $stmt->fetch();
    if (!$report) respond(404, ['error' => 'Report not found']);
    if ($level < 3 && $report['staffId'] !== $staffId) {
        respond(403, ['error' => 'Access denied']);
    }

    $original = $_FILES['file']['name'];
    $ext      = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if (!in_array($ext, allowed_extensions(), true)) {
        respond(422, ['error' => "File type .$ext is not allowed"]);
    }
    if ($_FILES['file']['size'] > 8 * 1024 * 1024) {
        respond(422, ['error' => 'File exceeds the 8 MB limit']);
    }

    $dir = uploads_root();
    $filename = date('YmdHis') . '_' . $reportId . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
    $target   = $dir . '/' . $filename;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        respond(500, ['error' => 'Could not store the uploaded file']);
    }

    $now   = date('Y-m-d H:i:s');
    $label = photo_type_label($photoType);
    $stmt  = $pdo->prepare(
        "INSERT INTO ucuaphoto
            (ucuaform_Id, evidenceImage, type, createby, createdate,
             editby, editdate, isdelete, deleteby, deletedate)
         VALUES (?, ?, ?, ?, ?, ?, ?, 0, '', ?)"
    );
    $stmt->execute([
        $reportId,
        'uploads/' . $filename,
        $label,
        $staffId,
        $now,
        $staffId,
        $now,
        $now,
    ]);
    $photoId = (int)$pdo->lastInsertId();

    // Mirror the newest evidence onto the report row for the legacy views.
    if ($photoType !== 'closed') {
        try {
            $pdo->prepare("UPDATE ucuaform SET evidenceImage = ? WHERE Id = ?")
                ->execute(['uploads/' . $filename, $reportId]);
        } catch (Exception $e) {
            // best effort
        }
    }

    add_report_history($reportId, $staffId, 'PHOTO', "Attached $label");

    respond(201, [
        'uploaded' => true,
        'photo'    => [
            'id'         => $photoId,
            'filename'   => $filename,
            'photo_type' => $label,
            'url'        => photo_public_url('uploads/' . $filename),
        ],
    ]);
} catch (Exception $e) {
    respond(500, ['error' => 'Photo operation failed', 'detail' => $e->getMessage()]);
}
