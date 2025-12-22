<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/fav.png">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="assets/fonts/flaticon.css">
  <link rel="stylesheet" type="text/css" href="assets/css/animate.css">
  <link rel="stylesheet" type="text/css" href="assets/css/owl.carousel.css">
  <link rel="stylesheet" type="text/css" href="assets/css/off-canvas.css">
  <link rel="stylesheet" type="text/css" href="assets/css/magnific-popup.css">
  <link rel="stylesheet" href="assets/css/rsmenu-main.css">
  <link rel="stylesheet" type="text/css" href="assets/inc/custom-slider/css/nivo-slider.css">
  <link rel="stylesheet" type="text/css" href="assets/inc/custom-slider/css/preview.css">
  <link rel="stylesheet" type="text/css" href="assets/css/rs-spacing.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
  <meta name="description" content="Check out some of features, function and implementations that make MQUAD the clear winner in research and collaboration software." />
  <title>Toolbox | MQUAD</title>
  <link href="Content/CSS/bootstrap/bootstrap.css" rel="stylesheet" />
  <link href="Content/CSS/Site.css" rel="stylesheet" />
  <script src="Content/Scripts/modernizr-2.6.2.js"></script>
  <style type="text/css">
	.search-part{
		background: #003b64;
		padding: 20px;
		margin-top: 15px;
	}
	.search-part .search-input {
		width: 100%;
		max-width: 100%;
		padding: 15px 25px;
		border-radius: 55px;
		border: none;
	}
	.form-group{
		margin: 0;
	}
	.search-part button{
		  position: absolute;
		  right:50px;
		  background: transparent;
		  border: none;
		  padding: 10px 15px;
		  top: 40px;
	  }
	  .breadcumb-div{
		padding: 12px;
		background: #eee;
		margin-top: 20px;
	  }
	  .breadcumb-div ul{
		  margin: 0;
		  display: flex;
		  flex-wrap: wrap;
	  }
	  .breadcumb-div ul li{
		  margin-right: 15px
	  }
	  .breadcumb-div ul li a, .breadcumb-div ul li{
		  color: #000;
	  }
	   .breadcumb-div ul li:not(:first-child)::before {
			content: "›";
			display: inline-block;
			vertical-align: top;
			font-size: 22px;
			margin-right: 0.5rem;
			line-height: 18px;
		}
	  .breadcumb-div ul li.active a, .breadcumb-div ul li.active{
		  color: #003b64;;
	  }
	  .page-body h2{
		   font-weight: 700;
	  }
	
	 
	 .page-body strong {
			font-weight: 600;
		}
	   .page-body p {
		font-size: 15px;
		font-weight: 200;
	}
	.page-body h5 {
		margin: 20px 0;
		font-size: 16px;
		color: #003b64;
	}
	.page-body ul {
		list-style: disc;
		margin-left: 15px;
	}
  </style>
</head>
<?php include('includes/header.php'); ?>

<body class="default-home">
<div class="main-content" style="margin-top: 40px;">
    <div class="rs-about main-home pt-60 pb-60">
      <div class="container">
        <div class="row y-middle">
          <div class="col-lg-12 md-mb-50">
            <div class="search-part">
              <form action="" method="post">
				  <div class="form-group">
				  	 <input type="text" name="search" class="search-input" placeholder="Search the tool"/>
					 <button type="submit" name=""><i class="fa fa-search"></i></button>
				  </div>
			  </form>
            </div>
          </div>
		  <div class="col-lg-12 pl-60 md-pl-15">
			  <div class="breadcumb-div">
			  	<ul class="">
				    <li>
						<a href="#"><i class="fa fa-home"></i> Home</a>
					</li>
					<li class="active">
						Getting Started
					</li>
			    </ul>
			  </div>
		  </div>
          <div class="col-lg-12 pl-60 md-pl-15">
            <div class="page-body">
              <h2 class="title pb-30">
                About MQUAD
              </h2>
				<p><strong>Last updated:</strong> <a href="#" class="reference">6 Apr 2022</a></p>
              <p class="text-justify">MQUAD is a mobile-based application system. It allows the research organizations primarily engaged in the development sector (knowledge-management, nutrition, health, education, governance, etc.) to conduct surveys & collect efficient data through electronic means. Further, present it using a generic visual layout and custom statistical widgets with ease.</p>
              <p>We take data protection very seriously. Data security means protecting our users’ data from any threats that may exist. This article summarizes some of our administrative, physical, organizational, and technical measures for enforcing data security on the KoboToolbox servers maintained by Kobo, Inc., the <a class="#">nonprofit organization behind KoboToolbox</a>.</p>
				
			<h3>Confidentiality</h3>
			<h5><strong>Physical Access Control</strong></h5>
			<ul>
				<li>
					<p>Physical access control measures, amongst others, are implemented by Amazon Web Services (AWS), which is used to host our KoboToolbox servers. These measures include, for example, video surveillance and physical security of server and network facilities, maintaining key card access control, limiting access to only authorized personnel. For a full list of details about AWS technical and organizational measures for physical access control, <a class="reference external" href="#">see this article</a> on data center controls provided by AWS.</p>
				</li>
			</ul>
				
			<h5><strong>Electronic Access Control</strong></h5>
			<ul>
				<li>
					<p>All KoboToolbox accounts are password-protected. Users are provided visual feedback about the complexity of their password, which encourages them to select a stronger password when applicable. Only encrypted password hashes are stored on the KoboToolbox server, utilizing the default open-source framework provided by Django, which uses the <a href="#">PBKDF2</a> algorithm with a SHA256 hash. Plain text passwords are never saved on the server.</p>
				</li>
				<li>
					<p>All database content is encrypted at rest (disk-level encryption).</p>
				</li>
				<li>
					<p>Data sent to the server is encrypted in transit using SHA-256 with RSA encryption.</p>
				</li>
				<li>
					<p>Users can choose to also enable encryption of their project data (data-level encryption) which renders it inaccessible at all stages of data processing and requires a private key to decrypt it locally.</p>
				</li>
			</ul>
				<h5><strong>Internal Access Control</strong></h5>
				<ul>
					<li><p>Only authorized system administrators can access the KoboToolbox Server.
					They may only do so for the express purpose of updating installed software
					or maintaining the server infrastructure.</p></li>
					<li><p>System administrators require additional authentication, including SSH
					Public Key authentication, for accessing the KoboToolbox Server and
					two-factor authentication for accessing control panels provided by AWS.</p></li>
					<li><p>AWS provides a log of actions taken in the AWS Console. For SSH connections
					into the individual KoboToolbox Server instances, Kobo collects “system
					access events” by SSH key, which can then be matched to the authorized
					users.</p></li>
					<li><p>SSH is further protected against brute-force attempts and unauthorized
					access by limiting connections at the firewall level to only a small list of
					explicitly-allowed IP addresses.</p></li>
				</ul>
				
				
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include('includes/footer.php'); ?>
  
</body>
</html>