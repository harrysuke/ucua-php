<?php
/**
 * POST /register.php - Employee self-registration (BR-001, BR-002).
 *
 * Body: { staffId, name, password, email?, phoneNo?, department?, designation? }
 * New accounts are created with AccessLevel 1 and Status 3 (pending PSSD approval).
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['error' => 'Method not allowed']);
}

$in = body_json();

$staffId  = isset($in['staffId']) ? trim($in['staffId']) : '';
$name     = isset($in['name']) ? trim($in['name']) : '';
$password = isset($in['password']) ? (string)$in['password'] : '';
$email    = isset($in['email']) ? trim($in['email']) : '';
$phoneNo  = isset($in['phoneNo']) ? trim($in['phoneNo']) : '';
$dept     = isset($in['department']) ? trim($in['department']) : '';
$desig    = isset($in['designation']) ? trim($in['designation']) : '';
$company  = isset($in['companyId']) ? trim($in['companyId']) : '';

$errors = [];
if ($staffId === '') $errors['staffId'] = 'Staff ID is required';
if ($name === '') $errors['name'] = 'Full name is required';
if (strlen($password) < 6) $errors['password'] = 'Password must be at least 6 characters';
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Enter a valid email address';
}
if (!empty($errors)) {
    respond(422, ['registered' => false, 'error' => reset($errors), 'fields' => $errors]);
}

try {
    $pdo = db_connect();

    // Staff IDs are unique across active and soft-deleted accounts (BR-002).
    $stmt = $pdo->prepare("SELECT staffId FROM user WHERE staffId = ? LIMIT 1");
    $stmt->execute([$staffId]);
    if ($stmt->fetch()) {
        respond(409, ['registered' => false, 'error' => 'This Staff ID is already registered']);
    }

    $now = date('Y-m-d H:i:s');
    $companyId = (int)$company;
    if ($companyId <= 0) $companyId = 1; // default company keeps the FK satisfied

    $stmt = $pdo->prepare(
        "INSERT INTO user
            (staffId, Name, Password, Password1, Email, PhoneNo, Department, Designation,
             CompanyID, AccessLevel, Status, isdelete, createby, createdate, editby, editdate, deleteby, deletedate)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 3, 0, ?, ?, '', ?, '', ?)"
    );
    $stmt->execute([
        $staffId,
        $name,
        md5($password), // legacy-compatible hash, see REQ-SEC-006 migration note
        md5($password),
        $email,
        $phoneNo,
        $dept,
        $desig,
        $companyId,
        $staffId,
        $now,
        $now,
        $now,
    ]);

    log_history($staffId, 'REGISTER', 'Registered via mobile app');

    respond(201, [
        'registered' => true,
        'staffId'    => $staffId,
        'status'     => 'pending_approval',
        'message'    => 'Registration submitted. Your account is pending PSSD approval.',
    ]);
} catch (Exception $e) {
    respond(500, ['registered' => false, 'error' => 'Registration failed', 'detail' => $e->getMessage()]);
}
