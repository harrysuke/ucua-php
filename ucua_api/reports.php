<?php
/**
 * Reports resource.
 *
 * GET  /reports.php?type=UC|UA&status=...&q=...   list reports
 * GET  /reports.php?id=123                        single report + photos + timeline
 * POST /reports.php (JSON)                        create a UC/UA report or draft
 * PUT|PATCH /reports.php?id=123 (JSON)            update (owner or admin only)
 * DELETE /reports.php?id=123                      delete report + photos (owner or admin only)
 */
require_once __DIR__ . '/config.php';

$user    = require_auth();
$pdo     = db_connect();
$method  = $_SERVER['REQUEST_METHOD'];
$staffId = $user['staffId'];
$access  = (int)$user['AccessLevel'];
$canSeeAll = $access >= 3; // PSSD Admin (level 3) and above may see all reports

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                $stmt = $pdo->prepare("SELECT * FROM ucuaform WHERE id = ? LIMIT 1");
                $stmt->execute([$id]);
                $report = $stmt->fetch();
                if (!$report) respond(404, ['error' => 'Report not found']);
                if (!$canSeeAll && $report['staffId'] !== $staffId) {
                    respond(403, ['error' => 'Access denied']);
                }

                $photos = [];
                try {
                    $photoStmt = $pdo->prepare(
                        "SELECT id, evidenceImage, type FROM ucuaphoto
                         WHERE ucuaform_Id = ? AND isdelete = 0 ORDER BY id ASC"
                    );
                    $photoStmt->execute([$id]);
                    foreach ($photoStmt->fetchAll() as $photo) {
                        $photos[] = [
                            'id'         => (int)$photo['id'],
                            'filename'   => basename((string)$photo['evidenceImage']),
                            'photo_type' => $photo['type'],
                            'url'        => photo_public_url($photo['evidenceImage']),
                        ];
                    }
                } catch (Exception $e) {
                    $photos = [];
                }

                $report['id']                = (int)$report['Id'];
                $report['photos']           = $photos;
                $report['history']          = report_timeline($id);
                $report['rejection_reason'] = latest_rejection_reason($id);
                $report['can_approve']      = can_act_on_stage($access, stage_for_status((string)$report['status']));
                $report['is_owner']         = $report['staffId'] === $staffId;
                respond(200, ['report' => $report]);
            }

            $where  = [];
            $params = [];

            $type = isset($_GET['type']) ? $_GET['type'] : '';
            if ($type === 'UC' || $type === 'Unsafe Condition') {
                $where[] = "form_type = 'Unsafe Condition'";
            } elseif ($type === 'UA' || $type === 'Unsafe Action') {
                $where[] = "form_type = 'Unsafe Action'";
            }

            if (isset($_GET['status']) && $_GET['status'] !== '') {
                $where[]  = 'status = ?';
                $params[] = $_GET['status'];
            }
            if (isset($_GET['q']) && trim($_GET['q']) !== '') {
                $where[]  = '(conditionDetails LIKE ? OR ViolatorName LIKE ? OR OffenceCode LIKE ?)';
                $like     = '%' . trim($_GET['q']) . '%';
                $params[] = $like;
                $params[] = $like;
                $params[] = $like;
            }
            if (!$canSeeAll || (isset($_GET['mine']) && $_GET['mine'] === '1')) {
                $where[]  = 'staffId = ?';
                $params[] = $staffId;
            }

            $sql = "SELECT id, staffId, form_type, LocationId, conditionDetails,
                           OffenceCode, ViolatorName, violatorCompany, ViolatorDept,
                           ActionTaken, submitdate, status, observerName, observerDepartment
                    FROM ucuaform";
            if (!empty($where)) $sql .= ' WHERE ' . implode(' AND ', $where);
            $sql .= ' ORDER BY id DESC';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            respond(200, ['reports' => $stmt->fetchAll()]);
            break;

        case 'POST':
            $in = body_json();
            $formType = isset($in['formType']) ? $in['formType'] : '';
            if ($formType !== 'Unsafe Condition' && $formType !== 'Unsafe Action') {
                respond(422, ['error' => 'formType must be "Unsafe Condition" or "Unsafe Action"']);
            }

            $isDraft    = !empty($in['draft']);
            $locationId = isset($in['locationId']) ? trim($in['locationId']) : '';
            $now        = date('Y-m-d H:i:s');
            $observerDept  = isset($user['Department']) ? $user['Department'] : '';
            $observerEmail = isset($user['Email']) ? $user['Email'] : '';
            $observerName  = isset($user['Name']) ? $user['Name'] : '';
            $status = $isDraft ? STATUS_DRAFT : STATUS_HSE;

            // LocationId is NOT NULL with a foreign key to `location`, so it is
            // required even when saving a draft.
            if ($locationId === '') {
                respond(422, ['error' => 'locationId is required']);
            }

            if ($formType === 'Unsafe Condition') {
                $details = isset($in['conditionDetails']) ? trim($in['conditionDetails']) : '';
                // Drafts may be incomplete; submissions must satisfy BR-014.
                if (!$isDraft && $details === '') {
                    respond(422, ['error' => 'locationId and conditionDetails are required']);
                }
                $stmt = $pdo->prepare(
                    "INSERT INTO ucuaform
                        (LocationId, conditionDetails, staffId, form_type, submitdate,
                         status, createby, createdate, observerName, observerDepartment,
                         observerEmail, observerDatetime)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->execute([
                    $locationId, $details, $staffId, $formType, $now,
                    $status, $staffId, $now, $observerName, $observerDept,
                    $observerEmail, $now,
                ]);
            } else {
                $offenceCode   = isset($in['offenceCode']) ? trim($in['offenceCode']) : '';
                $violatorName  = isset($in['violatorName']) ? trim($in['violatorName']) : '';
                $company       = isset($in['violatorCompany']) ? trim($in['violatorCompany']) : '';
                $violatorDept  = isset($in['violatorDept']) ? trim($in['violatorDept']) : '';
                $actionTaken   = isset($in['actionTaken']) ? trim($in['actionTaken']) : '';
                // BR-016..BR-018: offence, violator company and observer are mandatory.
                if (!$isDraft && ($offenceCode === '' || $company === '')) {
                    respond(422, ['error' => 'locationId, offenceCode and violatorCompany are required']);
                }
                $stmt = $pdo->prepare(
                    "INSERT INTO ucuaform
                        (submitdate, LocationId, OffenceCode, staffId, form_type,
                         ViolatorName, violatorCompany, ViolatorDept, ActionTaken,
                         status, createby, createdate, observerName, observerDepartment,
                         observerEmail, observerDatetime)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->execute([
                    $now, $locationId, $offenceCode, $staffId, $formType,
                    $violatorName, $company, $violatorDept, $actionTaken,
                    $status, $staffId, $now, $observerName, $observerDept,
                    $observerEmail, $now,
                ]);
            }

            $lastId = (int)$pdo->lastInsertId();
            add_report_history($lastId, $staffId, $isDraft ? 'DRAFT' : 'SUBMIT',
                $isDraft ? 'Saved as draft' : 'Submitted for PSSD HSE review');
            log_history($staffId, $isDraft ? 'DRAFT' : 'SUBMIT',
                ($isDraft ? 'Saved draft' : 'Created') . " report ID $lastId via mobile app", $lastId);
            respond(201, ['created' => true, 'id' => $lastId, 'status' => $status]);
            break;

        case 'PUT':
        case 'PATCH':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) respond(422, ['error' => 'Report id is required']);
            $stmt = $pdo->prepare("SELECT * FROM ucuaform WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $existing = $stmt->fetch();
            if (!$existing) respond(404, ['error' => 'Report not found']);

            $isOwner = $existing['staffId'] === $staffId;
            if (!$canSeeAll && !$isOwner) {
                respond(403, ['error' => 'Access denied']);
            }
            // Only drafts, rejected reports or admins may change a report.
            $editable = $existing['status'] === STATUS_DRAFT || is_rejected_status($existing['status']);
            if (!$editable && !$canSeeAll) {
                respond(409, ['error' => 'Only draft or rejected reports can be edited']);
            }

            $in = body_json();
            $locationId = array_key_exists('locationId', $in) ? trim($in['locationId']) : $existing['LocationId'];
            $conditionDetails = array_key_exists('conditionDetails', $in) ? trim($in['conditionDetails']) : (isset($existing['conditionDetails']) ? $existing['conditionDetails'] : '');
            $offenceCode = array_key_exists('offenceCode', $in) ? trim($in['offenceCode']) : (isset($existing['OffenceCode']) ? $existing['OffenceCode'] : '');
            $violatorName = array_key_exists('violatorName', $in) ? trim($in['violatorName']) : (isset($existing['ViolatorName']) ? $existing['ViolatorName'] : '');
            $violatorCompany = array_key_exists('violatorCompany', $in) ? trim($in['violatorCompany']) : (isset($existing['violatorCompany']) ? $existing['violatorCompany'] : '');
            $violatorDept = array_key_exists('violatorDept', $in) ? trim($in['violatorDept']) : (isset($existing['ViolatorDept']) ? $existing['ViolatorDept'] : '');
            $actionTaken = array_key_exists('actionTaken', $in) ? trim($in['actionTaken']) : (isset($existing['ActionTaken']) ? $existing['ActionTaken'] : '');
            $formType = array_key_exists('formType', $in) ? trim($in['formType']) : $existing['form_type'];

            if ($formType !== 'Unsafe Condition' && $formType !== 'Unsafe Action') {
                respond(422, ['error' => 'formType must be "Unsafe Condition" or "Unsafe Action"']);
            }

            // "Submit" promotes a draft/rejected report back into the approval chain.
            $submit = !empty($in['submit']);
            $newStatus = $existing['status'];
            if ($submit) {
                if ($locationId === '') respond(422, ['error' => 'locationId is required']);
                if ($formType === 'Unsafe Condition' && $conditionDetails === '') {
                    respond(422, ['error' => 'conditionDetails is required for Unsafe Condition']);
                }
                if ($formType === 'Unsafe Action' && ($offenceCode === '' || $violatorCompany === '')) {
                    respond(422, ['error' => 'offenceCode and violatorCompany are required for Unsafe Action']);
                }
                $newStatus = STATUS_HSE;
            }

            $stmt = $pdo->prepare(
                "UPDATE ucuaform SET form_type = ?, LocationId = ?, conditionDetails = ?,
                        OffenceCode = ?, ViolatorName = ?, violatorCompany = ?,
                        ViolatorDept = ?, ActionTaken = ?, status = ?,
                        submitdate = CASE WHEN ? = 1 THEN ? ELSE submitdate END
                 WHERE id = ?"
            );
            $stmt->execute([
                $formType, $locationId, $conditionDetails, $offenceCode,
                $violatorName, $violatorCompany, $violatorDept, $actionTaken,
                $newStatus, $submit ? 1 : 0, date('Y-m-d H:i:s'), $id,
            ]);

            add_report_history($id, $staffId, $submit ? 'RESUBMIT' : 'EDIT',
                $submit ? 'Submitted for review' : 'Report details updated');
            log_history($staffId, 'EDIT', "Updated report ID $id via mobile app", $id);
            respond(200, ['updated' => true, 'id' => $id, 'status' => $newStatus]);
            break;

        case 'DELETE':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) respond(422, ['error' => 'Report id is required']);
            $stmt = $pdo->prepare("SELECT id, staffId, status FROM ucuaform WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $existing = $stmt->fetch();
            if (!$existing) respond(404, ['error' => 'Report not found']);
            if (!$canSeeAll && $existing['staffId'] !== $staffId) {
                respond(403, ['error' => 'Access denied']);
            }
            // Submissions already in the approval chain cannot be deleted by the reporter.
            if (!$canSeeAll && $existing['status'] !== STATUS_DRAFT && !is_rejected_status($existing['status'])) {
                respond(409, ['error' => 'Only drafts or rejected reports can be deleted']);
            }
            try {
                $del = $pdo->prepare("DELETE FROM ucuaphoto WHERE ucuaid = ?");
                $del->execute([$id]);
            } catch (Exception $e) { /* photos table variant — best effort */ }
            try {
                $del = $pdo->prepare("DELETE FROM ucuaphoto WHERE ucuaform_Id = ?");
                $del->execute([$id]);
            } catch (Exception $e) { /* alternate column name — best effort */ }
            try {
                $pdo->prepare("DELETE FROM mobile_report_history WHERE report_id = ?")->execute([$id]);
            } catch (Exception $e) { /* optional table */ }
            $stmt = $pdo->prepare("DELETE FROM ucuaform WHERE id = ?");
            $stmt->execute([$id]);
            log_history($staffId, 'DELETE', "Deleted report ID $id via mobile app", $id);
            respond(200, ['deleted' => true, 'id' => $id]);
            break;

        default:
            respond(405, ['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    respond(500, ['error' => 'reports operation failed', 'detail' => $e->getMessage()]);
}
