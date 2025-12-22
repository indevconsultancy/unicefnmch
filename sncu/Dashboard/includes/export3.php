<?php include('config.php') ?>
<?php
session_start();

$filename = $_REQUEST['filename'];

$today = date('dmyhis');

$ssColumn = '';
$db_column = $_SESSION['dbb_column'];
$columnArr = explode(",", $db_column);

$sql = $_SESSION['dbb_query'];
$head_column = $_SESSION['hheader_column'];

// header_column,db_column, and query are store in session from the list page 

$qry = mysqli_query($conn, $sql);
$data = $head_column . "\n";

while ($row = mysqli_fetch_array($qry)) {
    foreach ($columnArr as $vl) {
        // $row[$vl] = str_replace(",", "`", $row[$vl]);
        $data .= $row[$vl] . ",";
    }
    $data .= "\n";
}

header('Content-Type: application/csv');
echo header('Content-Disposition: attachment; filename=	' . $filename . '-' . $today . '".csv');

echo $data;
exit();
?>
