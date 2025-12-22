

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MQUAD">
    <meta name="author" content="Satyendra">
    <meta name="keyword" content="MQUAD">
	<!--<link rel="shortcut icon" href="img/mquad-fav.png"> 
    <title>Mquad | Dashboard</title>-->
    <link href="<?=$base_url?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$base_url?>css/bootstrap-theme.css" rel="stylesheet">

    <link href="<?=$base_url?>css/elegant-icons-style.css" rel="stylesheet" />
    <link href="<?=$base_url?>css/font-awesome.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="all-min.css"> -->
    <!-- Custom styles -->
    <link href="<?=$base_url?>css/style.css" rel="stylesheet">
    <link href="<?=$base_url?>css/style-responsive.css" rel="stylesheet" />
    <style>
	body {
		color: #797979;
		background: #eeeeee;
		font-family: revert; //sans-serif
		padding: 0px !important;
		margin: 0px !important;
		font-size: 14px !important;
	}
      .panel-heading{
        background: #394a59;
        color: white;
        font-weight: bold;
      }
	  .header_logo{
			height: 50px;
			background-color: white;
			border-radius: 50%;
			padding: 4px;
			margin-top: -10px;
		}
		select[class="form-control"]
		{
			text-transform: capitalize;
		}
    </style>
  </head>
  <body>
    <section id="container" class="">
    <header class="header">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>
      <a href="<?=$base_url?>dashboard_new.php" class="logo">
	   <!--<span class="lite">Admin</span>-->
		<img src="img/mquad-logo.png" class="header_logo" />
		<span style="font-weight: bold;color: white;">MQUAD</span>
	  </a>
      <div class="nav search-row" id="top_menu">
        <ul class="nav top-menu">
          <li>
            <form class="navbar-form">
            </form>
          </li>
        </ul>
        <!--  search form end -->
      </div>
      
    </header>
