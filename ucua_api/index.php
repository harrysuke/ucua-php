<?php
/**
 * UCUA Mobile REST API - Router.
 *
 * Base URL: http://<host>/ucua300/ucua_api/
 *
 * Routes:
 *   POST /login.php                authenticate -> { token, user }
 *   POST /register.php             self-registration (pending approval)
 *   POST /forgot.php               password recovery request
 *   GET  /dashboard.php            KPI counters, trend, activity feed
 *   GET  /lookups.php              reference data (Bearer token required)
 *   GET  /reports.php?type=UC|UA   list reports (Bearer token required)
 *   GET  /reports.php?id=123       single report + photos + timeline
 *   POST /reports.php              create report or draft (Bearer token required)
 *   PUT  /reports.php?id=123        full update (owner or admin, Bearer required)
 *   PATCH /reports.php?id=123       partial update (owner or admin, Bearer required)
 *   DELETE /reports.php?id=123      delete report (owner or admin, Bearer required)
 *   POST /workflow.php             approve / reject / close / resubmit
 *   GET|POST /photos.php           photo evidence list / upload
 *
 * All protected endpoints require header: Authorization: Bearer <token>
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Optional health-check endpoint for mobile app connectivity tests.
if (isset($_GET['health'])) {
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'time' => date('c')]);
    exit;
}

echo json_encode([
    'name'    => 'UCUA Mobile REST API',
    'version' => '1.0.0',
    'endpoints' => [
        'POST /login.php',
        'POST /register.php',
        'POST /forgot.php',
        'GET  /dashboard.php',
        'GET  /lookups.php',
        'GET  /reports.php',
        'POST /reports.php',
        'PUT /reports.php?id=N',
        'PATCH /reports.php?id=N',
        'DELETE /reports.php?id=N',
        'POST /workflow.php',
        'GET  /photos.php?id=N',
        'POST /photos.php',
    ],
]);