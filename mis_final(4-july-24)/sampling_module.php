<?php include_once('includes/config.php'); ?>
<?php define("title","Add Sampling Module | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php
if(isset($_REQUEST['submit'])){
	 $survey_id=$_REQUEST['survey_id'];
	// $description=$_REQUEST['description'];
	
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['upload_sampling']['name']) && in_array($_FILES['upload_sampling']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['upload_sampling']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['upload_sampling']['tmp_name'], 'r');
		
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
			    //$survey_id  = $line[1];
                 $unique_household_id  = $line[0];
                 $description  = $line[1];
                       // Insert member data in the database
                    $insert="INSERT INTO sampling_module set survey_id='".$survey_id."',unique_household_id='".$unique_household_id."',description='".$description."' ";
					$insert_data=mysqli_query($conn,$insert);
               
            }
            // Close opened CSV file
            fclose($csvFile);
            
             echo "<script>alert('Sampling Module Added Successfully');</script>";
            echo"<script>window.location.href='survey-list.php'</script>";
	
        }else{
             echo "<script>alert('Some problem occurred, please try again.');</script>";
        }
    }else{
       // $qstring = '?status=invalid_file';
    	 echo "<script>alert('Invalid File Formate Only Support CSV Formate!!');</script>";
    }
}

?>
<section id="main-content">
  <section class="wrapper">
    <div class="row">
     <div class="col-lg-12">
        <!--<h3 class="page-header"><i class="fa fa-laptop"></i> Dashboard</h3>-->
        <ol class="breadcrumb">
          <li><i class="fa fa-home"></i><a href="index.html">Home</a></li>
          <li><i class="icon_documents_alt"></i>Form List</li>
          <li><i class="fa fa-plus"></i>Add Sampling Module</li>
       </ol>
      </div>
    </div>
    <!-- page start-->    
    
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">Add Sampling Module</header>
          <div class="panel-body">
            <div class="form">
              <form class="form-validate form-horizontal" id="myForm" method="post" enctype="multipart/form-data">
                <div class="form-group ">
                      <label for="fullname" class="control-label col-lg-2">Select Form <span style="color:red;">*</span></label>
					<div class="col-lg-10">
						<select class="form-control" name="survey_id">
							<option value="">Select Form </option>
							<?php  
								$surveysql=mysqli_query($conn,"SELECT id,survey_name FROM `survey` where del_action='N' order by id desc");
								while($type=mysqli_fetch_array($surveysql))
								{?>
									<option value="<?=$type['id'];?>"<?php if(isset($_REQUEST['survey_id'])){ echo "selected";} ?>><?=$type['survey_name'];?></option>
							
								<?php
								}
								?>
						</select>
                    </div>
				</div>
				<!--<div class="form-group ">
                      <label for="description" class="control-label col-lg-2">Description <span style="color:red;">*</span></label>
					<div class="col-lg-10">
					<textarea id="description"  class="form-control" name="description" rows="4" cols="50"> </textarea>
                    </div>
				</div>-->
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Upload Sampling: <span style="color:red;">*</span></label>
                  <div class="col-lg-8">
					<div class="form-check">
						<input type="file" class="form-control" type="file" id="upload_sampling" name="upload_sampling" accept=".csv" onchange="sampling_module()" > 
					</div>
					<span style="color:red;">Support only csv file format</span>
                  </div>
				  <div class="col-lg-2">
					<div class="form-check">
						<u><a href="sampling_module.csv" class="form-control btn btn-info"> Download Sample</a></u>
					</div>
                  </div>
               </div>
			   <div class="form-group">
                      <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-primary" type="submit" name="submit">submit</button>
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
<script type="text/javascript">
function sampling_module() {
    var formData = new FormData();
    var file = document.getElementById("upload_sampling").files[0];
    formData.append("Filedata", file);
    
    var t = file.type.split('/').pop().toLowerCase();
    if (t != "csv") {
        alert('Please select a valid file');
        document.getElementById("upload_sampling").value = '';
        return false;
    }

    return true;
}
</script>