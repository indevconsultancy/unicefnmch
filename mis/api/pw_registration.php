<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
require_once "config.php";
$_REQUEST = json_decode(file_get_contents('php://input'),true);
$data = Profile1($conn);
echo json_encode($data);
exit;



/*
///////////////////////////////// IVR CALL ///////////////////
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
  CURLOPT_POSTFIELDS =>'{
    "token": "32ffe110d39e1bc3acb1bf5280dd44eb0e966f2c",
    "application": "8",
    "template_id": "JB11664003",
    "message_type": "1",
    "data": [
        {
            "language": "en",
            "number": "918318910231",
            "template_message": [
                "raju kumar",
                "How to Stay Safe and Prevent Data Theft While Using the Internet for Your Agricultural Practices?"
            ]
        }
    ]
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Cookie: PHPSESSID=i1ln1jsuf8d67h220lmt62gnrl'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;


//////////////////////// IVR Message //////////////////////////

*/
function cleanMobile($mobile_no1) {
    // Remove any non-digit characters just in case
    $mobile_no1 = preg_replace('/\D/', '', $mobile_no1);

    // If it's 12 digits and starts with '91', remove the first 2 digits
    if (strlen($mobile_no1) === 12 && substr($mobile_no1, 0, 2) === '91') {
        return substr($mobile_no1, 2);
    }

    // Return original if conditions not met
    return $mobile_no;
}
			
function Profile1($conn)
{
	$arrResults = array();	
	$qry=" where 1=1";
	if(isset($_REQUEST))
		{
	    $name=isset($_REQUEST['name'])?$_REQUEST['name']:'';
		 $mobile=isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'';
		 $weight=isset($_REQUEST['weight'])?$_REQUEST['weight']:'';
		 $hb=isset($_REQUEST['hb'])?$_REQUEST['hb']:'';
		 $anm_mobile=isset($_REQUEST['anm_mobile'])?cleanMobile($_REQUEST['anm_mobile']):'';
		 
        if($_REQUEST['name']!='' )
	     {
				$doses=array();
				$ironCalculate=($weight*(round((11-$hb),1)*2.4)+500);
				$totalDose=$ironCalculate;
				$distribution=round(($totalDose/200),0);
				$dosestot=$totalDose." मिलीग्राम आयरन";
				$ironCalculate1=$ironCalculate ;
				
				$sqlregistraion=mysqli_query($conn, "select *  from pw_iron_registration where name='".$name."' and mobile='".$mobile."'");
				$countreg=mysqli_num_rows($sqlregistraion);
				////////////////////
				$facility_id='';
				$facilitator_id=0;
				$district_name='';
				$sqlfacilitator=mysqli_query($conn,"select id,facility_name,district_name from pw_facilitator where whatsAppNo='".$anm_mobile."'");
				$rwcount=mysqli_num_rows($sqlfacilitator);
				if($rwcount>0)
				{
					$datafacilitator=mysqli_fetch_object($sqlfacilitator);
					$facility_id=$datafacilitator->facility_name;
					$facilitator_id=$datafacilitator->id;
					$district_name=$datafacilitator->district_name;
				}
				/////////////////////////
				if($countreg>0)
				{
					$dataregistraion=mysqli_fetch_object($sqlregistraion);
					$lastId=$dataregistraion->id;
				}
				else {
				
				$Usersql = "insert into pw_iron_registration set total_dose='".$totalDose."',total_visit='".$distribution."',message='', name='".$name."',mobile='".$mobile."',weight='".$weight."',hb='".$hb."',anm_mobile='".$anm_mobile."',facility_id='".$facility_id."',facilitator_id='".$facilitator_id."',district_name='".$district_name."'";
				$sqlinsert=mysqli_query($conn,$Usersql);
				$lastId=mysqli_insert_id($conn);
				
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'http://115.241.99.180/indev/call-to-question.php?did=1135352929&number=0'.$mobile.'&audio_name=%2Fetc%2Fasterisk%2Fsounds%2Fcomplition',
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
				//echo $response;

				
			    }
				$mdate=date('Y-m-d');
				$vir=0;
				$updateStatuss='';
				if($distribution>4) { $distribution=4;}
				for($i=0; $i<$distribution; $i++)
				{
					if($i==0)
					{
						$vir=1;
						$updateStatuss=",updated_date='".date('Y-m-d')."'";
					}
					else { $vir=0; $updateStatuss='';  }
					if($ironCalculate1>200)
					{
					$caldate='';
						if($i==3)
						{
						$caldate="+".($i*2+1)." day";
						}
						else if($i==4)
						{
						$caldate="+".($i*2+1)." day";
						}
						else if($i==5)
						{
						$caldate="+".($i*2+1)." day";
						}
					else {
						$caldate="+".($i*2)." day";
					     }
					$dosevisit=$doset['visit']="खुराक-".($i+1);
					$dosedose=$doset['dose']='200 मिलीग्राम आयरन';
					
					$dosedate=$doset['visit_date']=date('Y-m-d', strtotime($caldate));
					$ironCalculate1=$ironCalculate1-200;
					}
					else {
						$caldate='';
						if($i==3)
						{
						$caldate="+".($i*2+1)." day";
						}
						else if($i==4)
						{
						$caldate="+".($i*2+1)." day";
						}
						else if($i==5)
						{
						$caldate="+".($i*2+1)." day";
						}
						else {
						$caldate="+".($i*2)." day";
					     }
						$dosevisit=$doset['visit']="खुराक-".($i+1);
						if($ironCalculate1<150)
						{
						$dosedose=$doset['dose']='100 मिलीग्राम आयरन';
						}
						else {
							$dosedose=$doset['dose']='200 मिलीग्राम आयरन';
						}
						//$caldate="+".($i*2)." day";
						$dosedate=$doset['visit_date']=date('Y-m-d', strtotime($caldate));
						
					}
					$dose_details[]=$doset;
					$doset=[];
					mysqli_query($conn,"insert into pw_iron_visit set pw_id='".$lastId."',`followup`='".$dosevisit."', `dose`='".$dosedose."', `visit_date`='".$dosedate."', `visit_status`='".$vir."',`mobile`='".$mobile."', `anm_mobile`='".$anm_mobile."',facility_id='".$facility_id."',facilitator_id='".$facilitator_id."',district_name='".$district_name."' $updateStatuss");
				}
				
				$Usersql1 = "update pw_iron_registration set message='".json_encode($dose_details)."' where id='".$lastId."'";
				$sqlinsert1=mysqli_query($conn,$Usersql1);
				
		        if($lastId)
				{	
                $arrResults = array("success" => 1, "message" => 'Success',"pw_id"=>$lastId,"pw_name"=>$name,"total_dose"=>$dosestot,"dose_details"=>$dose_details);
				}
				else
				{
                $arrResults = array("success" => 0, "message" => 'Failed1');
				}

        }
		else {
                $arrResults = array("success" => 0, "message" => 'Failed2');
				}
	    }
		else
		{
		 $arrResults = array("success" => 0, "message" => 'Failed3');
		}
	return $arrResults;	
}

?>