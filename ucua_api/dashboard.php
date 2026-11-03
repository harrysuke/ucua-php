<?php
/**
 * GET /dashboard.php - Aggregated KPI data for the mobile dashboard.
 * Implements REQ-DASH-001..003 (counters, trend chart, activity feed).
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    respond(405, ['error' => 'Method not allowed']);
}

$user    = require_auth();
$pdo     = db_connect();
$staffId = $user['staffId'];
$level   = (int)$user['AccessLevel'];
$isAdmin = $level >= 3;

try {
    // --- Personal counters -------------------------------------------------
    $counts = ['uc' => 0, 'ua' => 0, 'drafts' => 0, 'pending' => 0, 'closed' => 0, 'rejected' => 0];

    $stmt = $pdo->prepare(
        "SELECT form_type, status, COUNT(*) AS total
         FROM ucuaform WHERE staffId = ? GROUP BY form_type, status"
    );
    $stmt->execute([$staffId]);
    foreach ($stmt->fetchAll() as $row) {
        $status = (string)$row['status'];
        $total  = (int)$row['total'];
        if ($row['form_type'] === 'Unsafe Condition') $counts['uc'] += $total;
        if ($row['form_type'] === 'Unsafe Action')    $counts['ua'] += $total;
        if ($status === STATUS_DRAFT)          $counts['drafts']   += $total;
        elseif (is_closed_status($status))     $counts['closed']   += $total;
        elseif (is_rejected_status($status))   $counts['rejected'] += $total;
        elseif ($status !== '')                $counts['pending']  += $total;
    }

    // --- Approval queues (only populated for users who may act) ------------
    $queues = ['hse' => 0, 'hos' => 0, 'hod' => 0];
    $queueStatuses = [
        'hse' => [STATUS_HSE, 'Pending HSE Reps', 'Updated by HSE Reps'],
        'hos' => [STATUS_HOS, 'Approved by PSSD Officer'],
        'hod' => [STATUS_HOD],
    ];
    $stageByKey = ['hse' => 'HSE', 'hos' => 'HOS', 'hod' => 'HOD'];
    foreach ($queueStatuses as $key => $statuses) {
        if (!can_act_on_stage($level, $stageByKey[$key])) continue;
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM ucuaform WHERE status IN ($placeholders)");
        $stmt->execute($statuses);
        $queues[$key] = (int)$stmt->fetch()['total'];
    }

    // --- Six month submission trend ---------------------------------------
    $trend = [];
    $stmt = $pdo->prepare(
        "SELECT DATE_FORMAT(submitdate, '%Y-%m') AS ym, form_type, COUNT(*) AS total
         FROM ucuaform
         WHERE submitdate >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
           AND status <> 'Draft'
           AND (? = 1 OR staffId = ?)
         GROUP BY ym, form_type
         ORDER BY ym ASC"
    );
    $stmt->execute([$isAdmin ? 1 : 0, $staffId]);
    $byMonth = [];
    foreach ($stmt->fetchAll() as $row) {
        $ym = $row['ym'] ?: date('Y-m');
        if (!isset($byMonth[$ym])) $byMonth[$ym] = ['uc' => 0, 'ua' => 0];
        if ($row['form_type'] === 'Unsafe Condition') $byMonth[$ym]['uc'] += (int)$row['total'];
        if ($row['form_type'] === 'Unsafe Action')    $byMonth[$ym]['ua'] += (int)$row['total'];
    }
    // Always return six consecutive buckets so the chart has a stable axis.
    for ($i = 5; $i >= 0; $i--) {
        $ym    = date('Y-m', strtotime("-$i months"));
        $label = date('M', strtotime($ym . '-01'));
        $trend[] = [
            'month' => $ym,
            'label' => $label,
            'uc'    => isset($byMonth[$ym]) ? $byMonth[$ym]['uc'] : 0,
            'ua'    => isset($byMonth[$ym]) ? $byMonth[$ym]['ua'] : 0,
        ];
    }

    // --- Activity feed -----------------------------------------------------
    $stmt = $pdo->prepare(
        "SELECT id, action, activity, datetimestamp
         FROM history WHERE staffid = ? ORDER BY id DESC LIMIT 8"
    );
    $stmt->execute([$staffId]);
    $activity = array_map(function ($row) {
        return [
            'id'       => (int)$row['id'],
            'action'   => $row['action'],
            'activity' => $row['activity'],
            'date'     => $row['datetimestamp'],
        ];
    }, $stmt->fetchAll());

    // --- Participation leaderboard (REQ-ENG-004) --------------------------
    $leaderboard = [];
    try {
        $rows = $pdo->query(
            "SELECT f.staffId, MAX(u.Name) AS name, COUNT(*) AS total
             FROM ucuaform f
             LEFT JOIN user u ON u.staffId = f.staffId
             WHERE f.status <> 'Draft'
             GROUP BY f.staffId
             ORDER BY total DESC, f.staffId ASC
             LIMIT 5"
        )->fetchAll();
        foreach ($rows as $row) {
            $leaderboard[] = [
                'staffId' => $row['staffId'],
                'name'    => $row['name'] ?: $row['staffId'],
                'total'   => (int)$row['total'],
            ];
        }
    } catch (Exception $e) {
        // Leaderboard is decorative; never fail the dashboard for it.
    }

    respond(200, [
        'counts'      => $counts,
        'queues'      => $queues,
        'trend'       => $trend,
        'activity'    => $activity,
        'leaderboard' => $leaderboard,
        'accessLevel' => $level,
    ]);
} catch (Exception $e) {
    respond(500, ['error' => 'Failed to load dashboard', 'detail' => $e->getMessage()]);
}
