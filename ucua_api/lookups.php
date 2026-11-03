<?php
/**
 * GET /lookups - Returns reference data for forms.
 * Returns: locations, offences, companies, departments.
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    respond(405, ['error' => 'Method not allowed']);
}

require_auth();
$pdo = db_connect();

try {
    $locations = $pdo->query(
        "SELECT LocationId, LocationName FROM location WHERE isdelete = 0 ORDER BY LocationName"
    )->fetchAll();

    $offences = $pdo->query(
        "SELECT OffenceCode, offKeyword, Offence FROM offence ORDER BY OffenceCode"
    )->fetchAll();

    $companies = $pdo->query(
        "SELECT id, companyName FROM companies WHERE isDeleted = 0 ORDER BY companyName"
    )->fetchAll();

    $departments = $pdo->query(
        "SELECT deptId, dept, deptName FROM dept WHERE isdelete = 0 ORDER BY deptName"
    )->fetchAll();

    // Legacy rows carry padding spaces (e.g. offence code "001  "); trim so the
    // codes round-trip cleanly between the mobile dropdowns and the database.
    $trim = function ($rows, $fields) {
        return array_map(function ($row) use ($fields) {
            foreach ($fields as $field) {
                if (isset($row[$field]) && is_string($row[$field])) {
                    $row[$field] = trim($row[$field]);
                }
            }
            return $row;
        }, $rows);
    };

    respond(200, [
        'locations'   => $trim($locations, ['LocationId', 'LocationName']),
        'offences'    => $trim($offences, ['OffenceCode', 'offKeyword', 'Offence']),
        'companies'   => $trim($companies, ['companyName']),
        'departments' => $trim($departments, ['dept', 'deptName']),
    ]);
} catch (Exception $e) {
    respond(500, ['error' => 'Failed to load lookups', 'detail' => $e->getMessage()]);
}