<?php include_once('includes/config.php'); ?>
<?php define("title", "Summary-status"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php
$totalMonthss = 12;

if(isset($_GET['fromDate']) && $_GET['toDate'] != '') {
    $start_date2 = $_GET['fromDate'];
    $end_date2 = $_GET['toDate'];

    $start2 = new DateTime($start_date2);
    $start2->modify('first day of this month');

    $end2 = new DateTime($end_date2);
    $end2->modify('last day of this month');

    $start_date2 = $start2->format('Y-m-d');
    $end_date2 = $end2->format('Y-m-d');

    $diff = $start2->diff($end2);
    $months = ($diff->y * 12) + $diff->m + 1;
    $totalMonthss = $months;
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
            $output .= "<th style='background-color: #edc241; color: #000000!important; data-f-bold='true' data-fill-color='edc241' data-b-a-s='thin' data-b-a-c='000000'>" . $monthYear . "</th>";
        }
        $output .= "<th data-f-bold='true' data-fill-color='a0e3a0' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #a0e3a0; font-weight: bold; color: #000000!important'>Total</th>";
        break; 
    }

    return $output;
}

// participate mother
function monthly_summary_row($conn, $indicator, $start_date, $end_date)
{
    $output = "";
    $grand_total = 0;

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $end->modify('first day of next month');
    $interval = new DateInterval('P1M');
    $period = new DatePeriod($start, $interval, $end);

    foreach ($period as $dt) {
        $reporting_period = $dt->format('Y-m'); 
        $reporting_date = $reporting_period;

        $query = "SELECT SUM($indicator) as total FROM maa_monthly_report 
                  WHERE reporting_period like'$reporting_date%' 
                  AND district IN (SELECT district_code FROM districts WHERE state_code = 10)";
        $result = $conn->query($query);
        $value = 0;

        if ($result && $row = $result->fetch_assoc()) {
            $value = $row['total'] ?? 0;
        }

        $grand_total += $value;
        $output .= "<td style='background-color: #d1f0d1; font-weight: bold; color: #000000!important; data-f-bold='true' data-fill-color='d1f0d1' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #a0e3a0; font-weight: bold;''>$value</td>";
    }

    $output .= "<td style='background-color: #a0e3a0; font-weight: bold; color: #000000!important; data-f-bold='true' data-fill-color='a0e3a0' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #a0e3a0; font-weight: bold;''>$grand_total</td>";
    return $output;
}

