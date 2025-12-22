<?php include_once('../includes/config.php'); ?>

<?php
//change status my question from Question bank 
if (isset($_POST['questionid']) && $_POST['questionid'] != "") {
	$question_bank_id = mysqli_real_escape_string($conn, $_POST['questionid']);
	$updateQuestion = "UPDATE question_bank SET status='1' where question_bank_id='" . $question_bank_id . "' ";
	$delQuestion = mysqli_query($conn, $updateQuestion);
	if ($delQuestion) {
		$UpdateOption = "UPDATE question_bank_option SET status='1' where question_bank_id='" . $question_bank_id . "'";
		$delOption = mysqli_query($conn, $UpdateOption);
		if ($delOption) {
			$result = array("status" => 1, "message" => "Question deleted successfully.");
		} else {
			$result = array("status" => 0, "message" => "Something weng wrong");
		}
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}
	echo json_encode($result);
}
?>
<?php
//change pending to approve status for Question bank 
if (isset($_POST['approveID']) && $_POST['approveID'] != "") {
	$question_bank_id = mysqli_real_escape_string($conn, $_POST['approveID']);
	$approvedata = mysqli_query($conn, "update question_bank set status_type='1' where question_bank_id='" . $question_bank_id . "'");
	if ($approvedata) {
		$result = array("status" => 1, "message" => "Approved successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}
	echo json_encode($result);
}
?>
<?php
//change pending and approve to reject status for Question bank 
if (isset($_POST['rejectID']) && $_POST['rejectID'] != "") {
	$question_bank_id = mysqli_real_escape_string($conn, $_POST['rejectID']);
	$rejectdata = mysqli_query($conn, "update question_bank set status_type='2' where question_bank_id='" . $question_bank_id . "'");
	if ($rejectdata) {
		$result = array("status" => 1, "message" => "Rejected successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
?>
<?php
//change pending status to active user 
if (isset($_POST['activeuserid']) && $_POST['activeuserid'] != "") {
	$user_id = mysqli_real_escape_string($conn, $_POST['activeuserid']);
	$updateActive = mysqli_query($conn, "update users set status='0' where user_id='" . $user_id . "'");
	if ($updateActive) {
		$result = array("status" => 1, "message" => "Active successfully.");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
//change pending status to inactive user 
if (isset($_POST['inactive_userid']) && $_POST['inactive_userid'] != "") {
	$user_id = mysqli_real_escape_string($conn, $_POST['inactive_userid']);
	$updateInActive = mysqli_query($conn, "update users set status='1' where user_id='" . $user_id . "'");
	if ($updateInActive) {
		$result = array("status" => 1, "message" => "Inactive successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}
	echo json_encode($result);
}
//change status to user 
if (isset($_POST['deluserid']) && $_POST['deluserid'] != "") {
	$user_id = mysqli_real_escape_string($conn, $_POST['deluserid']);
	$delsql = mysqli_query($conn, "UPDATE users SET del_action='Y' where user_id='" . $user_id . "'");
	if ($delsql) {
		$result = array("status" => 1, "message" => "Delete successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}
	echo json_encode($result);
}
//reset status to user 
if (isset($_POST['resetid']) && $_POST['resetid'] != "") {
	$resetid = mysqli_real_escape_string($conn, $_POST['resetid']);

	$sqlUserreset = mysqli_query($conn, "SELECT user_id,firebase_token,device_id FROM `users`where user_id='" . $resetid . "'");
	$dataReset = mysqli_fetch_object($sqlUserreset);
	$firebase_token = $dataReset->firebase_token;
	$user_id = $dataReset->user_id;

	$activities = "MQUAD Login";
	$messages = "Your device has been reset";
	$jsons = array("user_id" => $user_id, "key" => "Logout");
	$jsonData = json_encode($jsons);
	$json_encoded = urlencode($jsonData);
	$activitis = str_replace(" ", "%20", $activities);
	$message = str_replace(" ", "%20", $messages);
	$pathNotifications = "https://mquad.org/firebase/?regId=$firebase_token&title=$activitis&message=$message&json=$json_encoded&push_type=individual";

	$notificationSend = file_get_contents($pathNotifications);

	if ($notificationSend) {
		$resetsql = mysqli_query($conn, "UPDATE users SET device_id='' where user_id='" . $resetid . "'");
	} else {
		$result = array("status" => 2, "message" => "Something weng wrong");
	}
	if ($resetsql) {
		$result = array("status" => 1, "message" => "Reset successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}
	echo json_encode($result);
}
?>
<?php
//change pending to approve status for Tool archive
if (isset($_POST['approve_id']) && $_POST['approve_id'] != "") {
	$tool_approve_id = mysqli_real_escape_string($conn, $_POST['approve_id']);
	$approvetool = mysqli_query($conn, "update survey_bank set tool_archive_status=1 where id='" . $tool_approve_id . "'");
	if ($approvetool) {
		$result = array("status" => 1, "message" => "Approved successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}

//change pending to reject status for Tool archive
if (isset($_POST['reject_id']) && $_POST['reject_id'] != "") {
	$tool_reject_id = mysqli_real_escape_string($conn, $_POST['reject_id']);
	$rejecttool = mysqli_query($conn, "update survey_bank set tool_archive_status=2 where id='" . $tool_reject_id . "'");
	if ($rejecttool) {
		$result = array("status" => 1, "message" => "Rejected successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}

//change pending to delete status for Tool archive
if (isset($_POST['tool_del_id']) && $_POST['tool_del_id'] != "") {
	$delid = mysqli_real_escape_string($conn, $_POST['tool_del_id']);
	$rejecttool = mysqli_query($conn, "UPDATE survey_bank SET status='1' where id='" . $delid . "'");
	if ($rejecttool) {
		$result = array("status" => 1, "message" => "Deleted successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
?>
<?php
//change pending to approve status for Data Repository
if (isset($_POST['approve_data_id']) && $_POST['approve_data_id'] != "") {
	$approve_data_id = mysqli_real_escape_string($conn, $_POST['approve_data_id']);
	$approvedata = mysqli_query($conn, "update data_repositroy set data_repositroy_status=1 where data_repository_id='" . $approve_data_id . "'");
	if ($approvedata) {
		$result = array("status" => 1, "message" => "Approved successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}

//change pending/approve to reject status for Data Repository
if (isset($_POST['data_reject_id']) && $_POST['data_reject_id'] != "") {
	$data_reject_id = mysqli_real_escape_string($conn, $_POST['data_reject_id']);
	$rejectdata = mysqli_query($conn, "update data_repositroy set data_repositroy_status=2 where data_repository_id='" . $data_reject_id . "'");
	if ($rejectdata) {
		$result = array("status" => 1, "message" => "Rejected successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}


//change pending to delete status for Data Repository
if (isset($_POST['del_data_id']) && $_POST['del_data_id'] != "") {
	$del_data_id = mysqli_real_escape_string($conn, $_POST['del_data_id']);
	$del_dataRepository = mysqli_query($conn, "UPDATE data_repositroy SET status='0' where data_repository_id='" . $del_data_id . "'");
	if ($del_dataRepository) {
		$result = array("status" => 1, "message" => "Deleted successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}

//Delete survey from view user pages
if (isset($_POST['surveyIdview']) && $_POST['surveyIdview'] != "") {
	$del_survey_id = mysqli_real_escape_string($conn, $_POST['surveyIdview']);
	$delsqlUser = mysqli_query($conn, "UPDATE assign_survey SET status='1' where id='" . $del_survey_id . "' ");
	if ($delsqlUser) {
		$result = array("status" => 1, "message" => "Deleted successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
?>

<?php
if (isset($_POST['country_id_mul'])) {
	$data = '';
	$country_id_mul = $_POST['country_id_mul'];
	$statesql = mysqli_query($conn, "SELECT state_id, state_code, state_name FROM states WHERE status='1' AND country_id='$country_id_mul'");
	$data .= '<option value="">Select State</option>';
	foreach ($statesql as $key => $row) {
		$state_id = $row['state_id'];
		$state_name = $row['state_name'];
		$data .= '<option value=' . $state_id . '>' . $state_name . '</option>';
	}
	echo $data;
}
?>
 
<?php

//change pending/approve to reject status for Data Repository
if (isset($_POST['reject_datsetid']) && $_POST['reject_datsetid'] != "") {
	$reject_datsetid = mysqli_real_escape_string($conn, $_POST['reject_datsetid']);
	$rejectdata = mysqli_query($conn, "update project_datasets set dataset_status=2 where dataset_id='" . $reject_datsetid . "'");
	if ($rejectdata) {
		$result = array("status" => 1, "message" => "Rejected successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}


?>
<?php
//change pending to approve status for Data Repository
if (isset($_POST['approve_datasetid']) && $_POST['approve_datasetid'] != "") {
	$approve_datasetid = mysqli_real_escape_string($conn, $_POST['approve_datasetid']);
	$approvedata = mysqli_query($conn, "update project_datasets set dataset_status=1 where dataset_id='" . $approve_datasetid . "'");
	if ($approvedata) {
		$result = array("status" => 1, "message" => "Approved successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
?>
<?php
//change pending to approve status for Data Repository
if (isset($_POST['del_datasetid']) && $_POST['del_datasetid'] != "") {
	$del_datasetid = mysqli_real_escape_string($conn, $_POST['del_datasetid']);
	$approvedata = mysqli_query($conn, "update project_datasets set status=0 where dataset_id='" . $del_datasetid . "'");
	if ($approvedata) {
		$result = array("status" => 1, "message" => "Deleted successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
?>
<?php
//change pending to approve status for Data Repository
if (isset($_POST['dataid']) && $_POST['dataid'] != "") {
	$dataid = mysqli_real_escape_string($conn, $_POST['dataid']);
	$approvedata = mysqli_query($conn, "update project_dataformat_file set status=0 where dataformat_file_id='" . $dataid . "'");
	if ($approvedata) {
		$result = array("status" => 1, "message" => "Deleted successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
?>

<?php //change pending/approve to reject status for Data Repository
if (isset($_POST['reject_data']) && $_POST['reject_data'] != "") {
	$reject_data = mysqli_real_escape_string($conn, $_POST['reject_data']);
	$rejectdata = mysqli_query($conn, "update project_dataformat_file set dataformat_file_status=2 where dataset_id='" . $reject_data . "'");
	if ($rejectdata) {
		$result = array("status" => 1, "message" => "Rejected successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}


?>
<?php
//change pending to approve status for Data Repository
if (isset($_POST['approve_data']) && $_POST['approve_data'] != "") {
	$approve_data = mysqli_real_escape_string($conn, $_POST['approve_data']);
	$approvedata = mysqli_query($conn, "update project_dataformat_file set dataformat_file_status=1 where dataformat_file_id='" . $approve_data . "'");
	if ($approvedata) {
		$result = array("status" => 1, "message" => "Approved successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
?>


<?php //change pending/approve to reject status for Data Repository
if (isset($_POST['tool_rejectID']) && $_POST['tool_rejectID'] != "") {
	$tool_rejectID = mysqli_real_escape_string($conn, $_POST['tool_rejectID']);
	$rejectdata = mysqli_query($conn, "update survey_bank set tool_archive_status=2 where id='" . $tool_rejectID . "'");
	if ($rejectdata) {
		$result = array("status" => 1, "message" => "Rejected successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}


?>
<?php
//change pending to approve status for Data Repository
if (isset($_POST['tool_approveID']) && $_POST['tool_approveID'] != "") {
	$tool_approveID = mysqli_real_escape_string($conn, $_POST['tool_approveID']);
	$approvedata = mysqli_query($conn, "update survey_bank set tool_archive_status=1 where id='" . $tool_approveID . "'");
	if ($approvedata) {
		$result = array("status" => 1, "message" => "Approved successfully");
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}

	echo json_encode($result);
}
?>


<?php
//change status my question from Question bank 
if (isset($_POST['tool_questionid']) && $_POST['tool_questionid'] != "") {
	$tool_questionid = mysqli_real_escape_string($conn, $_POST['tool_questionid']);
	$updateQuestion = "UPDATE survey_bank SET status='1' where id='" . $tool_questionid . "' ";
	$delQuestion = mysqli_query($conn, $updateQuestion);
	if ($delQuestion) {
		$UpdateOption = "UPDATE survey_bank SET status='1' where id='" . $tool_questionid . "'";
		$delOption = mysqli_query($conn, $UpdateOption);
		if ($delOption) {
			$result = array("status" => 1, "message" => "Question deleted successfully.");
		} else {
			$result = array("status" => 0, "message" => "Something weng wrong");
		}
	} else {
		$result = array("status" => 0, "message" => "Something weng wrong");
	}
	echo json_encode($result);
}
?>