<?php include_once('includes/config.php'); ?>
<?php define("title","Validate Excel Form | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
///Funtion Definitions
function get_duplicates ($array) {
	return array_unique( array_diff_assoc( $array, array_unique( $array ) ) );
}

?>


<!--main content start-->
<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
               <li><i class="icon_documents_alt"></i>Form</li>
               <li><i class="fa fa-file-excel-o" aria-hidden="true"></i>Validate Excel Form</li>
            </ol>
         </div>
      </div>
      <!-- page start-->    
      <div class="row">
         <div class="col-lg-12">
            
            <section class="panel">
               <header class="panel-heading">Upload Form</header>
               <div class="panel-body">
                  <?php
					
					if(isset($_POST['VerifySurvey'])){
						$fieldNameArr=$selectOneChoisesArr=[];
						$file_name = $_FILES['structure']['name'];
						$tmp_name = $_FILES['structure']['tmp_name'];

						$typeArr = ['text','number','date','time','datetime','picture','select_one','select_multiple','begin_repeat','end_repeat','begin_group','end_group','calculate','audio','video','hidden','gps-button','timeline_start','timeline_end','note'];
						$typeLblBlankArr = ['begin_repeat','end_repeat','begin_group','end_group','timeline_start','timeline_end','note'];
						
						$file_check = 1;//$_POST['file_check'];
						if ($file_check == 1){
							set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
							include 'PHPExcel/Classes/PHPExcel/IOFactory.php';
							$file = $tmp_name;//'/media/sf_E_DRIVE/om/newData.xlsx';
							$inputFileType = PHPExcel_IOFactory::identify($file);
							$objReader = PHPExcel_IOFactory::createReader($inputFileType);
							$objReader->setReadDataOnly(true);
							$objPHPExcel = $objReader->load($file);
							$objWorksheet = $objPHPExcel->getActiveSheet();
							$CurrentWorkSheetIndex = 0;
							
							$rowsss = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();
							$cellIterator = $rowsss->getCellIterator();
							$cellIterator->setIterateOnlyExistingCells(false);
							$fixedHeaders = ['type','choice_relation','name','label','deidentify','dictionary_label','lookups','media_file','preserve','unique_id','default_response','hint','limit','constraint','constraint_message','required','paradata','appearance','choice_filter','relevant','parameters','repeat_count','read_only','calculation'];
		
							$excelFormat=[];
							foreach ($cellIterator as $cell) {
								$excelFormat[]= $cell->getValue();
							}
							$excelFormat1 = array_filter($excelFormat, 'strlen' );
							//$excelFormat1 = $excelFormat;
							$headers=array_diff($fixedHeaders,$excelFormat1);
							
							if(count($headers)>0){
								//echo "Invalid Excel Format.";
								$_SESSION['status_error'] = "Invalid Excel Format.";
								$_SESSION['status_error_code'] = "warning";
							}
							
							foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
								// echo 'WorkSheet' . $CurrentWorkSheetIndex++ . "\n";
								// echo 'Worksheet number - ', $objPHPExcel->getIndex($worksheet), PHP_EOL;
								$sheetSno = $objPHPExcel->getIndex($worksheet);//, PHP_EOL;
								$highestRow = $worksheet->getHighestDataRow();
								$highestColumn = $worksheet->getHighestDataColumn();
								$headings = $worksheet->rangeToArray('A1:' . $highestColumn . 1, NULL,TRUE, FALSE);
								//echo "<pre>";

								if($sheetSno=="0"){
									$screen_no=1;
									$sequence_no=1;
									$group=0;
									$question_id=0;

									for ($row = 2; $row <= $highestRow; $row++) {
										$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
										$rowData[0] = array_combine($headings[0], $rowData[0]);
										$pos='';
										$sqltext='test';
										$questions_type_id=0;
										$question_input_type_id=0;
										$validation_id=0;
										$groupid=0;
										
										
										if($row==2){
											$fixedHeaders = ['type','choice_relation','name','label','deidentify','dictionary_label','lookups','media_file','preserve','unique_id','default_response','hint','limit','constraint','constraint_message','required','paradata','appearance','choice_filter','relevant','parameters','repeat_count','read_only','calculation'];
											$fixedHeaders1 = ['type','choice_relation','name','label','deidentify','dictionary_label','lookups','media_file','preserve','unique_id','default_response','hint','limit','constraint','constraint_message','required','paradata','appearance','choice_filter','relevant','parameters','repeat_count','read_only','calculation'];
											$language_masters=[];
											$allQuesKeys = array_keys($rowData[0]);
											foreach($allQuesKeys as $allQuesKey){
												$search = 'label::';
												if(preg_match("/{$search}/i", $allQuesKey)) {
													$language_masters[] = str_replace("label::","",$allQuesKey);
												}
											}
											$getLaguages = mysqli_query($conn,"SELECT language_id,language_name,language_code FROM languages WHERE status='0' order by language_id asc");
											$languagemasters = mysqli_fetch_all($getLaguages,MYSQLI_ASSOC);
											foreach($languagemasters as $language_master){
												$fixedHeaders[] =  "hint::".$language_master['language_code'];
												$fixedHeaders[] =  "label::".$language_master['language_code'];
												$fixedHeaders[] =  "constraint_message::".$language_master['language_code'];
												
											}
											//echo "<pre>";
											$headers=array_diff($allQuesKeys, $fixedHeaders);
											$headers1=array_diff($fixedHeaders1,$allQuesKeys);
											//print_r($headers1);
											foreach($headers as $header){
												if($header!=""){
													$error_arr[] = "Header/ Column does not exist: ".$header;
												}
											}
											
											foreach($headers1 as $header1){
												if($header1!=""){
													$error_arr[] = "Header/ Column is missing: ".$header1;
												}
											}
											
										}

										
										foreach ($rowData as $key => $rowvalue) {
											
											
											$category='';
											$type = safe_var($conn,$rowvalue['type']);
											$type = strtolower($type);

											$question_input_type_id=1;
									   
											$groupid=$group;
											$field_name = trim(safe_var($conn,str_replace(" ","",$rowvalue['name'])));
											$question_name = isset($rowvalue['label']) ? safe_var($conn,$rowvalue['label']) : safe_var($conn,$rowvalue['label::English']);
											$question_name = trim($question_name);
											$question_description = safe_var($conn,$rowvalue['hint']);
											$relevant = safe_var($conn,$rowvalue['relevant']);
											$constraints = safe_var($conn,$rowvalue['constraint']);
											$constraint_message = safe_var($conn,$rowvalue['constraint_message']);
											$parameters = safe_var($conn,$rowvalue['parameters']);
											$read_only = safe_var($conn,$rowvalue['read_only']);
											$calculation = safe_var($conn,$rowvalue['calculation']);
											$required = strtolower(safe_var($conn,$rowvalue['required']));
											$limit = $rowvalue['limit'];
											// $limit = $rowvalue['limit']?:"30";//safe_var($conn,$rowvalue['limit']);
											$repeat_count = safe_var($conn,$rowvalue['repeat_count']);
											$appearance = safe_var($conn,$rowvalue['appearance']);
											$choice_filter = safe_var($conn,$rowvalue['choice_filter']);
											$choice_relation = safe_var($conn,$rowvalue['choice_relation']);
											$default_response = safe_var($conn,$rowvalue['default_response']);
											$paradata = safe_var($conn,$rowvalue['paradata']);
										
											$category=$field_name;
											
											// XLS VALIDATE
											$sn = $sequence_no;
											$sn=$sn+1;
											
											if($type=="" && $field_name=="" && $question_name==""){
												break;
											}
											
											$allFiels[]=$field_name;
											if(!in_array($choice_filter,$allFiels) && $choice_filter!=""){
												$error_arr[] =  "Choice filter is invalid in line No.: ".$sn;
											}
											
											if($type!="begin_repeat"){
												$fieldNameArr[] = $field_name;
											}
											
											if($type=="select_one"){  
												$selectOneChoisesArr[$field_name] = $choice_relation;
												$selectOnelimitsArr[$field_name] = $limit;
											}
											
											if($type=="select_multiple"){  
												$selectMultiChoisesArr[$field_name] = $choice_relation;
												$selectMultilimitsArr[$field_name] = $limit;
											}
											
											if(!in_array($type, $typeArr)){
												$error_arr[] = "Type ".$type." Not Found line No.: ".$sn;
											}

											if($type=='select_one' && $choice_relation==""){
												$error_arr[] =  "Choice relation required in line No.: ".$sn;
											}
											if($type=='select_multiple' && $choice_relation==""){
												$error_arr[] =  "Choice relation required in line No.: ".$sn;
											}
											
											if($choice_relation!=""){
												$choice_relationArr[] = $choice_relation;
											}
											
											if($type=='begin_group'){
												$begingroup[]="begin_group";
												if($field_name==""){
													$error_arr[] =  "Please define group name in line No.: ".$sn;
												}
											}
											if($type=='end_group'){
												$endgroup[]="end_group";
											}
											
											if($type=='date' && $limit<10 ){
												$error_arr[] =  "Limit should be >10 in line No.: ".$sn;
											}
											if($type=='datetime' && $limit<20 ){
												$error_arr[] =  "Limit should be >20 in line No.: ".$sn;
											}
											
											if($type=='number' && $limit>20 ){
												$error_arr[] =  "Limit should be <20 in line No.: ".$sn;
											}
		
											if(!in_array($type, $typeLblBlankArr)){
												if($field_name==""){
													$error_arr[] =  "Name required in line No.: ".$sn;
												}
											}

											if(!in_array($type, $typeLblBlankArr)){
												if($question_name==""){
													$error_arr[] =  "Label required in line No.: ".$sn;
												}
											}

											if(!in_array($type, $typeLblBlankArr)){
												if($limit==""){
													$error_arr[] =  "Limit required in line No.: ".$sn;
												}
											}
											
											if($paradata!=""){
												$prdata = explode(",",$paradata);
												if(in_array("A",$prdata)){
													$totAudio[] = "A";
												}
											}
											
											// if(preg_match("/([%\$#\*]+)/", $constraints))
											// {
											   // $error_arr[] =  'Invalid constant value line No.: '.$sn;
											// }

											if($constraints!=""){
												if($constraint_message==""){
													$error_arr[] =  'Please enter constraints message line No.: '.$sn;
												}
											}
												
											if(preg_match("/([%\$#\*]+)/", $relevant))
											{
											   $error_arr[] =  'Invalid relevant value line No.: '.$sn;
											}
											
											foreach($language_masters as $language_master){
												$languagehint= 'label::'.$language_master;
												$langhints=$rowvalue[$languagehint];
												//$error_arr[] =  'Label missing Line No.: '.$langhints;
												if($langhints=='' && $type!='begin_group' && $type!='end_group' && $type!='begin_repeat' && $type!='end_repeat')
												{
													$error_arr[] =  $languagehint.' Missing line No.: '.$sn;
												} 
												
												$languageMsg= 'constraint_message::'.$language_master;
												$langMsg=$rowvalue[$languageMsg];
												if($constraints!="" && $langMsg=='')
												{
													$error_arr[] =  $languageMsg.' Missing line No.: '.$sn;
												}												 
											}
											
											$sequence_no=$sequence_no+1;
										}
									}
								}
							
								$duplicateNameArr = get_duplicates ($fieldNameArr);
								$duplicateName = implode(", ",$duplicateNameArr);
								if($duplicateName!=""){
									$error_arr[] =  'Duplictate: '.$duplicateName;
								}
								
								if(count($begingroup)!=count($endgroup)){
									$error_arr[] =  'Invalid Grouping ';
								}
								
								if(count($totAudio)>10){
									$error_arr[] =  'Maximun limit to record audio- 10 questions.';
								}
							
								//OPTIONS
								if($sheetSno=="1"){
									$survey_id=$inserted_form_id;
									$ddkey=2;
									for ($row = 2; $row <= $highestRow; $row++) {
										$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
					
										$rowData[0] = array_combine($headings[0], $rowData[0]);
										$serial_no_for_app=1;
										foreach ($rowData as $key => $rowvalue) {
											$option_category_name = trim(safe_var($conn,$rowvalue['list_name']));
											$option_value = safe_var($conn,$rowvalue['value']);
											$choice_filter_parent = safe_var($conn,$rowvalue['choice_filter_parent']);
											$option_label = $rowvalue['label'];
											
											if($option_category_name=="" && $option_value=="" && $option_label==""){
												break;
											}
											
											if($option_label==""){
												$error_arr[] = "Label missing in choices sheet, line No.: ".$ddkey;
											}
											if($option_value==""){
												$error_arr[] = "Label missing in choices sheet, line No.: ".$ddkey;
											}
											
											foreach($language_masters as $language_master){
												$languagehintOpt= 'label::'.$language_master;
												$langhintsOpt=$rowvalue[$languagehintOpt];
												if($langhintsOpt=='')
												{
													$error_arr[] =  $languagehintOpt.' Missing in choices sheet line No.: '.$ddkey;
												}
											}
											
											$optChoice_relationArr[]=$option_category_name;
											$allOptions[$option_category_name][] = $option_value;
											$ddkey++;
										}
									}
								}
							
							
								///CHECK SELECT-ONE LIMIT VALID OR NOT
								foreach($selectOneChoisesArr as $key=>$selectOneChoises){
									$optMaxLength = strlen(max($allOptions[$selectOneChoises]));
									if($selectOnelimitsArr[$key]<$optMaxLength){ $error_arr[] = "Error, limit should be ".$optMaxLength." for question ".$key; }
								}
								
								///CHECK SELECT-MULTIPLE LIMIT VALID OR NOT
								foreach($selectMultiChoisesArr as $key=>$selectMultiChoises){
									$optmMaxLength = count($allOptions[$selectMultiChoises]);
									if($selectMultilimitsArr[$key]<$optmMaxLength){ $error_arr[] = "Error, limit should be ".$optmMaxLength." for question ".$key; }
								}
							
							
							
								///CHECK CHOICES DEFINE OR NOT
								$choice_relationArrUnique = array_unique($choice_relationArr);
								$optChoice_relationArrUnique = array_unique($optChoice_relationArr);
								$notDefinedChoices=array_diff($choice_relationArrUnique,$optChoice_relationArrUnique);
								if(count($notDefinedChoices)>0){
									$notDefinedChoicesStr = implode(",",$notDefinedChoices);
									$error_arr[] =  'Choice Relations are not defined: '.$notDefinedChoicesStr;
								}
								
								/* if($sheetSno=="2"){
									echo "sheet-2";
								} */
							}
						}
					}

				?>
                  <div class="form">
                     <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group ">
                           <label for="cname" class="control-label col-lg-2">Excel File: </label>
                           <div class="col-lg-10">
                             <input type="file" name="structure" accept=".xls,.xlsx" class="form-control"  required /> 
                           </div>
                        </div>
                        
                        <div class="form-group">
                           <div class="col-lg-offset-2 col-lg-10 text-right">
                              <button class="btn btn-primary" type="submit" name="VerifySurvey" title="Verify XLSX FORM VALIDATION">Verify</button>
							  <?php 
								if(isset($_POST['VerifySurvey'])){
									if(count($error_arr)>0){
										echo '<button class="btn btn-danger" type="button"  data-toggle="modal" data-target="#xlsIssues" data-backdrop="static" data-keyboard="false" data-whatever="@fat">Error Found</button>';
									}else{
										echo '<span class="btn btn-success">Verified<span>';
									}
								}
							 ?>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </section>
         </div>
      </div>
      <!-- page end-->
   </section>
</section>
<div class="modal fade" id="xlsIssues" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				
				<h1 class="modal-title"  style="color:#394A59;">XLS Errors: </h1>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<h4 style="color: red;">We have found some errors in xls file.</h4>
					<?php
						
						foreach(array_unique($error_arr) as $errorarr){
							echo "▶ ".$errorarr;
							echo "<br>";
						}
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
	</div>
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