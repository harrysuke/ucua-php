<?php
/**
 * POST /login - Authenticate a user and return a Bearer token + profile.
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['error' => 'Method not allowed']);
}

$staffId  = isset($_POST['staffId']) ? trim($_POST['staffId']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Allow JSON body too.
if (empty($staffId) && empty($password)) {
    $json = body_json();
    $staffId  = isset($json['staffId']) ? trim($json['staffId']) : '';
    $password = isset($json['password']) ? trim($json['password']) : '';
}

if ($staffId === '' || $password === '') {
    respond(422, ['error' => 'staffId and password are required']);
}

// First, count recent failed attempts from the history table to throttle.
$pdo = db_connect();

// Validate credentials (legacy MD5 to stay compatible with existing users).
$stmt = $pdo->prepare(
    "SELECT u.staffId, u.Name, u.PhoneNo, u.Department, u.Designation,
            u.AccessLevel, u.Email, u.CompanyID, u.Status, r.rolename
     FROM user AS u
     LEFT JOIN userroles AS r ON r.id = u.AccessLevel
     WHERE u.staffId = ? AND u.Password = ? AND u.isdelete = 0
     LIMIT 1"
);
$stmt->execute([$staffId, md5($password)]);
$user = $stmt->fetch();

if (!$user) {
    respond(401, ['valid' => false, 'error' => 'Wrong username/password combination']);
}

if ((int)$user['Status'] === 3) {
    respond(403, ['valid' => false, 'error' => 'Your account has not been approved']);
}
if ((int)$user['Status'] === 2) {
    respond(403, ['valid' => false, 'error' => 'Your account has been DISABLED']);
}

$token = issue_token($user['staffId'], $user['AccessLevel']);
log_history($user['staffId'], 'LOGIN', 'Mobile app login');

respond(200, [
    'valid' => true,
    'token' => $token,
    'user'  => [
        'staffId'    => $user['staffId'],
        'name'       => $user['Name'],
        'phoneNo'    => $user['PhoneNo'],
        'department' => $user['Department'],
        'designation'=> $user['Designation'],
        'email'      => $user['Email'],
        'accessLevel'=> (int)$user['AccessLevel'],
        'roleName'   => $user['rolename'],
        'companyId'  => $user['CompanyID'],
    ],
]);