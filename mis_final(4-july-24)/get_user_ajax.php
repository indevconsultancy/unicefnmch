<?php include_once('includes/config.php'); ?>
<?php
if (isset($_POST['email'])) { 
	
    $email = $_POST['email'];
	
    $check_username = mysqli_query($conn,"SELECT email FROM users WHERE email='$email'"); 
    if (mysqli_num_rows($check_username)>0) {
        echo '<div style="color: #FF0001;"> <b>'.$email.'</b> is already registered! </div>';                                                                          
    } else {
        echo '<div style="color: green;"> <b>'.$email.'</b> is available! </div>'; 
	//No Record Found - Username is available
    }
}

if(isset($_POST['searchEmail']) && $_POST['searchEmail']!=""){
	$searchEmail = mysqli_real_escape_string($conn,$_POST['searchEmail']);
	$getUsers = mysqli_query($conn,"SELECT user_id, email, name FROM users WHERE email= '".$searchEmail."' AND del_action='N'");
	$users = mysqli_fetch_all($getUsers, MYSQLI_ASSOC);
	echo json_encode($users);
}
?>