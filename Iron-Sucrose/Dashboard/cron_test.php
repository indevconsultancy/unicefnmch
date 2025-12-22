<?php $curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'http://115.241.99.180/indev/call-to-question.php?did=1135352929&number=06264792693&audio_name=%2Fetc%2Fasterisk%2Fsounds%2FReminder',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				));

				$response = curl_exec($curl);

				curl_close($curl);
				echo $response;

?>