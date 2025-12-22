<?php 
define('hostname','localhost'); //'65.1.180.162'
define('username','root');
define('password','indev@123');
define('database','unicef_db');
$conn = mysqli_connect(hostname,username,password,database) or die(mysqli_error());
mysqli_set_charset($conn,"utf8");
if(!$conn)
{
    echo "Connection failed.......!";
}
?>
<?php

if (isset($_POST['district_id'])) {
    $district_id = intval($_POST['district_id']);
    
    // Fetch IYCF centers for the selected district
    $sql = "SELECT id, center_name FROM iycf_centers WHERE district_id ='".$district_id."'";
    $sqlFac = mysqli_query($conn,$sql);
	echo '<option value="">-- Select IYCF Counselling Center --</option>';
    while($row = mysqli_fetch_array($sqlFac)) {
        echo '<option value="' . $row['id'] . '">' . $row['center_name'] . '</option>';
    }
	
}

?>

