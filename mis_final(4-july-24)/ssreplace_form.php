<?php include_once('includes/config.php'); ?>
<?php define("title","Replace Form | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>

<!--main content start-->
<style>
    .panel-heading {
    background: #394a59;
    color: white;
    font-weight: unset;
	}
	.btn:not(:disabled):not(.disabled) {
		cursor: pointer;
	}
	.add-button-bg a {
		position: fixed;
		bottom: 54px;
		right: 50px;
		background: rgb(57,74,89);
		z-index: 99999;
		border-radius: 50%;
		width: 60px;
		height: 60px;
		color: #fff;
		line-height: 46px;
		font-size: 22px;
		transition: all .3s ease-in-out;
	}
	.add-button-bg a:hover{
		background: rgb(4 39 60);
		color: #ffffff;
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
		box-shadow: 1px 1px 1px 17px rgb(255 192 192 / 28%);
		
	}
	
	.sm_value1 {
		padding: 3px;
		color: #ffffff;
		border-radius: 5px;
		min-width: 45px;
		text-align: center;
		font-size: 20px;
		font-weight: 700;
		background: #033d66;
		width: 20px;
	}
</style>
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="icon_documents_alt"></i>Form </li> 
                    <li><i class="fa fa-wpforms"></i>Replace Form </li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <?php
            if(isset($_POST['add_survey'])){
				
				
				$digits = 10;
				$unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
				$survey_id = $_REQUEST['survey_id'];
                //$file_name = $_FILES['structure']['name'];
                $filename = $_FILES['structure']['name'];
                $tmp_name = $_FILES['structure']['tmp_name'];
				$clientid=$_SESSION['client_id'];
				$clientId="C".$clientid;
				
				
                /** Include path **/
					$language_id="1";
					$inserted_form_id = $survey_id; 
					
					$getSurvey=mysqli_query($conn,"select id,form_version,survey_name from survey where id='".$inserted_form_id."'");
					$datasvirson=mysqli_fetch_object($getSurvey);
					
					$survey_name=$datasvirson->survey_name;
					$form_version=$datasvirson->form_version;
					$fvid=substr($form_version,1);
					$fverid=$fvid+1;
					$formversion="V".$fverid;
					
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$surveyName=str_replace(' ','-',$survey_name);
					$file_name = $surveyName."-".$unique_id.".".$ext;
					$updated_at=currentTimeStamp();
                    
					$updateForm = mysqli_query($conn,"UPDATE survey SET questinnour_file='".$file_name."',unique_id='".$unique_id."',status='0',form_version='".$formversion."',updated_at='".$updated_at."' where id='".$inserted_form_id."' ");
				
					mysqli_query($conn,"DELETE From options where survey_id='".$inserted_form_id."'  ");
					mysqli_query($conn,"DELETE From options_language where survey_id='".$inserted_form_id."'  ");
					
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
            
                                    elseif($type=='camera')
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
                                    $field_name = trim(safe_var($conn,$rowvalue['name']));
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
										//if($type=='begin_group'){
										//if($type=='begin_group' && $relevant!=""){
											
											// mysqli_query($conn,"INSERT INTO normal_groups SET group_name='".$field_name."', conditions='".$relevant."', survey_id='".$inserted_form_id."'  ");
											// $insertnormalgroupid = mysqli_insert_id($conn);
											// $ngrupArr[] = $insertnormalgroupid;
										//}
										
										//if($type=='end_group'){
											// array_pop($ngrupArr);
										//}
										// $insert_normal_group_id = implode(",", $ngrupArr);
										
										
										$quest_labelarray=array();
										$getLaguages = mysqli_query($conn,"SELECT language_id,language_name,language_code FROM languages WHERE status='0' order by language_id asc");
											while($language_master = mysqli_fetch_object($getLaguages)){
												$languagehint= 'label::'.$language_master->language_code;
												
												$langhints=$rowvalue[$languagehint];
												if($langhints!='')
												{
												//$language_id=$language_master->language_id;	
												$lang_hintparam= 'hint::'.$language_master->language_code;
												$lang_labelparam= 'label::'.$language_master->language_code;
												$lang_constraint_messageparam= 'constraint_message::'.$language_master->language_code;												
												$quest_labelarray['question_name'][]= $rowvalue[$lang_labelparam]; 
												$quest_labelarray['question_description'][]= $rowvalue[$lang_hintparam];
												$quest_labelarray['constraint_message'][]= $rowvalue[$lang_constraint_messageparam];
												$quest_labelarray['language_id'][]= $language_master->language_id;
												}
												
											}
										
										
										
										if($language_id=="1"){
											
											
											//input_field_type
											//$type
											$question_sql ="update questions set question_name='".$question_name."',question_description='".$question_description."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."' ,validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',parameters='".$parameters."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', unique_id='".$unique_id."', preserve='".$preserve."',lookups='".$lookups."', media_file='".$media_file."' where survey_id='".$inserted_form_id."' and field_name='".$field_name."' ";
											
											$insertdata = mysqli_query($conn,$question_sql);
											// $last_id=mysqli_insert_id($conn);
											// $question_id=$last_id;
											$question_language_sql ="update questions_language set question_name='".$question_name."',question_description='".$question_description."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',ref_table='".$groupid."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."' ,relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' where survey_id='".$inserted_form_id."' and field_name='".$field_name."' and language_id='1' ";
											mysqli_query($conn,$question_language_sql);
											
											$group_id=0; 
											
											/* if($type=="begin_repeat"){
												$qid_insert=$last_id-1;//"11";
												$field_nameBgn = $field_name;
												$insertGroup = mysqli_query($conn,"INSERT INTO questions_group SET group_name='".$field_name."', survey_id='".$inserted_form_id."',group_type='group', client_id='".$client_id."' ");
												$insert_group_id = mysqli_insert_id($conn);
												$update = mysqli_query($conn,"UPDATE questions SET repeated='start',repeat_count='".$field_nameBgn."', group_id='".$insert_group_id."' WHERE question_id='".$qid_insert."' ");
												
												//$txt = "Raju";
												$reptno=(int)$repeat_count;
												if(is_int($reptno) && $reptno>0){
													$update = mysqli_query($conn,"UPDATE questions_language SET repeated='".$insert_group_id."',repeat_count='".$repeat_count."' WHERE question_id='".$qid_insert."' ");
												}else{
													$update = mysqli_query($conn,"UPDATE questions_language SET repeated='".$insert_group_id."',repeat_count='".$field_nameBgn."' WHERE question_id='".$qid_insert."' ");
												}
											}
											if($type=="end_repeat"){
												$qid_insert="";
											}
											if($qid_insert!=""){
												$group_id=$insert_group_id;
												$update = mysqli_query($conn,"UPDATE questions SET repeated='".$qid_insert."',repeat_count='".$field_nameBgn."',group_id='".$insert_group_id."' WHERE question_id='".$last_id."' ");
												$update = mysqli_query($conn,"UPDATE questions_language SET group_id='".$insert_group_id."',repeated='".$qid_insert."' WHERE question_id='".$last_id."' ");
											} */
											
											
											// START TIMELINE
											/* $qid_insertt_check = '';
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
											$qid_insertt_check=''; */
											//END TIMELINE
											
											
											// START SCREEN GROUP
											/* $qid_insertt_checks = '';
											if($type=="begin_group" && $appearance=="fieldlist"){
												$endfset="end";
												$qid_insertts=$last_id-1;//"11";
												$qid_insertt_checks=1;
												$field_nameScreen = $field_name;
												$insertGroups = mysqli_query($conn,"INSERT INTO questions_group SET group_name='".$field_name."', survey_id='".$inserted_form_id."',group_type='screen', client_id='".$client_id."' ");
												$insert_group_ids = mysqli_insert_id($conn);
												$update = mysqli_query($conn,"UPDATE questions SET screened='start',screened_count='".$field_nameScreen."' WHERE question_id='".$qid_insertts."' ");
												
												//$txt = "Raju";
												$update = mysqli_query($conn,"UPDATE questions_language SET screened='".$insert_group_ids."',screened_count='screen' WHERE question_id='".$qid_insertts."' ");
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
											$qid_insertt_checks=''; */
											//END SCREEN GROUP
											
											$report_format_sql ="update report_format set title='".$question_name."',seq_no='".$sequence_no."',rm_report='".$field_name."' where survey_id='".$inserted_form_id."' and field_lable='".$field_name."' ";
											$report_format = mysqli_query($conn,$report_format_sql);
											
										}else{
											
											// $getQuestionId = mysqli_query($conn,"SELECT question_id FROM questions WHERE survey_id='".$survey_id."' AND field_name='".$field_name."' ");
											// $questionId = mysqli_fetch_object($getQuestionId);
											// $question_id = $questionId->question_id;
											
											// $question_language_sql ="insert into questions_language set language_id='".$language_id."',question_id='".$question_id."',question_name='".$question_name."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', normal_group_id='".$insert_normal_group_id."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' ";
											// mysqli_query($conn,$question_language_sql);
										}
											$survey_id = $inserted_form_id;
										   if(count($quest_labelarray)>0)
										   {
											$getQuestionId1 = mysqli_query($conn,"SELECT question_id,group_id FROM questions WHERE survey_id='".$survey_id."' AND field_name='".$field_name."'");
											$questionId1 = mysqli_fetch_object($getQuestionId1);
											$group_idhi = $questionId1->group_id;
											$question_id = $questionId1->question_id;
											for($qns=0; $qns<count($quest_labelarray['language_id']); $qns++ )
											{
											$language_ids=$quest_labelarray['language_id'][$qns];
                                            $question_name=$quest_labelarray['question_name'][$qns];
                                            $question_description=$quest_labelarray['question_description'][$qns];
                                            $constraint_message=$quest_labelarray['constraint_message'][$qns];											
											$question_language_sql ="update questions_language set question_name='".$question_name."',question_description='".$question_description."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',ref_table='".$groupid."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' where language_id='".$language_ids."' and question_id='".$question_id."' and survey_id='".$inserted_form_id."' and field_name='".$field_name."' ";
											mysqli_query($conn,$question_language_sql);
										    }
										   }
                                        $sequence_no=$sequence_no+1;
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
                                    $option_label = $rowvalue['label'];
									$option_media_file = $rowvalue['media_file'];
									$constraint = $rowvalue['constraint'];
									

                                    // $option_label_hindi = $rowvalue['label::hindi'];
                                    // $option_label_marathi = $rowvalue['label::marathi'];
                                    // $option_label_Bengali = $rowvalue['label:Bengali'];
                                    // $option_label_Gujarati = $rowvalue['label:Gujarati'];

                                    if($option_label!=''){
                                        $getQuestion = mysqli_query($conn,"SELECT question_id FROM questions WHERE choice_relation='".$option_category_name."' and survey_id='".$inserted_form_id."'");
                                        //// Language////
										$option_labelarray=array();
									       $getLaguagesopt = mysqli_query($conn,"SELECT language_id,language_name,language_code FROM languages WHERE status='0' order by language_id asc");
											while($language_masteropt = mysqli_fetch_object($getLaguagesopt)){
												$languagehint1= 'label::'.$language_masteropt->language_code;
												$langhints1=$rowvalue[$languagehint1];
												if($langhints1!='')
												{
												$lang_labelparam= 'label::'.$language_masteropt->language_code;										
												$option_labelarray['option_label'][]= $rowvalue[$lang_labelparam];
												$option_labelarray['language_id'][]= $language_masteropt->language_id;
												}
											}
										
										///// Language//////
                                        //print_r($question_id);
										if($language_id=="1"){
											while($question_data = mysqli_fetch_array($getQuestion)){
												$question_id = $question_data['question_id'];
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
                                                $language_ids=$option_labelarray['language_id'][$ins];											
												$option_language_sql = "INSERT INTO options_language SET option_id='".$option_id."', option_constraint='".$constraint."', language_id='".$language_ids."',option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."',survey_id='".$survey_id."', media_file='".$option_media_file."' ";
												mysqli_query($conn,$option_language_sql);
											    }
												//$option_language_sql = "INSERT INTO options_language SET option_id='".$option_id."',language_id='".$language_id."',option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."'";
												//mysqli_query($conn,$option_language_sql);
											}
                                        	
										}
                                        $serial_no_for_app++;
                                    }
                                }
                            }
                        } 
						
						//LOOCKUPS
						/* if($sheetSno=='2'){
                            //$lookupsArr["lookup"] = "fsu";
                            $survey_id=$inserted_form_id;
                            for ($row = 2; $row <= $highestRow; $row++) {
                                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                                $rowData[0] = array_combine($headings[0], $rowData[0]);
                                foreach ($rowData as $keylkup => $rowvalue) {
                                    // print_r($rowvalue);
                                    $l=0;
                                    foreach($rowvalue as $lkey=>$lookupVal){
                                        // echo $lkey."===".$lookupVal;
                                        // echo "<br>";
                                        if($l==0){
                                            $fsudatas[$lkey] = $lookupVal;                                        }

                                        $fsu_data[$lkey] = $lookupVal;
                                        $l++;
                                    }
                                    $fsudatas["data"][] = $fsu_data;
                                    $fsu_data = array();
                                    $fsudata[] = $fsudatas;
                                    $fsudatas = array();
                                }
                            }
                            //$fsudataArr["fsu"] = $fsudata;
							$fsudataArr[$lookups_arr_name] = $fsudata;
                            $fsudataArrArr["lookups"][] = $fsudataArr;
                            // echo "<pre>";
                            // print_r($fsudataArrArr);
                            $lookups_json = json_encode($fsudataArrArr);

                            mysqli_query($conn,"INSERT INTO survey_loockups SET survey_id='".$survey_id."', loockup_data='".$lookups_json."' ");

                        } */
						
                    }
                
				$location="uploaded_questionnaire/".$clientId."/";
			//	$location="uploaded_questionnaire/";
				move_uploaded_file($tmp_name,$location.$file_name);
               /* $sequence_no = $sequence_no+1;
                $question_sql ="insert into questions set question_name='Your survey is complete. Do you want to submit?',question_description='Desc',questions_type_id='16',repeat_count='',question_input_type_id='',max_input='0',screen_no='1',input_field_type='note',read_only='',calculation='',ref_table='',group_id='0',sequence_no='".$sequence_no."',field_name='submit',validation_id='0',title='Submit',category_name='',parameters='',survey_id='".$inserted_form_id."', relevant='',constraints='',required='', constraint_msg='' , choice_filter='' , appearance='', choice_relation='',default_response='', paradata='', normal_group_id='', unique_id='', preserve='' ";
                $insertdata = mysqli_query($conn,$question_sql);
                $last_id=mysqli_insert_id($conn);
                $question_id=$last_id;
               // $question_language_sql ="insert into questions_language set language_id='".$language_id."', question_id='".$question_id."' ,question_name='Your survey is complete. Do you want to submit?',question_description='Desc',questions_type_id='16',repeat_count='',question_input_type_id='',max_input='0',screen_no='1',input_field_type='note',read_only='',calculation='',ref_table='',group_id='0',sequence_no='".$sequence_no."',field_name='submit',validation_id='0',title='Submit',category_name='',parameters='',survey_id='".$inserted_form_id."', relevant='',constraints='',required='', constraint_msg='' , choice_filter='' , appearance='', choice_relation='',default_response='', paradata='', normal_group_id='', unique_id='', preserve='' ";
                $question_language_sql ="insert into questions_language set language_id='1', question_id='".$question_id."' ,question_name='Your survey is complete. Do you want to submit?',question_description='Desc',questions_type_id='16',repeat_count='',question_input_type_id='',max_input='0',screen_no='1',input_field_type='note',read_only='',calculation='',ref_table='',group_id='0',sequence_no='".$sequence_no."',field_name='submit',validation_id='0',title='Submit',category_name='',parameters='',survey_id='".$inserted_form_id."', relevant='',constraints='',required='', constraint_msg='' , choice_filter='' , appearance='', choice_relation='',default_response='', paradata='', normal_group_id='', unique_id='', preserve='' ";
                mysqli_query($conn,$question_language_sql); */
 
			   $_SESSION['status'] = "Form has been replaced successfully";
			   $_SESSION['status_code'] = "success";
                echo "<script>window.location.href='survey-list.php';</script>";
            }
            ?>
		 
        <div class="row">
            <div class="col-lg-12">
            	<section class="panel">
                    <div class="panel-body">
                    	<div class="row">
                        	<div class="col-lg-12" style="min-height:420px;">
                            	<header class="panel-heading">Replace Form</header>
                                <section class="panel">
                                    <div class="panel-body">
                                        <div class="form">
                                            <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                                                
                                                <div class="form-group " id="survey_name">
                                                    <label for="cname" class="control-label col-lg-2">Select File: <span style="color:red;">*</span></label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control tooltips" name="structure" id="fileUpload" required accept=".xlsx,.xls," data-placement="top" data-toggle="tooltip"  data-original-title="Are you sure you want to replace the form"  minlength="5" type="file" />
													<span id="file_error" style="color:red;"></span>	
												  </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                    <div class="col-lg-offset-2 col-lg-10">
														<button class="btn btn-secondary" id="btnUpload" type="submit" name="add_survey" id="add_survey" >Submit</button>
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
 <?php if(isset($_SESSION['status']) && $_SESSION['status']!=''){ ?>
<script>
	swal.fire({
		title: "<?php echo $_SESSION['status'];?>",
		icon:"<?php echo $_SESSION['status_code']; ?>",
		confirmButtonColor: '#449A97',
		confirmButtonText: 'Ok'
	});
</script>
<?php unset($_SESSION['status']);}  ?>
<script>
 //Data lookups file upload
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