<?php 
define('hostname','localhost');
define('username','root');
define('password','indev@123');
define('database','stagemquad');


define('SECRETKEY','SaTYEndra@9554161643@#$%^&*(!@#$#%QASDCVBNJYGVJYTRD');



  $conn = mysqli_connect(hostname,username,password,database);
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
mysqli_set_charset($conn,"utf8");

function getone($tablename,$field,$qryfeild,$value,$conn)
{
 // echo "select $field from $tablename where $qryfeild='".$value."'"; die;
$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."'")or die(mysqli_error());
$dn=mysqli_fetch_object($sn);

	return ($dn->$field);
}
/////////// For sup survey
function getoneuser($tablename,$field,$qryfeild,$value,$conn)
{
	//echo "select $field from $tablename where $qryfeild='".$value."' GROUP BY user_id order by survey_data_monitoring_id desc";
 // echo "select $field from $tablename where $qryfeild='".$value."'"; die;
$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."' GROUP BY user_id order by survey_data_monitoring_id desc")or die(mysqli_error());
$ids='';
while($dn=mysqli_fetch_object($sn)){
	$ids.=$dn->$field.',';	
}
$data=substr(trim($ids),0,-1);
return ($data);
}
////////////////////////////////////
function getone_multi($tablename,$field,$qryfeild,$value,$qryfeild1,$value1,$conn)
{
 // echo "select $field from $tablename where $qryfeild='".$value."'";
$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."'")or die(mysqli_error());
$dn=mysqli_fetch_object($sn);

	return ($dn->$field);
}

function getone_multi_language($tablename,$field,$qryfeild,$value,$qryfeild1,$value1,$language_id,$conn)
{
 // echo "select $field from $tablename where $qryfeild='".$value."'";
$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."' and language_id='".$language_id."' ")or die(mysqli_error());
$dn=mysqli_fetch_object($sn);

	return ($dn->$field);
}

function countone($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$conn)
{
//   echo "select count($fields) from $tablename where $qryfeild1='".$value1."' and $qryfeild2='".$value2."'";
$sn2=mysqli_query($conn,"select count($fields) as counts from $tablename where $qryfeild1='".$value1."' and $qryfeild2='".$value2."'")or die(mysqli_error());
$dn2=mysqli_fetch_object($sn2);

	return ($dn2->counts);
}
function getid($tablename,$fields,$qryfeild1,$value1,$conn)
{
///echo "select $fields as ids from $tablename where $qryfeild1='".$value1."'";die;
$sn3=mysqli_query($conn,"select $fields as ids from $tablename where $qryfeild1='".$value1."'")or die(mysqli_error());
$dn3=mysqli_fetch_array($sn3);

	return ($dn3['ids']);
}

/////// count servey status////
function getsurvey($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$conn)
{
///echo "select $fields as ids from $tablename where $qryfeild1='".$value1."'";die;
$sn4=mysqli_query($conn,"select $fields as ids from $tablename where $qryfeild1='".$value1."' and  $qryfeild2='".$value2."'")or die(mysqli_error());
$dn4=mysqli_fetch_array($sn4);

	return ($dn4['ids']);
}

////// count servey status////
function getsurvey2($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$conn)
{
	
///echo "select $fields as ids from $tablename where $qryfeild1='".$value1."'";die;
$sn4=mysqli_query($conn,"select $fields as ids from $tablename where cluster_id in(select cluster_code from survey_data_monitoring where supervisor_id=$value1) and  $qryfeild2='".$value2."'")or die(mysqli_error());
$dn4=mysqli_fetch_array($sn4);

	return ($dn4['ids']);
}
function getsurvey3($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$conn)
{
	
///echo "select $fields as ids from $tablename where $qryfeild1='".$value1."'";die;
$sn4=mysqli_query($conn,"select $fields as ids from $tablename where cluster_no in(select cluster_code from survey_data_monitoring where supervisor_id=$value1) and  $qryfeild2='".$value2."'")or die(mysqli_error());
$dn4=mysqli_fetch_array($sn4);

	return ($dn4['ids']);
}

function getsurvey9($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$conn)
{
	
//echo "select $fields as ids from $tablename where cluster_no in(select cluster_no from starting_points where town_id in(select town_id from user_allocation where user_id='".$value1."')) and  $qryfeild2='".$value2."'";
$sn4=mysqli_query($conn,"select $fields as ids from $tablename where cluster_id in(select cluster_no from starting_points where town_id in(select town_id from user_allocation where user_id='".$value1."')) and  $qryfeild2='".$value2."'")or die(mysqli_error());
$dn4=mysqli_fetch_array($sn4);

	return ($dn4['ids']);
}
function getsurvey10($userid,$conn)
{
	//echo "select count(cluster_no) as ids from starting_points where town_id in(select town_id from user_allocation where user_id='".$userid."') and lock_status=1";
//echo "select count(cluster_no) as ids from starting_points where town_id in(select town_id from user_allocation where user_id='".$userid."') and lock_status=1";
///echo "select $fields as ids from $tablename where $qryfeild1='".$value1."'";die;
$sn4=mysqli_query($conn,"select count(cluster_no) as ids from starting_points where town_id in(select town_id from user_allocation where user_id='".$userid."') and starting_points.lock_status='1'")or die(mysqli_error());
$dn4=mysqli_fetch_array($sn4);

	return ($dn4['ids']);
}

function getsurvey12($userid,$conn)
{
	//echo "select count(survey_id) as ids from survey_data where cluster_id in(select town_id from user_allocation where user_id='".$userid."') and lock_status=1";
//echo "select count(cluster_no) as ids from starting_points where town_id in(select town_id from user_allocation where user_id='".$userid."') and lock_status=1";
///echo "select $fields as ids from $tablename where $qryfeild1='".$value1."'";die;
$sn4=mysqli_query($conn,"select count(survey_id) as ids from survey_data where cluster_id in(select town_id from user_allocation where user_id='".$userid."') and lock_status=1")or die(mysqli_error());
$dn4=mysqli_fetch_array($sn4);

	return ($dn4['ids']);
}

function getsurvey11($userid,$conn)
{
	//echo "select count(survey_id) as ids from survey_data where cluster_id in(select cluster_no from starting_points where town_id in(select town_id from user_allocation where user_id='".$userid."'))";
//echo "select count(cluster_no) as ids from starting_points where town_id in(select town_id from user_allocation where user_id='".$userid."') and lock_status=1";
///echo "select $fields as ids from $tablename where $qryfeild1='".$value1."'";die;
$sn4=mysqli_query($conn,"select count(survey_id) as ids from survey_data where cluster_id in(select cluster_no from starting_points where town_id in(select town_id from user_allocation where user_id='".$userid."'))")or die(mysqli_error());
$dn4=mysqli_fetch_array($sn4);

	return ($dn4['ids']);
}

function getsurvey1($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$qryfeild3,$value3,$conn)
{
//echo "select $fields as ids from $tablename where $qryfeild1='".$value1."' and  $qryfeild2='".$value2."' and $qryfeild3='".$value3."'";
$sn4=mysqli_query($conn,"select $fields as ids from $tablename where $qryfeild1='".$value1."' and  $qryfeild2='".$value2."' and $qryfeild3='".$value3."'")or die(mysqli_error());
$dn4=mysqli_fetch_array($sn4);

	return ($dn4['ids']);
}



?>