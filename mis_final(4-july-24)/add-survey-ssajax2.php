<?php include_once('includes/config.php'); ?>
<?php 
if(empty($_SESSION['username'])){
	echo "<script>alert('Session Expired. Please login again to continue..');</script>";
	echo "<script>window.location.href='logout.php'</script>"; 
	exit;
	}
?>
<?php include_once('includes/functions.php'); ?>
<?php
  $client_qry = "";
	if($_SESSION['role_id']=='3'){
		$client_id = $_SESSION['client_id'];
		$client_qry=" and projects.client_id='".$client_id."' ";
		
	}
?>
<?php 

if (isset($_POST['survey_name_txt'])) { 
    $survey_name_txt =SpecialCharRemove($_POST['survey_name_txt']);
	$project_id = $_POST['project_id'];
	$check_name = mysqli_query($conn,"SELECT id,survey_name  FROM survey WHERE survey_name ='".$survey_name_txt."' and project_id='".$project_id."' "); 
	
    if (mysqli_num_rows($check_name)>0) {
        echo '<div style="color: #FF0001;"> <b>'.$survey_name_txt.'</b> is already registered! </div>';                                                                          
    } else {
        echo '<div style="color: green;"> <b>'.$survey_name_txt.'</b> is available! </div>'; 
	//No Record Found - Username is available
    }
}

