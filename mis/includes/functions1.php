<?php
function getKeyWordName($conn, $keyWOrdID)
{
    $sqlKeyword = mysqli_query($conn, "SELECT GROUP_CONCAT(keyword_name) as keyname FROM keywords where status='1' and keywords_id in ($keyWOrdID)");
    $selkeydata = mysqli_fetch_array($sqlKeyword);
    return $selkeydata['keyname'];
}
function getKeyWordNames($conn, $tablename, $qryfield, $keyWordID, $fields)
{
    $tablename = mysqli_real_escape_string($conn, $tablename);
    $qryfield = mysqli_real_escape_string($conn, $qryfield);
    $keyWordID = mysqli_real_escape_string($conn, $keyWordID);
    $sqlKeyword = "SELECT GROUP_CONCAT($fields SEPARATOR ', ') as fields FROM $tablename WHERE status='1' AND $qryfield IN ($keyWordID)";
    $result = mysqli_query($conn, $sqlKeyword);
    $selkeydata = mysqli_fetch_array($result);
    return $selkeydata['fields'];
}

function getcotegryname($conn, $tablename, $qryfield, $cID, $fields)
{
    $tablename = mysqli_real_escape_string($conn, $tablename);
    $qryfield = mysqli_real_escape_string($conn, $qryfield);
    $cID = mysqli_real_escape_string($conn, $cID);
    $sqlKeyword = "SELECT GROUP_CONCAT($fields SEPARATOR ', ') as fields FROM $tablename WHERE  $qryfield IN ($cID)";
    $result = mysqli_query($conn, $sqlKeyword);
    $selkeydata = mysqli_fetch_array($result);
    return $selkeydata['fields'];
}
function getonefield($conn, $tablename, $field, $qryfeild, $value)
{
    $sn = mysqli_query($conn, "select  $field  as field from $tablename where $qryfeild='" . $value . "' ") or die(mysqli_error());
    $dn = mysqli_fetch_object($sn);
    return ($dn->field);
}

function sanitizeInput($data, $conn)
{
    // Trim whitespace from the beginning and end
    $data = trim($data);

    // Remove backslashes if magic quotes are enabled
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $data = stripslashes($data);
    }

    // Remove all HTML tags
    $data = strip_tags($data);

    // Remove JavaScript-related patterns and common functions
    $patterns = array(
        '/<\s*script\b[^>]*>(.*?)<\/\s*script>/is',     // Remove <script> tags
        '/<\s*iframe\b[^>]*>(.*?)<\/\s*iframe>/is',     // Remove <iframe> tags
        '/<\s*style\b[^>]*>(.*?)<\/\s*style>/is',       // Remove <style> tags
        '/on[a-z]+\s*=\s*[^"\'\s]+/is',                 // Remove inline event handlers
        '/javascript:[^"\']*/is',                        // Remove javascript: protocols
        '/<\s*img\b[^>]*\s*onerror\s*=\s*[^>]*>/is',     // Remove onerror attributes in <img> tags
        '/<[^>]+(on\w+|style|javascript:)[^>]*>/is',    // Remove tags with dangerous attributes
        '/(?<![\w\d])\b(?:on\w+|javascript|data)\b[\w\d\s]*=[^\s"\'<>]+/is', // Remove more patterns
        '/\b(?:alert|confirm|prompt|eval|exec|document\.write|window\.open|location\.href|console\.log|setInterval|setTimeout)\s*\([^)]*\)/is' // Remove common JS functions
    );
    $data = preg_replace($patterns, '', $data);

    // Remove any remaining script-like patterns
    $data = preg_replace('/\b(?:alert|confirm|prompt|eval|exec|document\.write|window\.open|location\.href|console\.log|setInterval|setTimeout)\s*\([^)]*\)/is', '', $data);

    // Remove specific characters
    $data = str_replace(array('%', '&', '+', '='), '', $data);

    // Convert special characters to HTML entities to prevent XSS
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    // Escape special characters for use in an SQL query
    $data = mysqli_real_escape_string($conn, $data);

    return $data;
}
function formatBytes($bytes, $precision = 2)
{
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;

    if ($bytes < $kilobyte) {
        return $bytes . ' B';
    } elseif ($bytes < $megabyte) {
        return round($bytes / $kilobyte, $precision) . ' KB';
    } elseif ($bytes < $gigabyte) {
        return round($bytes / $megabyte, $precision) . ' MB';
    } else {
        return round($bytes / $gigabyte, $precision) . ' GB';
    }
}
