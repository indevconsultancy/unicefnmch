<?php include_once('includes/config.php'); ?>
<?php define("title","Add Project | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php
	if($_REQUEST['eid'] && $_REQUEST['eid']!=''){
		
		$projectSql="SELECT project_id,project_name,clients.name as client_name,projects.client_id,projects.description FROM `projects` left join clients on clients.id=projects.project_id where projects.status='0' and project_id='".$_REQUEST['eid']."'";
		$projectqry=mysqli_query($conn,$projectSql);
		$data=mysqli_fetch_array($projectqry);
	}
?>
<?php
	if(isset($_POST['submit'])){
		$project_name=mysqli_real_escape_string($conn, $_POST['project_name']);
		$description=mysqli_real_escape_string($conn, $_POST['description']);
		$created_At=currentTimeStamp(); //create function in functions page
		$clientid = "";
		if($_SESSION['role_id']=='3'){
			$clientid = $_SESSION['client_id'];
		}else{
			$clientid=mysqli_real_escape_string($conn, $_POST['client_id']);
		}
		$sqlProject="insert into projects set project_name='".$project_name."',client_id='".$clientid."',description='".$description."',created_at='".$created_At."'";
		$dataProject=mysqli_query($conn,$sqlProject);
		if($dataProject!=''){
			$_SESSION['status'] = "Project has been added successfully";
			$_SESSION['status_code'] = "success";
			echo "<script>window.location.href='project-list.php'</script>";
		}
		$_SESSION['status_error'] = "Project has been not added !!";
		$_SESSION['status_error_code'] = "error";
	}
?>
<?php
	if(isset($_REQUEST['update'])){
		$project_id=mysqli_real_escape_string($conn,$_REQUEST['eid']);
		$project_name=mysqli_real_escape_string($conn,$_POST['project_name']);
		$description=mysqli_real_escape_string($conn,$_POST['description']);
		$created_At=currentTimeStamp();  //create function in functions page
		$clientid = "";
		if($_SESSION['role_id']=='3'){
			 $clientid = $_SESSION['client_id'];
		}else{
			 $clientid=$_REQUEST['client_id'];
		}
		$ProjectUpdate="update projects set project_name='".$project_name."',client_id='".$clientid."',description='".$description."',created_at='".$created_At."' where project_id='".$project_id."'";
		$UpdateData=mysqli_query($conn,$ProjectUpdate);
		if($UpdateData!=''){
			$_SESSION['status'] = "Project has been update successfully";
			$_SESSION['status_code'] = "success";
			echo "<script>window.location.href='project-list.php'</script>";
		}
		$_SESSION['status_error'] = "Project has been not updated !!";
		$_SESSION['status_error_code'] = "error";
	}
?>
<style>
#main-content .wrapper .row{
	margin-bottom:0px;
}
.panel{
	margin-bottom: 20px;
}
.panel .panel-heading {
    margin-top: -10px;
}
</style>
<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="row">
		<div class="col-lg-12">
			
			<ol class="breadcrumb">
			  <li><i class="icon_documents_alt"></i>Project Management</li>
			  <?php if($_REQUEST['eid']==''){ ?>
			  <li><i class="fa fa-plus"></i>Add Project</li>
			  <?php } else{ ?>
				<li><i class="fa fa-plus"></i>Update Project</li>
			<?php } ?>
			</ol>
		</div>
    </div>
    <!-- page start-->
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
		<?php if($_REQUEST['eid']==''){ ?>
			<header class="panel-heading">Add Project</header>
		  <?php } else{ ?>
			 <header class="panel-heading">Update Project</header> 
		<?php } ?>
          <div class="panel-body">
            <div class="form">
              <form class="form-validate form-horizontal" action="" id="myForm" method="post" enctype="multipart/form-data">
					<div class="form-group">
					  <label for="cname" class="control-label col-lg-2">Project Name: <span style="color:red">*</span></label>
					  <div class="col-lg-10">
						<input class="form-control" name="project_name" required type="text" value="<?=$data['project_name']?>"/>
					  </div>
					</div>
				<?php if($_SESSION['role_id']==1){?>
                <div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Client: <span style="color:red">*</span></label>
                  <div class="col-lg-10">
				   <select class="form-control select2" required name="client_id" id="client_id">
				   	<option value="">Select Client</option>
					<?php 
						$sqlClient=mysqli_query($conn,"SELECT id,name FROM clients where del_action='N'");
						while($row=mysqli_fetch_array($sqlClient)){ ?>
							<option value="<?=$row['id']?>"<?php if($row['id']==$data['client_id']){ echo "selected";}?> ><?=$row['name']?></option>
						<?php } ?>
						
					</select>
                  </div>
                </div>
				<?php } ?>
				<div class="form-group">
					  <label for="cname" class="control-label col-lg-2">Description: <span style="color:red">*</span></label>
					  <div class="col-lg-10">
						<textarea id="description" class="form-control" required name="description" maxlength="100" rows="4" cols="50"><?=$data['description']?></textarea>
					  </div>
					</div>
                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10  text-right">
					<?php if($_REQUEST['eid']!=''){ ?>
						 <button class="btn btn-primary" type="submit" name="update">Update</button>
					<?php } else{ ?>
						 <button class="btn btn-primary" type="submit" name="submit">Submit</button> 
					<?php } ?>
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
<?php if(isset($_SESSION['status_error']) && $_SESSION['status_error']!=''){ ?>
   <script>
		swal.fire({
			  title: "<?php echo $_SESSION['status_error'];?>",
			  icon:"<?php echo $_SESSION['status_error_code']; ?>",
			  confirmButtonColor: '#449A97',
			  confirmButtonText: 'Ok'
			});
	</script>
<?php unset($_SESSION['status_error']);}  ?>