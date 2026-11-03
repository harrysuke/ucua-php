<?php
/* -------------------------------------------------
   health_collect_windows.php
   Windows‑only system‑health collector.
   ------------------------------------------------- */

/**
 * @return array{float|null,float|null,float|null}
 */
function getMysqlResponseTime(): ?float
{
    // Database connection parameters (replace with your actual credentials)
    $host = 'localhost'; // MySQL server host
    $user = 'root'; // MySQL username
    $pass = ''; // MySQL password
    $db = 'ucua'; // MySQL database name

    // Attempt to connect to MySQL
    $conn = @mysqli_connect($host, $user, $pass, $db); // MySQL connection resource
    if (!$conn) {
        return null; // Return null if connection fails
    }

    // Measure time of a simple query
    [$_, $elapsedMs] = timeMs(function () use ($conn) {
        $result = mysqli_query($conn, 'SELECT 1'); // Simple query result
        return $result !== false; // Return true if query succeeds
    });

    // Close connection
    mysqli_close($conn); // Close MySQL connection

    // Return elapsed time if query succeeded, else null
    return $_ === true ? $elapsedMs : null; // MySQL response time in milliseconds
}
function getCpuLoad(): array
{
    // Try WMIC first (works on Windows 10/Server 2019 and older)
    $output = [];
    @exec('wmic cpu get LoadPercentage /value', $output);
    foreach ($output as $line) {
        if (preg_match('/LoadPercentage=(\d+)/i', $line, $m)) {
            $pct = (float)$m[1] / 100;          // 0‑1.0 range
            return [$pct, $pct, $pct];
        }
    }

    // If WMIC is missing (newer Windows 11/Server 2022), fall back to PowerShell.
    // PowerShell command returns a single integer 0‑100.
    $psCmd = 'powershell -NoProfile -Command "Get-Counter -Counter \"\\Processor(_Total)\\% Processor Time\" | '
        . 'Select -ExpandProperty CounterSamples | Select -ExpandProperty CookedValue"';
    $psOut = [];
    @exec($psCmd, $psOut);
    if (!empty($psOut[0]) && is_numeric(trim($psOut[0]))) {
        $pct = (float)trim($psOut[0]) / 100;   // convert to 0‑1.0
        return [$pct, $pct, $pct];
    }

    // If everything fails, return nulls – the dashboard will show “–”.
    return [null, null, null];
}

/**
 * Get memory information.
 *
 * Returns an associative array:
 *   [ 'total' => int|null,   // bytes
 *     'used'  => int|null,
 *     'free'  => int|null ]
 */
function getMemory(): array
{
    // WMIC gives values in kilobytes.
    $out = [];
    @exec('wmic OS get TotalVisibleMemorySize,FreePhysicalMemory /value', $out);
    $totalKb = $freeKb = null;
    foreach ($out as $line) {
        if (preg_match('/TotalVisibleMemorySize=(\d+)/i', $line, $m)) {
            $totalKb = (int)$m[1];
        }
        if (preg_match('/FreePhysicalMemory=(\d+)/i', $line, $m)) {
            $freeKb = (int)$m[1];
        }
    }

    // If WMIC failed, try PowerShell.
    if ($totalKb === null || $freeKb === null) {
        $psCmd = 'powershell -NoProfile -Command "Get-CimInstance -ClassName Win32_OperatingSystem '
            . '| Select-Object TotalVisibleMemorySize,FreePhysicalMemory"';
        $psOut = [];
        @exec($psCmd, $psOut);
        foreach ($psOut as $line) {
            if (preg_match('/(\d+)\s+(\d+)/', $line, $m)) {
                $totalKb = (int)$m[1];
                $freeKb  = (int)$m[2];
                break;
            }
        }
    }

    if ($totalKb === null || $freeKb === null) {
        return ['total' => null, 'used' => null, 'free' => null];
    }

    $totalBytes = $totalKb * 1024;
    $freeBytes  = $freeKb  * 1024;
    $usedBytes  = $totalBytes - $freeBytes;

    return ['total' => $totalBytes, 'used' => $usedBytes, 'free' => $freeBytes];
}

/**
 * Disk statistics for a given path (default is the drive where the script lives).
 *
 * @param string $path
 * @return array{total:int|null, used:int|null, free:int|null}
 */
function getDisk(string $path = __DIR__): array
{
    $total = @disk_total_space($path);
    $free  = @disk_free_space($path);
    if ($total === false || $free === false) {
        return ['total' => null, 'used' => null, 'free' => null];
    }
    return ['total' => $total, 'used' => $total - $free, 'free' => $free];
}

/**
 * Simple timer – returns [ $result, $elapsedMs ].
 *
 * @param callable $fn
 * @return array
 */
function timeMs(callable $fn): array
{
    $start = microtime(true);
    $result = $fn();
    $elapsed = (microtime(true) - $start) * 1000; // milliseconds
    return [$result, $elapsed];
}

