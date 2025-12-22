<?php include_once('../includes/config.php'); ?>
<?php
session_start();
$ssColumn = '';
$file_name = $_SESSION['file_name'];
$db_column = $_SESSION['db_column'];
$columnArr = explode(",", $db_column);
$sql=$_SESSION['query'];
	
$head_column = $_SESSION['header_column'];
$qry = mysqli_query($conn,$sql);
$data = $head_column."\n";
while($row = mysqli_fetch_array($qry)) {
	foreach($columnArr as $vl)
 	{
		$row[$vl]=str_replace(",","`", $row[$vl]);
 	    $data.=$row[$vl].",";
 	}
 	$data.="\n";
}
$fname="filename.csv";

if($file_name!=''){
	$fname=$file_name;
}

header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename='.$fname);
unset($_SESSION['file_name']);
echo $data; exit();
?>
