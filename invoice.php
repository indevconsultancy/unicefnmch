<?php include_once('mis/includes/config.php');
function getonefield($conn, $tablename, $field, $qryfeild, $value)
{
    $sn = mysqli_query($conn, "select  $field  as field from $tablename where $qryfeild='" . $value . "' ") or die(mysqli_error());
    $dn = mysqli_fetch_object($sn);
    return ($dn->field);
}

function generateInvoiceCode($conn)
{
    $selectQuery = "SELECT MAX(SUBSTRING(invoiceID, 6)) AS last_code FROM pm_payments";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);
    $lastCode = $row['last_code'];
    $newCodeNumber = ($lastCode !== null) ? $lastCode + 1 : 1;
    $NewPACode = sprintf("INV%05d", $newCodeNumber);
    return $NewPACode;
}


if(isset($_REQUEST['oid']) && $_REQUEST['oid']!='')
{
$oid=base64_decode($_REQUEST['oid']);
$invoiceID=generateInvoiceCode($conn);
	
mysqli_query($conn,"update pm_payments set invoiceID='".$invoiceID."' where OrderID='".$oid."' and invoiceID is NULL");

	
$sqlOrder=mysqli_query($conn,"SELECT client_id,OrderID,SubscriptionID,BillingName,BillingEmail,BillingAddress,BillingCountryID FROM pm_orders where OrderID='".$oid."'");
$dataOrder=mysqli_fetch_object($sqlOrder);

$sqlPayment=mysqli_query($conn,"SELECT amount,PaymentDate,invoiceID,PaymentMethod,TransactionID,AmountInUSD,conversionRate,paymentCountry,currency,OrderID,PaymentStatus FROM pm_payments where OrderID='".$oid."' ");
$dataPayment=mysqli_fetch_object($sqlPayment); 

$sqlSubscription=mysqli_query($conn,"SELECT SubscriptionID,SubscriptionName,Price,SuscriptionType,Description FROM `pm_subscriptions` where SubscriptionID='".$dataOrder->SubscriptionID."'");
$dataSubscription=mysqli_fetch_object($sqlSubscription);

}

$countryName=getonefield($conn, 'country', 'country_name', 'country_id', $dataOrder->BillingCountryID);
$output='';
$qty=1; $tax=18;
$subtotal=round($dataPayment->AmountInUSD*$qty,2);
$output='
<section class="py-5 py-md-7">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-12 col-xl-12 col-xxl-12">
        <div class="row gy-3 mb-3">
          <div class="col-6">
            <h2 class="text-uppercase text-endx m-0">Invoice</h2>
			<h4>From</h4>
            <address>
              <strong>PopulationCouncil Consulting Pvt Ltd</strong><br>
              5A, GF, INDIA HABITAT CENTRE,<br> 
			  Institutional Area, Lodi Colony,<br>
			  New Delhi, Delhi 110003<br>
              India<br>
              Phone: (+91) 011 2464 2901<br>
              Email: info@pcconsulting.co.in
            </address>
          </div>
          <div class="col-6">
            <a class="d-block text-end" href="#!">
              <img src="https://mquad.org/Content/Images/logo-lg.png" class="img-fluid" alt="BootstrapBrain Logo" width="135" height="44">
            </a>
			 <!--<h1 class="text-uppercase text-endx m-0 text-right">MQUAD</h1>-->
          </div>
          
        </div>
        <div class="row mb-3">
          <div class="col-12 col-sm-6 col-md-8">
            <h4 style="margin-top: 0px!important;">Bill To</h4>
            <address>
              <strong>'.$dataOrder->BillingName.'</strong><br>
              '.$dataOrder->BillingAddress.'<br>'.$countryName.'<br>
              Email: '.$dataOrder->BillingEmail.'
            </address>
          </div>
          <div class="col-12 col-sm-6 col-md-4">
            <h4 class="row">
              <span class="col-7">Invoice</span>
              <span class="col-5 text-sm-end">'.$invoiceID.'</span>
            </h4>
            <div class="row">
              <span class="col-7">Amount</span>
              <span class="col-5 text-sm-end">'.$dataPayment->amount.''.$dataPayment->currency.'</span>
			  <span class="col-7">Order ID </span>
              <span class="col-5 text-sm-end">#'.$dataOrder->OrderID.'</span>
			  <!--<span class="col-7">Amount in USD</span>
              <span class="col-5 text-sm-end">'.$dataPayment->AmountInUSD.'</span>
              <span class="col-7">Conversion Rate</span>
              <span class="col-5 text-sm-end">'.round($dataPayment->conversionRate,2).''.$dataPayment->currency.'/USD</span>-->
              <span class="col-7">Invoice Date</span>
              <span class="col-5 text-sm-end">'.date('d/m/Y',strtotime($dataPayment->PaymentDate)).'</span>
              <!--<span class="col-7">Payment Mode</span>
              <span class="col-5 text-sm-end">'.$dataPayment->PaymentMethod.'</span>-->
			  <span class="col-7">Payment Status</span>
              <span class="col-5 text-sm-end">'.$dataPayment->PaymentStatus.'</span>
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-12">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col" class="text-uppercase">Product</th>
					<th scope="col" class="text-uppercase text-end">Qty</th>
                    <th scope="col" class="text-uppercase text-end">Unit Price</th>
                    <th scope="col" class="text-uppercase text-end">Amount</th>
                  </tr>
                </thead>
                <tbody class="table-group-divider">
                  <tr>
				    <td>'.$dataSubscription->SubscriptionName.' - '.$dataSubscription->SuscriptionType.' Subscription  </td>
					<th class="text-end" >'.$qty.'</th>
                    <td class="text-end">$'.round($dataPayment->AmountInUSD,2).'</td>
                    <td class="text-end">$'.$subtotal.'</td>
                  </tr>
                  
                  <tr class="table-group-divider">
                    <td colspan="3" class="text-end">Subtotal</td>
                    <td class="text-end">$'.$subtotal.'</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="text-end">GST('.$tax.'%)</td>
                    <td class="text-end">$'.$taxable=round($subtotal*($tax/100),2).'</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="text-end">Total (In USD)</td>
                    <td class="text-end">$'.($subtotal+$taxable).'</td>
                  </tr>
                 
				  <tr>
                    <th scope="row" colspan="3" class="text-uppercase text-end">Total Payble in ('.$dataPayment->currency.')</th>
                    <td class="text-end">'.$dataPayment->amount.'</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!--<div class="row">
          <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary mb-3">Download Invoice</button>
            <button type="submit" class="btn btn-danger mb-3">Submit Payment</button>
          </div>
        </div>-->
      </div>
    </div>
  </div>
</section>';

include('pdf.php');

	$file_name = 'invoices/'.$invoiceID.'.pdf';
	$file_name1 = $_SERVER["DOCUMENT_ROOT"].'/'.$file_name;
	$admitURL = 'https://mquad.org/'.$file_name;
	$html_code = '<link href="https://mquad.org/Content/CSS/bootstrap/bootstrapV5-3.min.css" rel="stylesheet" /><link href="https://mquad.org/Content/CSS/Site.css" rel="stylesheet" />';
	echo $html_code .= $output;
	
	$pdf = new Pdf();
	$pdf->load_html($html_code);
	$pdf->render();
	$file = $pdf->output();
	file_put_contents($file_name, $file);
    $admitcard=base64_encode($file_name1);

  ?>