?>
<?php

	if(isset($_POST['add_survey']) && $_SESSION['ISMEMORYFULL']==false && !empty($_POST['survey_name'])){
		
		$tmp_name = $_FILES['structure']['tmp_name'];
		if(isset($tmp_name) && $tmp_name!=''){
			set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
			include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
			$file = $tmp_name;//'/media/sf_E_DRIVE/om/newData.xlsx';
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			
			$rowsss = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();
			$cellIterator = $rowsss->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			
			
			$fixedHeaders = ['type','choice_relation','name','label','deidentify','dictionary_label','lookups','media_file','preserve','unique_id','default_response','hint','limit','constraint','constraint_message','required','paradata','appearance','choice_filter','relevant','parameters','repeat_count','read_only','calculation'];
			$excelFormat=[];
			
			foreach ($cellIterator as $cell) {
				$excelFormat[]= $cell->getValue();
			}
			$excelFormat1 = array_filter( $excelFormat, 'strlen' );
			//$headers=array_diff($excelFormat1,$fixedHeaders);
			$headers=array_diff($fixedHeaders,$excelFormat1);
			
			if(count($headers)>0){
				$result = array("status"=>0,"msg"=>"Invalid Excel Format.");
				echo json_encode($result);
				exit();
			} 
		}
		
		$project_id = $_POST['project_id'];
		$survey_Name_field = SpecialCharRemove($_POST['survey_name']);
		$client_id = $_POST['client_id'];
		$category_id = $_POST['category_id'];
		
		$clientid=$_SESSION['client_id'];
		$clientId="C".$clientid;
		
		$sqlProject="select survey_name from survey where project_id='".$project_id."' and survey_name='".$survey_Name_field."'";
		$qryProject=mysqli_query($conn,$sqlProject);
		$dataProject=mysqli_fetch_array($qryProject);
		$surveyname=$dataProject['survey_name'];
		
		if($surveyname==$survey_Name_field){
			$result = array("status"=>2,"msg"=>"Form name already exist.");
			echo json_encode($result);
			
		}else{
			
			$survey_name=$survey_Name_field;
		
			// if($survey_name!=""){
				// $language_id = $_POST['language_id_english'];
			// }else{
				// $language_id = $_POST['language_id'];
			// }
			
			$language_id = $_POST['language_id'];
	
			$createdAt = currentTimeStamp();
			$digits = 10;
			$unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
			$survey_id = $_POST['survey_id'];
			//$filename = $_FILES['structure']['name'];
			//$tmp_name = $_FILES['structure']['tmp_name'];
			$filename = isset($_FILES['structure']['name']) ? $_FILES['structure']['name'] : '';
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$surveyName=str_replace(' ','-',$survey_name);
			$file_name = "Form_".$surveyName."-".$unique_id.".".$ext;
		
			if($survey_id!=''){
				$inserted_form_id = $survey_id;
			}elseif($_SESSION['client_id']){
				 
				$insertForm = mysqli_query($conn,"INSERT INTO survey SET survey_name='".$survey_name."',project_id='".$project_id."',unique_id='".$unique_id."', user_id='".$_SESSION['user_id']."', client_id='".$_SESSION['client_id']."', category_id='".$category_id."',questinnour_file='".$file_name."' ,created_at='".$createdAt."',form_version='V1' ");
				$inserted_form_id = mysqli_insert_id($conn);
				
			}else{
			   $insertForm = mysqli_query($conn,"INSERT INTO survey SET survey_name='".$survey_name."',project_id='".$project_id."',unique_id='".$unique_id."', user_id='".$_SESSION['user_id']."', client_id='".$client_id."', category_id='".$category_id."',questinnour_file='".$file_name."',created_at='".$createdAt."',form_version='V1' ");
				$inserted_form_id = mysqli_insert_id($conn);
			} 
		
			/** Include path **/
			$file_check = isset($_POST['file_check']) ? $_POST['file_check'] : '0';
			if ($file_check == "1"){
				$ngrupArr = [];
				$allLanguages = [];
				// set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
				// include 'PHPExcel/Classes/PHPExcel/IOFactory.php';
				//echo "ssesss";
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
								
								$allQuesKeys = array_keys($rowData[0]);
								foreach($allQuesKeys as $allQuesKey){
									$search = 'label::';
									if(preg_match("/{$search}/i", $allQuesKey)) {
										$lcode[] = str_replace("label::","",$allQuesKey);
									}
								}
								$langCodes = "'".implode("','",$lcode)."'";
								$getLaguages = mysqli_query($conn,"SELECT language_id,language_name,language_code FROM languages WHERE status='0' and language_code in($langCodes) order by language_id asc");
								$language_masters = mysqli_fetch_all($getLaguages,MYSQLI_ASSOC);
								//$headers=array_diff($fixedHeaders,$allQuesKeys);
								// if(count($headers)>1){ 
									// mysqli_query($conn,"delete from survey where id='".$inserted_form_id."' and client_id='".$_SESSION['client_id']."' ");
									// echo "<script>Invalid Excel Format.</script>"; 
								// }
							}

						
							foreach ($rowData as $key => $rowvalue) {
								
								
								$category='';
								$type = safe_var($conn,$rowvalue['type']);
								$type = strtolower($type);
		
								$question_input_type_id=1;
							 
								if($type=='text')
								{
									$questions_type_id=1;
								}
								elseif($type=='number')
								{
									$questions_type_id=2;
								}
		
								elseif($type=='picture')
								{
									$questions_type_id=10;
								}
								elseif($type=='begin_repeat')
								{
									$questions_type_id=8;
								}
								elseif($type=='end_repeat')
								{
									$questions_type_id=9;
								}
								elseif($type=='begin_group' || $type=='single_screen_group')
								{
									$questions_type_id=11;
								}
								elseif($type=='end_group' || $type=='end_single_screen_group')
								{
									$questions_type_id=12;
								}
								elseif($type=='date')//old calendar
								{
									$questions_type_id=7;
								}
								elseif($type=='datetime'){
									$questions_type_id=18;
								}
								elseif($type=='time'){
									$questions_type_id=17;
								}
								elseif($type=='hidden'){
									$questions_type_id=19;
								}
								elseif($type=='calculate')
								{
									$questions_type_id=13;
								}
								elseif($type=='audio')
								{
									$questions_type_id=14;
								}
								elseif($type=='video')
								{
									$questions_type_id=15;
								}elseif($type=='gps-button')
								{
									$questions_type_id=5;
								}
								elseif($type=='note')
								{
									$questions_type_id=16;
								}
								elseif($type=='decimal'){
									$questions_type_id=20;
								}
								elseif($type=='heading')
								{
									$screen_no++;
									$questions_type_id=6;
								}
								elseif($type=='select_one')
								{
									$group++;
									$questions_type_id=4;
									$required = safe_var($conn,$rowvalue['required']);
									if($required=='yes')
									{
										$validation_id=1;
									}
								}elseif($type=='select_multiple'){
		
									$questions_type_id=3;
									$required = safe_var($conn,$rowvalue['required']);
									if($required=='yes')
									{
										$validation_id=1;
									}
								}
								else
								{
									$sqltext='';
								}
	
								// if($sqltext!='')
								// {
								$groupid=$group;
								$field_name = trim(safe_var($conn,str_replace(" ","",$rowvalue['name'])));
								$question_name = isset($rowvalue['label']) ? safe_var($conn,$rowvalue['label']) : safe_var($conn,$rowvalue['label::English']);
								$question_name = trim($question_name);
								$dictionary_label = isset($rowvalue['dictionary_label']) ? safe_var($conn,$rowvalue['dictionary_label']) : safe_var($conn,$rowvalue['dictionary_label::English']);
								if(empty($dictionary_label)){ $dictionary_label = $question_name; }
								$encryption = $rowvalue['deidentify'];
								if(empty($encryption)){ $encryption = 0; }
								$question_description = safe_var($conn,$rowvalue['hint']);
								$relevant = safe_var($conn,$rowvalue['relevant']);
								$constraints = safe_var($conn,$rowvalue['constraint']);
								$constraint_message = safe_var($conn,$rowvalue['constraint_message']);
								$parameters = safe_var($conn,$rowvalue['parameters']);
								$read_only = safe_var($conn,$rowvalue['read_only']);
								$calculation = safe_var($conn,$rowvalue['calculation']);
								$required = strtolower(safe_var($conn,$rowvalue['required']));
								$limit = $rowvalue['limit']?:"30";//safe_var($conn,$rowvalue['limit']);
								$repeat_count = safe_var($conn,$rowvalue['repeat_count']);
								$appearance = safe_var($conn,$rowvalue['appearance']);
								$choice_filter = safe_var($conn,$rowvalue['choice_filter']);
								$choice_relation = safe_var($conn,$rowvalue['choice_relation']);
								$default_response = safe_var($conn,$rowvalue['default_response']);
								$paradata = safe_var($conn,$rowvalue['paradata']);
								$unique_id = safe_var($conn,$rowvalue['unique_id']);
								$preserve = safe_var($conn,$rowvalue['preserve']);
								$lookups = safe_var($conn,$rowvalue['lookups']);
								$media_file = safe_var($conn,$rowvalue['media_file']);
								
								if(strtolower($lookups)=="yes"){
									$lookups_arr_name = $field_name;
								}
							
								$category=$field_name;
								//if ($type == '74108520963'){
								if ($type != ''){
									//echo "check type";

									//if($type=='begin_group'){
									
									if($type=='begin_group'){
										if($relevant!="" && $appearance!="onescreen" ){
											mysqli_query($conn,"INSERT INTO normal_groups SET group_name='".$field_name."', conditions='".$relevant."', survey_id='".$inserted_form_id."'  ");
											$insertnormalgroupid = mysqli_insert_id($conn);
											$ngrupArr[] = $insertnormalgroupid;
											$prelv=$relevant;
										}else{
											$ngrupArr[]="";
										}
										
									}
								
									if($type=='end_group'){
										array_pop($ngrupArr);
										$prelv = "";
									}
									$insert_normal_group_id = implode(",", $ngrupArr);
								
									$quest_labelarray=array();
									// $getLaguages = mysqli_query($conn,"SELECT language_id,language_name,language_code FROM languages WHERE status='0' order by language_id asc");
									// while($language_master = mysqli_fetch_object($getLaguages)){
									foreach($language_masters as $language_master){
										$language_master = (object)$language_master;
										$languagehint= 'label::'.$language_master->language_code;
										//$rowvalue['custom::'.$language_master->language_code]='q';
										
										
										// if($type=='begin_group' && $appearance=="onescreen"){
											
											// $getLastQuestionsmls = mysqli_query($conn,"select question_id, input_field_type from questions where survey_id='".$inserted_form_id."' order by question_id desc limit 1 ");
											// $glastQuesmls = mysqli_fetch_object($getLastQuestionsmls);
											// if($glastQuesmls->input_field_type=='end_group'){
												 // $rowvalue['label::'.$language_master->language_code]='anonymous';
												// $rowvalue['hint::'.$language_master->language_code]='anonymous';
												// $rowvalue['constraint_message::'.$language_master->language_code]='anonymous'; 
												// $rowvalue['custom::'.$language_master->language_code]='cq'; //custom question */
											//     $qqid = $glastQuesmls->question_id+1;
											// 	echo $questionsqlmlt ="insert into questions_language set language_id='".$language_ids."', question_id='".$qqid."', question_name='anonymous',dictionary_label='anonymous',encrpt='".$encryption."',question_description='".$question_description."',questions_type_id='21',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='anonymous',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='anonymous',validation_id='".$validation_id."',title='anonymous',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."',lookups='".$lookups."', media_file='".$media_file."' ";
											// 	mysqli_query($conn,$questionsqlmlt);
											// }
										// }
										
										$langhints=$rowvalue[$languagehint];
										if($langhints!='')
										{
										//$language_id=$language_master->language_id;	
										$lang_hintparam= 'hint::'.$language_master->language_code;
										$lang_labelparam= 'label::'.$language_master->language_code;
										$lang_constraint_messageparam= 'constraint_message::'.$language_master->language_code;												
										$quest_labelarray['question_name'][]= safe_var($conn,$rowvalue[$lang_labelparam]); 
										$quest_labelarray['question_description'][]= safe_var($conn,$rowvalue[$lang_hintparam]);
										$quest_labelarray['constraint_message'][]= safe_var($conn,$rowvalue[$lang_constraint_messageparam]);
										$quest_labelarray['language_id'][]= $language_master->language_id;
										//$quest_labelarray['ssq'][]= $rowvalue['custom::'.$language_master->language_code];
										}
										
									}
								
									if($language_id=="1"){
										//echo "hhhhhh";
										/*
										if($type=='begin_group' && $appearance=="onescreen"){
											$getLastQuestions = mysqli_query($conn,"select question_id, input_field_type from questions where survey_id='".$inserted_form_id."' order by question_id desc limit 1 ");
											$glastQues = mysqli_fetch_object($getLastQuestions);
											if($glastQues->input_field_type=='end_group'){
												 $questionsql ="insert into questions set question_name='anonymous',dictionary_label='anonymous',encrpt='".$encryption."',question_description='".$question_description."',questions_type_id='21',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='anonymous',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='anonymous',validation_id='".$validation_id."',title='anonymous',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."',lookups='".$lookups."', media_file='".$media_file."' ";
												mysqli_query($conn,$questionsql);
												$last_id=mysqli_insert_id($conn);
												
												$questionsqlmlt ="insert into questions_language set language_id='1', question_id='".$last_id."', question_name='anonymous',dictionary_label='anonymous',encrpt='".$encryption."',question_description='".$question_description."',questions_type_id='21',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='anonymous',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='anonymous',validation_id='".$validation_id."',title='anonymous',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."',lookups='".$lookups."', media_file='".$media_file."' ";
												mysqli_query($conn,$questionsqlmlt); 
											}
										} 
										*/
									
										$question_sql ="insert into questions set question_name='".$question_name."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$question_description."',questions_type_id='".$questions_type_id."',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."',lookups='".$lookups."', media_file='".$media_file."' ";
										
										$insertdata = mysqli_query($conn,$question_sql);
										if($insertdata==false){
											$question_sql ="insert into questions set question_name='".$field_name."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$field_name."',questions_type_id='".$questions_type_id."',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."',lookups='".$lookups."', media_file='".$media_file."' ";
											mysqli_query($conn,$question_sql);
										}
										$last_id=mysqli_insert_id($conn);
										$question_id=$last_id;
										$question_language_sql ="insert into questions_language set language_id='1',question_id='".$last_id."',question_name='".$question_name."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
										$insertmldata = mysqli_query($conn,$question_language_sql);
										if($insertmldata==false){
											$question_language_sql ="insert into questions_language set language_id='1',question_id='".$last_id."',question_name='".$field_name."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$field_name."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
											$insertmldata = mysqli_query($conn,$question_language_sql);
										}
									
									
										$group_id=0; 
									
										if($type=="begin_repeat"){ 
											$qid_insert=$last_id-1;//"11";
											//$field_nameBgn = $repeat_count; //$field_name; 
											$rosterRelevant="";
											$prelv1="";
											if(empty($prelv)){ $prelv1=$prelv = $relevant; }
											if($parameters=="multiroster"){ $field_nameBgn=$repeat_count; $prelv=""; $rosterRelevant=", roster_relevent='".$relevant."' "; }else{ if(empty($repeat_count)){ $field_nameBgn=$field_name; }else{ $field_nameBgn=$repeat_count; } } 
											
											/* if(!empty($repeat_count)){
												$field_nameBgn=$repeat_count; $prelv=""; $rosterRelevant=", roster_relevent='".$relevant."' ";
											}else{
												$field_nameBgn=$repeat_count;
											} */
											
											$insertGroup = mysqli_query($conn,"INSERT INTO questions_group SET group_name='".$field_name."', survey_id='".$inserted_form_id."',group_type='group', client_id='".$client_id."' ");
											$insert_group_id = mysqli_insert_id($conn);
											$update = mysqli_query($conn,"UPDATE questions SET repeated='start',repeat_count='".$field_nameBgn."', group_id='".$insert_group_id."', parent_relevent='".$prelv."' $rosterRelevant  WHERE question_id='".$qid_insert."' ");
											
											//$txt = "Raju";
											$reptno=(int)$repeat_count;
											if(is_int($reptno) && $reptno>0){
												$update = mysqli_query($conn,"UPDATE questions_language SET repeated='".$insert_group_id."',repeat_count='".$repeat_count."', parent_relevent='".$prelv."' $rosterRelevant WHERE question_id='".$qid_insert."' ");
											}else{
												$update = mysqli_query($conn,"UPDATE questions_language SET repeated='".$insert_group_id."',repeat_count='".$field_nameBgn."', parent_relevent='".$prelv."' $rosterRelevant WHERE question_id='".$qid_insert."' ");
											}
											if(!empty($prelv1)){
												$prelv="";
											}
										}
										if($type=="end_repeat"){
											$qid_insert="";
										}
										if($qid_insert!=""){
											$group_id=$insert_group_id;
											$update = mysqli_query($conn,"UPDATE questions SET repeated='".$qid_insert."',repeat_count='".$field_nameBgn."',group_id='".$insert_group_id."',normal_group_id='' WHERE question_id='".$last_id."' ");
											$update = mysqli_query($conn,"UPDATE questions_language SET group_id='".$insert_group_id."',repeated='".$qid_insert."',normal_group_id='' WHERE question_id='".$last_id."' ");
										}
									
									
										// START TIMELINE
										$qid_insertt_check = '';
										if($type=="timeline_start"){
											$qid_insertt=$last_id;//-1;//"11";
											$qid_insertt_check=1;
											$field_nameBgn = $field_name;
											$insertGroup = mysqli_query($conn,"INSERT INTO questions_group SET group_name='".$field_name."', survey_id='".$inserted_form_id."',group_type='timeline', client_id='".$client_id."' ");
											$insert_group_id = mysqli_insert_id($conn);
											$update = mysqli_query($conn,"UPDATE questions SET repeated='start',repeat_count='".$field_nameBgn."' WHERE question_id='".$qid_insertt."' ");
											
											//$txt = "Raju";
											$reptno=(int)$repeat_count;
											if(is_int($reptno) && $reptno>0){
												$update = mysqli_query($conn,"UPDATE questions_language SET repeated='".$insert_group_id."',repeat_count='".$repeat_count."' WHERE question_id='".$qid_insertt."' ");
											}else{
												$update = mysqli_query($conn,"UPDATE questions_language SET repeated='".$insert_group_id."',repeat_count='timeline' WHERE question_id='".$qid_insertt."' ");
											}
										}
										if($type=="timeline_end" ){
											$qid_insertt="";
										}
										if($qid_insertt!=""){
											if($qid_insertt_check!=1){
												$group_id=$insert_group_id;
												$update = mysqli_query($conn,"UPDATE questions SET repeated='".$qid_insertt."',repeat_count='".$field_nameBgn."',group_id='".$insert_group_id."' WHERE question_id='".$last_id."' ");
												$update = mysqli_query($conn,"UPDATE questions_language SET group_id='".$insert_group_id."',repeated='".$qid_insertt."' WHERE question_id='".$last_id."' ");
											}
										}
										$qid_insertt_check='';
										//END TIMELINE
										
									
										// START SCREEN GROUP
										$qid_insertt_checks = '';
										if($type=="begin_group" && $appearance=="onescreen" ){
											$endfset="end";
											$qid_insertts=$last_id-1;//"11";
											$qid_insertt_checks=1;
											$field_nameScreen = $field_name;
											$insertGroups = mysqli_query($conn,"INSERT INTO questions_group SET group_name='".$field_name."', survey_id='".$inserted_form_id."',group_type='screen', client_id='".$client_id."' ");
											$insert_group_ids = mysqli_insert_id($conn);
											$update = mysqli_query($conn,"UPDATE questions SET screened='start',screened_count='".$field_nameScreen."', parent_relevent='".$relevant."' WHERE question_id='".$qid_insertts."' ");
											
											//$txt = "Raju";
											$update = mysqli_query($conn,"UPDATE questions_language SET screened='".$insert_group_ids."',screened_count='screen', parent_relevent='".$relevant."' WHERE question_id='".$qid_insertts."' ");
										}
										if($type=="end_group" && $endfset=="end"){
											$endfset="";
											$qid_insertts="";
										}
										if($qid_insertts!=""){
											if($qid_insertt_checks!=1){
												$group_id=$insert_group_ids;
												$update = mysqli_query($conn,"UPDATE questions SET screened='".$qid_insertts."',screened_count='".$field_nameScreen."',group_id='".$insert_group_ids."' WHERE question_id='".$last_id."' ");
												$update = mysqli_query($conn,"UPDATE questions_language SET group_id='".$insert_group_ids."',screened='".$qid_insertts."' WHERE question_id='".$last_id."' ");
											}
										}
										$qid_insertt_checks='';
										//END SCREEN GROUP
									
										$report_format_sql ="insert into report_format set title='".$question_name."',msci_report='".$encryption."',seq_no='".$sequence_no."',field_lable='".$field_name."',rm_report='".$field_name."',survey_id='".$inserted_form_id."', client_id='".$client_id."',group_id='".$group_id."' ";
										$report_format = mysqli_query($conn,$report_format_sql);
										
										// $question_sql ="insert into questions set question_name='".$question_name."',question_description='".$question_description."',questions_type_id='".$questions_type_id."',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."'";
										// $insertdata = mysqli_query($conn,$question_sql);
										// $last_id=mysqli_insert_id($conn);
										// $question_language_sql ="insert into questions_language set language_id='1',question_id='".$last_id."',question_name='".$question_name."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."'";
										// mysqli_query($conn,$question_language_sql);
									}
									else{
										
										$getQuestionId = mysqli_query($conn,"SELECT question_id FROM questions WHERE survey_id='".$survey_id."' AND field_name='".$field_name."' ");
										$questionId = mysqli_fetch_object($getQuestionId);
										$question_id = $questionId->question_id;
										
										$question_language_sql ="insert into questions_language set language_id='".$language_id."',question_id='".$question_id."',question_name='".$question_name."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
										$insertmlData = mysqli_query($conn,$question_language_sql);
										if($insertmlData==false){
											$question_language_sql ="insert into questions_language set language_id='".$language_id."',question_id='".$question_id."',question_name='".$field_name."',dictionary_label='".$field_name."',encrpt='".$encryption."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
											$insertmlData = mysqli_query($conn,$question_language_sql);
										}
									}
									$survey_id = $inserted_form_id;
									if(count($quest_labelarray)>0)
									{
										$getQuestionId1 = mysqli_query($conn,"SELECT question_id,group_id,question_name,question_description,constraint_msg FROM questions WHERE survey_id='".$survey_id."' AND field_name='".$field_name."'");
										$questionId1 = mysqli_fetch_object($getQuestionId1);
										$group_idhi = $questionId1->group_id;
										$question_id = $questionId1->question_id;
										$question_nameEn = $questionId1->question_name;
										$question_descriptionEn = $questionId1->question_description;
										$constraint_msgEn = $questionId1->constraint_msg;
										for($qns=0; $qns<count($quest_labelarray['language_id']); $qns++ )
										{
											
											//$ssq=$quest_labelarray['ssq'][$qns];
											$language_ids=$quest_labelarray['language_id'][$qns];
											$question_name=$quest_labelarray['question_name'][$qns];
											$question_description=$quest_labelarray['question_description'][$qns];
											$constraint_message=$quest_labelarray['constraint_message'][$qns];	
											$allLanguages[] = $language_ids;
											
											/*
											if($ssq=='cq'){
												$getQuestionId2 = mysqli_query($conn,"SELECT question_id,group_id,screened,screened_count FROM questions_language WHERE survey_id='".$survey_id."' AND field_name='anonymous'");
												$questionId2 = mysqli_fetch_object($getQuestionId2); 
												$questionid = $questionId2->question_id;
												$qscreened = $questionId2->screened;
												$qscreened_count = $questionId2->screened_count;
												$questionid = $questionId2->question_id; 
												//$questionsqlmlt ="insert into questions_language set language_id='".$language_ids."',screened='".$qscreened."',screened_count='".$qscreened_count."', question_id='".$questionid."', question_name='anonymous',dictionary_label='anonymous',encrpt='".$encryption."',question_description='".$question_description."',questions_type_id='21',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='anonymous',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='anonymous',validation_id='".$validation_id."',title='anonymous',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."',lookups='".$lookups."', media_file='".$media_file."' ";
												//mysqli_query($conn,$questionsqlmlt);
											}else{
												$question_language_sql ="insert into questions_language set language_id='".$language_ids."',question_id='".$question_id."',question_name='".$question_name."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='".$group_idhi."',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
												$insertmldata =  mysqli_query($conn,$question_language_sql);
												if($insertmldata==false){
													$question_language_sql ="insert into questions_language set language_id='".$language_ids."',question_id='".$question_id."',question_name='".$question_nameEn."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$question_descriptionEn."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='".$group_idhi."',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_msgEn."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
													mysqli_query($conn,$question_language_sql);
												}
											}
											*/
											
											$question_language_sql ="insert into questions_language set language_id='".$language_ids."',question_id='".$question_id."',question_name='".$question_name."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='".$group_idhi."',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
											$insertmldata =  mysqli_query($conn,$question_language_sql);
											if($insertmldata==false){
												$question_language_sql ="insert into questions_language set language_id='".$language_ids."',question_id='".$question_id."',question_name='".$question_nameEn."',dictionary_label='".$dictionary_label."',encrpt='".$encryption."',question_description='".$question_descriptionEn."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='".$group_idhi."',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_msgEn."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
												mysqli_query($conn,$question_language_sql);
											}
										}
									}
									$sequence_no=$sequence_no+1;
								}
								else{
									break;
								}
								// }
							}
						}
					}
				
					//OPTIONS
					if($sheetSno=='1'){
						// echo $sheetSno;
						$survey_id=$inserted_form_id;
						for ($row = 2; $row <= $highestRow; $row++) {
							$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		
							$rowData[0] = array_combine($headings[0], $rowData[0]);
							// print_r($rowData);
							$serial_no_for_app=1;
							foreach ($rowData as $key => $rowvalue) {
								// print_r($rowvalue);  
								$option_category_name = trim(safe_var($conn,$rowvalue['list_name']));
								$option_value = safe_var($conn,$rowvalue['value']);
								$choice_filter_parent = safe_var($conn,$rowvalue['choice_filter_parent']);
								$option_label = safe_var($conn,$rowvalue['label']);
								$option_media_file = $rowvalue['media_file'];
								$constraint = $rowvalue['constraint'];
								

								// $option_label_hindi = $rowvalue['label::hindi'];
								// $option_label_marathi = $rowvalue['label::marathi'];
								// $option_label_Bengali = $rowvalue['label:Bengali'];
								// $option_label_Gujarati = $rowvalue['label:Gujarati'];

								//if($option_label!=''){
								if($option_category_name!=''){
									$getQuestion = mysqli_query($conn,"SELECT question_id FROM questions WHERE choice_relation='".$option_category_name."' and survey_id='".$inserted_form_id."'");
									//// Language////
									$option_labelarray=array();
									   // $getLaguagesopt = mysqli_query($conn,"SELECT language_id,language_name,language_code FROM languages WHERE status='0' order by language_id asc");
										// while($language_masteropt = mysqli_fetch_object($getLaguagesopt)){
										foreach($language_masters as $language_masteropt){
											$language_masteropt = (object)$language_masteropt;
											$languagehint1= 'label::'.$language_masteropt->language_code;
											$langhints1=$rowvalue[$languagehint1];
											if($langhints1!='')
											{
												$lang_labelparam= 'label::'.$language_masteropt->language_code;										
												$option_labelarray['option_label'][]= safe_var($conn,$rowvalue[$lang_labelparam]);
												$option_labelarray['language_id'][]= $language_masteropt->language_id;
											}
										}
									
									///// Language//////
									//print_r($question_id);
									if($language_id=="1"){
										while($question_data = mysqli_fetch_array($getQuestion)){
											$question_id = $question_data['question_id'];
											$option_label = mysqli_real_escape_string($conn, $option_label);
											$option_sql = "INSERT INTO options SET option_name='".$option_label."',option_constraint='".$constraint."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."',survey_id='".$survey_id."', media_file='".$option_media_file."' ";
											mysqli_query($conn,$option_sql);
											$last_id1=mysqli_insert_id($conn);
											
											$option_language_sql = "INSERT INTO options_language SET option_id='".$last_id1."', option_constraint='".$constraint."', language_id='1',option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."',survey_id='".$survey_id."', media_file='".$option_media_file."' ";
											mysqli_query($conn,$option_language_sql);
										}
									 
									}else{
										while($question_data = mysqli_fetch_array($getQuestion)){
											$question_id = $question_data['question_id'];
											$getOptionIds = mysqli_query($conn,"SELECT option_id FROM options WHERE question_id='".$question_id."' AND category_name='".$option_category_name."' AND option_sequence='".$option_value."' ");
											$getOptionId = mysqli_fetch_object($getOptionIds);
											$option_id = $getOptionId->option_id;
											
											$option_language_sql = "INSERT INTO options_language SET option_id='".$option_id."', option_constraint='".$constraint."', language_id='".$language_id."',option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."',survey_id='".$survey_id."', media_file='".$option_media_file."' ";
											mysqli_query($conn,$option_language_sql);
										}
									}
								
									if(count($option_labelarray)>0)
									{
										$getQuestion1 = mysqli_query($conn,"SELECT question_id FROM questions WHERE choice_relation='".$option_category_name."' and survey_id='".$inserted_form_id."'");
										while($question_data = mysqli_fetch_array($getQuestion1)){
											$question_id = $question_data['question_id'];
											$getOptionIds = mysqli_query($conn,"SELECT option_id FROM options WHERE question_id='".$question_id."' AND category_name='".$option_category_name."' AND option_sequence='".$option_value."' ");
											$getOptionId = mysqli_fetch_object($getOptionIds);
											$option_id = $getOptionId->option_id;
											
											for($ins=0; $ins<count($option_labelarray['language_id']); $ins++)
											{
												$option_label=$option_labelarray['option_label'][$ins];
												$option_label = mysqli_real_escape_string($conn, $option_label);
												$language_ids=$option_labelarray['language_id'][$ins];											
												$option_language_sql = "INSERT INTO options_language SET option_id='".$option_id."', option_constraint='".$constraint."', language_id='".$language_ids."',option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."',survey_id='".$survey_id."', media_file='".$option_media_file."' ";
												mysqli_query($conn,$option_language_sql);
											}
											//$option_language_sql = "INSERT INTO options_language SET option_id='".$option_id."',language_id='".$language_id."',option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."'";
											//mysqli_query($conn,$option_language_sql);
										}
										
									}
									$serial_no_for_app++;
								}else{
									break;
								}
							}
						}
					}
				
					if ($sheetSno == '2') {
						//$lookupsArr["lookup"] = "fsu";
						$survey_id = $inserted_form_id;
						for ($row = 2; $row <= $highestRow; $row++) {
							$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
							$rowData[0] = array_combine($headings[0], $rowData[0]);
							foreach ($rowData as $keylkup => $rowvalue) {
								$l = 0;
								foreach ($rowvalue as $lkey => $lookupVal) {
									// Check if the column is not blank before adding to the array
									if (!empty($lookupVal)) {
										if ($l == 0) {
											$fsudatas[$lkey] = safe_var($conn, $lookupVal); // trim($lookupVal);                                       
										}

										$fsu_data[$lkey] = safe_var($conn, $lookupVal); // trim($lookupVal);
										$l++;
									}
								}
								// Add data only if there is at least one non-blank column in the row
								if (!empty($fsu_data)) {
									$fsudatas["data"][] = $fsu_data;
									$fsu_data = array();
									$fsudata[] = $fsudatas;
									$fsudatas = array();
								}
							}
						}
						//$fsudataArr["fsu"] = $fsudata;
						$fsudataArr[$lookups_arr_name] = $fsudata;
						$fsudataArrArr["lookups"][] = $fsudataArr;
						// echo "<pre>";
						// print_r($fsudataArrArr);
						$lookups_json = json_encode($fsudataArrArr);

						mysqli_query($conn, "INSERT INTO survey_loockups SET survey_id='" . $survey_id . "', loockup_data='" . $lookups_json . "' ");
					}
				}
				
				$location="uploaded_questionnaire/".$clientId."/";
				if (!file_exists($location)) {
					if (!mkdir($location, 0777, true)) {
						//$error='Somthing Went wrong !!';
					}else{
						//echo "create";
						mkdir($location, 0777, true);
					}
				}
			
				move_uploaded_file($tmp_name,$location.$file_name);
				$sequence_no = $sequence_no+1;
				$question_sql ="insert into questions set question_name='Your survey is complete. Do you want to submit?',question_description='Desc',questions_type_id='16',repeat_count='',question_input_type_id='',max_input='0',screen_no='1',input_field_type='note',read_only='',calculation='',ref_table='',group_id='0',sequence_no='".$sequence_no."',field_name='submit',validation_id='0',title='Submit',category_name='',parameters='',survey_id='".$inserted_form_id."', relevant='',constraints='',required='', constraint_msg='' , choice_filter='' , appearance='', choice_relation='',default_response='', paradata='', normal_group_id='', unique_id='', preserve='' ";
				$insertdata = mysqli_query($conn,$question_sql);
				 $last_id=mysqli_insert_id($conn);
				$question_id=$last_id;
			   // $question_language_sql ="insert into questions_language set language_id='".$language_id."', question_id='".$question_id."' ,question_name='Your survey is complete. Do you want to submit?',question_description='Desc',questions_type_id='16',repeat_count='',question_input_type_id='',max_input='0',screen_no='1',input_field_type='note',read_only='',calculation='',ref_table='',group_id='0',sequence_no='".$sequence_no."',field_name='submit',validation_id='0',title='Submit',category_name='',parameters='',survey_id='".$inserted_form_id."', relevant='',constraints='',required='', constraint_msg='' , choice_filter='' , appearance='', choice_relation='',default_response='', paradata='', normal_group_id='', unique_id='', preserve='' ";
				$question_language_sql ="insert into questions_language set language_id='1', question_id='".$question_id."' ,question_name='Your survey is complete. Do you want to submit?',question_description='Desc',questions_type_id='16',repeat_count='',question_input_type_id='',max_input='0',screen_no='1',input_field_type='note',read_only='',calculation='',ref_table='',group_id='0',sequence_no='".$sequence_no."',field_name='submit',validation_id='0',title='Submit',category_name='',parameters='',survey_id='".$inserted_form_id."', relevant='',constraints='',required='', constraint_msg='' , choice_filter='' , appearance='', choice_relation='',default_response='', paradata='', normal_group_id='', unique_id='', preserve='' ";
				mysqli_query($conn,$question_language_sql);
			
				if(count($allLanguages)>0){
					$allLanguages = array_unique($allLanguages);
					foreach($allLanguages as $allLanguage){
						$question_language_sql ="insert into questions_language set language_id='".$allLanguage."', question_id='".$question_id."' ,question_name='Your survey is complete. Do you want to submit?',question_description='Desc',questions_type_id='16',repeat_count='',question_input_type_id='',max_input='0',screen_no='1',input_field_type='note',read_only='',calculation='',ref_table='',group_id='0',sequence_no='".$sequence_no."',field_name='submit',validation_id='0',title='Submit',category_name='',parameters='',survey_id='".$inserted_form_id."', relevant='',constraints='',required='', constraint_msg='' , choice_filter='' , appearance='', choice_relation='',default_response='', paradata='', normal_group_id='', unique_id='', preserve='' ";
						mysqli_query($conn,$question_language_sql);
					}
				}
				$result = array("status"=>1,"msg"=>"Success");
				echo json_encode($result);
			}
			else{
				$result = array("status"=>1,"msg"=>"Success");
				echo json_encode($result);
			}
			
		}	
	}
	/*else{
		$result = array("status"=>0,"msg"=>"Something went wrong");
		echo json_encode($result);
	} */
?>