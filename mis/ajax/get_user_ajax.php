<?php include_once('../includes/config.php'); ?>
<?php
if (isset($_POST['username'])) { 
    $username = $_POST['username'];
    $check_username = mysqli_query($conn,"SELECT username FROM users WHERE username='$username'"); 
    if (mysqli_num_rows($check_username)>0) {
        echo '<div style="color: #FF0001;"> <b>'.$username.'</b> is already registered! </div>';                                                                          
    } else {
        echo '<div style="color: green;"> <b>'.$username.'</b> is available! </div>'; 

    }
}

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
	$ustatus='';
	$status = $_POST['status'];
	if($status==0)
	{
		$ustatus=1;
	}
	if($status==1)
	{
		$ustatus=0;
	}
    

    // Update query to change the status
	//echo "UPDATE clients SET subscription = '".$ustatus."' WHERE id ='".$id."'";
    $stmt = mysqli_query($conn,"UPDATE clients SET subscription = '".$ustatus."' WHERE id ='".$id."'");
    

    echo "Status updated";
} else {
    echo "Invalid input";
}



?>
