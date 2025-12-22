<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
require_once "config.php";
$_REQUEST = json_decode(file_get_contents('php://input'),true);
			$data = Profile1($conn);
			echo json_encode($data);
			exit;

			
function Profile1($conn)
{

	
	$arrResults = array();	
	
	if(isset($_REQUEST))
		{
		 $mobile=isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'';
		 $anm_mobile=isset($_REQUEST['anm_mobile'])?$_REQUEST['anm_mobile']:'';
        if($_REQUEST['mobile']!='' )
	     {
			 //echo "select * from pw_iron_registration where mobile='".$_REQUEST['mobile']."'";
			 $sqlregistration=mysqli_query($conn,"select * from pw_iron_registration where mobile='".$_REQUEST['mobile']."' order by id desc limit 0,1");
			 $datarowCount=mysqli_num_rows($sqlregistration);
			 if($datarowCount>0)
			 {
				 $dataregistration=mysqli_fetch_object($sqlregistration);
				 $sqlvisit=mysqli_query($conn,"select * from pw_iron_visit where pw_id='".$dataregistration->id."'");
				 $rowvisit=mysqli_num_rows($sqlvisit);
				 while($data=mysqli_fetch_object($sqlvisit))
				 {
					 $status='';
					 $doset['visit_id']=$data->id;
					 $doset['visit']=$data->followup;
					 $doset['dose']=$data->dose;
					 $doset['visit_date']=date('Y-m-d', strtotime($data->visit_date));
					 if($data->visit_status==1) { $status="Completed"; } else { $status="Pending"; }
					 $doset['visit_status']=$status;
					 $dose_details[]=$doset;
					 $doset=[];
				 }
				
		        if($rowvisit>0)
				{
					$totDose=$dataregistration->total_dose.' मिलीग्राम आयरन';
                 $arrResults = array("success" => 1, "message" => 'Success',"pw_id"=>$dataregistration->id,"pw_name"=>$dataregistration->name,"total_dose"=>$totDose,"dose_details"=>$dose_details);
				}
				else
				{
                $arrResults = array("success" => 0, "message" => 'Failed1');
				}
			 }
			 else
				{
                $arrResults = array("success" => 1, "message" => 'Not Found');
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