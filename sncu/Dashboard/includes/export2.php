  <?php include('config.php') ?>
  <?php
  
  session_start();
  
  $filename = $_REQUEST['filename'];
  
  $today = date('dmyhis');
  //$bid=$_SESSION['bid'];
  
  $ssColumn = '';
  $db_column = $_SESSION['db_column'];
  $columnArr = explode(",", $db_column);
  
  $sqlSess = $_SESSION['query'];
  $head_column = $_SESSION['header_column'];
  
  // header_column,db_column, and query are store in session from the list page 

  ?>
 <?php
//require 'vendor/autoload.php'; // Path to Composer's autoload

use PhpSpreadsheet\Spreadsheet;
use PhpSpreadsheet\Writer\Xlsx;


// 2. Fetch data
$sql =  $sqlSess;
$result = mysqli_query($conn,$sql);

// 3. Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 4. Set column headers
$headers = ['ID', 'Sex', 'GA', 'Weight', 'Length', 'Headcircumference'];
$sheet->fromArray($headers, NULL, 'A1');

// 5. Write data rows
$row = 2;
while ($data =mysqli_fetch_assoc($result)) {
	$ga='';
    if ($datafacilitator->type_of_monitoring == 'Date of Admission') { $ga=$datafacilitator->gestational_age_LBW * 7; }
    if ($datafacilitator->type_of_monitoring == 'Discharge Day') {$ga=($datafacilitator->gestational_age_LBW * 7)+($datafacilitator->age_in_days);}
    $sheet->setCellValue("A$row", $data['mon_id']);
    $sheet->setCellValue("B$row", $data['sex']);
    $sheet->setCellValue("C$row", $ga);
    $sheet->setCellValue("D$row", $data['admission_weight']);
    $sheet->setCellValue("E$row", $data['admission_length']);
    $sheet->setCellValue("F$row", $data['admission_head_circumference']);
    $row++;
}

// 6. Export to XLSX
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="exported_data.xls"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

  
