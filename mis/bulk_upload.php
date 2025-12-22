<?php include_once('includes/config.php'); ?>
<?php define("title","Import Data | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php

	$surveyid = $_REQUEST['survey_id'];
	if(isset($_POST['import_data'])){
		 $survey_id = $surveyid;
		 $language_id = $_REQUEST['language_id'];
		
		 $survey_name= getone($conn,'survey','survey_name','id',$survey_id);
		
		$filename = $_FILES['import_file']['name'];
		$tmp_name = $_FILES['import_file']['tmp_name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$file_name = "".$unique_id.".".$ext;
			
		/** Include path **/
		
		if ($survey_id!=""){
			
			$getQuestions = mysqli_query($conn,"SELECT question_id, field_name, input_field_type FROM questions_language WHERE survey_id='".$survey_id."' AND language_id='".$language_id."' AND input_field_type!='note'");
			while($question = mysqli_fetch_object($getQuestions)){
				$quesArr[$question->field_name] = $question->question_id;
				$quesTypes[$question->field_name] = $question->input_field_type;
			}
		
			//echo "<pre>";
			//print_r($quesArr);
			set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
			include 'PHPExcel/Classes/PHPExcel/IOFactory.php';
			$file = $tmp_name;//'/media/sf_E_DRIVE/om/newData.xlsx';
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$CurrentWorkSheetIndex = 0;
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				// echo 'WorkSheet' . $CurrentWorkSheetIndex++ . "\n";
				// echo 'Worksheet number - ', $objPHPExcel->getIndex($worksheet), PHP_EOL;
				$sheetSno = $objPHPExcel->getIndex($worksheet);//, PHP_EOL;
				$highestRow = $worksheet->getHighestDataRow();
				$highestColumn = $worksheet->getHighestDataColumn();
				$headings = $worksheet->rangeToArray('A1:' . $highestColumn . 1, NULL,TRUE, FALSE);
				//echo "<pre>";

				if($sheetSno=='0'){
					
					//$surveydataArr["survey_data"]=[];
					for ($row = 2; $row <= $highestRow; $row++) {
						$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
						$rowData[0] = array_combine($headings[0], $rowData[0]);
						
						foreach ($rowData as $key => $rowvalue) {
							
							array_pop($rowvalue);
							$digits = 10;
							$unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
							
							$sdataArrf["user_id"]=$_SESSION['user_id'];
							$sdataArrf["survey_name"]=$survey_name;
							$sdataArrf["selected_language"]=$language_id;
							$sdataArrf["survey_name_id"]=$survey_id;
							$sdataArrf["survey_id"]=$unique_id;
							$sdataArrf["app_version"]="web";
							$sdataArrf["survey_status"]="1";
							$sdataArrf["cluster_no"]="1234";
							foreach($rowvalue as $rowvaluekey=>$rowvalueVal){
								$sdata["field_name"] = $rowvaluekey;
								$sdata["question_id"] = $quesArr[$rowvaluekey];
								$qType = $quesTypes[$rowvaluekey];
								$option_id="";
								$option_value = "";
								if($qType=="select_one" || $qType=="select_multiple"){
									$option_id=$rowvalueVal;
								}else{
									$option_value=$rowvalueVal;
								}
								$sdata["option_id"] = $option_id;
								$sdata["option_value"] = $option_value;
								$sdataArr[]=$sdata;
								$sdata = array();
							}
							$sdataArrf["survey_data"]=$sdataArr;
							$sdataArr=array();
							 // print_r($sdataArrf["survey_data"]);
							   json_encode($sdataArrf);
							
							//$url = 'https://unicef.indevconsultancy.in/mis/api/survey_data_upload_v2.php';
							$url = 'https://unicef.indevconsultancy.in/mis/api/survey_data_upload_v2_web.php';
							//$data = array("first_name" => "First name","last_name" => "last name","email"=>"email@gmail.com","addresses" => array ("address1" => "some address" ,"city" => "city","country" => "CA", "first_name" =>  "Mother","last_name" =>  "Lastnameson","phone" => "555-1212", "province" => "ON", "zip" => "123 ABC" ) );

							$postdata = json_encode($sdataArrf);

							$ch = curl_init($url); 
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
							curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
							$result = curl_exec($ch);
							curl_close($ch);
							// print_r ($postdata);
							 // print_r ($result);
							 // die();
							if($result){
								$_SESSION['status'] = "Your data has been successfully uploaded";
								$_SESSION['status_code'] = "success";
								echo "<script>window.location.href='survey-list.php'</script>";
							}else{
								$_SESSION['status_error'] = "Something went wrong!!";
								$_SESSION['status_error_code'] = "error";
							}
						}
					}
				}
			}
		}
	}
    ?>

<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
				
                <ol class="breadcrumb">
                    <li><i class="icon_documents_alt"></i>Form </li>
                    <li><i class="fa fa-plus"></i>Import Data </li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        
        <div class="row">
            <div class="col-lg-12">
            	<section class="panel">
                    <div class="panel-body">
                    	<div class="row">
                        	<div class="col-lg-12" style="min-height:420px;">
                            	<header class="panel-heading">Import Data 
								</header>
                                <section class="panel">
                                    <div class="panel-body">
                                        <div class="form">
                                            <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                                              
												<div class="mb-3 row">
													<label for="email" class="col-lg-2 col-form-label text-end">Select Language: <span class="text-danger">*</span></label>
													<div class="col-lg-10">
													  <select class="form-control" name="language_id" id="language_id" required>
                                                            <option value="">Select Language</option>
                                                            <?php
                                                                $getSurveyname = mysqli_query($conn,"SELECT languages.language_id,languages.language_name FROM questions_language inner join languages on languages.language_id=questions_language.language_id where survey_id='".$_REQUEST['survey_id']."' group by questions_language.language_id");
                                                                while($surveyid = mysqli_fetch_array($getSurveyname)){ ?>
                                                                    <option value="<?php echo $surveyid['language_id'];?>"><?php echo $surveyid['language_name'];?></option>
                                                                <?php	
                                                                }
                                                            ?>
                                                        </select>
													</div>
												  </div>
												<div class="mb-3 row">
													<label for="email" class="col-lg-2 col-form-label text-end">Select File: <span class="text-danger">*</span></label>
													<div class="col-lg-10">
													  <input class="form-control" required id="fileUpload" name="import_file" accept=".xlsx,.xls," type="file" />
													  <span id="file_error" style="color:red;"></span>
													</div>
												  </div>
													<div class="col-lg-10 offset-lg-2 text-end">
													  <button class="btn btn-primary" type="submit" id="import_data" name="import_data">Submit</button>
														<a href="import_formate.php?survey_id=<?=$_REQUEST['survey_id'];?>" class="btn btn-primary float-end ms-2" id="downloadTemplate"><i class="fa fa-download"></i> Download Template</a>
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
<?php if(isset($_SESSION['status_error']) && $_SESSION['status_error']!=''){ ?>
<script>
	swal.fire({
		title: "<?php echo $_SESSION['status_error'];?>",
		icon:"<?php echo $_SESSION['status_error_code']; ?>",
		confirmButtonColor: '#449A97',
		confirmButtonText: 'Ok'
	});
</script>
<?php unset($_SESSION['status_error']);}  ?>
<script>
 //Bulk file upload
  $("body").on("click", "#btnUpload", function () {
        var allowedFiles = [".xlsx", ".xls"];
        var fileUpload = $("#fileUpload");
        var file_error = $("#file_error");
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(fileUpload.val().toLowerCase())) {
            file_error.html("Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
            return false;
        }
        file_error.html('');
        return true;
    });
	
 </script>
