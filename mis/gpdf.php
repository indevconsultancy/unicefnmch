<?php
include('includes/config.php');
include('pdf.php');
?>
<?php
ini_set('memory_limit', '-1');
//FILTER_SANITIZE_NUMBER_INT



if (filter_var($_GET['survey_id'], FILTER_VALIDATE_INT)) {
	$survey_id = $_GET['survey_id'];

	$getQuestions = mysqli_query($conn, "SELECT question_id, question_name,relevant, field_name, input_field_type,question_description,constraints,constraint_msg FROM questions_language WHERE survey_id='" . $survey_id . "' AND question_name!='' AND language_id='1' AND field_name!='submit' ");
	if (mysqli_num_rows($getQuestions) < 1) {
		$res = ["status" => 500, "message" => "Something went wrong."];
		echo json_encode($res);
		exit;
	}
}



// echo "select id,survey_name,client_id,unique_id,form_version from survey where id='".$survey_id."'";
$sqlform = mysqli_query($conn, "select id,survey_name,client_id,unique_id,form_version from survey where id='" . $survey_id . "'");
$formdata = mysqli_fetch_array($sqlform);
$survey_name = $formdata['survey_name'];
$client_id = $formdata['client_id'];
$unique_id = $formdata['unique_id'];
$form_version = $formdata['form_version'];



//$file_name = $survey_name."-".$survey_id.".".pdf;

$surveyName = str_replace(' ', '-', $survey_name);
 $file_name = "PDF_form".$surveyName . "-" . $unique_id . $form_version . "." . pdf;



 $flocation = "uploaded_questionnaire/pdf/C" . $client_id . "/" . $file_name;

if (file_exists($flocation)) {
	$furl = "https://mquad.org/mis/uploaded_questionnaire/pdf/C" . $client_id . "/" . $file_name;
	$res = ["status" => 1, "message" => "PDF Created sss", "url" => $furl, "fname" => $file_name];
	echo json_encode($res);
	exit;
}

// $html_code = '<link rel="stylesheet" href="style.css">';
// $html_code = '';

$output = '';
$looprtr = '';

while ($questions = mysqli_fetch_object($getQuestions)) {
	$question_name = $questions->question_name;
	$field_name = $questions->field_name;
	$question_id = $questions->question_id;
	$input_field_type = $questions->input_field_type;
	$relevant = $questions->relevant;
	$relevantv2 = wordwrap($relevant, 15, "<br/>", true);
	$question_hint = $questions->question_description;
	//$constraints = $questions->constraints;
	//$constraint_msg = $questions->constraint_msg;

	$getOptions = mysqli_query($conn, "SELECT option_id, option_sequence, option_name FROM options_language WHERE survey_id='" . $survey_id . "' AND language_id='1' and question_id='" . $question_id . "' ");
	$outputss = '';
	$outputtype = '';
	while ($options = mysqli_fetch_object($getOptions)) {
		$outputss .= '<b>' . $options->option_sequence . ': </b> ' . $options->option_name . '<br>';
	}

	$looprtr .= '<tr>
				  <td valign="top" style="border: 1px solid #00000059; padding:5px;font-size:10px;">' . $field_name . '</td>
				  <td valign="top" style="border: 1px solid #00000059; padding:5px;font-size:10px;">' . $question_name . '<br></td>
				  <td style="border: 1px solid #00000059;padding:5px;font-size:10px; font-family: serif;">' . $outputss . '</td>
				  <td style="border: 1px solid #00000059;padding:5px;font-size:10px; font-family: serif;">' . $relevantv2 . '</td></tr>';
}
$output .= '<h4 style="font-size: 25px;">' . $survey_name . ' Form</h4> <hr><br>';

$output .= '<table cellspacing="0" cellpadding="0" width="100%" class="table table-bordered style="border: 1px solid #00000059;">
					  <thead>
						<tr style="background: #61b8a4fc;color: white;text-align: left;">
						  <th style="border: 1px solid #00000059;padding:5px;">Field Name</th>
						  <th style="border: 1px solid #00000059;padding:5px;">Questions</th>
						  <th style="border: 1px solid #00000059;padding:5px;">Option Value</th>
						  <th style="border: 1px solid #00000059;padding:5px;">Skip/Remark</th>
						</tr>
					  </thead>
					  <tbody>';
$output .= $looprtr;
$output .= '</tbody>
				</table>';

$html_code .= $output;

//////////Create folder////////
//$client_id=$_SESSION['client_id'];
$clientId = "C" . $client_id;
$location = "uploaded_questionnaire/pdf/" . $clientId . "/";

if (!file_exists($location)) {
	if (!mkdir($location, 0777, true)) {
		$error = 'Somthing Went wrong !!';
	} else {
		//echo "create";
		mkdir($location, 0777, true);
	}
}
///////////////////
$pdf = new Pdf();
$pdf->load_html($html_code);
$pdf->render();
$file = $pdf->output();
$ss = file_put_contents("uploaded_questionnaire/pdf/" . $clientId . "/" . $file_name, $file);
$url = "https://mquad.org/mis/uploaded_questionnaire/pdf/" . $clientId . "/" . $file_name;
if ($ss) {
	mysqli_query($conn, "UPDATE survey set questionnaire_pdf='" . $file_name . "' where id='" . $survey_id . "' ");
	$res = ["status" => 1, "message" => "PDF Created", "url" => $url, "fname" => $file_name];
	echo json_encode($res);
	return true;
}

?>