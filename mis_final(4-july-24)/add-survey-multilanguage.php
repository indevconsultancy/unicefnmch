<?php include_once('includes/config.php'); ?>
<?php define("title","Add Form Multilanguage | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="index.html">Home</a></li>
                    <li><i class="icon_documents_alt"></i>Form</li>
                    <li><i class="fa fa-plus"></i>Add Form Multilanguage</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <?php
            if(isset($_POST['add_survey'])){
            
                $survey_name = $_POST['survey_name'];
				$client_id = $_POST['client_id'];
				
				if($survey_name!=""){
					$language_id = $_POST['language_id_english'];
				}else{
					$language_id = $_POST['language_id'];
				}
				
				$survey_id = $_POST['survey_id'];
                $file_name = $_FILES['structure']['name'];
                $tmp_name = $_FILES['structure']['tmp_name'];
				if($survey_id!=''){
					$inserted_form_id = $survey_id;
				}else{
					$insertForm = mysqli_query($conn,"INSERT INTO survey SET survey_name='".$survey_name."',user_id='".$_SESSION['user_id']."', client_id='".$client_id."' ");
					$inserted_form_id = mysqli_insert_id($conn);
				}
				
				//$languageArr=array();
				
				

                /** Include path **/
                $file_check = $_POST['file_check'];
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
                            

                            for($row = 2; $row<= $highestRow; $row++) 
						{
                                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                                $rowData[0] = array_combine($headings[0], $rowData[0]);
                                $pos='';
                                $sqltext='test';
                                $questions_type_id=0;
                                $question_input_type_id=0;
                                $validation_id=0;
                                $groupid=0;
                                foreach ($rowData as $key => $rowvalue) {
                                    $language_id=0;
                                    $category='';
                                    $type = safe_var($conn,$rowvalue['type']);
									$type = strtolower($type);
            //          $input_type = safe_var($conn,$rowvalue['input_type']);
            
                                    /*if($input_type='Date'){
                                        $question_input_type_id=4;
                                    }elseif($input_type='Integer'){
                                        $question_input_type_id=2;
                                    }else{*/
                                        $question_input_type_id=1;
                                    //}
            
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
                                    elseif($type=='begin_group')
                                    {
                                        $questions_type_id=11;
                                    }
                                    elseif($type=='end_group')
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
                                    $question_description = safe_var($conn,$rowvalue['hint']);
									$language_id=1;
                                    $relevant = safe_var($conn,$rowvalue['relevant']);
                                    $constraints = safe_var($conn,$rowvalue['constraint']);
                                    $constraint_message = safe_var($conn,$rowvalue['constraint_message']);
                                    $parameters = safe_var($conn,$rowvalue['parameters']);
                                    $read_only = safe_var($conn,$rowvalue['read_only']);
                                    $calculation = safe_var($conn,$rowvalue['calculation']);
                                    $required = strtolower(safe_var($conn,$rowvalue['required']));
                                    $limit = $rowvalue['limit']?:"0";//safe_var($conn,$rowvalue['limit']);
                                    $repeat_count = safe_var($conn,$rowvalue['repeat_count']);
                                    $appearance = safe_var($conn,$rowvalue['appearance']);
                                    $choice_filter = safe_var($conn,$rowvalue['choice_filter']);
                                    $choice_relation = safe_var($conn,$rowvalue['choice_relation']);
                                    $default_response = safe_var($conn,$rowvalue['default_response']);
                                    
                                    
                                    //HINDI LANGUAGE
                                    // $label_hindi = safe_var($conn,$rowvalue['label::hindi']); 
                                    // $hint_hindi = safe_var($conn,$rowvalue['hint::hindi']);
                                    // $constraint_message_hindi = safe_var($conn,$rowvalue['constraint_message::hindi']);
 
                                    //MARATHI LANGUAGE
                                    // $label_marathi = safe_var($conn,$rowvalue['label::marathi']); 
                                    // $hint_marathi = safe_var($conn,$rowvalue['hint::marathi']);
                                    // $constraint_message_marathi = safe_var($conn,$rowvalue['constraint_message::marathi']);

                                    //BENGALI LANGUAGE
                                    // $label_bengali = safe_var($conn,$rowvalue['label::bengali']); 
                                    // $hint_bengali = safe_var($conn,$rowvalue['hint::bengali']);
                                    // $constraint_message_bengali = safe_var($conn,$rowvalue['constraint_message::bengali']);

                                    //GUJARATI LANGUAGE
                                    // $label_Gujarati = safe_var($conn,$rowvalue['label::Gujarati']); 
                                    // $hint_Gujarati = safe_var($conn,$rowvalue['hint::Gujarati']);
                                    // $constraint_message_Gujarati = safe_var($conn,$rowvalue['constraint_message::Gujarati']);
                                    
                                    
                                    // print_r($rowvalue);
                                    // die;
                                   
                                    $category=$field_name;
                                    //if ($type == '74108520963'){
                                    if ($type != ''){
										$quest_labelarray=array();
										$getLaguages = mysqli_query($conn,"SELECT language_id,language_name,language_code FROM languages WHERE status='0' order by language_id asc");
											while($language_master = mysqli_fetch_object($getLaguages)){
												$languagehint= 'label::'.$language_master->language_code;
												
												$langhints=$rowvalue[$languagehint];
												if($langhints!='')
												{
												$language_id=$language_master->language_id;	
												$lang_hintparam= 'hint::'.$language_master->language_code;
												$lang_labelparam= 'label::'.$language_master->language_code;
												$lang_constraint_messageparam= 'constraint_message::'.$language_master->language_code;	
                                                $option_labelarray['option_label'][]=												
												$quest_labelarray['question_name'][]= $rowvalue[$lang_labelparam]; 
												$quest_labelarray['question_description'][]= $rowvalue[$lang_hintparam];
												$quest_labelarray['constraint_message'][]= $rowvalue[$lang_constraint_messageparam];
												$quest_labelarray['language_id'][]= $language_master->language_id;
												}
													
											}
										
										if($language_id=="1"){
											
											
											//input_field_type
											//$type
											$question_sql ="insert into questions set question_name='".$question_name."',question_description='".$question_description."',questions_type_id='".$questions_type_id."',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."'";
											$insertdata = mysqli_query($conn,$question_sql);
											$last_id=mysqli_insert_id($conn);
											
											$question_language_sql ="insert into questions_language set language_id='1',question_id='".$last_id."',question_name='".$question_name."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."'";
											mysqli_query($conn,$question_language_sql);
											
											$group_id=0;
											
											if($type=="begin_repeat"){
												$qid_insert=$last_id-1;//"11";
												$field_nameBgn = $field_name;
												$insertGroup = mysqli_query($conn,"INSERT INTO questions_group SET group_name='".$field_name."', survey_id='".$inserted_form_id."', client_id='".$client_id."' ");
												$insert_group_id = mysqli_insert_id($conn);
												$update = mysqli_query($conn,"UPDATE questions SET repeated='start',repeat_count='".$field_nameBgn."', group_id='".$insert_group_id."' WHERE question_id='".$qid_insert."' ");
												$update = mysqli_query($conn,"UPDATE questions_language SET repeated='".$insert_group_id."',repeat_count='".$field_nameBgn."' WHERE question_id='".$qid_insert."' ");
											}
											if($type=="end_repeat"){
												$qid_insert="";
											}
											if($qid_insert!=""){
												$group_id=$insert_group_id;
												$update = mysqli_query($conn,"UPDATE questions SET repeated='".$qid_insert."',repeat_count='".$field_nameBgn."',group_id='".$insert_group_id."' WHERE question_id='".$last_id."' ");
												$update = mysqli_query($conn,"UPDATE questions_language SET group_id='".$insert_group_id."',repeated='".$qid_insert."' WHERE question_id='".$last_id."' ");
												
											}
											
											$report_format_sql ="insert into report_format set title='".$question_name."',seq_no='".$sequence_no."',field_lable='".$field_name."',rm_report='".$field_name."',survey_id='".$inserted_form_id."', client_id='".$client_id."',group_id='".$group_id."' ";
											$report_format = mysqli_query($conn,$report_format_sql);
											
											
											// $question_sql ="insert into questions set question_name='".$question_name."',question_description='".$question_description."',questions_type_id='".$questions_type_id."',repeat_count='".$repeat_count."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."',screen_no='".$screen_no."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',parameters='".$parameters."',survey_id='".$inserted_form_id."', relevant='".$relevant."',constraints='".$constraints."',required='".$required."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."'";
											// $insertdata = mysqli_query($conn,$question_sql);
											// $last_id=mysqli_insert_id($conn);
											// $question_language_sql ="insert into questions_language set language_id='1',question_id='".$last_id."',question_name='".$question_name."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."'";
											// mysqli_query($conn,$question_language_sql);
										}else{
											$getQuestionId = mysqli_query($conn,"SELECT question_id FROM questions WHERE survey_id='".$survey_id."' AND field_name='".$field_name."' ");
											$questionId = mysqli_fetch_object($getQuestionId);
											$question_id = $questionId->question_id;
											for($qns=0; $qns<count($quest_labelarray['language_id']); $qns++ )
											{
											$language_id=$quest_labelarray['language_id'][$qns];
                                            $question_name=$quest_labelarray['question_name'][$qns];
                                            $question_description=$quest_labelarray['question_description'][$qns];
                                            $constraint_message=$quest_labelarray['constraint_message'][$qns];											
											$question_language_sql ="insert into questions_language set language_id='".$language_id."',question_id='".$question_id."',question_name='".$question_name."',question_description='".$question_description."',repeat_count='".$repeat_count."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',input_field_type='".$type."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',screen_no='".$screen_no."',ref_table='".$groupid."',group_id='0',sequence_no='".$sequence_no."',field_name='".$field_name."',validation_id='".$validation_id."',title='".$field_name."',category_name='".$category."',survey_id='".$inserted_form_id."', relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraints."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."'";
											mysqli_query($conn,$question_language_sql);
										    }
										}
                                        $sequence_no=$sequence_no+1;
                                    }
                                    // }
                                }
                            }
                        }
						
						//die();
                        //OPTIONS
                        if($sheetSno=='1'){
                            // echo $sheetSno;
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
                                    $option_label = isset($rowvalue['label']) ? safe_var($conn,$rowvalue['label']) : safe_var($conn,$rowvalue['label::English']);
                                     
                                    // $option_label_hindi = $rowvalue['label::hindi'];
                                    // $option_label_marathi = $rowvalue['label::marathi'];
                                    // $option_label_Bengali = $rowvalue['label:Bengali'];
                                    // $option_label_Gujarati = $rowvalue['label:Gujarati'];

                                    if($option_label!=''){
                                        $getQuestion = mysqli_query($conn,"SELECT question_id FROM questions WHERE choice_relation='".$option_category_name."' and survey_id='".$inserted_form_id."'");
                                           $option_labelarray=array();
									       $getLaguagesopt = mysqli_query($conn,"SELECT language_id,language_name,language_code FROM languages WHERE status='0' order by language_id asc");
											while($language_masteropt = mysqli_fetch_object($getLaguagesopt)){
												$languagehint= 'label::'.$language_masteropt->language_code;
												
												$langhints=$rowvalue[$languagehint];
												if($langhints!='')
												{
												$language_id= $language_masteropt->language_id;	
												$lang_labelparam= 'label::'.$language_masteropt->language_code;										
												$option_labelarray['option_label'][]= $rowvalue[$lang_labelparam];
												$option_labelarray['language_id'][]= $language_masteropt->language_id;
												}
											}												
                                        //print_r($question_id);
										if($language_id=="1"){
											while($question_data = mysqli_fetch_array($getQuestion)){
												$question_id = $question_data['question_id'];
												$option_sql = "INSERT INTO options SET option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."' ";
												mysqli_query($conn,$option_sql);
												$last_id1=mysqli_insert_id($conn);
												
												$option_language_sql = "INSERT INTO options_language SET option_id='".$last_id1."',language_id='1',option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."'";
												mysqli_query($conn,$option_language_sql);
											}
											
										}else{
											while($question_data = mysqli_fetch_array($getQuestion)){
												$question_id = $question_data['question_id'];
												$getOptionIds = mysqli_query($conn,"SELECT option_id FROM options WHERE question_id='".$question_id."' AND category_name='".$option_category_name."' AND option_sequence='".$option_value."' ");
												$getOptionId = mysqli_fetch_object($getOptionIds);
												$option_id = $getOptionId->option_id;
												for($ins=0; $ins<count($optionparam['option_label']); $ins++)
												{
												$option_label=$optionparam['option_label'][$ins];
                                                $language_id=$optionparam['language_id'][$ins];												
												$option_language_sql = "INSERT INTO options_language SET option_id='".$option_id."',language_id='".$language_id."',option_name='".$option_label."',question_id='".$question_id."',serial_no_for_app='".$serial_no_for_app."',option_sequence='".$option_value."',category_name='".$option_category_name."',choice_filter_parent='".$choice_filter_parent."'";
												mysqli_query($conn,$option_language_sql);
											    }
											}
											
										}

                                        $serial_no_for_app++;
                                    }
                                }
                            }
                        }
                    }
                }
                echo "<script>window.location.href='survey-list.php';</script>";
            }
            ?>
        <div class="row">
            <div class="col-lg-12" style="min-height: 420px;">
                <section class="panel">
                    <header class="panel-heading">
                        Add Survey
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="form-group ">
                                    <label for="cname" class="control-label col-lg-2">Survey Type <span  class="required">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                id="exampleRadios1" onclick="manage_survey_form(this.value)" value="0" >
                                            <label class="form-check-label"  for="exampleRadios1">
                                            Existing Survey
                                            </label>
                                            &nbsp;
                                            <input class="form-check-input" type="radio" name="type"
                                                id="exampleRadios1" onclick="manage_survey_form(this.value)" value="1" >
                                            <label class="form-check-label" for="exampleRadios1">
                                            New Survey
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    let manage_survey_form = (value) => {
                                        let survey_name = document.getElementById("survey_name");
                                        let survey_select = document.getElementById("survey_select");
                                        if (value == 1){
                                            survey_name.style.display = 'block';
                                            survey_select.style.display = 'none';
											//survey_name_txt.attributes.required = "required";
											//survey_select_txt.attributes.required = "";
											document.getElementById("survey_name_txt").required = true;
											document.getElementById("survey_select_txt").required = false;
											document.getElementById("language_id_txt").required = false;
                                        }
                                        if (value == 0){
                                            survey_name.style.display = 'none';
                                            survey_select.style.display = 'block';
											// survey_name_txt.attributes.required = "";
											// survey_select_txt.attributes.required = "required"; 
											document.getElementById("survey_name_txt").required = false;
											document.getElementById("survey_select_txt").required = true;
											document.getElementById("language_id_txt").required = true;
                                        }
                                    }
                                </script>
                                <div class="form-group " id="survey_name" style="display: none;">
                                    <label for="cname" class="control-label col-lg-2">Survey Name <span
                                        class="required">*</span></label>
                                    <div class="col-lg-10">
                                        <input class="form-control" name="survey_name" id="survey_name_txt" type="text"/>
                                    </div>
									<br><br>
									<label for="cname" class="control-label col-lg-2">Select Language<span class="required">*</span></label>
									<div class="col-lg-10">
										<select class="form-control" name="language_id_english" >
											<option value="1" selected >English</option>
										</select>
									</div>
                                </div>
                                <div class="form-group " id="survey_select" style="display: none;">
                                    <label for="cname" class="control-label col-lg-2">Select Survey<span
                                        class="required">*</span></label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="survey_id" id="survey_select_txt">
                                            <option value="">Select Survey</option>
                                            <?php
                                                $sql = "SELECT * FROM survey where del_action = 'N'";
                                                $result = mysqli_query($conn, $sql);
                                                while ($survey = mysqli_fetch_array($result)){
                                                    ?>
                                            <option value="<?=$survey['id'];?>"><?=$survey['survey_name'];?></option>
                                            <?php
                                                }
                                                ?>
                                        </select>
                                    </div>
									<br><br>
									<label for="cname" class="control-label col-lg-2">Select Language<span class="required">*</span></label>
									<div class="col-lg-10">
										<select class="form-control" name="language_id" id="language_id_txt" >
											<option value="">Select Language</option>
											<?php 
												$getLanguages = mysqli_query($conn,"SELECT language_id, language_name FROM languages WHERE status='0'  ORDER BY language_name");
												while($language = mysqli_fetch_object($getLanguages)){ ?>
													<option value="<?=$language->language_id;?>"><?=$language->language_name;?></option>
												<?php	
												}
											?>
										</select>
									</div>
                                </div>
                                <div class="form-group ">
                                    <label for="cname" class="control-label col-lg-2">
                                        <!--<span
                                            class="required">*</span>-->
                                    </label>
                                    <div class="col-lg-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="file_check"
                                                id="file_check" onclick="upload_file(this.value)"  value="0">
                                            <label class="form-check-label"  for="exampleRadios1">
                                            Upload From File
                                            </label>
                                        </div>
                                        <script>
                                            let upload_file = (value) => {
                                            
                                                let upload = document.getElementById('upload_structure');
                                            
                                                console.log(value);
                                            
                                                if (value == '0'){
                                            
                                                    upload.style.display = 'block';
                                            
                                                    document.getElementById('file_check').value = 1;
                                            
                                                }
                                            
                                                if (value == '1'){
                                            
                                                    upload.style.display = 'none';
                                            
                                                    document.getElementById('file_check').value = 0;
                                            
                                                }
                                            
                                            }
                                            
                                        </script>
                                    </div>
                                </div>
                                <div class="form-group " style="display: none;" id="upload_structure">
                                    <label for="cname" class="control-label col-lg-2">Upload Structure <span class="required">*</span></label>
                                    <div class="col-lg-10">
                                        <input class="form-control"  name="structure" accept=".xlsx,.xls,"  minlength="5" type="file" />
                                    </div>
                                </div>
								
								<div class="form-group " >
									<label for="cname" class="control-label col-lg-2">client_id <span
                                        class="required">*</span></label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="client_id">
											<option value="">Select Client</option>
											<?php
												$getClients = mysqli_query($conn,"SELECT id,name FROM clients WHERE del_action='N' AND role_id='3'");
												while($client = mysqli_fetch_object($getClients)){ ?>
													<option value="<?=$client->id;?>"><?=$client->name;?></option>
												<?php	
												}
											?>
										</select>
                                    </div>
								</div>
								
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-primary" type="submit" name="add_survey">Save</button>
                                        <button class="btn btn-default" type="button">Cancel</button>
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
<!--main content end-->
<?php include_once('includes/footer.php'); ?>