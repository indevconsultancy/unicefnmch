<?php include('includes/config.php');

require_once __DIR__ . '/skpdf/vendor/mpdf/mpdf/mpdf.php';

 //error_reporting(E_ALL);
 //ini_set('display_errors', 1);

ini_set('memory_limit', '-1');

if (filter_var($_REQUEST['survey_id'], FILTER_VALIDATE_INT)) {
	$survey_id = $_REQUEST['survey_id'];
	$language_id =$_REQUEST['language_id'] ? $_REQUEST['language_id'] : '1';
	$getQuestions = mysqli_query($conn, "SELECT question_id, question_name,relevant, field_name, input_field_type,question_description,constraints,constraint_msg FROM questions_language WHERE survey_id='" . $survey_id . "' AND question_name!='' AND language_id='".$language_id."' AND field_name!='submit' ");
	if (mysqli_num_rows($getQuestions) < 1) {
		$res = ["status" => 500, "message" => "Something went wrong."];
		echo json_encode($res);
		exit;
	}
}
 
$sqlform = mysqli_query($conn, "select id,survey_name,client_id,unique_id,form_version from survey where id='" . $survey_id . "'");
$formdata = mysqli_fetch_array($sqlform);
$survey_name = $formdata['survey_name'];
$client_id = $formdata['client_id'];
$unique_id = $formdata['unique_id'];
$form_version = $formdata['form_version'];
$clientId = "C" . $client_id;

$location="uploaded_questionnaire/pdf/".$clientId."/";
				if (!file_exists($location)) {
					if (!mkdir($location, 0777, true)) {
						//$error='Somthing Went wrong !!';
					}else{
						//echo "create";
						mkdir($location, 0777, true);
					}
				}

$surveyName = str_replace(' ', '-', $survey_name);
$file_name = "PDF_form_" . $surveyName . "-" . $unique_id . $form_version . ".pdf";
$flocation = "uploaded_questionnaire/pdf/C" . $client_id . "/" . $file_name;

$html_code = '';
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
	
	$getOptions = mysqli_query($conn, "SELECT option_id, option_sequence, option_name FROM options_language WHERE survey_id='" . $survey_id . "' AND language_id='".$language_id."' and question_id='" . $question_id . "' ");
	$outputss = '';
	$outputtype = '';
	while ($options = mysqli_fetch_object($getOptions)) {
		$outputss .= '<b>' . $options->option_sequence . ': </b> ' . $options->option_name . '<br>';
	}

	$looprtr .= '<tr>
				  <td valign="top" style="border: 1px solid #00000059;width:20px; padding:5px;font-size:15px; ">' . $field_name . '</td>
				  <td valign="top" style="border: 1px solid #00000059;width:30px; padding:5px;font-size:15px; ">' . $question_name . '<br></td>
				  <td style="border: 1px solid #00000059;padding:5px;width:30px; font-family: serif;font-size:15px; ">' . $outputss . '</td>
				  <td style="border: 1px solid #00000059;padding:5px;width:20px; font-family: serif;serif;font-size:15px; ">' . $relevantv2 . '</td></tr>';
}
				$output .= '<h4 style="font-size: 25px; ">' . $survey_name . ' Form</h4> <hr><br>';

				$output .= '<table cellspacing="0" cellpadding="0" width="100%" class="table table-bordered" style="border: 1px solid #00000059;">
					  <thead>
						<tr style="background: #f47c20;color: #fff !important;text-align: left;">
						  <th style="border: 1px solid #00000059;padding:5px;text-align: left; color:white;">Field Name</th>
						  <th style="border: 1px solid #00000059;padding:5px;text-align: left; color:white;">Questions</th>
						  <th style="border: 1px solid #00000059;padding:5px;text-align: left; color:white;">Option Value</th>
						  <th style="border: 1px solid #00000059;padding:5px;text-align: left; color:white;">Skip/Remark</th>
						</tr>
					  </thead>
					  <tbody>';
				$output .= $looprtr;
				$output .= '</tbody>
				</table>';

 $html_code .= $output;
//die();
// $mpdf = new Mpdf();

$mpdf = new mPDF('utf-8','', 0, '', 15, 15, 16, 16, 9, 9, 'P');
$mpdf->SetAutoFont();

// $mpdf->WriteHTML(utf8_encode($html_code));
$mpdf->WriteHTML($html_code);
$mpdf->Output($flocation);
// print_r($mpdf->Output($flocation));
$url =base_url()."/uploaded_questionnaire/pdf/" . $clientId . "/" . $file_name;

mysqli_query($conn, "UPDATE survey set questionnaire_pdf='" . $file_name . "' where id='" . $survey_id . "' ");
$res = ["status" => 1, "message" => "PDF Created", "url" => $url, "fname" => $file_name];
echo json_encode($res);
//return true;


?>