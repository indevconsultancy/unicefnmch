<?php include_once('includes/config.php'); ?>
<?php define("title", "Reporting Status"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php
$totalMonths=12;
$rowArray = [];

if(isset($_GET['fromDate']) && $_GET['toDate']!='')
{
	$start_date1=$_GET['fromDate'];
	$end_date1=$_GET['toDate'];

    $start1 = new DateTime($start_date1);
    $start1->modify('first day of this month');

    $end1 = new DateTime($end_date1);
    $end1->modify('last day of this month');

    $start_date1 = $start1->format('Y-m-d');
    $end_date1 = $end1->format('Y-m-d');
    
    $diff = $start1->diff($end1);
    $months = ($diff->y * 12) + $diff->m + 1; 
    $totalMonths = $months;
	
}

// total mothers
function header_db($conn, $start_date, $end_date)
{
    $query = "SELECT * FROM districts WHERE state_code = 10";
    $result = $conn->query($query);
    $output = "";

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $end->modify('first day of next month');
    $interval = new DateInterval('P1M');
    $period = new DatePeriod($start, $interval, $end);

    while ($row = $result->fetch_assoc()) {
        foreach ($period as $dt) {
            $monthYear = $dt->format("M-y");
            $output .= "<th data-f-bold='true' data-fill-color='d1f0d1' data-b-a-s='thin' data-b-a-c='FFF9C4'>" . $monthYear . "</th>";
        }
        $output .= "<th data-f-bold='true' data-fill-color='62e9d7' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #62e9d7;''>Total</th>";
        break; 
    }

    return $output;
}

function footer_db($conn, $start_date, $end_date)
{
	global $rowArray;
    $query = "SELECT * FROM districts WHERE state_code = 10";
    $result = $conn->query($query);
    $output = "";

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $end->modify('first day of next month');
    $interval = new DateInterval('P1M');
    $period = new DatePeriod($start, $interval, $end);

    while ($row = $result->fetch_assoc()) {
		
        foreach ($period as $dt) {
            $monthYear = $dt->format("M-y");
		 $reporting_period = $dt->format('Y-m');
         $month_sum = isset($rowArray[$reporting_period]) ? array_sum($rowArray[$reporting_period]) : 0;
			 
			
            $output .= "<th data-f-bold='true' data-fill-color='62e9d7' data-b-a-s='thin' data-b-a-c='FFF9C4' style='background-color: #62e9d7;'>" .$month_sum . "</th>";
        }
        $output .= "<th data-f-bold='true' data-fill-color='62e9d7' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #62e9d7;'>".array_sum(array_map('array_sum', $rowArray))."</th>";
        break; 
    }

    return $output;
}

function monthly_summary_row($conn, $district_name, $start_date, $end_date)
{
	global $rowArray;
    $output = "";
    $ss = 0;

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $end->modify('first day of next month');
    $interval = new DateInterval('P1M');
    $period = new DatePeriod($start, $interval, $end);

    $district_code = null;
    $district_query = "SELECT district_code FROM districts WHERE district_name = '$district_name'";
    $district_result = mysqli_query($conn, $district_query);

    if ($district_row = mysqli_fetch_assoc($district_result)) {
        $district_code = $district_row['district_code'];
    }

    if ($district_code) {
        foreach ($period as $dt) {
            $reporting_period = $dt->format('Y-m'); 
            $reporting_periodstring='s'.$dt->format('Ym').$district_code;
            $query = "SELECT sum(total_nos_asha) as total FROM maa_monthly_report WHERE reporting_period like'$reporting_period%' AND district = '$district_code'";

            $result = mysqli_query($conn, $query);
			$datas=mysqli_fetch_object($result);
           
            if ($datas->total>0) {
				
				$rowArray[$reporting_period][]=1;
                $output .= "<td data-f-bold='true' data-fill-color='409f40' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #409f40; font-weight: bold;'>Yes</td>";
                $ss++;
            } else {
				$rowArray[$reporting_period][]=0;
                $output .= "<td data-f-bold='true' data-fill-color='d51829' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #d51829; font-weight: bold;'>No</td>";
            }
        }
    }
    $output .= "<td data-f-bold='true' data-fill-color='62e9d7' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #62e9d7; font-weight: bold;'> $ss</td>";
    return $output;
}

?>



<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<title>MAA Monthly Reporting</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .table-wrapper {
        max-width: 100%;

        overflow-x: auto;
    }

    table {
        border-collapse: collapse;
        width: max-content;
        /* Prevent table from squeezing */
        min-width: 100%;
        max-height: 60vh;
        height: 80vh;
    }

    th,
    td {
        border: 1px solid #999;
        padding: 6px 10px;
        text-align: center;
        white-space: nowrap;
        /* Prevent text wrapping */
    }

    th {
        background-color: rgb(180, 213, 224);
    }


    .highlight {
        background-color: #fce4d6;
    }
	table tr th {
    color: #000000 !important;
    border-bottom: 2px solid #dddddd !important;
}
table tr td {
    color: #000000 !important;
    vertical-align: baseline;
}
</style>


<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12" style="background-color:rgb(238, 142, 7);">
                <nav aria-label="breadcrumb ">
                    <ol class="breadcrumb mb-0 py-2">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
                        <li class="breadcrumb-item"><i class="fa fa-calandar"></i>Reporting Status</li>
                    </ol>

                </nav>
            </div>
        </div>
        <form action="?" method="get" class="row g-3 justify-content-center m-3">
            <div class="col-md-2">
                <input type="date" class="form-control" id="fromDate" name="fromDate" value="<?= isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : date('Y-m-01') ?>" onchange="setmindate();" required>
            </div>

            <div class="col-auto d-flex align-items-center px-2">
                <span><b>to</b></span>
            </div>

            <div class="col-md-2">
                <input type="date" class="form-control" id="toDate" name="toDate" value="<?= isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : date('Y-m-01') ?>" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" name="seachhh" class="btn btn-primary w-100">Search</button>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <a href="reporting_status.php" type="reset" class="btn btn-danger w-100">Reset</a>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-success width-md waves-effect waves-light form-control" id="button-excel"><i class="fas fa-file-excel"></i> Export to excel</button>
            </div>
        </form>
        <section class="panel p-1">
            <div class="table-responsive">
                <table id="simpleTable1">
                    <!-- first header -->
                  
				   
                    <tr style="position: sticky; top: 0; background:#2c1ea5; z-index: 1; height: 40px;">
                         <td class="header1" style="font-size: 20px; color:#FFFFFF!important;" colspan="<?=$totalMonths+3?>" data-f-sz="20" data-a-h="center" data-fill-color="2c1ea5" data-f-color="FFFFFF" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true"><b>Reporting Status</b></td>
                         <input type="hidden" value="<?=$totalMonths?>">
                    </tr>
                    
                    <!-- Second Header -->
                    <tr>
                        <th data-f-bold='true' data-fill-color='d1f0d1' data-b-a-s='thin' data-b-a-c='FFF9C4' style="text-align:left">Sl. No</th>
                        <th data-f-bold='true' data-fill-color='d1f0d1' data-b-a-s='thin' data-b-a-c='FFF9C4' style="text-align:left">Name of District</th>
                        <?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo header_db($conn, '01-01-2024', '01-12-2024');
                        } else {
                            echo header_db($conn, $_GET['fromDate'], $_GET['toDate']);
                        }
                        ?>
                    </tr>
                    <?php 
                        $query_num = "SELECT district_name FROM districts WHERE state_code = 10";
                        $result_num = mysqli_query($conn,$query_num);
						$im=1;
                        while($res = mysqli_fetch_assoc($result_num)){
                        ?>
                        <tr style="background-color: #FFF9C4;">
                            <td data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000'><?=$im?></td>
                            <td data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000' style="text-align:left"><?= $res['district_name']; ?></td>
                            <?php
                            if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                                echo monthly_summary_row($conn, $res['district_name'], '01-01-2024', '01-12-2024');
								
                            } else {
                                echo monthly_summary_row($conn, $res['district_name'], $_GET['fromDate'], $_GET['toDate']); 
                            }
                            ?>
                        </tr>
                        <?php $im++;} ?>
						<tr>
                        <th data-f-bold='true' style="background-color:#62e9d7" data-fill-color='62e9d7' data-b-a-s='thin' data-b-a-c='FFF9C4' colspan="2">Total</th>
                        
						<?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo footer_db($conn, '01-01-2024', '01-12-2024');
                        } else {
                            echo footer_db($conn, $_GET['fromDate'], $_GET['toDate']);
                        }
                        ?>
						</tr>
                    
                </table>
            </div>
        </section>
    </section>
</section>
<?php include_once('includes/footer.php'); ?>
<!-- ************************************************************************** -->
<script>
    function setmindate() {
        const from = document.getElementById('fromDate').value;
        document.getElementById('toDate').setAttribute('min', from);
    }
</script>

<!-- ************************************************************************** -->
<SCRIPT>
    let button = document.querySelector("#button-excel");
    button.addEventListener("click", (e) => {
        let table = document.querySelector("#simpleTable1");
        TableToExcel.convert(table, {
            name: "Reporting-Status Report.xlsx", // Set your desired file name here
            sheet: {
                name: "Reporting-Status-Report" // Set the sheet name here
            }
        });
    });
</SCRIPT>
<!-- ************************************************************************** -->