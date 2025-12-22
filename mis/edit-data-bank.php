<?php include_once('includes/config.php'); ?>
<?php define("title","Update Dataset | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
if($_REQUEST['eid'] && $_REQUEST['eid']!=''){
	 $sqldataset="SELECT data_repositroy.data_repository_id,data_repositroy.category_id, data_repositroy.study_name,data_repositroy.data_repositroy_status,data_repositroy.description,data_repositroy.institution_name, data_repositroy.published_date,data_repositroy.category_id,data_repositroy.data_study_year,data_repositroy_otherdata.data_access,data_repositroy_otherdata.data_name,data_repositroy_otherdata.author_name,data_repositroy_otherdata.upload_codebook,data_repositroy_otherdata.contact_email FROM data_repositroy left join users on users.user_id=data_repositroy.user_id left join data_repositroy_otherdata on data_repositroy.data_repository_id=data_repositroy_otherdata.data_repository_id where data_repositroy.data_repository_id='".$_REQUEST['eid']."'";
	$qrydataset=mysqli_query($conn,$sqldataset);
	$dataset=mysqli_fetch_array($qrydataset);
}
?>
<?php
	/* 
	if(isset($_REQUEST['submit']))
	{
			$user_id=$_SESSION['user_id'];
			$studyname=$_REQUEST['studyname'];
			$institution_name=$_REQUEST['institution_name'];
			$description=$_REQUEST['description'];
			$published_date= $_REQUEST['published_date'];
			$data_study_year=$_REQUEST['data_study_year'];
			$category_id=$_REQUEST['category_id'];
			$category_id=implode(",",$category_id);
			 
			$data_name=$_REQUEST['data_name'];
			$data_access=$_REQUEST['data_access'];
			$author_name=$_REQUEST['author_name'];
			
			$data_format_name = $_REQUEST['data_format_name'];
			$contact_email=$_REQUEST['contact_email'];
			
				$insertdatarep="insert into data_repositroy set  study_name='".$studyname."',institution_name='".$institution_name."',category_id='".$category_id."',description='".$description."',published_date='".$published_date."',data_study_year='".$data_study_year."',user_id='".$user_id."' ";	
				$datarepository=mysqli_query($conn,$insertdatarep);
				$last_data_repositroy_id=mysqli_insert_id($conn);
				
			foreach($data_name as $data_key=>$datanames){
				$dataaccess = $data_access[$data_key];
				$authorname = $author_name[$data_key];
				
				$filename=$_FILES['upload_codebook']['name'][$data_key];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$filename = "".$datanames."_".$published_date.".".$ext;
				//$filename = "".$studyname.".".$ext;
				$tempname = $_FILES['upload_codebook']['tmp_name'][$data_key];
				$file_size=$_FILES['upload_codebook']['size'][$data_key];
				$upload_codebook_file = str_replace(" ", "_", $filename);
				$location="upload_data_file/upload_codebook/";
				
				$contactemail = $contact_email[$data_key];
				
				$insertdatarepother="insert into data_repositroy_otherdata set data_name='".$datanames."',data_access='".$dataaccess."',upload_codebook='".$upload_codebook_file."',contact_email='".$contactemail."',author_name='".$authorname."',data_repository_id='".$last_data_repositroy_id."',user_id='".$user_id."' ";	
				$datarep_other=mysqli_query($conn,$insertdatarepother);
				move_uploaded_file($tempname,$location.$upload_codebook_file);
				$last_data_repositroy_otherdata_id=mysqli_insert_id($conn);
				
				foreach($data_format_name[$data_key] as $dataformate_key=>$data_format_namess){
					
						$data_formate=$_FILES['ch_for']['name'][$data_key][$dataformate_key];
						$tempname = $_FILES['ch_for']['tmp_name'][$data_key][$dataformate_key];
						$location="upload_data_file/upload_dataformate_file/";
						
						$insertdatarepother="insert into repository_dataformat set data_repository_id='".$last_data_repositroy_id."',data_repositroy_otherdata_id='".$last_data_repositroy_otherdata_id."', data_format_name='".$data_format_namess."',data_formate_file='".$data_formate."',user_id='".$user_id."' ";	
						$datarep_other=mysqli_query($conn,$insertdatarepother);
						move_uploaded_file($tempname,$location.$data_formate);
							if($insertdatarepother){
								echo "<script>alert('Submitted successfully');window.location.href='data_bank.php'</script>";
								
							}
				}
			}			
	}
	*/
    ?>
	<style>
.field_set{
 
     border: 1px groove #ddd !important;
    padding-top: 10px;
    padding-right: 5px;
	padding-bottom: 5px;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
     box-shadow:  0px 0px 0px 0px #000;
 #more {display: none;}
}

</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />  
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper">
    <div class="row">
     <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><i class="icon_documents_alt"></i>Data Repository</li>
          <li><i class="fa fa-plus"></i>Update Dataset</li>
       </ol>
      </div>
    </div>
    <!-- page start-->    
    
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">Data Repository 
		 </header>
          <div class="panel-body">
            <div class="form">
              <form class="form-validate form-horizontal" id="myForm" method="post" enctype="multipart/form-data">
                  <div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Study Name: </label>
                  <div class="col-lg-10">
                    <input class="form-control" id="study_name" name="studyname"  type="text" value="<?=$dataset['study_name'];?>"/>
					
                  </div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Year: </label>
                  <div class="col-lg-10">
                    <input class="form-control" id="datepicker" name="data_study_year" type="text" value="<?=$dataset['data_study_year'];?>"/>
                  </div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Institution : </label>
                  <div class="col-lg-10">
                    <input class="form-control" id="institution_name" name="institution_name" type="text" value="<?=$dataset['institution_name'];?>"/>
					
                  </div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Information Area: </label>
                  <div class="col-lg-10">
					  <div class="form-check">
						  <select name="category_id[]" class="form-control multiple-select" multiple required >
						   <?php 
							  $categorysql=mysqli_query($conn,"SELECT category_id,category_name FROM categories where status='0'");
							  while($categoryform=mysqli_fetch_array($categorysql)) { ?>
							 
									<option value="<?php echo $categoryform['category_id'];?>"><?php echo $categoryform['category_name']; ?></option>
							<?php  } ?>
						  </select>
					  </div>
                  </div>
               </div>
			<fieldset class="field_set" id="more">
			<!--<legend>Data Repository data:</legend>-->
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Data Name: </label>
                  <div class="col-lg-10">
                    <input class="form-control" id="data_name" name="data_name[]" type="text" value="<?=$dataset['data_name'];?>"/>
					
                  </div>
                </div>
				 
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Data Access: <span style="color:red; ">*</span></label>
					<div class="col-lg-10">
							<select class="form-control" name="data_access[]">
								<option value="">Select Data Type</option>
								<option value="Public">Public</option>
								<option value="Private">Private</option>
							</select>
					</div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Data Format: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
						
						<div class=" row ss">
							<div class="col-lg-2">
								<div class="form-check">
								<input type="checkbox"  value="Excel" name="data_format_name[0][]"> Excel
								</div>
							</div>
							<div class="col-lg-10">
								<input type="file" name="ch_for[0][]" style="display:none" class="form-control ch_for ">
							</div>
						</div>
						<div class=" row ss">
							<div class="col-lg-2">
								<div class="form-check">
								<input type="checkbox"  value="SPSS" name="data_format_name[0][]"> SPSS
								</div>
							</div>
							<div class="col-lg-10">
								<input type="file" name="ch_for[0][]" style="display:none" class="form-control ch_for ">
							</div>
						</div>
						<div class=" row ss">
							<div class="col-lg-2">
								<div class="form-check">
								<input type="checkbox"  value="Stata" name="data_format_name[0][]"> Stata
								</div>
							</div>
							<div class="col-lg-10">
								<input type="file" name="ch_for[0][]" style="display:none" class="form-control ch_for ">
							</div>
						</div>
						
                  </div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Upload Code Book: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
                    <input class="form-control" name="upload_codebook[]"  type="File"/>
					
                  </div>
                </div>
				
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Email: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
                    <input class="form-control" id="meta_data" name="contact_email[]"  type="email" value="<?=$dataset['contact_email'];?>"/>
					
                  </div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Author: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
                    <input class="form-control" id="author_name" name="author_name[]"  type="text" value="<?=$dataset['author_name'];?>"/>
                  </div>
                </div>
			</fieldset>
			<div id='readId'></div>
			<button type="button" class="btn btn-primary btn-sm" onclick="myFunction(1)" style="float: right;">Add More</button></br>
				<div class="form-group" style="margin-top: 17px;">
					<label for="cname" class="control-label col-lg-2">Description: <span style="color:red;">*</span></label>
					<div class="col-lg-10">
						<textarea class="form-control"  placeholder="" name="description"  rows="5"><?=$dataset['description'];?></textarea> 
					</div>
				</div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Published Date: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
                    <input class="form-control" value="<?=$dataset['published_date'];?>" name="published_date" type="date" />
                  </div>
                </div>
				
                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <button class="btn btn-primary" type="submit" id="submit" name="update">Update</button>
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

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
	$(".multiple-select").select2({
	//maximumSelectionLength: 2
	});
	
 $("#datepicker").datepicker({
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years",
    autoclose:true //to close picker once year is selected
});
</script>
<script>
$(document).ready(function () {
  $('.ss input:checkbox').on('click', function(){
    $(this).closest('.ss').find('.ch_for').toggle();
  })
});


</script>
<script>
var sss=1;
function myFunction(val)
{
	var readmoreid = 1;
//alert(sss);
	$.ajax({
            type:'post',
            url:'add_databank_ajax.php',
            data:{readmoreid:readmoreid,sss:sss},
			success:function(responsedata){
                // alert(responsedata);
                $('#readId').append(responsedata);
            }
       });
	   sss++;
}

$(document).on("click", ".remv", function() {
	$(this).parent().remove(); 
});
</script>

