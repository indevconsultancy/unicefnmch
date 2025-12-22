<?php 
header('Access-Control-Allow-Origin: *');
require_once "config.php";
$_REQUEST = json_decode(file_get_contents('php://input'),true);
			$data = Profile1($conn);
			echo json_encode($data);
			exit;
		 
function Profile1($conn)
{

	$Usersql = 0;
	$target_file=0;
	$arrResults = array();	
	$qry=" where 1=1";
	if(isset($_REQUEST))
		{
	     $table=isset($_REQUEST['table_name'])?$_REQUEST['table_name']:'';
		 $user_id=isset($_REQUEST['user_id'])?$_REQUEST['user_id']:''; 
        if($_REQUEST['table_name']!='' )
	     {
		   
	       if ($_REQUEST['table_name']=='states')
	       {
	          $Usersql = "SELECT `id`, `state_code`, `state_name`, `map_code`, `status`, `noofdistricts`, `abbreviation` FROM `states` where state_code=10"; 
	       }
           if ($_REQUEST['table_name']=='districts')
	       {
	          $Usersql = "SELECT `id`, `state_code`, `district_code`, `district_name`, `map_code`, `status` FROM `districts` where state_code=10"; 
	       }
           if ($_REQUEST['table_name']=='blocks' )
	       {
			   
	          //$Usersql = "SELECT `sub_district_id`, `sub_district_name`,sub_district_code FROM `sub_districts` WHERE  status='0' and sub_district_id in (SELECT sub_district_id FROM `user_allocation` where user_id='".$user_id."')";
	          $Usersql = "SELECT `id`, `state_code`, `district_code`, `block_code`, `block_name`, `map_code`, `status` FROM `blocks` where state_code=10";
	       }
		   if ($_REQUEST['table_name']=='nccs_matrix')
	       {
	          $Usersql = "SELECT `id`, `durables_id`, `education_id`,`nccs_education_id`, `nccs_category` FROM `nccs_matrix` WHERE  status='0'"; 
	       }
        }
		else {
			$Usersql = "SELECT `id`, `durables_id`, `education_id`,`nccs_education_id`, `nccs_category` FROM `nccs_matrix` WHERE  status='0'";
		}
        
		 
	    }
		else
		{
		$Usersql = "";
		}
		$return_arr = array();
		mysqli_set_charset($conn,'utf8'); 
		$fetch = mysqli_query($conn,$Usersql); 
		$rows = array();
		while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) 
		{
			$rows[] = $row;
		}
	return $rows;	
}

?>