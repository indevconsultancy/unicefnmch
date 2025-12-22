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
?>