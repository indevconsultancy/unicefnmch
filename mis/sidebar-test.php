<?php include_once('includes/config.php'); ?>
<?php define("title", "Form View | MQUAD"); ?> 
<?php include_once('includes/header1.php'); ?>
<?php include_once('includes/left-sidebar1.php'); ?>
<?php $survey_id = mysqli_real_escape_string($conn, $_REQUEST['survey_id']); ?>
<script src="js/jquery.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<link href="css/select2-bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
<?php include_once('includes/footer.php'); ?>
 <script>
	let arrow=document.querySelectorAll(".arrow");

	for (var i=0; i < arrow.length; i++) {
		arrow[i].addEventListener("click", (e)=> {
				let arrowParent=e.target.parentElement.parentElement; //selecting main parent of arrow
				arrowParent.classList.toggle("showMenu");
			});
	}

	let sidebar=document.querySelector(".sidebar");
	let sidebarBtn=document.querySelector(".bx-menu");
	console.log(sidebarBtn);

	sidebarBtn.addEventListener("click", ()=> {
			sidebar.classList.toggle("close");
		});
</script> 