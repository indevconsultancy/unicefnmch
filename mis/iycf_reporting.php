<?php include_once('includes/config.php'); ?>
<?php define("title", "IYCF Reporting"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php

// month and summary row
function header_db($conn, $start_date, $end_date)
{
    $query = "SELECT * FROM districts where state_code=10";
    $result = $conn->query($query);
    $output = "";

    $start = new DateTime($start_date);
    $start->modify('first day of this month');

    $end = new DateTime($end_date);
    $end->modify('first day of next month');

    $interval = new DateInterval('P1M');
    $period = new DatePeriod($start, $interval, $end);

    while ($row = $result->fetch_assoc()) {
        foreach ($period as $dt) {
            $monthYear = $dt->format("M-y");
            $output .= "<th style='background-color: rgb(237, 194, 65);'>" . $monthYear . "</th>";
        }
        $output .= "<th style='background-color: lightgreen;'>Summary</th>";
    }
    return $output;
}


function getreportdata($conn, $indicator, $reporting_period, $district)
{
    $reporting_period_full = $reporting_period;

    // Prepare the SQL query
    $query = "SELECT SUM($indicator) as tot FROM iycf_monthly_reporting 
              WHERE reporting_period = '$reporting_period_full' 
              AND district = '$district'";

    $result = $conn->query($query);

    // Ensure query ran successfully
    if ($result && $row = $result->fetch_assoc()) {
        return $row['tot'] ?? 0;
    }
    return 0;
}

function row_data($conn, $indicator, $start_month, $end_month)
{
    $outputss = '';

    $query = "SELECT * FROM districts WHERE state_code = 10 ORDER BY district_name ASC";
    $result = $conn->query($query);

    $start = new DateTime($start_month);
    $start->modify('first day of this month');
    $end = new DateTime($end_month);
    $end->modify('first day of this month');

    $months = [];
    while ($start <= $end) {
        $months[] = $start->format('Y-m');
        $start->modify('+1 month');
    }

    while ($row = $result->fetch_assoc()) {
        $district = $row['district_code'];
        $finaltotal = 0;

        foreach ($months as $reporting_month) {
            $totalVal = getreportdata($conn, $indicator, $reporting_month, $district);
            $outputss .= "<td>" . ($totalVal ?? 0) . "</td>";
            $finaltotal += $totalVal;
        }

        $outputss .= "<td style='background-color: lightgreen;'>" . $finaltotal . "</td>";
    }

    return $outputss;
}

?>



<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<title>IYCF Reporting</title>


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
        max-height: 100vh;
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
                        <li class="breadcrumb-item"><i class="fa fa-calandar"></i>IYCF Report</li>
                    </ol>

                </nav>
            </div>
        </div>
        <form action="?" method="get" class="row g-3 justify-content-center m-3">
            <div class="col-md-2">
                <input type="date" class="form-control" id="fromDate" name="fromDate" onchange="setmindate();" required>
            </div>

            <div class="col-auto d-flex align-items-center px-2">
                <span><b>to</b></span>
            </div>

            <div class="col-md-2">
                <input type="date" class="form-control" id="toDate" name="toDate" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" name="seachhh" class="btn btn-primary w-100">Search</button>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <a href="iycf_reporting.php" type="reset" class="btn btn-danger w-100">Reset</a>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-success width-md waves-effect waves-light form-control" id="button-excel"><i class="fas fa-file-excel"></i> Export to excel</button>
            </div>
        </form>
        <section class="panel p-1">
            <div class="table-responsive">
                <table id="simpleTable1">
                    <!-- first header -->

                    <tr style="position: sticky; top: 0; background: #ffe600; z-index: 1;">
                        <td class="header1" style="font-size: 20px; text-align: left; padding-left: 4%;" colspan="154" data-f-sz="20" data-a-h="center" data-fill-color="F2F2F2" data-f-color="C0504D" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true">IYCF Program Reporting</td>
                    </tr>
                    <tr>
                        <th rowspan="2">Sl. No</th>
                        <th rowspan="1" style="width: 5%;">Name of District</th>

                        <?php $query =  "SELECT * FROM districts where state_code=10 order by district_name asc";

                        $result = $conn->query($query);

                        // find diffrence between months
                        $difrence = 4;
                        if ((isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            $start_dif = new DateTime($_GET['fromDate']);
                            $end_dif = new DateTime($_GET['toDate']);

                            $start_dif->modify('date koi bhi ho 1 date assign kar denge');
                            $end_dif->modify('date koi bhi ho 1 date assign kar denge');


                            $interval = $start_dif->diff($end_dif);
                            $difrence = ($interval->y * 12) + $interval->m + 2;
                        }

                        while ($row = $result->fetch_assoc()) {
                            echo "<td colspan='$difrence' style='background-color: lightblue; font-weight: bold;'>" . $row['district_name'] . "</td>";
                        }
                        ?>
                    </tr>
                    <!-- Second Header -->
                    <tr>
                        <th>Months</th>
                        <?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo header_db($conn, '01-10-2024', '01-12-2024');
                        } else {
                            echo header_db($conn, $_GET['fromDate'], $_GET['toDate']);
                        }
                        ?>
                    </tr>

                    <!-- Sample Data Rows -->
                    <tr style="background-color: #FFF9C4;">
                        <td>1</td>
                        <td>Total number of ASHAs</td>
                        <?php
                        if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'total_nos_asha', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'total_nos_asha', $_GET['fromDate'], $_GET['toDate']);
                        }
                        ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>2</td>
                        <td>Number of ASHAs oriented on IYCF during block meetings</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'ashas_oriented_iycf', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'ashas_oriented_iycf', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>3</td>
                        <td>Number of ASHAs having infokit/ flipchart</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'ashas_with_infokit', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'ashas_with_infokit', $_GET['fromDate'], $_GET['toDate']);
                        }
                        ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>4</td>
                        <td>Number of ASHAs conducted Mothers' meetings</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'ashas_conducted_meetings', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'ashas_conducted_meetings', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>5</td>
                        <td>Number of ASHAs received incentive for meetings</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'ashas_received_incentive', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'ashas_received_incentive', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td rowspan="3">6</td>
                        <td>Total number of Pregnant & lactating mothers (according to line list) per month (a+b)</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'total_mothers', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'total_mothers', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">

                        <td>a) Pregnant</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'pregnant_mothers', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'pregnant_mothers', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">

                        <td>b) Lactating</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'lactating_mothers', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'lactating_mothers', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>7</td>
                        <td>Number of pregnant & lactationg mothers partcipated in mother's meeting in the month</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, '	mothers_participated_meeting', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, '	mothers_participated_meeting', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>8</td>
                        <td>Number of IYCF master trainers in the district</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'iycf_master_trainers', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'iycf_master_trainers', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>9</td>
                        <td>Number of mothers meeting held in the month</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'mothers_meetings_held', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'mothers_meetings_held', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td rowspan="2">10</td>
                        <td>Number of staffs trained in one day sensitization program in reporting month</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'staff_sensitized', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'staff_sensitized', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>Number of delivery points for which one day sensitization covered in reporting month</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'delivery_points_sensitized', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'delivery_points_sensitized', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
                    </tr>

                    <tr style="background-color: #FFF9C4;">
                        <td>11</td>
                        <td>Number of facilities given MAA Award</td>
                        <?php if (!(isset($_GET['fromDate']) && isset($_GET['toDate']))) {
                            echo row_data($conn, 'facilities_given_maa_award', '01-10-2024', '01-12-2024');
                        } else {
                            echo row_data($conn, 'facilities_given_maa_award', $_GET['fromDate'], $_GET['toDate']);
                        } ?>
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
            name: "iycf_monthly_reporting.xlsx", // Set your desired file name here
            sheet: {
                name: "IYCF Monthly Report" // Set the sheet name here
            }
        });
    });
</SCRIPT>
<!-- ************************************************************************** -->