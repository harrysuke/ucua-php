<?php
$title = 'RSS Feeds';
include('testFunctions.php');
$fullurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$sql = "SELECT f.Id, o.offKeyword, f.conditionDetails, f.form_type, f.submitdate FROM ucuaform AS f 
LEFT JOIN offence AS o ON f.OffenceCode = o.OffenceCode 
ORDER BY f.submitdate DESC LIMIT 10";

try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $articles = $result->fetch_all(MYSQLI_ASSOC);
} catch (PDOException $e) {
    die($e->getMessage());
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/rss+xml; charset=utf-8');

ob_start();
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<rss version="2.0">' . "\n";
echo '  <channel>' . "\n";
echo '    <title>' . $title . '</title>' . "\n";
echo '    <link>index.php?page=rss</link>' . "\n";
echo '    <description>This is the RSS feed for UCUX feeds</description>' . "\n";
echo '    <language>en-us</language>' . "\n";
echo '    <pubDate>' . date(DATE_RSS) . '</pubDate>' . "\n";

foreach ($articles as $article) {
    echo '    <item>' . "\n";
    echo '      <title>' . htmlspecialchars($article['form_type']) . '</title>' . "\n";
    echo '      <link>default.php?page=newuiuc_form&id=' . base64_encode($article['Id']) . '</link>' . "\n";
    echo '      <description>' . htmlspecialchars($article['offKeyword'] . ' ' . $article['conditionDetails']) . '</description>' . "\n";
    echo '      <pubDate>' . date(DATE_RSS, strtotime($article['submitdate'])) . '</pubDate>' . "\n";
    echo '    </item>' . "\n";
}

echo '  </channel>' . "\n";
echo '</rss>' . "\n";
ob_end_flush();
