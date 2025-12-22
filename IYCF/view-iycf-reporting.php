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

function getone($conn,$tablename,$field,$qryfeild,$value)
{
//echo  "select $field from $tablename where $qryfeild='".$value."'";
$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."'")or die(mysqli_error());
$dn=mysqli_fetch_object($sn);
	return ($dn->$field);
}

 ?>

<?php if(isset($_REQUEST['rid']) && $_REQUEST['rid']>0)
{   
	$sqlResult=mysqli_query($conn,"select * from iycf_monthly_reporting where id='".$_REQUEST['rid']."'");
	$dataResult=mysqli_fetch_object($sqlResult);
	$reportTitle='IYCF-Monthly-Report'.'-'.$dataResult->iycf_center_name.'-'.date('M-Y',strtotime($dataResult->reporting_period)).'.xlsx';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View IYCF Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	    
		<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
		
	<style type="text/css">
	    table {width:100%;}
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Calibri"; font-size:16px; }
		a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
		comment { display:none;  } 
		.form-control {
    display: block;
    width: 100%;
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: var(--bs-body-color);
    background-color: #f7f0f0!important;
    background-clip: padding-box;
    border: var(--bs-border-width) solid #707376!important;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: var(--bs-border-radius);
	</style>
</head>
<body>
    <!-- Header Section -->
     <header class="bg-primary text-white text-center py-3">
        <img src="bnmch.PNG" alt="Logo" style="max-height: 60px;"> <span style="font-size:25px; font-weight:700; width:200px;">State IYCF Resource Center, NMCH</span>
    </header>

    <div class="container mt-3">
	
    <table id="simpleTable1" cellspacing="0" border="0" cellpadding="5">
	<colgroup width="50"></colgroup>
	<colgroup width="154"></colgroup>
	<colgroup width="187"></colgroup>
	<colgroup width="454"></colgroup>
	<colgroup span="2" width="100"></colgroup>
	<colgroup width="112"></colgroup>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=7 height="58" align="center" valign=middle bgcolor="#F2F2F2" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;आई. वाई. सी. एफ. मासिक प्रतिवेदन प्रपत्र ( जिला एवं IYCF Counselling केन्द्र हेतु )&quot;}"><b><font face="Noto Sans" size=6 color="#000000">आई. वाई. सी. एफ. मासिक प्रतिवेदन प्रपत्र (IYCF Counselling केन्द्र हेतु )</font></b></td>
		</tr>
	<tr>
	    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;जिला का नामः&quot;}"><font face="Noto Sans" color="#000000">जिला का नामः</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"  align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}">
                           <?=$dataResult->district_name?>
		</td>
		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;IYCF परामर्श केन्द्र का नाम:&quot;}"><div style="vertical-align:middle"><div style="float:left; padding-top: 6px; width:50%"><font face="Noto Sans" color="#000000">IYCF परामर्श केन्द्र का नाम:</font></div><div style="float:right; width:50%"> 
                              <?=$dataResult->iycf_center_name?></div></div></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font face="Noto Sans" color="#000000">माह:</font>
		</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan="2" height="42" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;माह:&quot;}">
                             <?=date('M-Y',strtotime($dataResult->reporting_period))?>
		</td>
		</tr>
	<tr>
		<td height="13" align="left" valign=bottom bgcolor="#4472C4" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td align="left" valign=bottom bgcolor="#4472C4" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td align="center" valign=bottom bgcolor="#4472C4" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td align="left" valign=bottom bgcolor="#4472C4" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td align="left" valign=bottom bgcolor="#4472C4" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td align="left" valign=bottom bgcolor="#4472C4" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td align="left" valign=bottom bgcolor="#4472C4" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="40" align="left" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;क्रमांक&quot;}"><b><font face="Noto Sans" color="#000000">क्रमांक</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;लाभार्थी विवरणी&quot;}"><b><font face="Noto Sans" color="#000000">लाभार्थी विवरणी</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;क्र0 सं0&quot;}"><b><font face="Noto Sans" color="#000000">क्र0 सं0</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;सूचकांक&quot;}"><b><font face="Noto Sans" color="#000000">सूचकांक</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कुल संख्या &quot;}"><b><font face="Noto Sans" color="#000000">कुल संख्या </font></b></td>
		</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="39" align="center" valign=bottom sdval="1" sdnum="1033;"><font color="#000000">1</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कुल गर्भवती महिलाओे की संख्या जिन्हे IYCF पर परामर्श दिया गया ( तीसरी तिमाही (7-9 माह ) &quot;}"><font face="Noto Sans" color="#000000">कुल गर्भवती महिलाओे की संख्या जिन्हे IYCF पर परामर्श दिया गया ( तीसरी तिमाही (7-9 माह ) </font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}" colspan="3" ><font color="#000000" ><?=$dataResult->adviceToPW?></font></td>
		
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="39" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;सूचकांक&quot;}"><font face="Noto Sans" size=3 color="#000000">सूचकांक</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;संख्या (लड़की)&quot;}"><font face="Noto Sans" size=3 color="#000000">संख्या (लड़की)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;संख्या (लड़का)&quot;}"><font face="Noto Sans" size=3 color="#000000">संख्या (लड़का)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कुल संख्या &quot;}"><font face="Noto Sans" size=3 color="#000000">कुल संख्या </font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=5 height="195" align="center" valign=middle sdval="2" sdnum="1033;"><font color="#000000">2</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=5 align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कुल नवजात की संख्या उम्र (0-28 दिन)&quot;}"><font face="Noto Sans" color="#000000">कुल नवजात की संख्या उम्र (0-28 दिन)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="2.1" sdnum="1033;"><font color="#000000">2.1</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;माता\/देखभालकर्त्ता की संख्या जिन्हें IYCF पर परामर्श दिया गया ।&quot;}"><font face="Noto Sans" color="#000000">माता/देखभालकर्त्ता की संख्या जिन्हें IYCF पर परामर्श दिया गया ।</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->nb028_adviceToMother_g?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->nb028_adviceToMother_b?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->nb028_adviceToMother_all?></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="2.2" sdnum="1033;"><font color="#000000">2.2</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;एक घण्टे के अन्दर स्तनपाल शुरू कराये गये बच्चों की संख्या&quot;}"><font face="Noto Sans" color="#000000">एक घण्टे के अन्दर स्तनपाल शुरू कराये गये बच्चों की संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_breastfeedIn1hr_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_breastfeedIn1hr_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_breastfeedIn1hr_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="2.3" sdnum="1033;"><font color="#000000">2.3</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कम वज़न (2.5 कि0ग्रा0 से कम) वाले नवजात शिशुओं की संख्या&quot;}"><font face="Noto Sans" color="#000000">कम वज़न (2.5 कि0ग्रा0 से कम) वाले नवजात शिशुओं की संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_lbw_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_lbw_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_lbw_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="2.4" sdnum="1033;"><font color="#000000">2.4</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;नाटापन वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">नाटापन वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->nb028_stunted_g?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->nb028_stunted_b?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->nb028_stunted_all?></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="2.5" sdnum="1033;"><font color="#000000">2.5</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;दुबलापन वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">दुबलापन वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_wasted_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_wasted_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->nb028_wasted_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="39" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;(सूचकांक)&quot;}"><font face="Noto Sans" color="#000000">(सूचकांक)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;संख्या (लड़की)&quot;}"><font face="Noto Sans" color="#000000">संख्या (लड़की)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;संख्या (लड़का)&quot;}"><font face="Noto Sans" color="#000000">संख्या (लड़का)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कुल संख्या &quot;}"><font face="Noto Sans" color="#000000">कुल संख्या </font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=6 height="234" align="center" valign=middle sdval="3" sdnum="1033;"><font color="#000000">3</font></td>
		<td style="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=6 align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;1-5 माह वाले बच्चे की विवरणी&quot;}"><font face="Noto Sans" color="#000000">1-5 माह वाले बच्चे की विवरणी</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3.1" sdnum="1033;"><font color="#000000">3.1</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;माता\/देखभालकर्त्ता की संख्या जिन्हें IYCF पर परामर्श दिया गया ।&quot;}"><font face="Noto Sans" color="#000000">माता/देखभालकर्त्ता की संख्या जिन्हें IYCF पर परामर्श दिया गया ।</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_adviceToMother_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_adviceToMother_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_adviceToMother_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3.2" sdnum="1033;"><font color="#000000">3.2</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;छः माह तक केवल स्तनपाल करने वाले बच्चों की संख्या&quot;}"><font face="Noto Sans" color="#000000">छः माह तक केवल स्तनपाल करने वाले बच्चों की संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_brestfeedOnly6m_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_brestfeedOnly6m_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_brestfeedOnly6m_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3.3" sdnum="1033;"><font color="#000000">3.3</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कम वज़न वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">कम वज़न वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_undeweight_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_undeweight_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_undeweight_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3.4" sdnum="1033;"><font color="#000000">3.4</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;नाटापन वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">नाटापन वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_stunted_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_stunted_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_stunted_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3.5" sdnum="1033;"><font color="#000000">3.5</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;दुबलापन वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">दुबलापन वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_wasted_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_wasted_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_wasted_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3.6" sdnum="1033;"><font color="#000000">3.6</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;एन.आर.सी. में रेफर किये गये बच्चों की संख्या&quot;}"><font face="Noto Sans" color="#000000">एन.आर.सी. में रेफर किये गये बच्चों की संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_referToNRC_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_referToNRC_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child1to5_referToNRC_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="39" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;(सूचकांक)&quot;}"><font face="Noto Sans" size=3 color="#000000">(सूचकांक)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;संख्या (लड़की)&quot;}"><font face="Noto Sans" size=3 color="#000000">संख्या (लड़की)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;संख्या (लड़का)&quot;}"><font face="Noto Sans" size=3 color="#000000">संख्या (लड़का)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कुल संख्या &quot;}"><font face="Noto Sans" size=3 color="#000000">कुल संख्या </font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=6 height="234" align="center" valign=middle sdval="4" sdnum="1033;"><font color="#000000">4</font></td>
		<td style="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=6 align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;6-8 माह वाले बच्चे की विवरणी&quot;}"><font face="Noto Sans" color="#000000">6-8 माह वाले बच्चे की विवरणी</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4.1" sdnum="1033;"><font color="#000000">4.1</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;माता\/देखभालकर्त्ता की संख्या जिन्हें IYCF पर परामर्श दिया गया ।&quot;}"><font face="Noto Sans" color="#000000">माता/देखभालकर्त्ता की संख्या जिन्हें IYCF पर परामर्श दिया गया ।</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_adviceToMother_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_adviceToMother_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_adviceToMother_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4.2" sdnum="1033;"><font color="#000000">4.2</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;नाटापन वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">नाटापन वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_stunted_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_stunted_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_stunted_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4.3" sdnum="1033;"><font color="#000000">4.3</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;दुबलापन वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">दुबलापन वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_wasted_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_wasted_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_wasted_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4.4" sdnum="1033;"><font color="#000000">4.4</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;एन.आर.सी. में रेफर किये गये बच्चों की संख्या&quot;}"><font face="Noto Sans" color="#000000">एन.आर.सी. में रेफर किये गये बच्चों की संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_referToNRC_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_referToNRC_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_referToNRC_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4.5" sdnum="1033;"><font color="#000000">4.5</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;उपरी आहार शुरू करने वाले कुल बच्चों की संख्या&quot;}"><font face="Noto Sans" color="#000000">उपरी आहार शुरू करने वाले कुल बच्चों की संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_complementryFood_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_complementryFood_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_complementryFood_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4.6" sdnum="1033;"><font color="#000000">4.6</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;जिन्हें 2-3 बार आहार एवं 1-2 बार नाश्ता खिलाये गये कुल बच्चों की संख्या &quot;}"><font face="Noto Sans" color="#000000">जिन्हें 2-3 बार आहार एवं 1-2 बार नाश्ता खिलाये गये कुल बच्चों की संख्या </font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_2timesFood_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_2timesFood_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child6to8_2timesFood_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="39" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font size=3 color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font size=3 color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font size=3 color="#000000"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;(सूचकांक)&quot;}"><font face="Noto Sans" size=3 color="#000000">(सूचकांक)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;संख्या (लड़की)&quot;}"><font face="Noto Sans" size=3 color="#000000">संख्या (लड़की)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;संख्या (लड़का)&quot;}"><font face="Noto Sans" size=3 color="#000000">संख्या (लड़का)</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कुल संख्या &quot;}"><font face="Noto Sans" size=3 color="#000000">कुल संख्या </font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=6 height="234" align="center" valign=middle sdval="5" sdnum="1033;"><font color="#000000">5</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=6 align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;9-23 माह वाले बच्चे की विवरणी&quot;}"><font face="Noto Sans" color="#000000">9-23 माह वाले बच्चे की विवरणी</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="5.1" sdnum="1033;"><font color="#000000">5.1</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;माता\/देखभालकर्त्ता की संख्या जिन्हें IYCF पर परामर्श दिया गया ।&quot;}"><font face="Noto Sans" color="#000000">माता/देखभालकर्त्ता की संख्या जिन्हें IYCF पर परामर्श दिया गया ।</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_adviceToMother_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_adviceToMother_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_adviceToMother_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="5.2" sdnum="1033;"><font color="#000000">5.2</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;नाटापन वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">नाटापन वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_stunted_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_stunted_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_stunted_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="5.3" sdnum="1033;"><font color="#000000">5.3</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;दुबलापन वाले बच्चों की कुल संख्या&quot;}"><font face="Noto Sans" color="#000000">दुबलापन वाले बच्चों की कुल संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_wasted_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_wasted_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_wasted_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="5.4" sdnum="1033;"><font color="#000000">5.4</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;एन.आर.सी. में रेफर किये गये बच्चों की संख्या&quot;}"><font face="Noto Sans" color="#000000">एन.आर.सी. में रेफर किये गये बच्चों की संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_referToNRC_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_referToNRC_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_referToNRC_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="5.5" sdnum="1033;"><font color="#000000">5.5</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;उपरी आहार शुरू करने वाले कुल बच्चों की संख्या&quot;}"><font face="Noto Sans" color="#000000">उपरी आहार शुरू करने वाले कुल बच्चों की संख्या</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_complementryFood_g?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_complementryFood_b?></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><?=$dataResult->child9to23_complementryFood_all?></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="5.6" sdnum="1033;"><font color="#000000">5.6</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;जिन्हें 2-3 बार आहार एवं 1-2 बार नाश्ता खिलाये गये कुल बच्चों की संख्या &quot;}"><font face="Noto Sans" color="#000000">जिन्हें 2-3 बार आहार एवं 1-2 बार नाश्ता खिलाये गये कुल बच्चों की संख्या </font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="middle" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->child9to23_2timesFood_g?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->child9to23_2timesFood_b?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><font color="#000000"><?=$dataResult->child9to23_2timesFood_all?></font></td>
	</tr>
