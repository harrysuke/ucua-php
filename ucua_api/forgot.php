<?php
/**
 * POST /forgot.php - Password recovery request (BR-005).
 *
 * The legacy system has no working mail transport (sendEmail is stubbed), so the
 * request is logged for PSSD/ICT follow-up instead of silently pretending to send
 * a reset link.
 *
 * Body: { staffId, email? }
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['error' => 'Method not allowed']);
}

$in      = body_json();
$staffId = isset($in['staffId']) ? trim($in['staffId']) : '';
if ($staffId === '') {
    respond(422, ['error' => 'Staff ID is required']);
}

try {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT staffId, Name, Email FROM user WHERE staffId = ? AND isdelete = 0 LIMIT 1");
    $stmt->execute([$staffId]);
    $user = $stmt->fetch();

    // Always return the same acknowledgement so the endpoint cannot be used to
    // enumerate valid staff IDs.
    if ($user) {
        log_history($staffId, 'FORGOT', 'Password recovery requested via mobile app');
    }

    respond(200, [
        'requested' => true,
        'message'   => 'Recovery request received. PSSD/ICT will contact you to reset your password.',
    ]);
} catch (Exception $e) {
    respond(500, ['error' => 'Recovery request failed', 'detail' => $e->getMessage()]);
}
