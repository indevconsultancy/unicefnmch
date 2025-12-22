<?php include_once('includes/config.php'); ?>
 <?php define("title","Assign User | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
$userid=$_GET['userid'];
//echo "hello";
if(isset($_REQUEST['save_multiple']))
{
	
	$forms=$_POST['forms'];
	foreach($forms as $formrow)
	{
		
		//echo $formrow;
		//$insertsql="insert into assign_survey (user_id,survey_id) values ('$userid','$formrow')";
		$insertsql="insert into assign_survey (user_id,survey_id) values ('$userid','$formrow')";
		$insquery=mysqli_query($conn,$insertsql);
	}
	if($insquery)
	{
		echo'<script>alert(" Records inserted successfully.")</script>';
        echo "<script>location.href='user-list.php'</script>"; 
	}
	else
	{
		echo'<script>alert("No Inserted Data")</script>';
        echo "<script>location.href='assign-user.php'</script>"; 
	}
}	

?>
    <style>

        .cls {

            color: white;

        }

    </style>

    <!--main content start-->

    <section id="main-content">

        <section class="wrapper">

            
		<div class="row">
            <div class="col-lg-12">
                <!--<h3 class="page-header"><i class="fa fa-laptop"></i> Dashboard</h3>-->
                <ol class="breadcrumb">
                    <!-- <li><i class="fa fa-home"></i><a href="index.html">Home</a></li> -->
                    <li><i class="icon_documents_alt"></i>User  Management</li>
                    <li><i class="fa fa-bars"></i>List Users </li>
                     <li><i class="fa fa-plus"></i>Assign User </li>
                </ol>
            </div>
        </div>
            
	<div class="row">
        <div class="col-lg-12">
          
            <section class="panel">
				<header class="panel-heading">Assign User</header>
			  <div class="panel-body">
				<div class="form">
					<form class="form-validate form-horizontal"action="" method="post">	
					<div class="row">
					
					 <div class="col-lg-10">
					 <label for=""> Select Survey </label>
					
					 <select name="forms[]" class="form-control multiple-select" multiple required >
					 
					 <?php $clnt='';
						if($_SESSION['role_id']=='3'){
							$clnt = " and client_id='".$_SESSION['client_id']."' ";
						}
						if($_SESSION['role_id']=='9'){
							$uid = $_SESSION['user_id'];
						 $clnt=" and id in(SELECT DISTINCT(survey_id) FROM assign_survey WHERE user_id='".$uid."') ";
						
						}
						echo $form_sql="select id,survey_name from survey where survey.del_action='N' AND survey.id not in(SELECT survey_id FROM `assign_survey` where user_id='$userid') $clnt";
						
					 $form_query=mysqli_query($conn,$form_sql);
					 if(mysqli_num_rows($form_query)>0)
					 {
						foreach($form_query as $rowform)
						{ ?>
							<option value="<?php echo $rowform['id'];?>"><?php echo $rowform['survey_name']; ?></option>
						<?php
						}
					 }
					 else
					 {
						 echo "No Record Found";
					 }
					 ?>
					 
					 </select>
					 </div>
					 <div class="col-md-2">
					  <label for=""> </label>
					 <button type="submit" name="save_multiple" class="btn btn-primary btn-block">Submit</button>
					 </div>
					</div> 
				 </form>	
				</div>
				</div>		
            </section>
		</div>
	</div>
        
            <!-- page end-->
			
        </section>

    </section>
    <!--main content end-->

<?php include_once('includes/footer.php'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />          

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<script>
		$(".multiple-select").select2({
		  //maximumSelectionLength: 2
		});
	</script>