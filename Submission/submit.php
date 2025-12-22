<?php
include('../includes/config.php');
function getone($conn,$tablename,$field,$qryfeild,$value)
{
//echo  "select $field from $tablename where $qryfeild='".$value."'";
$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."'")or die(mysqli_error());
$dn=mysqli_fetch_object($sn);
	return ($dn->$field);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $user = htmlspecialchars(trim($_POST['user']));
    $survey = htmlspecialchars(trim($_POST['survey']));
    $visitDate = htmlspecialchars(trim($_POST['visitDate']));
    $district = htmlspecialchars(trim($_POST['district']));
    
    // Error array
    $errors = [];

    // Validate Facilitator Name
    if (empty($user)) {
        $errors[] = "User Name is required.";
    }

    // Validate WhatsApp Number
    if (empty($survey)) {
        $errors[] = "Select Form .";
    } 

    // Validate Facility Name
    if (empty($visitDate)) {
        $errors[] = "Visit Date is required.";
    }

    

    // Display errors if they exist
    if (!empty($errors)) {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Form Errors</title>
            <!-- Bootstrap CSS -->
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body>
            <div class='container mt-5'>
                <div class='alert alert-danger'>
                    <h4 class='alert-heading'>Form Submission Errors</h4>
                    <p>Please fix the following issues before resubmitting the form:</p>
                    <ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>
                </div>
                <div class='text-center'>
                    <a href='index.php' class='btn btn-danger'>Back to Form</a>
                </div>
            </div>
        </body>
        </html>";
    } else {
		$sqlquery="select count(*) as total from survey_data_monitoring where survey_name_id='".$survey."' and monitor_name='".$user."' and visit_date='".$visitDate."'";
		$sqlD=mysqli_query($conn,$sqlquery);
		$dataD=mysqli_fetch_object($sqlD);			  
        // If no errors, display the thank-you message
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Thank You</title>
            <!-- Bootstrap CSS -->
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body>
            <div class='container mt-5'>
                <div class='card shadow'>
                    <div class='card-header bg-primary text-white'>
                        <h2>Your Submissions</h2>
                    </div>
                    <div class='card-body'>
                        <p><strong>User Name:</strong> $user</p>
                        <p><strong>Form Name:</strong> ".getone($conn,'survey','survey_name','id',$survey)."</p>
                        <p><strong>Visit Date:</strong> $visitDate</p>
                        <p><strong>Total Submission:</strong> $dataD->total Forms</p>
                    </div>
                    <div class='card-footer text-center'>
                        <a href='index.php' class='btn btn-primary'>Back to Search</a>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }
}
?>
