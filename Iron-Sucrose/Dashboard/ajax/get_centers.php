<?php 
include('../includes/config.php');

if (isset($_POST['district'])) {
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    
    $query = "SELECT DISTINCT ccenter_name, center_code FROM ivis_facilities WHERE ccenter_name != ''";
    
    if (!empty($district)) {
        $query .= " AND districts = '$district'";
    }
    
    $query .= " ORDER BY ccenter_name";
    
    $result = mysqli_query($conn, $query);
    
    $centers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $centers[] = [
            'name' => $row['ccenter_name'],
            'code' => $row['center_code']
        ];
    }
    
    echo json_encode($centers);
}
?>