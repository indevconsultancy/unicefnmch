<?php session_start(); ?>
<?php include('includes/config.php'); ?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/php;charset=utf-8" />

<head>
   <link rel="icon" type="image/png" href="favicon.png">
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="A bundle of solutions that assist you in generating, managing and visualizing data for programs, policies, and actions." />
   <title>Home | AAKLAN </title>
   <link href="Content/CSS/bootstrap/bootstrapV5-3.min.css" rel="stylesheet" />
   <link href="Content/CSS/Site.css" rel="stylesheet" />
   <link href="Content/CSS/main.css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="assets/css/rs-spacing.css">
   <link href="style.css" rel="stylesheet" />
   <script src="Content/Scripts/modernizr-2.6.2.js"></script>
   <link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css" />

</head>
<style type="">
   .introPanel > div > div > .homeIntroBoxText > h3 {
      margin-top: 0;
      margin-bottom: 20px;
      }
      h3 {
      font-family: 'Poppins', sans-serif;
      color: white;
      margin: 0 0 26px;
      line-height: 1.2;
      }
      .ml-5{
      margin-top: 74px;
      margin-left:-26px;
      background-color:white;
      }
      .awesomePanel .homeAwesomeBox {
      min-height: auto;
      }
      .homeAwesomeBox > .div-box {
      background-color: #fff;
      text-align: left;
      padding: 36px 15px 36px 15px;
      border-style: solid;
      border-width: 0px 0px 0px 0px;
      box-shadow: 0px 0px 10px 0px #eee;
      position: relative;
      display: -webkit-box;
      display: -webkit-flex;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -webkit-flex-direction: column;
      -ms-flex-direction: column;
      flex-direction: column;
      width: 100%;
      transition: all .9s ease 0s;
      min-height: 407px;
      display: block;
      border-radius: 4px;
      overflow: hidden;
		  
      }
      .awesomePanel .homeAwesomeBox {
      float: left;
      min-height: 1px;
      height: 343px;
      }
      .cta_btn {
      font-size: 17px;
      line-height: 26px;
      font-weight: 600;
      text-transform: capitalize;
      cursor: pointer;
      width: 100%;
      box-shadow: none;
      border: none;
      display: block;
      transition: all 0.4s;
      z-index: 1;
      padding: 15px 40px 15px 40px;
      text-align: center;
      background: rgb(139 198 63);
      color: white;
      text-decoration: none;
      transition: background .3s ease;
      margin-top:15px;
      }
      .card_title_sub{
      text-align: center;
      }
      .fa-fa-icon{
      text-align: center;
      }
      .features.icon-item li .list-icon i {
      font-size: 13px;
      width: 1.25rem;
      width: 25px;
      height: 25px;
      background: #8bc63f;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      }
      .modal-title{
      color:#003b64;
      }
      .homeAwesomeBox {
      margin-left: 3rem;
      padding: 2rem;
      min-width: 30%;
      padding-bottom: 5rem;
      }
      .modal-body {
      min-width: 100%;
      }
      .modal-body input, .modal-body select, .modal-body textarea {
      max-width: 100%;
      }
      .modal-body .box {
      border: none !important;
      }	
      .col-form-label{
      font-weight: 300;
      }
   </style>
<style>
   .box-option {
      padding: 0.5em;
      width: 100%;
      margin: 0.5em;
   }

   .box-2-option {
      padding: 0.5em;
      float: left;
      width: calc(100%/2 - 1em);
      display: inline-block !important;
   }

   .options label,
   .options input {
      width: 4em;
      padding: 0.5em 1em;
   }

   .hide {
      display: none;
   }

   img {
      max-width: 100%;
   }

   .options label,
   .options input {
      width: 5em;
      padding: 0.5em 1em;
   }

   #imgcode {
      width: 97px;
      height: 200px;
   }

   #statususer {
      color: red;
   }

   .introPanel {
      /* background: linear-gradient(260deg, #fff, #1cabe2); */
      /* background: linear-gradient(to right, #1cabe2 50%, #fff 50%); */
      background: linear-gradient(241deg, #1cabe2, #ffffffd9);
      /* background: linear-gradient(89deg, #f47c20, #ffffffd9); */
      /* background: #1cabe2; */
   }

   .introPanel>div>div>.homeIntroBoxText {
      text-align: left;
   }

   @media (min-width: 768px) {
      .introPanel>div>div>.homeIntroImage>img {
         margin-bottom: 36px;
         min-height: 324px;
         max-height: 324px;
      }
   }

   .box {
      /*background: linear-gradient(229deg, #ffffff, #f7f7f7);*/
      background: linear-gradient(229deg, #f47c20, #f47c20);
      border: 2px solid rgb(194 194 194 / 33%);
   }

   .box:before,
   .box:after,
   .box .box-content:before,
   .box .box-content:after {
      transform: scale(1);
      background: #fff;
   }

   .box img {
      opacity: 0.25;
      transform: scale(1.25);
   }

   .box .inner-content {
      opacity: 1;
      top: 50%;
   }

   .awesomePanel .homeAwesomeTitle h2::after {
      background: #8cc63f;
      content: unset;
      display: none;
      height: 3px;
      left: calc(50% - 165px);
      bottom: 18px;
      min-width: 400px;
      margin: 0 auto;
      width: auto;
   }

   .badge-primary {
      background-color: #003b64;
   }

   .box .inner-content span {
      text-align: justify !important;
      display: block;
   }

   .introPanel>div>div>.homeIntroBoxText {
      padding-top: 73px;
   }
</style>

<body>
   <div class="content">
      <?php include('includes/header.php'); ?>
   </div>
   <div class="introPanel py-5">
      <div class="row align-items-center justify-content-between">
         <div class="col-md-4">
            <img src="mis/img/unicef.png" alt="img-fluid w-100">
         </div>
         <div class="col-md-6">
            <div class="homeIntroBoxText pt-0 w-100 text-dark">
               <h3 id="greatResearch" class="text-dark" style="opacity: 1;">
                  AAKLAN
               </h3>
               <h3 id="madeEasy" class="text-dark" style="opacity: 1; font-size:22px!important; ">
                  <!--<span>Made</span> Easy!-->Suposhit Bihar
               </h3>
               <p id="introText">Conduct surveys to collect quality data through simple web interface & export it to different statistical software formats with ease and store data in a reliable server infrastructure.</p>
            </div>
         </div>

      </div>
   </div>
   <div class="awesomePanel">
      <div class="homeAwesomeTitle">
         <div>
            <h2>Collect, Manage, Store, Visualize & Share Data</h2>
         </div>
      </div>
      <div class="row">

         <div class="col-md-4 col-sm-6">
            <a href="#!">
               <div class="box">
                  <img src=" " />
                  <div class="box-content">
                     <div class="inner-content">
                        <h3 class="title">Enter Data Anywhere</h3>
                        <span class="">Collect data using digital forms on Web or Mobile Applications from any location</span>
                        <ul class="icon">
                     </div>
                  </div>
               </div>
            </a>
         </div>

         <div class="col-md-4 col-sm-6">
            <a href="#!">
               <div class="box">
                  <img src=" " />
                  <div class="box-content">
                     <div class="inner-content">
                        <h3 class="title">Data Management</h3>
                        <span class="">A tool to make personelized dashboard and reports to handle data better</span>
                        <ul class="icon">
                     </div>
                  </div>
               </div>
            </a>
         </div>
         <div class="col-md-4 col-sm-6">
            <a href="#!">
               <div class="box">
                  <img src=" " />
                  <div class="box-content">
                     <div class="inner-content">
                        <h3 class="title">Analyze & Visualize</h3>
                        <span class="">Display the data with a standard visual format and a customizable statistical tool, and allow exporting of data for additional analysis</span>
                     </div>
                  </div>
               </div>
            </a>
         </div>

         <div class="col-md-4 col-sm-6">
            <a href="#!">
               <div class="box">
                  <img src=" " />
                  <div class="box-content">
                     <div class="inner-content">
                        <h3 class="title">Privacy & Security</h3>
                        <span class="">Keep your data on a strong, secure with end-to-end encryption, and dependable cloud server infrastructure</span>
                        <ul class="icon">
                     </div>
                  </div>
               </div>
            </a>
         </div>
      </div>
      <div class="row">
         <div class="col-md-4 col-sm-6">
            <a href="#!">
               <div class="box">
                  <img src=" " />
                  <div class="box-content">
                     <div class="inner-content">
                        <h3 class="title">Data Quality</h3>
                        <span class="">First to provide extensive types of paradata to monitor and improve data quality</span>
                        <ul class="icon">
                     </div>
                  </div>
               </div>
            </a>
         </div>
         <div class="col-md-4 col-sm-6">
            <a href="#!">
               <div class="box">
                  <img src=" " />
                  <div class="box-content">
                     <div class="inner-content">
                        <h3 class="title">Data Repository</h3>
                        <span class="">Provides facility to store all documents including data and metadata</span>
                        <ul class="icon">
                     </div>
                  </div>
               </div>
            </a>
         </div>
         <div class="col-md-4 col-sm-6">
            <a href="#!">
               <div class="box">
                  <img src=" " />
                  <div class="box-content">
                     <div class="inner-content">
                        <h3 class="title">Cloud Based</h3>
                        <span class="">Data Accessibility - Anytime, Anywhere, Any networked device</span>
                     </div>
                  </div>
               </div>
            </a>
         </div>
         <div class="col-md-4 col-sm-6">
            <a href="#!">
               <div class="box">
                  <img src=" " />
                  <div class="box-content">
                     <div class="inner-content">
                        <h3 class="title">Knowledge driven</h3>
                        <span class="">Our objective is to assist research that can lead to knowledge building</span>
                     </div>
                  </div>
               </div>
            </a>
         </div>
      </div>
   </div>
   <!-- <div class="whatIsDedoosePanel">
      <div>
         <div>
            <div class="title">
               <h2>Overview of AAKLAN </h2>
            </div>
            <div class="videoContainer" style="padding-top: 33.44px;">
               <div>
                  <div class="rs-videos choose-video">
                     <video id="introVideoPreview" controls style="height: 100% ; width:100%;">
                        <source src="assets/images/mquad-ppt.mp4" type="video/mp4">
                     </video>
                   
                  </div>
               </div>
            </div>
            <div class="description">
               This brief summary highlights AAKLAN   distinctive features, aimed at improving your knowledge and experience of the application.

            </div>
            <div class="viewButton">
            </div>
         </div>
      </div>
   </div> -->
   <!-- <div class="tryDedoosePanel">
      <div>
         <div>
            <div class="imageBox">
               <img src="Content/Images/Icons/clipart379064.png" alt="Try MQUAD Free, Today!  (Box Art)" title="Get Started with a Free Trial of MQUAD" />
            </div>
            <div class="descriptionPanel">
               <div>
                  <div class="header">
                     <h2>Try AAKLAN  Now !</h2>
                     <h3>FREE first 7 days</h3>
                     <div class="viewButton">
                        <a href="#" class="btn-theme-primary">Coming Soon...</a>
                     </div>
                
                  </div>
                  <div class="buttons">
                     <div class="signUp" data-track-content data-content-name="Try Now Signup Button" data-content-piece="Try now to signup page">
                      </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div> -->
   <!--<div class="blogPanel">
         <div>
            <div>
               <div class="title">
                  <h2>Membership</h2>
               </div>
               <div class="row ml-5">
                  <div class="homeAwesomeBox col-md-4 min-height-max">
                     <div class="membership-box" style=" background-color: #f5b500;height: 23px;color: white;text-align: center;"> POPULAR
                     </div>
                     <div class="div-box">
                        <div class="homeAwesomeBoxInnerContainer">
                           <h4 class="card_title_sub">BASIC</h4>
                           <h2 class="fa-fa-icon"><i class="fa fa-usd" aria-hidden="true"></i>0</h2>
                           <div class="awesomeBoxContent">
                              <ul class="icon-item features">
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">100 interviews in 1 survey</span>
                                 </li>
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">Collect data using the web or mobile app with electronic forms</span>
                                 </li>
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">Data export for further analysis</span>
                                 </li>
                              </ul>
                           </div>
                           <div>
                              <button class="cta_btn" data-toggle="modal" data-target="#regmemem1" value="1" onclick="memRegister(this.value)">SIGN UP</button>
                             </div>
                        </div>
                     </div>
                  </div>
                  <div class="homeAwesomeBox col-md-4 min-height-max">
                     <div class="membership-box" style=" background-color: blue;height: 23px;color: white;text-align: center;"> MOST POPULAR
                     </div>
                     <div class="div-box">
                        <div class="homeAwesomeBoxInnerContainer">
                           <h4 class="card_title_sub">STANDARD</h4>
                           <h2 class="fa-fa-icon"><i class="fa fa-usd" aria-hidden="true"></i>10</h2>
                           <div class="awesomeBoxContent">
                              <ul class="icon-item features">
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">500 interviews in 5 surveys</span>
                                 </li>
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">Collect data using the web or mobile app with electronic forms</span>
                                 </li>
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">Collect data from anywhere even without the internet</span>
                                 </li>
                              </ul>
                            </div>
                           <div>
                              <button class="cta_btn" data-toggle="modal" data-target="#regmemem1" value="2" onclick="memRegister(this.value)">SIGN UP</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="homeAwesomeBox col-md-4 min-height-max">
                     <div class="membership-box" style=" background-color: #8bc63f; height: 23px;color: white;text-align: center;"> BEST VALUE
                     </div>
                     <div class="div-box">
                        <div class="homeAwesomeBoxInnerContainer">
                           <h4 class="card_title_sub">ADVANCE</h4>
                           <h2 class="fa-fa-icon"><i class="fa fa-usd" aria-hidden="true"></i>100</h2>
                           <div class="awesomeBoxContent">
                              <ul class="icon-item features">
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">10,000 interviews in 10 surveys</span>
                                 </li>
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">Customizable Dashboard making module to manage the cases effectively</span>
                                 </li>
                                 <li>
                                    <span class="list-icon">
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="list-text">Data sharing functionality available</span>
                                 </li>
                              </ul>
                           </div>
                           <div>
                              <button class="cta_btn" data-toggle="modal" data-target="#regmemem1" value="3" onclick="memRegister(this.value)">SIGN UP</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
		</div>-->
   <!--<div class="blogPanel">
         <div>
            <div>
               <div class="title">
                  <h2>MQUAD BLOG</h2>
               </div>
               <div class="homeBloxItem">
                  <div class="postContent">
                     <h3>AERA <span style="bold: none;font-weight: 100;">(American Educational Research Association)</span> 2022 Recap, Division E Seed Grant Competition, and May Webinar Sneak Peek</h3>
                     <div>
                        <p>
                           AERA 2022 highlights Div E Seed Grant Student Researcher Competition. MQUAD sponsored both events. Get a sneak peek of what is to come in May.
                        </p>
                     </div>
                  </div>
                  <div class="viewPost">
                     <a href="#">VIEW POST</a>
                  </div>
               </div>
               <div class="homeBloxItem">
                  <div class="postContent">
                     <h3>How does MQUAD protect my data? Does this process comply with the IRB Guidelines?</h3>
                     <div>
                        <p>
                           &quot;Does data security in MQUAD comply with the IRB standards?” Curious if our data protections are up-to-par with your thesis or dissertation requirements? Read the basics of access, storage, and retention.
                        </p>
                     </div>
                  </div>
                  <div class="viewPost">
                     <a href="#">VIEW POST</a>
                  </div>
               </div>
               <div class="homeBloxItem">
                  <div class="postContent">
                     <h3>MQUAD Desktop App</h3>
                     <div>
                        <p>
                           Ensure you&#39;re up to date with MQUAD version 9.0.46! With our ever-improving software, look here to make sure you don&#39;t fall behind the latest version.
                        </p>
                     </div>
                  </div>
                  <div class="viewPost">
                     <a href="#">VIEW POST</a>
                  </div>
               </div>
            </div>
         </div>
		</div>-->
   <div class="clientviewpanel" style="background-color:#dceded;">
      <div class="clientsPanel">
         <div class="title">
            <h2>Who Uses AAKLAN ?</h2>
            <p>AAKLAN has a wide variety of users. They use AAKLAN in different ways to discover insights.</p>
         </div>
         <div class="clientBoxes">
            <div class="row">
               <div class="col-sm-3 col-xs-6">
                  <div class="ih-item circle colored effect1">
                     <a href="javascript:void(0);">
                        <div class="spinner"></div>
                        <div class="img"><img src="Content/Images/Home/market-researchers.svg" /></div>
                        <div class="info">
                           <div class="info-back">
                              <h3>Evaluators</h3>
                              <!-- <p>Description goes here</p>-->
                           </div>
                        </div>
                        <p class="lebel lebel-primary">Evaluators</p>
                     </a>
                  </div>
               </div>
               <div class="col-sm-3 col-xs-6">
                  <div class="ih-item circle colored effect1">
                     <a href="javascript:void(0);">
                        <div class="spinner"></div>
                        <div class="img"><img src="Content/Images/Home/product-researchers.svg" /></div>
                        <div class="info">
                           <div class="info-back">
                              <h3>Market Researchers</h3>
                           </div>
                        </div>
                        <p class="lebel lebel-primary">Market Researchers</p>
                     </a>
                  </div>
               </div>
               <div class="col-sm-3 col-xs-6">
                  <div class="ih-item circle colored effect1">
                     <a href="javascript:void(0);">
                        <div class="spinner"></div>
                        <div class="img"><img src="Content/Images/Home/phychologist.svg" /></div>
                        <div class="info">
                           <div class="info-back">
                              <h3>Program Managers</h3>
                           </div>
                        </div>
                        <p class="lebel lebel-primary">Program Managers</p>
                     </a>
                  </div>
               </div>
               <div class="col-sm-3 col-xs-6">
                  <div class="ih-item circle colored effect1">
                     <a href="javascript:void(0);">
                        <div class="spinner"></div>
                        <div class="img"><img src="Content/Images/Home/medical-researchers.svg" /></div>
                        <div class="info">
                           <div class="info-back">
                              <h3>Health Researchers</h3>
                           </div>
                        </div>
                        <p class="lebel lebel-primary">Health Researchers</p>
                     </a>
                  </div>
               </div>

               <div class="col-sm-3 col-xs-6">
                  <div class="ih-item circle colored effect1">
                     <a href="javascript:void(0);">
                        <div class="spinner"></div>
                        <div class="img"><img src="Content/Images/Home/social-researchers.svg" /></div>
                        <div class="info">
                           <div class="info-back">
                              <h3>Social Scientists</h3>
                           </div>
                        </div>
                        <p class="lebel lebel-primary">Social Scientists</p>
                     </a>
                  </div>
               </div>
               <div class="col-sm-3 col-xs-6">
                  <div class="ih-item circle colored effect1">
                     <a href="javascript:void(0);">
                        <div class="spinner"></div>
                        <div class="img"><img src="Content/Images/Home/education.svg" /></div>
                        <div class="info">
                           <div class="info-back">
                              <h3>Students and Teachers</h3>
                           </div>
                        </div>
                        <p class="lebel lebel-primary">Students and Teachers</p>
                     </a>
                  </div>
               </div>
               <div class="col-sm-3 col-xs-6">
                  <div class="ih-item circle colored effect1">
                     <a href="javascript:void(0);">
                        <div class="spinner"></div>
                        <div class="img"><img src="Content/Images/Home/political-research.svg" /></div>
                        <div class="info">
                           <div class="info-back">
                              <h3>Policy Researchers</h3>
                           </div>
                        </div>
                        <p class="lebel lebel-primary">Policy Researchers</p>
                     </a>
                  </div>
               </div>
               <div class="col-sm-3 col-xs-6">
                  <div class="ih-item circle colored effect1">
                     <a href="javascript:void(0);">
                        <div class="spinner"></div>
                        <div class="img"><img src="Content/Images/Home/social-scientists.svg" /></div>
                        <div class="info">
                           <div class="info-back">
                              <h3>Sociologists</h3>
                           </div>
                        </div>
                        <p class="lebel lebel-primary">Sociologists</p>
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <!--<div class="viewClients">
            <a href="about/clients.php">VIEW CLIENTS</a>
         </div>-->
      </div>
   </div>
   <!--<div class="eventsPanel">
         <div>
            <div>
               <div>
                  <div class="header">
                     <h2>UPCOMING EVENTS</h2>
                  </div>
               </div>
               <div class="events">
                  <div class="eventItem">
                     <div class="header">
                        <div class="date">
                           <div class="glyphicon glyphicon-calendar"></div>
                           <span>Fri, May 13, 2022 - Fri, May 13, 2022</span>
                        </div>
                     </div>
                     <div class="body">
                        <h3>Free MQUAD Introductory Webinar - May 13th</h3>
                       
                        <p>This session will start with a basic overview of MQUAD (https://mquad.io), including some of the advantages of how this web-based application can support qualitative and mixed methods research analysis completely online. While this session will be generalized, we will show you how to import documents, excerpt and code sections of text, and touch on how to bring your analysis to life with easy to use charts, graphs, and filters. We will also briefly discuss some mixed methods research topics and how to incorporate mixed methods dimensions into your project with MQUAD.
                           If you would like to sign up for your 30 day trial of MQUAD prior to attending this webinar so that you can follow along please visit this link to sign up:
                           https://mquad.io/pricing-and-features/ 
                        </p>
                     </div>
                  </div>
                  <div class="eventItem">
                     <div class="header">
                        <div class="date">
                           <div class="glyphicon glyphicon-calendar"></div>
                           <span>Mon, May 16, 2022 - Mon, May 16, 2022</span>
                        </div>
                     </div>
                     <div class="body">
                        <h3>Free MQUAD Introductory Webinar - Asia-Pacific, May 16th, 2022</h3>
                        
                        <p>This session will start with a basic overview of the MQUAD web-based application and how it can support qualitative and mixed methods data analysis. We will then show you how to import documents, how to excerpt and code text, and how to bring your analysis to life using charts, graphs, and filters. The session will be life and include time for questions. If you would like to sign up for your 30-day trial of MQUAD prior to attending this webinar so that you can follow along please click the Sign Up link in the upper left corner of the window.</p>
                     </div>
                  </div>
                  <div class="eventItem">
                     <div class="header">
                        <div class="date">
                           <div class="glyphicon glyphicon-calendar"></div>
                           <span>Tue, May 17, 2022 - Tue, May 17, 2022</span>
                        </div>
                     </div>
                     <div class="body">
                        <h3>Free MQUAD Introductory Webinar - May 17th</h3>
                        
                        <p>This session will start with a basic overview of MQUAD (https://mquad.icpl.tech), including some of the advantages of how this web-based application can support qualitative and mixed methods research analysis completely online. While this session will be generalized, we will show you how to import documents, excerpt and code sections of text, and touch on how to bring your analysis to life with easy to use charts, graphs, and filters. We will also briefly discuss some mixed methods research topics and how to incorporate mixed methods dimensions into your project with MQUAD.
                           If you would like to sign up for your 30 day trial of MQUAD prior to attending this webinar so that you can follow along please visit this link to sign up:
                           &lt;https://MQUAD.com/signup&gt; 
                        </p>
                     </div>
                  </div>
                  <div class="eventItem">
                     <div class="header">
                        <div class="date">
                           <div class="glyphicon glyphicon-calendar"></div>
                           <span>Thu, May 19, 2022 - Thu, May 19, 2022</span>
                        </div>
                     </div>
                     <div class="body">
                        <h3>Free MQUAD Introductory Webinar - May 19th</h3>
                        
                        <p>This session will start with a basic overview of MQUAD, including some of the advantages of how this web-based application can support qualitative and mixed methods research analysis completely online. While this session will be generalized, we will show you how to import documents, excerpt and code sections of text, and touch on how to bring your analysis to life with easy to use charts, graphs, and filters. We will also briefly discuss some mixed methods research topics and how to incorporate mixed methods dimensions into your project with MQUAD.
                           If you would like to sign up for your 30 day trial of MQUAD prior to attending this webinar so that you can follow along please click the Sign Up link in the upper left corner of the window.
                        </p>
                     </div>
                  </div>
                  <div class="eventItem">
                     <div class="header">
                        <div class="date">
                           <div class="glyphicon glyphicon-calendar"></div>
                           <span>Wed, May 25, 2022 - Wed, May 25, 2022</span>
                        </div>
                     </div>
                     <div class="body">
                        <h3>Free MQUAD Introductory Webinar - May 25th</h3>
                       
                        <p>This session will start with a basic overview of MQUAD, including some of the advantages of how this web-based application can support qualitative and mixed methods research analysis completely online. While this session will be generalized, we will show you how to import documents, excerpt and code sections of text, and touch on how to bring your analysis to life with easy to use charts, graphs, and filters. We will also briefly discuss some mixed methods research topics and how to incorporate mixed methods dimensions into your project with MQUAD.
                           If you would like to sign up for your 30 day trial of MQUAD prior to attending this webinar so that you can follow along please click the Sign Up link in the upper left corner of the window.
                        </p>
                     </div>
                  </div>
                  <div class="eventItem">
                     <div class="header">
                        <div class="date">
                           <div class="glyphicon glyphicon-calendar"></div>
                           <span>Fri, May 27, 2022 - Fri, May 27, 2022</span>
                        </div>
                     </div>
                     <div class="body">
                        <h3>Free MQUAD Introductory Webinar - May 27th</h3>
                       
                        <p>This session will start with a basic overview of MQUAD, including some of the advantages of how this web-based application can support qualitative and mixed methods research analysis completely online. While this session will be generalized, we will show you how to import documents, excerpt and code sections of text, and touch on how to bring your analysis to life with easy to use charts, graphs, and filters. We will also briefly discuss some mixed methods research topics and how to incorporate mixed methods dimensions into your project with MQUAD.
                           If you would like to sign up for your 30 day trial of MQUAD prior to attending this webinar so that you can follow along please click the Sign Up link in the upper left corner of the window.
                        </p>
                     </div>
                  </div>
                  <div class="eventItem">
                     <div class="header">
                        <div class="date">
                           <div class="glyphicon glyphicon-calendar"></div>
                           <span>Tue, May 31, 2022 - Tue, May 31, 2022</span>
                        </div>
                     </div>
                     <div class="body">
                        <h3>Free MQUAD Introductory Webinar - May 31st</h3>
                       
                        <p>This session will start with a basic overview of MQUAD, including some of the advantages of how this web-based application can support qualitative and mixed methods research analysis completely online. While this session will be generalized, we will show you how to import documents, excerpt and code sections of text, and touch on how to bring your analysis to life with easy to use charts, graphs, and filters. We will also briefly discuss some mixed methods research topics and how to incorporate mixed methods dimensions into your project with MQUAD.
                           If you would like to sign up for your 30 day trial of MQUAD prior to attending this webinar so that you can follow along please click the Sign Up link in the upper left corner of the window.
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
		</div>-->
   <!---------------- End Signup modal---------------------- -->
   <?php include('includes/footer.php'); ?>

</body>

</html>