<?php
include_once('mis/includes/config.php');


if(isset($_POST['orderID']) && isset($_POST['userID'])){
	
    mysqli_query($conn,"insert into pm_payments set OrderID='".$_POST['orderID']."',client_id='".$_POST['client_id']."',SubscriptionID='".$_POST['subscriptionID']."',UserID='".$_POST['userID']."',Amount='".$_POST['paidAmount']."',PaymentStatus='Pending',currency='".$_POST['currency']."',conversionRate='".$_POST['conversionRate']."',ipAddress='".$_POST['ipAddress']."',paymentCountry='".$_POST['paymentCountry']."',description='".$_POST['description']."',AmountInUSD='".$_POST['AmountInUSD']."'");
    $_SESSION['payment_id']=mysqli_insert_id($conn);
	//echo "insert into pm_payments set OrderID='".$_POST['orderID']."',SubscriptionID='".$_POST['subscriptionID']."',UserID='".$_POST['userID']."',Amount='".$_POST['paidAmount']."',PaymentStatus='Pending',currency='".$_POST['currency']."',conversionRate='".$_POST['conversionRate']."',ipAddress='".$_POST['ipAddress']."',paymentCountry='".$_POST['paymentCountry']."',description='".$_POST['description']."',AmountInUSD='".$_POST['AmountInUSD']."'";
	}

if(isset($_REQUEST['payment_id'])){
   $payment_ids=$_REQUEST['payment_id'];
   mysqli_query($conn,"update pm_payments set PaymentStatus='Completed',TransactionID='".$payment_ids."' where PaymentID='".$_SESSION['payment_id']."'");
  $sqlpayment=mysqli_query($conn,"select * from pm_payments where PaymentID='".$_SESSION['payment_id']."'");
  $datapayment=mysqli_fetch_object($sqlpayment);
  $packageDays=getone($conn,'pm_subscriptions','DurationDays','SubscriptionID',$datapayment->SubscriptionID);
 $startdate=date('Y-m-d h:i:s');
  $fdays=$packageDays.' days';
 $enddate=date('Y-m-d h:i:s',strtotime($startdate.' '.$fdays));
 mysqli_query($conn,"update pm_orders set Status='Completed' where OrderID='".$datapayment->OrderID."'");
 mysqli_query($conn,"insert into pm_usersubscriptions set UserID='".$datapayment->UserID."',OrderID='".$datapayment->OrderID."',PaymentID='".$_SESSION['payment_id']."',client_id='".$datapayment->client_id."',SubscriptionID='".$datapayment->SubscriptionID."',StartDate='".$startdate."',EndDate='".$enddate."',Status='Active'");
 //echo "insert into pm_usersubscriptions set UserID='".$datapayment->UserID."',client_id='".$datapayment->client_id."',SubscriptionID='".$datapayment->SubscriptionID."',StartDate='".$startdate."',EndDate='".$enddate."',Status='Active'";
 mysqli_query($conn,"call CheckUserSubscription($datapayment->client_id)");  
  }

?>