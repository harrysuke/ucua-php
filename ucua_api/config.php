<?php
/**
 * UCUA Mobile REST API - Configuration & Shared Helpers
 * Backs the native Android app.
 * Target database: ucua-staging (the live application database).
 */

// --- CORS + JSON headers (applied to every request) ----------------------
if (PHP_SAPI !== 'cli') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");
}

// Handle preflight
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

define('JWT_SECRET', 'change-this-ucua-mobile-secret-2026');

// --- Report workflow ----------------------------------------------------
// Approval chain: PSSD HSE Officer -> HOS -> HOD -> Closed  (BRR-003).
// Status strings intentionally match the legacy web application so reports
// created on mobile render correctly in the existing dashboard and reports.
define('STATUS_DRAFT',  'Draft');
define('STATUS_HSE',    'Pending PSSD HSE Officer');       // awaiting PSSD HSE Officer
define('STATUS_HOS',    'Approved by PSSD HSE Officer');   // awaiting HOS
define('STATUS_HOD',    'Approved by HOS');                // awaiting HOD
define('STATUS_CLOSED', 'Approved by HOD');
define('STATUS_REJECTED', 'Rejected by PSSD HSE Officer');

/** Legacy HSE Rep queue statuses that sit before the PSSD HSE Officer stage. */
function is_rejected_status($status)
{
    return stripos((string)$status, 'Rejected') === 0;
}

function is_closed_status($status)
{
    $status = (string)$status;
    return $status === STATUS_CLOSED || $status === 'Approved by HODs';
}

/**
 * Access levels allowed to act on a workflow stage.
 * 3 = PSSD Admin, 4 = PSSD HSE Officer, 6 = PSSD HOS, 7 = PSSD HOD,
 * 8 = HSE Rep, 9 = ICT Admin.
 */
function stage_approvers($stage)
{
    switch ($stage) {
        case 'HSE':   return [3, 4, 8, 9];
        case 'HOS':   return [3, 6, 9];
        case 'HOD':   return [3, 7, 9];
        case 'CLOSE': return [3, 7, 9];
        default:      return [];
    }
}

/** Map a stored status string (including legacy variants) to a stage key. */
function stage_for_status($status)
{
    switch ((string)$status) {
        case STATUS_HSE:
        case 'Pending HSE Reps':
        case 'Updated by HSE Reps':
            return 'HSE';
        case STATUS_HOS:
        case 'Approved by PSSD Officer':
            return 'HOS';
        case STATUS_HOD:
            return 'HOD';
        case STATUS_CLOSED:
        case 'Approved by HODs':
            return 'CLOSE';
        default:
            return null;
    }
}

function next_status($stage)
{
    switch ($stage) {
        case 'HSE': return STATUS_HOS;
        case 'HOS': return STATUS_HOD;
        case 'HOD': return STATUS_CLOSED;
        default:    return null;
    }
}

/** Status recorded when an approver rejects at a given stage (BRR-004). */
function rejected_status($stage)
{
    switch ($stage) {
        case 'HOS': return 'Rejected by HOS';
        case 'HOD': return 'Rejected by HOD';
        default:    return STATUS_REJECTED;
    }
}

function can_act_on_stage($accessLevel, $stage)
{
    return in_array((int)$accessLevel, stage_approvers($stage), true);
}

// --- Database -----------------------------------------------------------
function db_connect()
{
    static $cont = null;
    if ($cont === null) {
        $host = 'localhost';
        $db   = 'ucua-staging';
        $user = 'root';
        $pass = '';
        try {
            $cont = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8mb4",
                $user,
                $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            respond(500, ['error' => 'Database connection failed']);
        }
        db_bootstrap($cont);
    }
    return $cont;
}

function db_bootstrap($pdo)
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS mobile_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            token VARCHAR(255) NOT NULL,
            staffId VARCHAR(50) NOT NULL,
            expires_at DATETIME NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uk_token (token),
            KEY idx_staff (staffId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
    // Per-report workflow timeline used by the mobile client. The collation must
    // match the legacy tables (utf8mb4_general_ci) or joins on staffId fail.
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS mobile_report_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            report_id INT NOT NULL,
            staffId VARCHAR(50) NOT NULL,
            action VARCHAR(30) NOT NULL,
            remarks TEXT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            KEY idx_report (report_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    );
    try {
        $pdo->exec(
            "ALTER TABLE mobile_report_history
             CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci"
        );
    } catch (Exception $e) {
        // Already using a compatible collation.
    }
    // Called only when starting a fresh process connection.
}

