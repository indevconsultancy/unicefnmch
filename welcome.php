<?php include_once('includes/config.php'); ?>

<!DOCTYPE html>
<html lang="en"> 
   <meta http-equiv="content-type" content="text/html;charset=utf-8" /> 
   <head>
      <link rel="icon" type="image/png" href="../favicon.png">
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Get in touch with MQUAD if you need support, on-on-one training or just want to leave feedback.  Contacting MQUAD is just an email away." />
      <title>Welcome | MQUAD</title> 
      <link href="Content/CSS/bootstrap/bootstrapV5-3.min.css" rel="stylesheet" />
      <link href="Content/CSS/Site.css" rel="stylesheet" />
      <script src="Content/Scripts/modernizr-2.6.2.js"></script>
      
   </head>
   <style>
.message
{
	text-shadow:1px 1px 3px ;
	color: 6B5EA8;
	margin-left:100px;
	font-size:35px;
	text-align:center;
}
.message1
{
	text-shadow:1px 1px 2px ;
	color: 6B5EA8;
	font-size:25px;
	text-align:center;
	 margin-left: 80px;
}
.contactPageOuter .contactPageMain .header .headerTitle {
    position: relative;
    float: left;
    width: 100%;
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
    text-align: center;
    font-size: 2.4em;
    padding-bottom: 2.5em;
}
</style>
   <?php include('includes/header.php');?>
   <body>
      <div class="contactPageOuter">
         <div class="contactPageMain">
            <div class="header">
               <div class="headerImage">
                  <img src="assets/images/welcome.jpeg" alt="MQUAD prides itself on outstanding customer service." title="Get in Touch with MQUAD" />
               </div>
               <div class="headerTitle">
                  <P class="message">Thank you for the registration!</p>
				<p class="message1">Username and Password has been sent to your Email. </p> 
				 
               </div>
			     <div class="headerTitle mt-5 mb-5">
				 <a href="mis/index.php" type="button" class="btn btn-primary">Login</a>
				 </div>
            </div>
         </div>
      </div>
   <?php include('includes/footer.php');?>
 
   </body>
 </html>