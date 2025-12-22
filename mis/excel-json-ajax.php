<?php
										
											//VIEW EXCEL
											// ini_set('display_errors', 1);
											// ini_set('display_startup_errors', 1);
											// error_reporting(E_ALL);
											
											
										// $sqlques1=mysqli_query($conn,"SELECT survey_id FROM `questionnaires` where survey_id='".$survey_id."'");
										// $dataques1=mysqli_num_rows($sqlques1);
										// if($dataques1>0){
											// echo "<script>window.location.href='survey-list.php'</script>";
											// header('location: survey-list.php');
											// exit;
										// }
										
											include('includes/config.php');
											
											 $survey_id=$_REQUEST['survey_id'];
											
											$sqlSurvey=mysqli_query($conn,"SELECT id,questinnour_file,client_id,user_id FROM `survey` where id='".$survey_id."' and del_action='N'");
											$dataSurvey=mysqli_fetch_array($sqlSurvey);;
											$sqlDistinctLang=mysqli_query($conn,"SELECT group_concat(DISTINCT(language_id)) as langId FROM `questions_language` where survey_id='".$survey_id."' and language_id!=1 and status='0'");
											$dataDistinctLang=mysqli_fetch_array($sqlDistinctLang);
											$distinctLanguage=$dataDistinctLang['langId'];
											$client_id=$dataSurvey['client_id'];
											$user_id=$dataSurvey['user_id'];
											$questinnour_file=$dataSurvey['questinnour_file'];
											$clientID="C".$client_id;
											
											set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
											include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
											$inputFileType = 'Excel2007';
											//$inputFileName = 'https://mquad.org/mis/uploaded_questionnaire/C91/Cimmyt-test-survey-1-1361641465.xlsx';
											//$excelFile = 'uploaded_questionnaire/C91/test-file.xlsx';
											$excelFile = 'uploaded_questionnaire/'.$clientID.'/'.$questinnour_file;
											$inputFileType = PHPExcel_IOFactory::identify($excelFile);
											$objReader = PHPExcel_IOFactory::createReader($inputFileType);
											$objReader->setReadDataOnly(true);
											$objPHPExcel = $objReader->load($excelFile);
											$objWorksheet = $objPHPExcel->getActiveSheet();
											$CurrentWorkSheetIndex = 0;
											
											$allQuestionArr=$allChoises=$finalQuestionnaires=[];
											$activeSheets = ['survey','choices'];
											foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
												$sheetSno = $objPHPExcel->getIndex($worksheet);//, PHP_EOL;
												$highestRow = $worksheet->getHighestDataRow();
												$highestColumn = $worksheet->getHighestDataColumn();
												$headings = $worksheet->rangeToArray('A1:' . $highestColumn . 1, NULL,TRUE, FALSE);
												$sheetTitle = strtolower($worksheet->getTitle());
												
												if($sheetTitle=='survey'){
													/* SURVEY SECTION  */
													for ($row = 2; $row <= $highestRow; $row++) {
														$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
														$rowData[0] = array_combine($headings[0], $rowData[0]);
														//echo "<pre>";
													     //print_r($rowData);
														 //echo "<br>";
														
														foreach ($rowData as $key => $rowvalue) {
															$type = safe_var($conn,$rowvalue['type']);
															$field_name = trim(safe_var($conn,str_replace(" ","",$rowvalue['name'])));
															$dictionary_label = safe_var($conn,$rowvalue['dictionary_label']);
															$label = safe_var($conn,$rowvalue['label']);
															$hint = safe_var($conn,$rowvalue['hint']);
															$limit = safe_var($conn,$rowvalue['limit']);
															$relevant = safe_var($conn,$rowvalue['relevant']);
															$default_response = safe_var($conn,$rowvalue['default_response']);
															$constraint = safe_var($conn,$rowvalue['constraint']);
															$constraint_message = safe_var($conn,$rowvalue['constraint_message']);
															$required = safe_var($conn,$rowvalue['required']);
															$paradata = safe_var($conn,$rowvalue['paradata']);
															$unique_id = safe_var($conn,$rowvalue['unique_id']);
															$appearance = safe_var($conn,$rowvalue['appearance']);
															$choice_filter = safe_var($conn,$rowvalue['choice_filter']);
															$repeat_count = safe_var($conn,$rowvalue['repeat_count']);
															$lookups = safe_var($conn,$rowvalue['lookups']);
															$media_file = safe_var($conn,$rowvalue['media_file']);
															$preserve = safe_var($conn,$rowvalue['preserve']);
															$parameters = safe_var($conn,$rowvalue['parameters']);
															$read_only = safe_var($conn,$rowvalue['read_only']);
															$calculation = safe_var($conn,$rowvalue['calculation']);
															$deidentify = safe_var($conn,$rowvalue['deidentify']);
															$choice_relation = safe_var($conn,$rowvalue['choice_relation']);
															//$labelhi = safe_var($conn,$rowvalue['label::hi']);
															$questions['type'] = $type;
															$questions['name'] = $field_name;
															$questions['dictionary_label'] = $dictionary_label;
															$questions['label'] = $label;
															$questions['hint'] = $hint;
															$questions['limit'] = $limit;
															$questions['relevant'] = $relevant;
															$questions['default_response'] = $default_response;
															$questions['constraint'] = $constraint;
															$questions['constraint_message'] = $constraint_message;
															$questions['required'] = $required;
															$questions['paradata'] = $paradata;
															$questions['unique_id'] = $unique_id;
															$questions['appearance'] = $appearance;
															$questions['choice_filter'] = $choice_filter;
															$questions['choice_relation'] = $choice_relation;
															$questions['repeat_count'] = $repeat_count;
															$questions['lookups'] = $lookups;
															$questions['media_file'] = $media_file;
															$questions['preserve'] = $preserve;
															$questions['parameters'] = $parameters;
															$questions['read_only'] = $read_only;
															$questions['calculation'] = $calculation;
															$questions['deidentify'] = $deidentify;
															//echo "SELECT language_code FROM languages WHERE status='0' and language_id in($distinctLanguage) order by language_id asc";
															$getLaguages = mysqli_query($conn,"SELECT language_code FROM languages WHERE status='0' and language_id in($distinctLanguage) order by language_id asc");
															while($language_masters = mysqli_fetch_object($getLaguages))
															{
																
															$lang_hintparam= 'hint::'.strtoupper($language_masters->language_code);
															$lang_labelparam= 'label::'.strtoupper($language_masters->language_code);
															
															$lang_constraint_messageparam= 'constraint_message::'.strtoupper($language_masters->language_code);
																$questions[$lang_labelparam] = plan_text($conn,$rowvalue[strtolower($lang_labelparam)]);
																$questions[$lang_hintparam] = plan_text($conn,$rowvalue[strtolower($lang_hintparam)]);
																$questions[$lang_constraint_messageparam] = plan_text($conn,$rowvalue[strtolower($lang_constraint_messageparam)]);
															}
															//$questions['label::hi'] = $labelhi;
															// $questions['deidentify'] = $deidentify;
															// $questions['deidentify'] = $deidentify;
															// $questions['deidentify'] = $deidentify;
															
															
															$allQuestionArr[] = $questions;
														}
													}
													
													$finalQuestionnaires['questions'] = $allQuestionArr;
													
													
												}
												
												//echo json_encode($finalQuestionnaires);
												if($sheetTitle=='choices'){
													/* CHOICES SECTION  */
													//echo "choice section";
													$allChoicesArr=[];
													for ($row = 2; $row <= $highestRow; $row++) {
														$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
														$rowData[0] = array_combine($headings[0], $rowData[0]);
														foreach ($rowData as $key => $rowvalue) {
															$list_name = safe_var($conn,$rowvalue['list_name']);
															$value = safe_var($conn,$rowvalue['value']);
															$label = safe_var($conn,$rowvalue['label']);
															$choice_filter_parent = safe_var($conn,$rowvalue['choice_filter_parent']);
															$media_file = safe_var($conn,$rowvalue['media_file']);
															$constraint = safe_var($conn,$rowvalue['constraint']);
															$choices['list_name'] = $list_name;
															$choices['value'] = $value;
															$choices['label'] = $label;
															$choices['choice_filter_parent'] = $choice_filter_parent;
															$choices['media_file'] = $media_file;
															$choices['constraint'] = $constraint;
															$getLaguagesOpt = mysqli_query($conn,"SELECT language_code FROM languages WHERE status='0' and language_id in($distinctLanguage) order by language_id asc");
															while($language_mastersOpt = mysqli_fetch_object($getLaguagesOpt))
															{
															$lang_labelparamOpt= 'label::'.strtoupper($language_mastersOpt->language_code);
															$choices[$lang_labelparamOpt] = safe_var($conn,$rowvalue[strtolower($lang_labelparamOpt)]);
															}
															$allChoicesArr[] = $choices;
														}
													}
													$finalQuestionnaires['choices'] = $allChoicesArr;
													
												}
											}
											//echo "<pre>";
											//print_r($finalQuestionnaires);
											
											//insert and update questionnaires table 
											//echo json_encode($finalQuestionnaires);
											 $finalQuestionnaires=mysqli_real_escape_string($conn,json_encode($finalQuestionnaires));
											
											 $sqlques=mysqli_query($conn,"SELECT survey_id FROM `questionnaires` where survey_id='".$survey_id."'");
											$dataques=mysqli_num_rows($sqlques);
											if($dataques>0){
												$sqlQuestions="update questionnaires set question_json='".$finalQuestionnaires."' where survey_id='".$survey_id."'";
												$dataResult=mysqli_query($conn,$sqlQuestions);
											}else{
												$sqlQuestionnaires="insert into questionnaires set survey_id='".$survey_id."',client_id='".$client_id."',user_id='".$user_id."',question_json='".$finalQuestionnaires."'";
												$dataResult=mysqli_query($conn,$sqlQuestionnaires);
											}
											if($dataResult!=''){
												//echo "<script>alert('Web Form generated successfully')</script>";
												 // $_SESSION['status'] = "Web Form Generated Successfully";
												// $_SESSION['status_code'] = "success";
												 $result = array("status" => 1, "message" => "Web Form Generated Successfully");
												//echo "<script>window.location.href='add-question-web-v1.php?survey_id=$survey_id';</script>";
												
											}else{
												// $_SESSION['status_error'] = "Web Form Not Created";
												// $_SESSION['status_error_code'] = "error";
												 $result = array("status" => 0, "message" => "Web Form Not Created");
												//echo "<script>alert('Web Form Not Created')</script>";
											}

											header('Content-Type: application/json');
											echo json_encode($result);
											exit;
										?>
		