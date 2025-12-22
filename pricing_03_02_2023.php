<!DOCTYPE html>
<html lang="en">
  
   <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  
   <head>
      <link rel="icon" type="image/png" href="favicon.png">
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Starting as low as $8.95 per month, MQUAD offers affordable monthly plans for individuals, large and small groups as well as Enterprise Packages." />
      <title>Pricing - Home | MQUAD</title>
      <link href="Content/CSS/bootstrap/bootstrap.css" rel="stylesheet" />
      <link href="Content/CSS/Site.css" rel="stylesheet" />
      <script src="Content/Scripts/modernizr-2.6.2.js"></script>
   </head>
    <?php include('includes/header.php');?>
   <body>
  
      <div class="pricingOuter">
         <div class="pricingMain">
            <div class=header>
               <div class="headerImage">
                  <img src="Content/Images/pricing/pricing-circle.svg" alt="MQUAD Pricing" title="MQUAD Pricing Options" />
               </div>
               <div class="headerTitle">
                  <h2>PRICING</h2>
               </div>
               <div class="headerText">
                  <p>
                     MQUAD pricing adjusts automatically based on the size of your group—<b>see rates below*</b>
                     <br />All new users have access to MQUAD for their first month at <b>no cost</b><br />
                     Please note that a MQUAD account monthly cycle is based on account holder’s date of creation.<br />
                     For example, a cycle begun on May 6th will run to June 6th, to July 6th, and <br />so on—not calendar months.
                  </p>
               </div>
            </div>
            <div class="pricingBoxes">
               <div class="pricingBoxesHeader">
                  <h2>MONTHLY PLANS</h2>
               </div>
               <div class="pricingBox">
                  <div>
                     <div class="pricingBoxImage">
                        <img src="Content/Images/pricing/individual-plans-logo.svg" alt="MQUAD Individual Pricing Plans" title="Check out Individual Plans Offered by MQUAD" />
                     </div>
                     <div class="pricingContent">
                        <h4>Individual</h4>
                        <h1>$14.95<span class="usDollar">USD</span></h1>
                        <p>Per Active Month</p>
                     </div>
                  </div>
               </div>
               <div class="pricingBox">
                  <div>
                     <div class="pricingBoxImage">
                        <img src="Content/Images/pricing/individual-plans-logo.svg" alt="MQUAD Small Group Pricing Plans" title="Small Group Plans Offered by MQUAD" />
                     </div>
                     <div class="pricingContent">
                        <h4>Small Group (2-5 users)</h4>
                        <h1>$12.95<span class="usDollar">USD</span></h1>
                        <p>Per Active User Per Month</p>
                     </div>
                  </div>
               </div>
               <div class="pricingBox">
                  <div>
                     <div class="pricingBoxImage">
                        <img src="Content/Images/pricing/individual-plans-logo.svg" alt="MQUAD Student Pricing Plans" title="Student Plans Offered by MQUAD" />
                     </div>
                     <div class="pricingContent">
                        <h4>Student</h4>
                        <h1>$10.95<span class="usDollar">USD</span></h1>
                        <p>Per Active Month<br />with valid student ID</p>
                     </div>
                  </div>
               </div>
               <div class="pricingBox">
                  <div>
                     <div class="pricingBoxImage">
                        <img src="Content/Images/pricing/individual-plans-logo.svg" alt="MQUAD Large Group Pricing Plans" title="Large Group Plans Offered by MQUAD" />
                     </div>
                     <div class="pricingContent">
                        <h4>Large Group (6+ users)</h4>
                        <h1>$10.95<span class="usDollar">USD</span></h1>
                        <p>Per User Per Month</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="monthlyOffer">
               <h2>YOUR FIRST 30 DAYS FREE</h2>
               <div class="monthlyOfferContent">
                  <p>The system automatically handles the plans for each billing cycle based on the number of users in the account </p>
                  <div data-track-content data-content-name="Pricing page Signup Button" data-content-piece="Pricing Signup Button">
                     <a href="index.php">SIGN UP NOW</a>
                  </div>
               </div>
            </div>
            <div class="packages">
               <div class="blueBox">
                  <div>
                     <div class="blueBoxImage">
                        <img src="Content/Images/pricing/enterprise-logo.svg" alt="MQUAD Enterprise Pricing Available" title="View MQUAD Enterprise Package Solutions" />
                     </div>
                     <div class="blueBoxHeader">
                        <h3>MQUAD Enterprise and Premier Pricing</h3>
                     </div>
                     <div class="blueBoxContent">
                        <p>Designed for groups and organizations with exceptional management, technical, security, and/or end-user support needs. <a href="resources/articledetail/MQUADpremierenterpriseaccounts.html">See here</a> for more information on these programs. Feel free to contact our team if you are interested in learning more or have other needs we can help with. </p>
                     </div>
                  </div>
               </div>
               <div>
               </div>
            </div>
         </div>
      </div>
      <?php include('includes/footer.php');?>
      <script type="text/javascript">
         gtag('event', 'visit', {'event_category' : 'pricing page',
             'event_label': 'Pricing visit'
         });
         
         $(document).ready(function () {
             AnimateIn();
         });
         
         function AnimateIn() {
             TweenLite.from(".headerImage", .5, { delay: 0, alpha: 0 });
             TweenLite.from(".headerTitle", .5, { delay: .4, alpha: 0 });
             TweenLite.from(".pricingUpperContent", .5, { delay: .4, alpha: 0 });
             TweenLite.from(".pricingBoxesHeader", .5, { delay: .6, alpha: 0 });
             TweenLite.from(".pricingBoxes", .5, { delay: .7, alpha: 0, x: -1000 });
         };
      </script>
   </body>
   <!-- Mirrored from www.MQUAD.com/home/pricing by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 18 May 2022 04:57:40 GMT -->
</html>