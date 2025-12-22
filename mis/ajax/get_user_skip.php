<?php include_once('../includes/config.php'); ?>
<?php
if (isset($_POST['mobile'])) { 
    $mobile = $_POST['mobile'];
    $check_mobileno = mysqli_query($conn,"SELECT mobile FROM users WHERE mobile='".$mobile."' and del_action='N'"); 
    $data=mysqli_fetch_array($check_mobileno);
	 //$mobile=$data['mobile'];
	if (mysqli_num_rows($check_mobileno)>0) {
        echo '<div style="color: #FF0001;"> <b>'.$mobile.'</b> is already registered! Please go to next step.</div>';                                                                          
    } else {
        echo '<div style="color: green;"> <b>'.$mobile.'</b> is available! </div>'; 

    }
}
?>