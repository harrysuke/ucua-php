<?php
/**
 * POST /workflow.php - Approval workflow actions (BR-021..BR-027).
 *
 * Body: { id, action: "approve" | "reject" | "close" | "resubmit", remarks? }
 *
 * Chain: PSSD HSE Officer -> HOS -> HOD -> Closed.
 * Rejection requires a documented reason (BRR-004).
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['error' => 'Method not allowed']);
}

$user    = require_auth();
$pdo     = db_connect();
$staffId = $user['staffId'];
$level   = (int)$user['AccessLevel'];

$in      = body_json();
$id      = isset($in['id']) ? (int)$in['id'] : 0;
$action  = isset($in['action']) ? strtolower(trim($in['action'])) : '';
$remarks = isset($in['remarks']) ? trim($in['remarks']) : '';

if ($id <= 0) respond(422, ['error' => 'Report id is required']);
if (!in_array($action, ['approve', 'reject', 'close', 'resubmit'], true)) {
    respond(422, ['error' => 'action must be approve, reject, close or resubmit']);
}

try {
    $stmt = $pdo->prepare("SELECT * FROM ucuaform WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $report = $stmt->fetch();
    if (!$report) respond(404, ['error' => 'Report not found']);

    $status = (string)$report['status'];
    $stage  = stage_for_status($status);

    // --- Owner resubmission of a rejected report -------------------------
    if ($action === 'resubmit') {
        if ($report['staffId'] !== $staffId) {
            respond(403, ['error' => 'Only the submitter can resubmit this report']);
        }
        if ($status !== STATUS_DRAFT && !is_rejected_status($status)) {
            respond(409, ['error' => 'Only rejected or draft reports can be resubmitted']);
        }
        $pdo->prepare("UPDATE ucuaform SET status = ?, submitdate = ? WHERE id = ?")
            ->execute([STATUS_HSE, date('Y-m-d H:i:s'), $id]);
        add_report_history($id, $staffId, 'RESUBMIT', $remarks ?: 'Resubmitted for review');
        log_history($staffId, 'RESUBMIT', "Resubmitted report ID $id via mobile app", $id);
        respond(200, ['updated' => true, 'id' => $id, 'status' => STATUS_HSE]);
    }

    if ($stage === null) {
        respond(409, ['error' => 'This report is not awaiting approval']);
    }
    if (!can_act_on_stage($level, $stage)) {
        respond(403, ['error' => 'Your role is not authorised for this approval stage']);
    }

    if ($action === 'reject') {
        if ($remarks === '') {
            respond(422, ['error' => 'A rejection reason is required']);
        }
        $pdo->prepare("UPDATE ucuaform SET status = ? WHERE Id = ?")
            ->execute([rejected_status($stage), $id]);
        add_report_history($id, $staffId, 'REJECT', $remarks);
        log_history($staffId, 'REJECT', "Rejected report ID $id via mobile app", $id);
        respond(200, ['updated' => true, 'id' => $id, 'status' => rejected_status($stage)]);
    }

    if ($action === 'close') {
        if (!can_act_on_stage($level, 'CLOSE') || $stage !== 'HOD') {
            respond(403, ['error' => 'Only HOD or an administrator can close this report']);
        }
        $pdo->prepare("UPDATE ucuaform SET status = ? WHERE id = ?")
            ->execute([STATUS_CLOSED, $id]);
        add_report_history($id, $staffId, 'CLOSE', $remarks ?: 'Report closed with evidence');
        log_history($staffId, 'CLOSE', "Closed report ID $id via mobile app", $id);
        respond(200, ['updated' => true, 'id' => $id, 'status' => STATUS_CLOSED]);
    }

    // --- Approve: advance one step in the chain ---------------------------
    $next = next_status($stage);
    if ($next === null) {
        respond(409, ['error' => 'Report cannot be advanced further']);
    }
    $pdo->prepare("UPDATE ucuaform SET status = ? WHERE id = ?")->execute([$next, $id]);
    add_report_history($id, $staffId, 'APPROVE', $remarks ?: "Approved at $stage stage");

    // Closing evidence photos may be attached during the final approval.
    if (isset($in['evidence']) && is_array($in['evidence'])) {
        foreach ($in['evidence'] as $photoId) {
            try {
                $pdo->prepare("UPDATE ucuaphoto SET photo_type = 'closed' WHERE id = ? AND ucuaid = ?")
                    ->execute([(int)$photoId, $id]);
            } catch (Exception $e) {
                // photo_type column is optional.
            }
        }
    }

    log_history($staffId, 'APPROVE', "Approved report ID $id at $stage stage via mobile app", $id);
    respond(200, ['updated' => true, 'id' => $id, 'status' => $next]);
} catch (Exception $e) {
    respond(500, ['error' => 'Workflow action failed', 'detail' => $e->getMessage()]);
}
