<?php 
include_once('includes/config.php');
header('Content-Type: application/json');
$_REQUEST = json_decode(file_get_contents('php://input'),true);
	error_reporting(0);
    $data = surveyJson($conn);
	echo json_encode($data,JSON_PRETTY_PRINT);
	exit;

?>
<?php 
function surveyJson($conn)
{
	$data = array();
	$getSurvey = mysqli_query($conn,"select * from survey where del_action='N'");
	while($survey = mysqli_fetch_array($getSurvey,MYSQLI_ASSOC)){
		$survey_data['id'] = $survey['id'];
		$survey_data['survey_name'] = $survey['survey_name'];


		$survey_id = $survey['id'];
		$getForms = mysqli_query($conn,"SELECT id,form_name,survey_id FROM forms WHERE survey_id='".$survey_id."' ");
		while($forms = mysqli_fetch_array($getForms)){
			$form_data['id'] = $forms['id'];
			$form_data['form_name'] = $forms['form_name'];
			$form_data['survey_id'] = $forms['survey_id'];
			$form_datas = $form_data;

			$form_id = $forms['id'];
			$getScreen = mysqli_query($conn,"SELECT DISTINCT(screen_no) AS screen_no FROM questions WHERE del_action='N' AND  survey_id='".$survey_id."' AND form_id='".$form_id."'");
			while($screen = mysqli_fetch_array($getScreen)){

				$screen_no['screen_no'] = $screen['screen_no'];
				$screen_data[] = $screen_no;

				$scrno = $screen['screen_no'];


				$getQuestions = mysqli_query($conn,"SELECT * FROM questions WHERE del_action='N' AND survey_id='".$survey_id."' AND form_id='".$form_id."' AND screen_no='".$scrno."'");
				while($questions = mysqli_fetch_array($getQuestions)){
					$ques_data['id'] = $questions['id'];
					$ques_data['question_label'] = $questions['question_label'];
					$question_data[] = $ques_data;
				}
			}
			// $screen_data['questions'] = array('sss');//$question_data;
			// $question_data = array();
			// $form_datas['screens'] = $screen_data;
			// $screen_data = array();
		}
		// $screen_data['questions'] = $question_data;
		// $question_data = array();
		$form_datas['screens'] = $screen_data;
		$screen_data = array();
		$survey_data['forms'] = $form_datas;
		$form_datas = array();
		$surveys['surveys'][]=$survey_data;

	}


	// $surveys['surveys']
	$alldata = $surveys;
	return $alldata;
	
	
}
?>