<?php include('includes/config.php');?>
<?php include('includes/functions.php');?>
<?php
  
  date_default_timezone_set("Asia/Calcutta"); 
   $t = date("H:i:00");
   $mydate= date('Y-m-d H:i:s');
   $visitDate = date('Y-m-d', strtotime('+1 day'));
   if($t<'20:00:00' && $t>'08:00:00'){
   $sql="select id,mobile from pw_iron_visit where visit_status='0' and visit_date='".$visitDate."' and ivr_scheduled_status=0 limit 0,10";
   $sch_list=mysqli_query($conn, $sql);
   if(mysqli_num_rows($sch_list)>0)
   {
   while($getfarmer = mysqli_fetch_object($sch_list)){
     $mobiles=$getfarmer->mobile; 
     $id=$getfarmer->id;
     /////////////////////////////////////
     $path='http://115.241.99.180/indev/call-to-question.php?did=1135352929&number=0'.$mobiles.'&audio_name=%2Fetc%2Fasterisk%2Fsounds%2Fcomplition';
	 $check = file_get_contents($path);
   if($check){
       $ress=json_decode($check); 
    //$callid=$ress->call_id;
	$callid= mt_rand(1000000, 9999999);
    //$status=$ress->status;
   $mydate= date('Y-m-d H:i:s');
   if($ress->Len=='4'){
	  //echo "update pw_iron_visit set callid='".$callid."',created_at='".$mydate."',ivr_scheduled_status='3' where id='".$id."'";
       mysqli_query($conn,"update pw_iron_visit set callid='".$callid."',created_at='".$mydate."',ivr_scheduled_status='2' where id='".$id."'");  
   }     
   else{
	   //echo "update pw_iron_visit set callid='".$callid."',created_at='".$mydate."',ivr_scheduled_status='2' where id='".$id."'";
      mysqli_query($conn,"update pw_iron_visit set callid='".$callid."',created_at='".$mydate."',ivr_scheduled_status='2' where id='".$id."'");  
   }
	}
   
	}
	}
 }
?>