</table>
 <div class="text-center mt-4">
               <button type="submit" id="button-excel" class="btn btn-secondary"> <i class="fas fa-file-excel"></i> Export to excel</button></td>
				</div>
</br>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
  
    document.addEventListener("input", function (e) {
        if (e.target.matches(".girls_count, .boys_count")) {
            const row = e.target.closest("tr");
            const girlsCount = parseInt(row.querySelector(".girls_count").value) || 0;
            const boysCount = parseInt(row.querySelector(".boys_count").value) || 0;
            const totalCount = girlsCount + boysCount;
            row.querySelector(".total_count").value = totalCount;
        }
    });
</script>

<SCRIPT>
    let button = document.querySelector("#button-excel");

    button.addEventListener("click", (e) => {
        let table = document.querySelector("#simpleTable1");
        TableToExcel.convert(table, {
            name: "<?=$reportTitle?>", // Set your desired file name here
            sheet: {
                name: "IYCF Monthly Report" // Set the sheet name here
            }
        });
    });

</SCRIPT>

<script>
        $(document).ready(function() {
            // AJAX call to update the IYCF centers based on selected district
            $('#district').on('change', function() {
                let districtId = $(this).val();

                if (districtId) {
                    $.ajax({
                        url: 'get_iycf_centers.php',
                        type: 'POST',
                        data: { district_id: districtId },
                        success: function(data) {
                            $('#iycf_centers').html(data);
                        },
                        error: function() {
                            alert('Error fetching IYCF centers.');
                        }
                    });
                } else {
                    $('#iycf_centers').html('<option value="">-- Select IYCF Counselling Center --</option>');
                }
            });
        });
    </script>

<!-- ************************************************************************** -->
</body>

</html>
