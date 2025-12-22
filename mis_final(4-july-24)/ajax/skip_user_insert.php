<?php include('../includes/config.php');?>
<?php 
	
	$name=$_REQUEST['nameuser'];
	$mobile=$_REQUEST['mobileuser'];
	$client_id=$_REQUEST['clientId'];
	$check_mobileno = mysqli_query($conn,"SELECT user_id,mobile,name,username,client_id FROM users WHERE mobile='".$mobile."' and del_action='N'"); 
  
	if (mysqli_num_rows($check_mobileno)>0) {
		$data=mysqli_fetch_array($check_mobileno);
		$user_id=$data['user_id'];
		$name=$data['name'];
		$username=$data['username'];
		$client_id=$data['client_id'];
		echo json_encode(array("status"=>0));
		$_SESSION['user_id']=$user_id;
		$_SESSION['name']=$name;
		$_SESSION['client_id']=$client_id;
		//$_SESSION['username']=$username;
	} else{
		$userSql=mysqli_query($conn,"insert into users set name='".$name."',mobile='".$mobile."',username='".$mobile."',role_id='3',user_code='".$name."',client_id='".$client_id."'");
		$user_id=mysqli_insert_id($conn);
		$_SESSION['user_id']=$user_id;
		$_SESSION['name']=$name;
		$_SESSION['client_id']=$client_id;
		//$_SESSION['username']=$username;
		echo json_encode(array("status"=>1));
		
	}
	mysqli_close($conn);
?>