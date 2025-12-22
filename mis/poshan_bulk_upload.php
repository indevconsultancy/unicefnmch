<?php include_once('includes/config.php'); ?>
<?php define("title", "Upload Poshan Tracker | AKALAN"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
function getIdBySearch($conn,$tableName,$getField,$qryFeild,$qryValue)
{
	$ids=0;
//echo  "select $field from $tablename where $qryfeild='".$value."'";
$sn=mysqli_query($conn,"select $getField as total from $tableName where $qryFeild='".$qryValue."'")or die(mysqli_error());
$rowcc=mysqli_num_rows($sn);
if($rowcc>0)
{
$dn=mysqli_fetch_object($sn);
$ids=$dn->total;
}
return ($ids);
}

if (isset($_POST['submit'])) {
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            fgetcsv($csvFile);
            $err = 0;
            $errmsg = '';
            $rowno = 1;
			$districtID=$_POST['district'];
			$error='';
			$genderArray=['0','M','F'];
			$beneficiary_typeArray=['0','children_0m_6m','children_3y_6y','children_6m_3y'];
			$stuntedArray=['0','normal','moderately stunted','severly stunted'];
			$wastedArray=['0','normal','mam','sam','overweight','obese','Reference Data Not Found','reference_error','NA'];
			$underweightArray=['0','normal','moderately underweight','severly underweight'];
			$i=0;
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                $temp = $line;
                $searches = array("'", "/");
                $replacements = array('&#39;', "-");
                $line = str_replace($searches, $replacements, $temp);
                $project_name=$line[0];
				$sector_name=$line[1];
				$awc_name=$line[2];
				$awc_code=$line[3];
				$beneficiary_name=$line[4];
				$beneficiary_type=$line[5];
				$mother_name=$line[6];
				$dob=date('Y-m-d',strtotime($line[7]));
				$gender=$line[8];
				$weight=$line[9];
				$height=$line[10];
				$capture_date=date('Y-m-d',strtotime($line[11]));
				$stunted=$line[12];
				$wasted=$line[13];
				$underweight=$line[14];
								
				$uniqueID=$project_name."/".$beneficiary_name."/".$mother_name."/".$dob."/".$gender;
				
				$childID=getIdBySearch($conn,'child_masters','child_id','unique_id',$uniqueID);
                if($childID==0)
				{
					$createChild = "INSERT INTO child_masters set district = '" . $districtID . "', project_name = '" . $project_name . "',beneficiary_name = '" . $beneficiary_name . "',mother_name = '" . $mother_name . "',dob = '" . $dob . "',gender = '" . $gender . "', unique_id = '".$uniqueID."'";
                    $SQLChild = mysqli_query($conn, $createChild);
                    $childID = mysqli_insert_id($conn);
				}
				$AwcID=getIdBySearch($conn,'awc','id','code',$awc_code);
                if($AwcID==0)
				{
					$createAwc = "INSERT INTO awc set name = '" . $awc_name . "', code = '" . $awc_code . "',district = '" . $districtID . "',project = '" . $project_name . "',sector = '" . $sector_name . "'";
                    $SQLAwc = mysqli_query($conn, $createAwc);
                    $AwcID = mysqli_insert_id($conn);
				}
				$genderID=array_search($gender, $genderArray);
				$beneficiary_typeID=array_search($beneficiary_type, $beneficiary_typeArray);
				$stuntedID=array_search($stunted, $stuntedArray);
				$wastedID=array_search($wasted, $wastedArray);
				$underWeightID=array_search($underweight, $underweightArray);
				$month=date('m',strtotime($capture_date));
				$year=date('Y',strtotime($capture_date));
				if($childID>0)
				{
				$createRecord = "INSERT into child_monitorings SET awc_id='".$AwcID."',beneficiary_type='".$beneficiary_typeID."',gender='".$genderID."',weight='".$weight."',height='".$height."',capture_date='".$capture_date."',months='".$month."',years='".$year."',stunted='".$stuntedID."',wasted='".$wastedID."',underweight='".$underWeightID."',unique_id='".$uniqueID."',child_id='".$childID."',district='".$districtID."'";
				mysqli_query($conn,$createRecord);
				}
				else {
					$error.="Record not inserted for Row".$i."<br/>";
				}
				$i++;
			}
		}
	}
	if ($errmsg != '') {
                $message = 'Somthing Wrong';
                echo "<script>alert('$message');
				window.location.href='poshan_tracker.php?message=".$errmsg."';
				</script>";
            }
    else {
		 $message = 'Data Inserted Sucessfuly';
		 $rowsd=$i.' Rows inserted';
                echo "<script>alert('$message');
				window.location.href='poshan_tracker.php?message=".$rowsd."';
				</script>";
	}
}

?>
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Poshan Tracker</li>
                        <li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>Upload Data</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12" style="min-height:420px;">
                                <header class="panel-heading">Upload Poshan Tracker Data
                                </header>
                                <section class="panel">
                                    <div class="panel-body">
                                        <div class="form">
                                            <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
											<div class="row mb-2">
												<div class="col-4">
													<label for="district" class="form-label">Select District</label>
													<select class="form-select" id="district" name="district" required="">
														<option value="">-- Select District --</option>
														<option value="Gaya">Gaya</option>
														<option value="Purnea">Purnea</option>
													</select>
												</div>
                                                <div class="col-6">
                                                    <label for="cname" class="form-label">Select File: </label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" required name="file" accept=".xlsx,.xls,.csv" type="file" />
                                                    </div>
                                                </div>
											</div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-offset-2 col-lg-12 text-end">
                                                        <button class="btn btn-secondary" type="submit" name="submit" id="import_data">Submit</button>
                                                        <a href="poshan-data-template.csv" class="btn btn-primary">Download Template</a>
                                                    </div>
                                                </div>
											
                                            </form>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
<?php if (isset($_SESSION['status_error']) && $_SESSION['status_error'] != '') { ?>
    <script>
        swal.fire({
            title: "<?php echo $_SESSION['status_error']; ?>",
            icon: "<?php echo $_SESSION['status_error_code']; ?>",
            confirmButtonColor: '#449A97',
            confirmButtonText: 'Ok'
        });
    </script>
<?php unset($_SESSION['status_error']);
}  ?>