<?php include_once('includes/config.php'); ?>
<?php define("title", "FAQs | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
   <link rel="stylesheet" href="css/help.css" />
</head>
<style>
   .userGuideMain .userGuideNav>div>ul>li>a {
      height: 50px !important;
   }
</style>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122580788-1"></script>
<script>
   window.dataLayer = window.dataLayer || [];

   function gtag() {
      dataLayer.push(arguments);
   }
   gtag('js', new Date());

   gtag('config', 'UA-122580788-1');
</script>
<section id="main-content">
   <div class="wrapper">
      <div class="userGuideWrapper">
         <div class="userGuideMain">
            <div class="userGuideNav">
               <div class="scrollbar">
                  <ul id="right-nav" id="scroll" class="div-child">
                     <li> <a href="#section1" data-target="section1"> MQUAD </a>
                     </li>
                     <li>
                        <a href="#section2" data-target="section2">How to sign up for MQUAD ? </a>
                     </li>
                     <li>
                        <a href="#section3" data-target="section3">What are the features offered? </a>
                     </li>
                     <li>
                        <a href="#section4" data-target="section4">Do I need to pay for sign up? </a>
                     </li>
                     <li>
                        <a href="#section5" data-target="section5">How can I get a demo of MQUAD?</a>
                     </li>
                     <li>
                        <a href="#section6" data-target="section6">Who all can use MQUAD? </a>
                     </li>
                     <li>
                        <a href="#section7" data-target="section7">What are the subscription options available? </a>
                     </li>
                     <li>
                        <a href="#section8" data-target="section8">What types of support available from MQUAD to deploy this in my organization?</a>
                     </li>
                     <li>
                        <a href="#section9" data-target="section9">What is the maximum number of questions one can build in? </a>
                     </li>
                     <li>
                        <a href="#section10" data-target="section10">What kind of data security protocols are in place? </a>
                     </li>
                     <li>
                        <a href="#section11" data-target="section11">What type of questions can be developed? </a>
                     </li>
                     <li>
                        <a href="#section12" data-target="section12">What are the domains/topics on which validated questions are available? </a>
                     </li>
                     <li>
                        <a href="#section13" data-target="section13">How does MQUAD help in data quality assurance? </a>
                     </li>
                     <li>
                        <a href="#section14" data-target="section14">How does MQUAD work? </a>
                     </li>
                     <li>
                        <a href="#section15" data-target="section15">Do I need to know programming to work with MQUAD? </a>
                     </li>
                     <li>
                        <a href="#section16" data-target="section16">If I am not a survey expert, what support/resources does MQUAD provide? </a>
                     </li>
                     <li>
                        <a href="#section17" data-target="section17">Can I try MQUAD before I take a subscription? </a>
                     </li>
                     <li>
                        <a href="#section18" data-target="section18">Can MQUAD be used beyond survey data collection?</a>
                     </li>
                     <li>
                        <a href="#section19" data-target="section19">Can I add a logo to the form? </a>
                     </li>
                     <li>
                        <a href="#section20" data-target="section20">Can I customize the color and the font in the form/question? </a>
                     </li>
                     <li>
                        <a href="#section21" data-target="section21">Does it have a spell check feature?</a>
                     </li>
                     <li>
                        <a href="#section22" data-target="section22">Do you provide any template to develop form?</a>
                     </li>
                     <li>
                        <a href="#section23" data-target="section23">Can survey questions be made mandatory or optional?</a>
                     </li>
                     <li>
                        <a href="#section24" data-target="section24">What are the options available to implement online surveys? </a>
                     </li>
                     <li>
                        <a href="#section25" data-target="section25">Do I need to download MQUAD application?</a>
                     </li>
                     <li>
                        <a href="#section26" data-target="section26">What platforms MQUAD supports? </a>
                     </li>
                     <li>
                        <a href="#section27" data-target="section27">Does MQUAD have partial save options? </a>
                     </li>
                     <li>
                        <a href="#section28" data-target="section28">Does it allow referring to external files? </a>
                     </li>
                     <li>
                        <a href="#section29" data-target="section29">Does it have supervisory level controls? </a>
                     </li>
                     <li>
                        <a href="#section30" data-target="section30">How does MQUAD allows real-time virtual data monitoring? </a>
                     </li>
                     <li>
                        <a href="#section31" data-target="section31">What types of data visualization does MQUAD offer? </a>
                     </li>
                     <li>
                        <a href="#section32" data-target="section32">Can I link the data with external apps like PowerBi or Google Dashboard?</a>
                     </li>
                     <li>
                        <a href="#section33" data-target="section33">Can I share my survey data with other users? </a>
                     </li>
                     <li>
                        <a href="#section34" data-target="section34">How can I deposit my questions/form into MQUAD question bank? </a>
                     </li>
                     <li>
                        <a href="#section35" data-target="section35">Can I download a PDF version of the form built in MQUAD? </a>
                     </li>
                     <li>
                        <a href="#section36" data-target="section36">Do I get any credit for my contribution to MQUAD? </a>
                     </li>
                     <li>
                        <a href="#section37" data-target="section37">Where the data is stored? </a>
                     </li>
                     <li>
                        <a href="#section38" data-target="section38">How do I access paradata? </a>
                     </li>
                     <li>
                        <a href="#section39" data-target="section39">Does MQUAD work offline? </a>
                     </li>
                     <li>
                        <a href="#section40" data-target="section40">What are the languages supported in MQUAD? </a>
                     </li>
                     <li>
                        <a href="#section41" data-target="section41">Is MQUAD General Data Protection Regulation (GDPR) complaint? </a>
                     </li>
                     <li>
                        <a href="#section42" data-target="section42">What are the customized support services available? </a>
                     </li>
                     <li>
                        <a href="#section43" data-target="section43">How can I change my client subscription preferences? </a>
                     </li>
                     <li>
                        <a href="#section44" data-target="section44">Does MQUAD offer trainings? </a>
                     </li>
                     <li>
                        <a href="#section45" data-target="section45">How do I change user functionality of my team? </a>
                     </li>
                     <li>
                        <a href="#section46" data-target="section46">How do I edit contact information for billing? </a>
                     </li>
                     <li>
                        <a href="#section47" data-target="section47">What is a project space in MQUAD? </a>
                     </li>
                     <li>
                        <a href="#section48" data-target="section48">How can I monitor my subscription usage? </a>
                     </li>
                     <li>
                        <a href="#section49" data-target="section49">How do I remove an user from my subscription?</a>
                     </li>
                     <li>
                        <a href="#section50" data-target="section50">How do I delete data from the server?</a>
                     </li>
                     <li>
                        <a href="#section51" data-target="section51">If I stop my subscription, what will happen to my data and forms? </a>
                     </li>
                     <li>
                        <a href="#section52" data-target="section52">What if I need additional forms/storage space than my subscription limit? </a>
                     </li>
                     <li>
                        <a href="#section53" data-target="section53">Can I be refunded for unused months or space? </a>
                     </li>
                     <li>
                        <a href="#section54" data-target="section54">Is there any discount for enterprise or long term agreements? </a>
                     </li>
                     <li>
                        <a href="#section55" data-target="section55">How do I request a quotation </a>
                     </li>
                     <li>
                        <a href="#section56" data-target="section56"> How to login to MQUAD ?</a>
                     </li>
                     <li>
                        <a href="#section57" data-target="section57">How to create a form ?</a>
                     </li>
                     <li>
                        <a href="#section58" data-target="section58">How to validate an Excel form?</a>
                     </li>
                     <li>
                        <a href="#section59" data-target="section59">How to publish a form ?</a>
                     </li>
                     <li>
                        <a href="#section60" data-target="section60">How to assign a form to the user ?</a>
                     </li>
                     <li>
                        <a href="#section61" data-target="section61">How to visualize data in MQUAD ?</a>
                     </li>
                     <li>
                        <a href="#section62" data-target="section62">How to export data in MQUAD ?</a>
                     </li>
                     <li>
                        <a href="#section63" data-target="section63">What is a Advance Manage Option in MQUAD ?</a>
                     </li>
                     <li>
                        <a href="#section64" data-target="section64">How to Edit a Questionnaire from the Web ?</a>
                     </li>
                     <li>
                        <a href="#section65" data-target="section65">How to start surveys via the web ? </a>
                     </li>
                     <li>
                        <a href="#section66" data-target="section66">How to add and list clients ? </a>
                     </li>
                     <li>
                        <a href="#section67" data-target="section67">How to add and list users ? </a>
                     </li>
                     <li>
                        <a href="#section68" data-target="section68">How to add and show questions ? </a>
                     </li>
                     <li>
                        <a href="#section69" data-target="section69">How to use Tool Archives ? </a>
                     </li>
                     <li>
                        <a href="#section70" data-target="section70">How do I deposit my data to the Data Repository ? </a>
                     </li>
                     <li>
                        <a href="#section71" data-target="section71">What are the sampling options available? </a>
                     </li>
                     <li>
                        <a href="#section72" data-target="section72">How to locate an Information Area ? </a>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="userGuideContentArea">
               <div class="header main-top">
                  <div class="headerImage">
                  </div>
                  <div class="headerTitle">
                     <h2>Frequently Asked Questions</h2>
                  </div>
               </div>
               <div>
                  <div class="userGuideArticle">
                     <a id="becoming_familiar_with_Dedoose_ug"></a>
                     <div id="section1" class="container-fluid">
                        <h3>About MQUAD</h3>
                        <p>A mobile-based application specifically designed to cater to the requirements of the development sector to map, measure, monitor and managed data efficiently.</p>
                        <p><img src="img/Faq_img/mquad.png" /></p>
                        <p><img src="img/Faq_img/aboutmquad.png" /></p>
                     </div>
                     <div id="section2" class="container-fluid">
                        <h3>Sign up for MQUAD</h3>
                        <p>There is availability of membership to conduct interviews, visualize data and export it for further analysis.</p>
                        <p>You can become member by subscribing to Basic, Standard or Advance membership plans.</p>
                        <p><img src="img/Faq_img/pricing.png" /></p>
                     </div>
                     <div id="section3" class="container-fluid">
                        <h3>What are the features offered? </h3>
                        <h4>Repository Services</h4>
                        <p>MQUAD provides comprehensive solutions to store, exchange and access tools, questionnaires and collected data as part of MQUAD or otherwise.</p>
                        <ul>
                           <li>• Question bank</li>
                           <li>• Tool archives.</li>
                           <li>• Data repository.</li>
                        </ul>
                        <h4>Advanced Form Management</h4>
                        <p>State of art functionalities allows user to create, manage and do several other things with menu driven options</p>
                        <ul>
                           <li>• Excel and web-based form development.</li>
                           <li>• Multi-format look-up functionality.</li>
                           <li>• Inter-portability.</li>
                        </ul>
                        <h4>Sampling Module</h4>
                        <p>Implements algorithm to draw samples without storing data</p>
                        <ul>
                           <li>• Simple random sampling.</li>
                           <li>• Systematic random sampling.</li>
                        </ul>
                        <h4>Data Quality Analytics</h4>
                        <p>MQUAD provides a range of data quality parameters including time, GPS, keystroke, etc.</p>
                        <ul>
                           <li>• Timestamp.</li>
                           <li>• Keystroke.</li>
                           <li>• Audio.</li>
                           <li>• Sleep time.</li>
                           <li>• Hint and error statistics.</li>
                           <li>• Others.</li>
                        </ul>
                        <h4>User Friendly</h4>
                        <p>No need for users to have advanced technical skills or programming knowledge, hence, user friendly.</p>
                        <ul>
                           <li>• Menu driven.</li>
                           <li>• Easy to locate functions.</li>
                           <li>• Rich help files.</li>
                           <li>• Example excel forms.</li>
                        </ul>
                        <h4>Data Export</h4>
                        <p>MQUAD allows export of data into multiple formats.</p>
                        <ul>
                           <li>• Excel.</li>
                           <li>• Stata.</li>
                           <li>• SPSS.</li>
                           <li>• JSON.</li>
                        </ul>
                        <h4>Support Services</h4>
                        <p>MQUAD has a system to support its users in a quick and transparent way.</p>
                        <ul>
                           <li>• Response within 12 hours.</li>
                           <li>• Demo videos.</li>
                           <li>• MQUAD community.</li>
                        </ul>
                     </div>
                     <div id="section4" class="container-fluid">
                        <h3>Do I need to pay for sign up?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section5" class="container-fluid">
                        <h3>How can I get a demo of MQUAD?</h3>
                        <p>The demo of MQUAD is defined on the demonstration section of home page .
                           Here is a quick overview of the specific features of MQUAD that will enhance your understanding and experience in data collection, management, analysis and reporting.
                        </p>
                        <p><img src="https://mquad.org/Content/Images/video.jpg" /></p>
                     </div>
                     <div id="section6" class="container-fluid">
                        <h3>Who all can use MQUAD? </h3>
                        <p>MQUAD has a wide variety of users. They use MQUAD in different ways to discover insights. </p>
                        <ul>
                           <li>• Evaculator.</li>
                           <li>• Market Researchers.</li>
                           <li>• Psychologists.</li>
                           <li>• Health Researchers.</li>
                           <li>• Social Scientists.</li>
                           <li>• Students and Teachers.</li>
                           <li>• Policy Researchers.</li>
                           <li>• Sociologists.</li>
                        </ul>
                     </div>
                     <div id="section7" class="container-fluid">
                        <h3>What are the subscription options available?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section8" class="container-fluid">
                        <h3> Types of support available from MQUAD to deploy this in my organization.</h3>
                        <p>MQUAD has a wide variety of features. </p>
                        <ul>
                           <li>• Designed for groups and organizations with exceptional management.</li>
                           <li>• Technical.</li>
                           <li>• Security.</li>
                           <li>• End-user support needs.</li>
                        </ul>
                     </div>
                     <div id="section9" class="container-fluid">
                        <h3>What is the maximum number of questions one can build in?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section10" class="container-fluid">
                        <h3> Types of support available from MQUAD to deploy this in my organization.</h3>
                        <p>MQUAD has a wide variety of features. </p>
                        <ul>
                           <li>• Designed for groups and organizations with exceptional management.</li>
                           <li>• Technical.</li>
                           <li>• Security.</li>
                           <li>• End-user support needs.</li>
                        </ul>
                     </div>
                     <div id="section11" class="container-fluid">
                        <h3>What type of questions can be developed?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section12" class="container-fluid">
                        <h3>What are the domains/topics on which validated questions are available?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section13" class="container-fluid">
                        <h3>How does MQUAD help in data quality assurance?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section14" class="container-fluid">
                        <h3>How does MQUAD work?</h3>
                        <p>MQUAD work by Conducting surveys to collect quality data through simple web interface & export it to different statistical software formats with ease and store data in a reliable server infrastructure.</p>
                     </div>
                     <div id="section15" class="container-fluid">
                        <h3>Do I need to know programming to work with MQUAD?</h3>
                        <p>No it is not compulsory : anyone can use it easily. MQUAD has a wide variety of users. They use MQUAD in different ways to discover insights. </p>
                     </div>
                     <div id="section16" class="container-fluid">
                        <h3>If I am not a survey expert, what support/resources does MQUAD provide?</h3>
                        <p>MQUAD provides sufficient materials that help to conduct the survey.</p>
                        <ul>
                           <li>• Help File.</li>
                           <li>• FAQ.</li>
                           <li>• Video Demonstration.</li>
                           <li>• sample Question.</li>
                        </ul>
                     </div>
                     <div id="section17" class="container-fluid">
                        <h3>Can I try MQUAD before I take a subscription?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section18" class="container-fluid">
                        <h3>Can MQUAD be used beyond survey data collection?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section19" class="container-fluid">
                        <h3>Can I add a logo to the form?</h3>
                        <p>You can add a logo when login MQUAD. </p>
                     </div>
                     <div id="section20" class="container-fluid">
                        <h3>Can I customize the color and the font in the form/question?</h3>
                        <p>Yes, you can customize the colour and the font in the form/question by including CSS in question.</p>
                     </div>
                     <div id="section21" class="container-fluid">
                        <h3>Does it have a spell check feature?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section22" class="container-fluid">
                        <h3>Do you provide any template to develop form?</h3>
                        <p>You can download a template to develop form.</p>
                        <ul>
                           <li>• Go to the form builder.</li>
                           <li>• Click on the new form builder.</li>
                        </ul>
                        <p><img src="img/Faq_img/downloadtemplate.png" /></p>
                        <ul>
                           <li>• Click on the download template button.</li>
                        </ul>
                     </div>
                     <div id="section23" class="container-fluid">
                        <h3>Can survey questions be made mandatory or optional?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section24" class="container-fluid">
                        <h3>What are the options available to implement online surveys?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section25" class="container-fluid">
                        <h3>Do I need to download MQUAD application?</h3>
                        <p> Yes, you can download the MQUAD application from google play store</p>
                     </div>
                     <div id="section26" class="container-fluid">
                        <h3>What platforms MQUAD supports?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section27" class="container-fluid">
                        <h3>Does MQUAD have partial save options?</h3>
                        <p>The MQUAD application has a partial save option available.</p>
                     </div>
                     <div id="section28" class="container-fluid">
                        <h3>Does it allow referring to external files?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section29" class="container-fluid">
                        <h3>Does it have supervisory level controls?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section30" class="container-fluid">
                        <h3>How does MQUAD allows real-time virtual data monitoring?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section31" class="container-fluid">
                        <h3>What types of data Visualization does MQUAD offer?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section32" class="container-fluid">
                        <h3>Can I link the data with external apps like PowerBi or Google Dashboard?</h3>
                        <p>Yes, you can link the data with external apps like PowerBi or Google Dashboard</p>
                        <ul>
                           <li>• Click on form and go to list form.</li>
                           <li>• Click on dashboard button and after that click on data api.</li>
                           <li>• The external dashboard link will be copied.</li>
                           <li>• Paste on Google Dashboard</li>
                        </ul>
                        <p><img src="img/Faq_img/externaldashboard.png" /></p>
                     </div>
                     <div id="section33" class="container-fluid">
                        <h3>Can I share my survey data with other users?</h3>
                        <p> You can share survey data with other by following method : </p>
                        <ul>
                           <li>• Go to the survey list page.</li>
                           <li>• Select your survey data and click on dashboard.</li>
                           <li>• After that click on share which is given on the right side of dashboard.</li>
                           <li>• Share url with other users.</li>
                        </ul>
                        <p><img src="img/Faq_img/sharedata.png" /></p>
                     </div>
                     <div id="section34" class="container-fluid">
                        <h3>How can I deposit my questions/form into MQUAD question bank?</h3>
                        <p>You can deposit questions in two ways.</p>
                        <p>Go to the question bank</p>
                        <ul>
                           <li>• Click on add question.</li>
                           <li>• Fill out the filled and submit.</li>
                        </ul>
                        <p><img src="img/Faq_img/addquestions.png" /></p>
                        <p>Secondly, go to the show question </p>
                        <ul>
                           <li>• Click on add question or upload question bank.</li>
                           <li>• Fill out the filled and submit.</li>
                        </ul>
                        <p><img src="img/Faq_img/uploadquestion.png" /></p>
                     </div>
                     <div id="section35" class="container-fluid">
                        <h3>Can I download a PDF version of the form built in MQUAD?</h3>
                        <p>Steps to download a PDF version of the form built in MQUAD</p>
                        <ul>
                           <li>• Go to the form builder.</li>
                           <li>• Click on the list form.</li>
                           <li>• Click on the view button of the form.</li>
                           <li>• Select the advance option and click on the download PDF section.</li>
                        </ul>
                        <p><img src="img/Faq_img/downloadpdf.png" /></p>
                     </div>
                     <div id="section36" class="container-fluid">
                        <h3>Do I get any credit for my contribution to MQUAD?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section37" class="container-fluid">
                        <h3>Where the data is stored?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section38" class="container-fluid">
                        <h3>How do I access paradata?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section39" class="container-fluid">
                        <h3>Does MQUAD work offline?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section40" class="container-fluid">
                        <h3>What are the languages supported in MQUAD? </h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section41" class="container-fluid">
                        <h3>What are the customized support services available?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section42" class="container-fluid">
                        <h3>What are the customized support services available?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section43" class="container-fluid">
                        <h3>How can I change my client subscription preferences?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section44" class="container-fluid">
                        <h3>Does MQUAD offer trainings?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section45" class="container-fluid">
                        <h3>How do I change user functionality of my team?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section46" class="container-fluid">
                        <h3>How do I edit contact information for billing?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section47" class="container-fluid">
                        <h3>What is a project space in MQUAD?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section48" class="container-fluid">
                        <h3>How can I monitor my subscription usage?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section49" class="container-fluid">
                        <h3>How do I remove an user from my subscription?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section50" class="container-fluid">
                        <h3>How do I delete data from the server?</h3>
                        <ul>
                           <li>Go to the list form in form Builder</li>
                           <li> • Select your form which you want to delete and click on delete button.</li>
                           <li>• Open a modal for verification of your email. </li>
                           <li><img src="img/Faq_img/deletedata.png" /></li>
                           <li> • click on verify button get an OTP </li>
                           <li> • After submitting OTP the data is deleted permanently from the server.</li>
                           <ul>
                     </div>
                     <div id="section51" class="container-fluid">
                        <h3>If I stop my subscription, what will happen to my data and forms?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section52" class="container-fluid">
                        <h3>What if I need additional forms/storage space than my subscription limit?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section53" class="container-fluid">
                        <h3>Can I be refunded for unused months or space?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section54" class="container-fluid">
                        <h3>Is there any discount for enterprise or long term agreements?</h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section55" class="container-fluid">
                        <h3>How do I request a quotation </h3>
                        <p>To be answered shortly</p>
                     </div>
                     <div id="section56" class="container-fluid">
                        <h3>Login to MQUAD</h3>
                        <p>You can login with your username and password.</p>
                        <p><img src="img/Faq_img/mquadlogin.png" /></p>
                        <p>In case, you forget your password, you have an option to <b>Reset</b> it:</p>
                        <ul>
                           <li>• Click Forget Password.</li>
                           <li>• Enter your email ID.</li>
                           <li>• Tick the captcha.</li>
                           <li>• Submit.</li>
                        </ul>
                        <p><img src="img/Faq_img/resetpassword.png" /></p>
                     </div>
                     <div id="section57" class="container-fluid">
                        <h3>How to create a form?</h3>
                        <p>Once you login, you are on the Home page</p>
                        <p>To create a new form :</p>
                        <ul>
                           <li>• Go to <b>Form Builder</b>, select <b>New Form Builder</b></li>
                           <li>• Select the <b>Information Area</b> and the <b>Client name</b>.</li>
                           <li>• Fill up the <b>form name</b>.</li>
                           <li>• Click on <b>Upload from File</b> and select the excel form.</li>
                           <li>• Click on the <b>Save</b> option to upload.</li>
                        </ul>
                        <p><img src="img/Faq_img/dashboard.png" /></p>
                        <p><img src="img/Faq_img/formbuilder.png" /></p>
                     </div>
                     <div id="section58" class="container-fluid">
                        <h3> To validate a questionnaire Excel Sheet: </h3>
                        <ul>
                           <li>• Go to <b>Form Builder</b>, select <b>Validate Excel Form</b></li>
                           <li>• Click on <b>Upload Questionnaire</b> and select the <b>Excel File</b>.</li>
                           <li>• Click on the <b>Verify</b> option to upload.</li>
                        </ul>
                        <p><img src="img/Faq_img/validateexcel.png" /></p>
                     </div>
                     <div id="section59" class="container-fluid">
                        <h3>How to publish a form</h3>
                        <ul>
                           <li>• After creating a Form, go to <b>Form Builder</b>, click on <b>Form List</b>.</li>
                           <li>• Then, go to the <b>Manage Section</b> and click on the<b>Publish</b> button to Publish.</li>
                           <li>• <img src="img/Faq_img/formlist.png" /></li>
                        </ul>
                     </div>
                     <div id="section60" class="container-fluid">
                        <h3>How to assign a form to the user ?</h3>
                        <p>To assign a user:</p>
                        <ul>
                           <li>• Select the user</li>
                           <li>• Click on the <b>Submit</b> button to <b>Assign</b>.</li>
                        </ul>
                        <p><img src="img/Faq_img/assignuser.png" /></p>
                     </div>
                     <div id="section61" class="container-fluid">
                        <h3>How to visualize data in MQUAD ?</h3>
                        <ul>
                           <li>• <b>Data API</b></li>
                           <li>• <b>Custom Dashboard</b></li>
                        </ul>
                        <img src="img/Faq_img/anayalisis.png" />
                        <!--<p><img src="img/Faq_img/type.png"/></p>-->
                     </div>
                     <div id="section62" class="container-fluid">
                        <h3>How to export data in MQUAD and what type of data can be exported ?</h3>
                        <p>Select the <b>Export Data</b> option.</p>
                        <p>You can export data with multiple formats.</p>
                        <ul>
                           <li>• <b>Excel with Label</b></li>
                           <li>• <b>Excel without Label</b></li>
                           <li>• <b>STATA</b></li>
                           <li>• <b>SPSS</b></li>
                           <li>• <b>JSON</b></li>
                           <li>• <b>PARADATA</b></li>
                        </ul>
                        <img src="img/Faq_img/exportdata.png" />
                     </div>
                     <div id="section63" class="container-fluid">
                        <h3>Advanced Manage Option in MQUAD ?</h3>
                        <p>You can access the Advance Manage option:</p>
                        <ul>
                           <li>• Go to <b>Form Builder</b></li>
                           <li>• Click <b>List Form</b></li>
                           <li>• Click on the <b>Dashboard</b></li>
                        </ul>
                        <img src="img/Faq_img/dashboard.png" />
                     </div>
                     <div id="section64" class="container-fluid">
                        <h3>How to Edit questionnaire from the Web ?</h3>
                        <p>To edit a Questionnaire:</p>
                        <ul>
                           <li>• Go to <b>Form Builder</b></li>
                           <li>• Click <b>List Form</b></li>
                           <li>• Click on <b>Edit Question</b></li>
                        </ul>
                        <img src="img/Faq_img/edit_question.png   " />
                     </div>
                     <div id="section65" class="container-fluid">
                        <h3>How to start surveys via the Web ?</h3>
                        <p>• Select the language and start the interview.</p>
                        <p><img src="img/Faq_img/selectlanguage.png" /></p>
                     </div>
                     <div id="section66" class="container-fluid">
                        <h3>How to add and list clients ?</h3>
                        <p> To <b>Add Client:</b></p>
                        <ul>
                           <li>• Go to <b>Cilent Managements</b></li>
                           <li>• Select <b>Add Client</b></li>
                           <li>• Full up the <b>details</b></li>
                           <li>• <b>Submit</b></li>
                        </ul>
                        <img src="img/Faq_img/addclient.png" />
                        <p>To List <b>Clients:</b></p>
                        <ul>
                           <li>• Go to <b>Cilent Managements</b></li>
                           <li>• Click <b>List Client</b></li>
                        </ul>
                         <img src="img/Faq_img/listclient.png" /> 
                     </div>
                     <div id="section67" class="container-fluid">
                        <h3>How to add and list users ?</h3>
                        <p> To <b>Add User:</b></p>
                        <ul>
                           <li>• Select <b>User Managements</b></li>
                           <li>• Click <b>Add User</b></li>
                           <li>• Full up the <b>details</b></li>
                        </ul>
                        <img src="img/Faq_img/adduser.png" />
                        <p>To <b>List Users:</b></p>
                        <ul>
                           <li>• Select <b>User Management</b></li>
                           <li>• Click <b>List Users</b></li>
                        </ul>
                        <img src="img/Faq_img/listusers.png" />
                     </div>
                     <div id="section68" class="container-fluid">
                        <h3>How to add and show questions ?</h3>
                        <p>To <b>Add Questions:</b></p>
                        <ul>
                           <li>• Select <b>Question Bank</b></li>
                           <li>• Click <b>Add Question</b></li>
                           <li>• Fill up the <b>details</b></li>
                           <li>• <b>Submit</b></li>
                        </ul>
                        <p><img src="img/Faq_img/addquestion.png" /></p>
                        <p>To <b>Show Questions</b>:</p>
                        <ul>
                           <li>• Select <b>Question Bank</b></li>
                           <li>• Click <b>Show Questions</b></li>
                        </ul>
                        <p><img src="img/Faq_img/showquestion.png" /></p>
                     </div>
                     <div id="section69" class="container-fluid">
                        <h3>How to use Tool Archives ?</h3>
                        <p>To Add Tool:</p>
                        <ul>
                           <li>• Select <b>Tool Archives</b></li>
                           <li>• Click <b>Add Tool</b></li>
                           <li>• Fill up the <b>details</b></li>
                        </ul>
                        <p><img src="img/Faq_img/addtool.png" /></p>
                        <p>To Show Tools:</p>
                        <ul>
                           <li>• Select <b>Tool Archives</b></li>
                           <li>• Click <b>Show Tools</b></li>
                        </ul>
                        <p><img src="img/Faq_img/showtool.png" /></p>
                     </div>
                     <div id="section70" class="container-fluid">
                        <h3>How is the Dataset added and seen ?</h3>
                        <p>To Add Dataset:</p>
                        <ul>
                           <li>• Select <b>Data Repository</b></li>
                           <li>• Click <b>Add Dataset</b></li>
                           <li>• Fill up the <b>details</b></li>
                        </ul>
                        <p><img src="img/Faq_img/adddataset.png" /></p>
                        <p>To Show Dataset:</p>
                        <ul>
                           <li>• Select <b>Data Repository</b></li>
                           <li>• Click <b>Show Dataset</b></li>
                        </ul>
                        <p><img src="img/Faq_img/showdata.png" /></p>
                     </div>
                     <div id="section71" class="container-fluid">
                        <h3>How to do Sampling ?</h3>
                        <p>To do Sampling:</p>
                        <ul>
                           <li>• Select <b>Sampling</b></li>
                           <li>• Click <b>Simple Random/Systematic Random sampling</b></li>
                           <li>• <b>Upload File</b></li>
                           <li>• Fill up the <b>details</b></li>
                           <li>• <b>Submit</b></li>
                        </ul>
                        <p><img src="img/Faq_img/simple_random_sampling.png" /></p>
                        <br>
                        <p><img src="img/Faq_img/systematic_random_sampling.png" /></p>
                     </div>
                     <div id="section72" class="container-fluid">
                        <h3>How to locate an Information Area ?</h3>
                        <p> To locate Information Area:</p>
                        <ul>
                           <li>• Select <b>Setting</b></li>
                           <li>• Click <b>Information Area</b></li>
                        </ul>
                        <p><img src="img/Faq_img/information_area.png" /></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
</section>
<?php include_once('includes/footer.php'); ?>
<script src="../Scripts/jquery-3.4.1.min.js"></script>
<script src="../Content/Scripts/modernizr-2.6.2.js"></script>
<script src="../Content/Scripts/jquery.als-1.7.js"></script>
<script src="../Content/Scripts/bootstrap.js"></script>
<script src="../Content/Scripts/respond.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.3/jquery.scrollTo.min.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="js/scripts.js"></script>
<script>
   $(document).ready(function() {
      scrollTo();
      scrollToTop();

      function scrollTo() {
         $('#right-nav li > a').click(function(e) {
            e.preventDefault();
            $('#right-nav li > a').removeClass('active');
            $(this).addClass('active');
            var distanceTopToSection = $('#' + $(this).data('target')).offset().top - 70;
            $('body, html').animate({
               scrollTop: distanceTopToSection
            }, 'slow');
         });
      }

      function scrollToTop() {
         var backToTop = $('.backToTop');
         var showBackTotop = $(window).height();
         backToTop.hide();
         var children = $(".mainMenu li").children();
         var tab = [];
         for (var i = 0; i < children.length; i++) {
            console.log(children[i]);
            var child = children[i];
            var ahref = $(child).attr('href');
            console.log(ahref);
            tab.push(ahref);
         }
      }
   });
</script>
</body>

</html>