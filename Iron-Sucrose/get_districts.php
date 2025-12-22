<?php
define('hostname','localhost'); //'65.1.180.162'
define('username','unicef_db');
define('password','unicef_dblean@!pA');
define('database','unicef_db');

$conn = mysqli_connect(hostname,username,password,database) or die(mysqli_error());
mysqli_set_charset($conn,"utf8");
if(!$conn)
{
    echo "Connection failed.......!";
}

$district = $_POST['district'];
echo "<option value=''>-- स्वास्थ्य केंद्र--</option>";
$sql=mysqli_query($conn,"select * from ivis_facilities1 where districts='".$district."'");
while($data=mysqli_fetch_object($sql))
{
 echo "<option value='" . $data->ccenter_name . "'>" . $data->center_hindi . "</option>";	
}

?>