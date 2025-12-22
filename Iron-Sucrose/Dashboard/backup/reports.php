<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php $titleText='Reports Iron Sucrose'; ?>
<?php include('includes/headers.php'); ?>
<style>
    @media print {
      body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .no-print {
        display: none;
      }
      table, th, td {
        border: 1px solid black !important;
      }
    }

    .summary-box {
      background-color: #f8f9fa;
      padding: 1rem;
      margin-bottom: 1rem;
	  border: #b7c3cf solid 1px;
    }

    .table th, .table td {
      vertical-align: middle;
      text-align: center;
    }
  </style>
  <?php 
   $from = $_GET['from_date'] ?? date('Y-m-d',strtotime('2024-11-27'));
	$to = $_GET['to_date'] ?? date('Y-m-d');
    $qry='';
	if ($from && $to) {
		// Query with BETWEEN
		$qry = " and created_on BETWEEN '$from' AND '$to'";
		$qry1 = " and visit_date BETWEEN '$from' AND '$to'";
		$qry2 = " and updated_date BETWEEN '$from' AND '$to'";
	}
  ?>
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Reports</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                                        <li class="breadcrumb-item active">Weekly</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
					<div class="row no-print">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
								<form class="row gy-2 gx-3 align-items-end" action="reports.php" method="GET">
							<div class="col-md-4">
							  <label for="from_date" class="form-label">From Date</label>
							  <input type="date" class="form-control" id="from_date" name="from_date" required value="<?=$from?>">
							</div>
							<div class="col-md-4">
							  <label for="to_date" class="form-label">To Date</label>
							  <input type="date" class="form-control" id="to_date" name="to_date" required value="<?=$to?>">
							</div>
							<div class="col-md-4 d-flex align-items-end">
							  <button type="submit" class="btn btn-primary w-100">🔍 Search</button>
							</div>
						  </form>
							    </div>
						    </div>
						</div>
					</div>
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
									<div class="d-flex justify-content-between align-items-center">
									 <h2>Iron Sucrose Follow-up Program</h2>
									<p>Report Period [<?=date('d-M-Y',strtotime($from))?> To: <?=date('d-M-Y',strtotime($to))?>]</p>
									  
									<button onclick="window.print()" class="btn btn-success no-print">🖨️ Print Report</button>
									  </div>
							    </div>
					       </div>
					</div>
				    </div>
					 <div class="card">
					  <div class="card-body">
					  <div class="row text-center m-1">
					   
						<div class="col-md-4 summary-box">
						  <h5>Total Registration</h5>
						  <p><?php $sqlRegistration=mysqli_query($conn,"select count(*) as total from pw_iron_registration where id>0 $qry");
                          $dataRegistration=mysqli_fetch_object($sqlRegistration);?>
						  <strong style="font-size:18px;"><?=$dataRegistration->total?> </strong></p>
						</div>
						<div class="col-md-4 summary-box">
						  <h5>Total Follow-up Visit</h5>
						  <p><?php $sqlVisit=mysqli_query($conn,"select count(*) as total from pw_iron_visit where visit_status=1 $qry1");
                          $dataVisit=mysqli_fetch_object($sqlVisit);?>
						  <strong style="font-size:18px;"><?=$dataVisit->total?> </strong>
						  <br> ( <?php $sqlvisitCount=mysqli_query($conn,"select followup,count(*) as total from pw_iron_visit where visit_status=1 $qry1 group by followup");
						  $co=1;
                          while($datavisitCount=mysqli_fetch_object($sqlvisitCount)) { 
                           if($co>1)
						   {
							   echo "|";
						   }
						  ?> 
						  
						  <?=$datavisitCount->followup?>: <strong><?=$datavisitCount->total?></strong>   
						  <?php $co++; } ?>)
						  </p>
						</div>
						<div class="col-md-4 summary-box">
						  <h5>Total IVRS Calls</h5>
						  <p><?php $sqlIvrCalls=mysqli_query($conn,"select count(*) as total from pw_iron_visit where ivr_scheduled_status>0 $qry1");
                          $dataIvrCalls=mysqli_fetch_object($sqlIvrCalls);?>
						  <strong style="font-size:18px;"><?=$dataIvrCalls->total?> </strong><br>(Connected: <?php $sqlIvrCallsConn=mysqli_query($conn,"select count(*) as total from pw_iron_visit where ivr_scheduled_status=2 $qry1");
                          $dataIvrCallsConn=mysqli_fetch_object($sqlIvrCallsConn);?> <?=$dataIvrCallsConn->total?> | Missed: <?php $sqlIvrCallsConn=mysqli_query($conn,"select count(*) as total from pw_iron_visit where ivr_scheduled_status=1 $qry1");
                          $dataIvrCallsConn=mysqli_fetch_object($sqlIvrCallsConn);?> <?=$dataIvrCallsConn->total?>)</p>
						</div>
					  </div>
					  <div class="row text-center m-1">
						<div class="col-md-6 summary-box">
						  <h5>Facilities Participated</h5>
						  <p><?php $sqlFacilityParticipations=mysqli_query($conn,"select count(distinct(facility_id)) as total from pw_iron_visit where visit_status=1 $qry1");
                          $dataFacilityParticipations=mysqli_fetch_object($sqlFacilityParticipations);?>
						  <strong style="font-size:18px;"> <?=$dataFacilityParticipations->total?></strong></p>
						</div>
						<div class="col-md-6 summary-box">
						  <h5>Facilitators Participation</h5>
						   <p><?php $sqlFacilitatorParticipations=mysqli_query($conn,"select count(distinct(anm_mobile)) as total from pw_iron_visit where visit_status=1 $qry1");
                          $dataFacilitatorParticipations=mysqli_fetch_object($sqlFacilitatorParticipations);?>
						  <strong style="font-size:18px;"> <?=$dataFacilitatorParticipations->total?></strong></p>
						</div>
					  </div>
                     
                      </div>
					  </div>
					  
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
					  <h4 class="mt-1">Facility-Wise Reporting</h4>
					  </div>
					  <div class="card-body">
					 
					  <table class="table table-bordered table-sm text-center">
    <thead class="table-success text-dark">
        <tr>
            <th colspan="12">Iron Sucrose Follow-up status report through WhatsApp based Tele-calling</th>
        </tr>
        <tr class="table-warning">
            <th>S.No</th>
            <th>Facility Name</th>
            <th>No. of Facilitators Participation</th>
            <th>Total Registration</th>
            <th>Total IVRS Calls</th>
            <th>Connected</th>
            <th>Missed</th>
            <th>Total Visit</th>
            <th>1st dose</th>
            <th>2nd dose</th>
            <th>3rd dose</th>
            <th>4th dose</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $sqlFacilities = mysqli_query($conn, "SELECT DISTINCT facility_id FROM pw_iron_registration WHERE id > 0 $qry ORDER BY facility_id");
        while ($facility = mysqli_fetch_object($sqlFacilities)) {
            $facility_id = $facility->facility_id;

            // Facilitator count
            $sqlFacilitators = mysqli_query($conn, "SELECT COUNT(DISTINCT anm_mobile) as total FROM pw_iron_visit WHERE facility_id='$facility_id' AND visit_status=1 $qry1");
            $facilitators = mysqli_fetch_object($sqlFacilitators)->total;

            // Total Registration
            $sqlReg = mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_registration WHERE facility_id='$facility_id' $qry");
            $totalReg = mysqli_fetch_object($sqlReg)->total;

            // IVRS Calls
            $sqlIVRS = mysqli_query($conn, "SELECT 
                SUM(ivr_scheduled_status > 0) as total, 
                SUM(ivr_scheduled_status = 2) as connected, 
                SUM(ivr_scheduled_status = 1) as missed 
                FROM pw_iron_visit WHERE facility_id='$facility_id' $qry1");
            $ivrs = mysqli_fetch_object($sqlIVRS);

            // Follow-up visits
            $sqlFollowup = mysqli_query($conn, "SELECT 
                COUNT(*) as total, 
                SUM(followup='खुराक-1') as dose1, 
                SUM(followup='खुराक-2') as dose2, 
                SUM(followup='खुराक-3') as dose3, 
                SUM(followup='खुराक-4') as dose4 
                FROM pw_iron_visit 
                WHERE facility_id='$facility_id' AND visit_status=1 $qry1");
            $followup = mysqli_fetch_object($sqlFollowup);
        ?>
            <tr>
                <td><?= $i++ ?></td>
                <td style="text-align:left"><?= $facility_id ?></td>
                <td><?= $facilitators ?></td>
                <td><?= $totalReg ?></td>
                <td><?= $ivrs->total ?></td>
                <td><?= $ivrs->connected ?></td>
                <td><?= $ivrs->missed ?></td>
                <td><?= $followup->total ?></td>
                <td><?= $followup->dose1 ?></td>
                <td><?= $followup->dose2 ?></td>
                <td><?= $followup->dose3 ?></td>
                <td><?= $followup->dose4 ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

					  </div>
					</div>
				</div>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
<?php include('includes/footers.php'); ?>