<?php include_once('includes/config.php'); ?>
<?php define("title","Sampling Download | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<style>
#szlider{
    width:100%;
    height:30px;
    border:0px solid #000;
    overflow:hidden;
}
#szliderbar{
    width:37%;
    height:30px;
    border-right: 0px solid #000000;
    background: #4cd964;
}
#szazalek {
    color: #000000;
    font-size: 16px;
    font-style: italic;
    font-weight: bold;
    left: 25px;
    position: relative;
    top: -20px; 
}
</style>

<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
               <li><i class="icon_documents_alt"></i>Sampling</li>
               <li><i class="fa fa-plus"></i>Sampling Download</li>
            </ol>
         </div>
      </div>
      <!-- page start-->    
      <div class="row">
         <div class="col-lg-12">
            <section class="panel">
               <header class="panel-heading">Download</header>
               <div class="panel-body" style="height:300px;">
					<div class="col-md-12 mt-5">
						 <div onload="drawszlider(121, 56);">
							<div id="szlider">
								<div id="szliderbar"></div>
								<div id="szazalek"></div>
							</div>   
						</div>
					</div>
					<br>
					 <div class="col-md-12 text-center">
						<?php echo $_SESSION['download_sampling']; ?>
					</div>
				</div>
            </section>
         </div>
      </div>
      <!-- page end-->
   </section>
</section>
<!--main content end-->
<script>
function progressbar(percent){
    //var szazalek=Math.round((meik*100)/ossz);
    document.getElementById("szliderbar").style.width=percent+'%';
    document.getElementById("szazalek").innerHTML=percent+'%';
}

var elapsedTime=0;
function timer()
{
if(elapsedTime > 100)
    {
		document.getElementById("szazalek").style.color = "#FFF";
        document.getElementById("szazalek").innerHTML = "Completed.";
		if(elapsedTime >= 107)
		{
			clearInterval(interval);
			history.go(-1);
		}
    }
	else
	{
		progressbar(elapsedTime);
	}
	elapsedTime++;
    
}

var myVar=setInterval(function(){timer()},100);

</script>

<?php include_once('includes/footer.php'); ?>