// find parcentage
function monthly_percentage_row($conn, $numerator, $denominator, $start_date, $end_date)
{
    $output = "";
    $total_numerator = 0;
    $total_denominator = 0;

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $end->modify('first day of next month');
    $interval = new DateInterval('P1M');
    $period = new DatePeriod($start, $interval, $end);

    foreach ($period as $dt) {
        $reporting_period = $dt->format('Y-m');
        $reporting_date = $reporting_period;

        $query_num = "SELECT SUM($numerator) as total FROM maa_monthly_report WHERE reporting_period like'$reporting_date%' AND district IN (SELECT district_code FROM districts WHERE state_code = 10)";
        $result_num = $conn->query($query_num);
        $num = 0;
        if ($result_num && $row = $result_num->fetch_assoc()) {
            $num = isset($row['total']) ? $row['total'] : 0;
        }

        $query_den = "SELECT SUM($denominator) as total FROM maa_monthly_report WHERE reporting_period like'$reporting_date%' AND district IN (SELECT district_code FROM districts WHERE state_code = 10)";
        $result_den = $conn->query($query_den);
        $den = 0;
        if ($result_den && $row = $result_den->fetch_assoc()) {
            $den = isset($row['total']) ? $row['total'] : 0;
        }

        $percentage = ($den > 0) ? round(($num / $den) * 100) : 0;

        $total_numerator += $num;
        $total_denominator += $den;

        $output .= "<td style='background-color: #d1f0d1; font-weight: bold; color: #000000!important; data-f-bold='true' data-fill-color='d1f0d1' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #409f40; font-weight: bold;''>$percentage</td>";
    }

    $grand_percentage = ($total_denominator > 0) ? round(($total_numerator / $total_denominator) * 100) : 0;
    $output .= "<td style='background-color: #a0e3a0; font-weight: bold; color: #000000!important; data-f-bold='true' data-fill-color='a0e3a0' data-b-a-s='thin' data-b-a-c='000000' style='background-color: #409f40; font-weight: bold;''>$grand_percentage</td>";

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
</style>


<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12" style="background-color:rgb(238, 142, 7);">
                <nav aria-label="breadcrumb ">
                    <ol class="breadcrumb mb-0 py-2">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
                        <li class="breadcrumb-item"><i class="fa fa-calandar"></i>Summary Report</li>
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
                <a href="summary_report.php" type="reset" class="btn btn-danger w-100">Reset</a>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-success width-md waves-effect waves-light form-control" id="button-excel"><i class="fas fa-file-excel"></i> Export to excel</button>
            </div>
        </form>
        <section class="panel p-1">
            <div class="table-responsive">
                <table id="simpleTable1">
                    <!-- first header -->

                    <tr style="position: sticky; top: 0; background: #2c1ea5; z-index: 1; height: 40px;">
                        <td class="header1" style="font-size: 20px; color:#FFFFFF!important;" colspan="<?= $totalMonthss+3 ?>" data-f-sz="20" data-a-h="center" data-fill-color="2c1ea5" data-f-color="FFFFFF" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true"><b>Number of PW & LW participated in mothers meeting during</b></td>
                    </tr>
                    <!-- Second Header -->
                    <tr>
                    <th data-f-bold='true' data-fill-color='d1f0d1' data-b-a-s='thin' data-b-a-c='FFF9C4' style="font-size: 20px; color: #000000!important;">Sl. No</th>
                    <th data-f-bold='true' data-fill-color='d1f0d1' data-b-a-s='thin' data-b-a-c='FFF9C4' style="font-size: 20px; color: #000000!important;">Activity</th>
                        <?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo header_db($conn, '01-01-2024', '01-12-2024');
                        } else {
                            echo header_db($conn, $_GET['fromDate'], $_GET['toDate']);
                        }
                        ?>
                    </tr>

                    <!-- Sample Data Rows -->
                    <tr style="background-color: #FFF9C4;">
                        <td data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #FFF9C4;">1</td>
                        <td style="text-align: left;" data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000'>Total No. of Targeted Pregnant & Lactating mothers per quarter (a+b)</td>
                        <?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo monthly_summary_row($conn,'total_mothers','01-01-2024', '01-12-2024'); 
                        } else {
                            echo monthly_summary_row($conn,'total_mothers',$_GET['fromDate'], $_GET['toDate']); 
                        }
                        ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #FFF9C4;">2</td>
                        <td style="text-align: left;" data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000' >No. of Pregnant & Lactating mothers participated in mother's meeting during this  Month </td>
                        <?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo monthly_summary_row($conn,'mothers_participated_meeting','01-01-2024', '01-12-2024'); 
                        } else {
                            echo monthly_summary_row($conn,'mothers_participated_meeting',$_GET['fromDate'], $_GET['toDate']); 
                        }
                        ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #FFF9C4;">3</td>
                        <td style="text-align: left;" data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000'>% of Pregnant & Lactating mothers participated in mother's meeting during this period</td>
                        <?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo monthly_percentage_row($conn, 'mothers_participated_meeting', 'total_mothers','01-01-2024', '01-12-2024');
                        } else {
                            echo monthly_percentage_row($conn, 'mothers_participated_meeting', 'total_mothers',$_GET['fromDate'], $_GET['toDate']);
                        }
                        ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #FFF9C4;">4</td>
                        <td style="text-align: left;" data-f-bold='true' data-fill-color='FFF9C4' data-b-a-s='thin' data-b-a-c='000000'>Number of mothers meeting held during this  Month </td>
                        <?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo monthly_summary_row($conn,'mothers_meetings_held','01-01-2024', '01-12-2024'); 
                        } else {
                            echo monthly_summary_row($conn,'mothers_meetings_held',$_GET['fromDate'], $_GET['toDate']);
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
            name: "Summary-status.xlsx", // Set your desired file name here
            sheet: {
                name: "Summary-status" // Set the sheet name here
            }
        });
    });
</SCRIPT>
<!-- ************************************************************************** -->