// --- Response helpers -----------------------------------------------------
function respond($code, $payload)
{
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

function body_json()
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function base64url_encode($value)
{
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

// --- Token helpers ---------------------------------------------------------
function issue_token($staffId, $accessLevel)
{
    $pdo      = db_connect();
    $expires  = time() + 60 * 60 * 24 * 7; // 7 days
    $payload  = json_encode(['staffId' => $staffId, 'lvl' => (int)$accessLevel, 'exp' => $expires]);
    $encoded  = base64url_encode($payload);
    $sig      = hash_hmac('sha256', $encoded, JWT_SECRET);
    $token    = "$encoded.$sig";
    $stmt = $pdo->prepare(
        "INSERT INTO mobile_tokens (token, staffId, expires_at) VALUES (?, ?, ?)"
    );
    $stmt->execute([$token, $staffId, date('Y-m-d H:i:s', $expires)]);
    return $token;
}

function user_from_token($token)
{
    $pdo = db_connect();
    $stmt = $pdo->prepare(
        "SELECT staffId FROM mobile_tokens WHERE token = ? AND expires_at > NOW() LIMIT 1"
    );
    $stmt->execute([$token]);
    $row = $stmt->fetch();
    if (!$row) return null;

    $stmt = $pdo->prepare(
        "SELECT u.staffId, u.Name, u.PhoneNo, u.Department, u.Designation,
                u.AccessLevel, u.Email, u.CompanyID, r.rolename
         FROM user AS u
         LEFT JOIN userroles AS r ON r.id = u.AccessLevel
         WHERE u.staffId = ? AND u.Status = 1 AND u.isdelete = 0
         LIMIT 1"
    );
    $stmt->execute([$row['staffId']]);
    return $stmt->fetch();
}

function require_auth()
{
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $auth = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    $token = '';
    if (strpos($auth, 'Bearer ') === 0) $token = substr($auth, 7);
    if ($token === '' && isset($_GET['token'])) $token = $_GET['token'];
    if ($token === '') respond(401, ['error' => 'Authentication required']);

    $user = user_from_token($token);
    if (!$user) respond(401, ['error' => 'Invalid or expired token']);
    return $user;
}

function log_history($staffId, $action, $activity, $reportId = 0)
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare(
            "INSERT INTO history (ucuaid, staffid, accessLevel, action, activity, status, `sql`, ipaddress, useragent, datetimestamp)
             VALUES (?, ?, 0, ?, ?, 'SUCCESS', '', ?, ?, ?)"
        );
        $stmt->execute([
            (int)$reportId,
            $staffId,
            $action,
            $activity,
            isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
            isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            date('Y-m-d H:i:s'),
        ]);
    } catch (Exception $e) {
        // Best-effort logging only.
    }
}

/**
 * Append an entry to the per-report workflow timeline (BR-027 / BRR-008).
 */
function add_report_history($reportId, $staffId, $action, $remarks = null)
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare(
            "INSERT INTO mobile_report_history (report_id, staffId, action, remarks, created_at)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$reportId, $staffId, $action, $remarks, date('Y-m-d H:i:s')]);
    } catch (Exception $e) {
        // Timeline logging must never break the workflow action.
    }
}

function report_timeline($reportId)
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare(
            "SELECT h.id, h.action, h.remarks, h.created_at, h.staffId, u.Name AS staffName
             FROM mobile_report_history h
             LEFT JOIN user u ON u.staffId = h.staffId
             WHERE h.report_id = ?
             ORDER BY h.id ASC"
        );
        $stmt->execute([$reportId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/** Latest rejection reason recorded for a report, if any. */
function latest_rejection_reason($reportId)
{
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare(
            "SELECT remarks FROM mobile_report_history
             WHERE report_id = ? AND action = 'REJECT' ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute([$reportId]);
        $row = $stmt->fetch();
        return $row ? $row['remarks'] : null;
    } catch (Exception $e) {
        return null;
    }
}

// --- File uploads ---------------------------------------------------------
/**
 * Evidence lives in the application web root (`ucua300/uploads`), matching the
 * legacy convention where `ucuaphoto.evidenceImage` stores `uploads/<file>`.
 */
function uploads_root()
{
    $dir = dirname(__DIR__) . '/uploads';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

/** Absolute URL of the application web root, e.g. http://host/ucua300/ */
function app_base_url()
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '/ucua300/ucua_api/index.php';
    $appDir = rtrim(dirname(dirname($script)), '/'); // strip /ucua_api/<file>.php
    return $scheme . '://' . $host . $appDir . '/';
}

/** Kept for backwards compatibility with earlier endpoints. */
function uploads_url()
{
    return app_base_url() . 'uploads';
}

/**
 * Resolve a stored `evidenceImage` value (already `uploads/<file>` or a full
 * URL) into something the mobile client can load.
 */
function photo_public_url($evidenceImage)
{
    $value = trim((string)$evidenceImage);
    if ($value === '') return '';
    if (preg_match('#^https?://#i', $value)) return $value;
    return app_base_url() . ltrim(str_replace('\\', '/', $value), '/');
}

/**
 * Allowed evidence extensions (BRR-006). Reads the legacy `prefix` table when
 * available and always permits the standard image types.
 */
function allowed_extensions()
{
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    try {
        $pdo  = db_connect();
        $rows = $pdo->query("SELECT * FROM prefix")->fetchAll();
        foreach ($rows as $row) {
            foreach ($row as $key => $value) {
                if (!is_string($value)) continue;
                $candidate = strtolower(ltrim(trim($value), '.'));
                if ($candidate !== '' && preg_match('/^[a-z0-9]{2,5}$/', $candidate)) {
                    $allowed[] = $candidate;
                }
            }
        }
    } catch (Exception $e) {
        // prefix table is optional.
    }
    return array_values(array_unique($allowed));
}