/**
 * Human‑readable byte formatter (used only for optional alerts).
 *
 * @param int $bytes
 * @return string
 */
function fmtBytes(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

/* -------------------------------------------------
   3️⃣  COLLECT THE METRICS
   ------------------------------------------------- */

[$cpu1, $cpu5, $cpu15] = getCpuLoad();          // three identical values on Windows

$mem  = getMemory();                           // ['total'=>..., 'used'=>..., 'free'=>...]
$disk = getDisk();                             // same keys

// Example PHP‑code timing – replace with a real request if you wish.
list($_, $phpRespMs) = timeMs(fn() => usleep(10_000)); // ~10 ms dummy



// Optional custom note – you can fill this from any app‑specific check.
$note = null;   // e.g. "queue empty", "cache warm", etc.
$mysqlRespMs = getMysqlResponseTime();

/* -------------------------------------------------
   5️⃣  OPTIONAL ALERTING (email or Slack)
   ------------------------------------------------- */
// Adjust thresholds to your environment.
$highCpu = $cpu1 !== null && $cpu1 > 0.80; // Flag for high CPU usage (>80%)
$lowDisk = $disk['free'] !== null && $disk['free'] < 5 * 1024 * 1024 * 1024; // Flag for low disk space (<5GB)
$lowMem  = $mem['free'] !== null && $mem['free'] < 2 * 1024 * 1024 * 1024; // Flag for low memory (<2GB)
$highMysql = $mysqlRespMs !== null && $mysqlRespMs > 500; // Flag for high MySQL response time (>500ms)
$msg = '';

if ($highCpu || $lowDisk || $lowMem || $highMysql) {
    $msg .= "⚠️ System‑health warning on " . php_uname('n') . PHP_EOL; // Alert message header
    if ($highCpu)   $msg .= " CPU load ≈ " . round($cpu1 * 100, 1) . "% (1‑min)<br/>" . PHP_EOL; // CPU alert message
    if ($lowDisk)   $msg .= " Disk free: " . fmtBytes($disk['free']) . "<br/>" . PHP_EOL; // Disk alert message
    if ($lowMem)    $msg .= " Memory free: " . fmtBytes($mem['free']) . "<br/>" . PHP_EOL; // Memory alert message
    if ($highMysql) $msg .= " MySQL response time: " . round($mysqlRespMs, 2) . "ms <br/>" . PHP_EOL; // MySQL alert message
}

/* -------------------------------------------------
   4️⃣  INSERT THE ROW
   ------------------------------------------------- */
echo $msg;
echo 'CPU load for 1 minute ' . $cpu1 . '<br/>'; // CPU load for 1 minute
echo 'CPU load for 5 minutes ' . $cpu5 . '<br/>'; // CPU load for 5 minutes
echo 'CPU load for 15 minutes  ' . $cpu15 . '<br/>'; // CPU load for 15 minutes
echo 'Total memory ' . fmtBytes($mem['total']) . '<br/>'; // Total memory in bytes
echo 'Used memory ' . fmtBytes($mem['used']) . '<br/>'; // Used memory in bytes
echo 'Free memory ' . fmtBytes($mem['free']) . '<br/>'; // Free memory in bytes
echo 'Total disk space ' . fmtBytes($disk['total']) . '<br/>'; // Total disk space in bytes
echo 'Used disk space ' . fmtBytes($disk['used']) . '<br/>'; // Used disk space in bytes
echo 'Free disk space ' . fmtBytes($disk['free']) . '<br/>'; // Free disk space in bytes
echo 'PHP response time in milliseconds ' . $phpRespMs . '<br/>'; // PHP response time in milliseconds
echo 'MySQL response time in milliseconds ' . $mysqlRespMs . '<br/>'; // MySQL response time in milliseconds (commented out)
echo 'Custom note for app-specific status ' . $note . '<br/>'; // Custom note for app-specific status
echo "✅ Health snapshot stored at " . date('c') . PHP_EOL;

/* -------------------------------------------------
   5️⃣  OPTIONAL ALERTING (email or Slack)
   ------------------------------------------------- */
// Adjust thresholds to your environment.
$highCpu   = $cpu1 !== null && $cpu1 > 0.80;          // >80 % CPU usage
$lowDisk   = $disk['free'] !== null && $disk['free'] < 5 * 1024 * 1024 * 1024; // <5 GB free
$lowMem    = $mem['free']  !== null && $mem['free']  < 2 * 1024 * 1024 * 1024; // <2 GB free

if ($highCpu || $lowDisk || $lowMem) {
    $msg = "⚠️ System‑health warning on " . php_uname('n') . PHP_EOL;
    if ($highCpu)   $msg .= "- CPU load ≈ " . round($cpu1 * 100, 1) . "% (1‑min)" . PHP_EOL;
    if ($lowDisk)   $msg .= "- Disk free: " . fmtBytes($disk['free']) . PHP_EOL;
    if ($lowMem)    $msg .= "- Memory free: " . fmtBytes($mem['free']) . PHP_EOL;
}
