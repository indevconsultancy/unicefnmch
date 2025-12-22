<?php
define('hostname','localhost'); //'65.1.180.162'
define('username','unicef_db');
define('password','unicef_dblean@!pA');
define('database','unicef_db');

$conn = mysqli_connect(hostname,username,password,database) or die(mysqli_error());
mysqli_set_charset($conn,"utf8");
if(!$conn)
{
    echo "Connection failed.......!";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $facilitatorName = htmlspecialchars(trim($_POST['facilitatorName']));
    $whatsappNumber = htmlspecialchars(trim($_POST['whatsappNumber']));
    $facilityName = htmlspecialchars(trim($_POST['facilityName']));
    $district = htmlspecialchars(trim($_POST['district']));
    
    // Error array
    $errors = [];

    // Validate Facilitator Name
    if (empty($facilitatorName)) {
        $errors[] = "Facilitator Name is required.";
    }

    // Validate WhatsApp Number
    if (empty($whatsappNumber)) {
        $errors[] = "WhatsApp Number is required.";
    } elseif (!preg_match('/^[6-9]\d{9}$/', $whatsappNumber)) {
        $errors[] = "Invalid WhatsApp Number. Must be 10 digits and start with 6-9.";
    }

    // Validate Facility Name
    if (empty($facilityName)) {
        $errors[] = "Facility Name is required.";
    }

    // Validate District
    if (empty($district)) {
        $errors[] = "District selection is required.";
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
		$sqlquery="insert into `pw_facilitator` SET facilitator_name='".$facilitatorName."',whatsAppNo='".$whatsappNumber."',facility_name='".$facilityName."',district_name='".$district."'";
		mysqli_query($conn,$sqlquery);
					  $curl = curl_init();
					  curl_setopt_array($curl, array(
					  CURLOPT_URL => 'http://13.234.16.186:8040/mexico/add-survey/',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS =>'{ "mobile_number": "91'.$whatsappNumber.'", "answer": "", "last_steps": 0, "latest_survey_id": 0 }',
					  CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json'
					  ),
					));

					$response = curl_exec($curl);

					curl_close($curl);
					
					////////////////// WhatsApp Message///////////////
					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => 'https://pickyassist.com/app/api/v2/push',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS =>'{ "token": "9ed47053b4b961842c7d0c9b245b0da4b7b21597", "application": "121", "template_id": "AG11358804", "message_type": "1", "data": [ { "language": "hi", "number": "91'.$whatsappNumber.'", "template_message": [ "'.$facilitatorName.'" ] } ] }',
					  CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json',
						'Cookie: PHPSESSID=q842jijbostcov274v1lgaot2c'
					  ),
					));

					$response = curl_exec($curl);

					curl_close($curl);

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
                        <h2>पंजीकरण के लिए धन्यवाद</h2>
                    </div>
                    <div class='card-body'>
                        <p><strong>सहायक का नाम :</strong> $facilitatorName</p>
                        <p><strong>व्हाट्सएप नंबर :</strong> $whatsappNumber</p>
                        <p><strong>स्वास्थ्य केंद्र का नाम  :</strong> $facilityName</p>
                        <p><strong>जिला :</strong> $district</p>
						<hr></hr>
						<p><strong>निर्देश :</strong> आपके व्हाट्सएप नंबर पर एक संदेश प्राप्त होगा। व्हाट्सएप नंबर को \"Iron Sucrose Program\" के नाम से सुरक्षित करें। गर्भवती महिलाओं को रजिस्टर करने के लिए व्हाट्सएप चैट बॉक्स में \"Go\" लिखें।</p>
                    </div>
                    <div class='card-footer text-center'>
                        <a href='index.php' class='btn btn-primary'>पंजीकरण फॉर्म पर जाएं</a>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }
}
?>
