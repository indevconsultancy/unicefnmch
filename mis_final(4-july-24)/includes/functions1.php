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
