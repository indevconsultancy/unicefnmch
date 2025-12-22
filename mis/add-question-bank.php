<?php include_once('includes/config.php'); ?>
<?php define("title", "Add Question | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php

$status_type = '';
if ($_SESSION['role_id'] == '1') {
   $status_type = "status_type='1'";
} else if ($_SESSION['role_id'] == '3') {
   $status_type = "status_type='0'";
}

?>
<?php
if (isset($_POST['submit_data'])) {

   $user_id = $_SESSION['user_id'];
   $category_id = $_POST['category_id'];
   $categoryId=implode(',',$category_id);
   $theme_id = $_POST['theme_id'];
   $questions_type_id = $_POST['questions_type_id'];
   
    $question_bank_name = sanitizeInput($_POST['question_bank_name'],$conn);
   $option_names = sanitizeInput($_POST['option_name'],$conn);
   $option_values = sanitizeInput($_POST['option_value'],$conn);
   $data_source =sanitizeInput($_POST['data_source'],$conn);
   // $target_group = $_POST['target_group'];
   $optionname_multiple = sanitizeInput($_POST['option_name_multiple'],$conn);
   $optionvalue_multiple = sanitizeInput($_POST['option_value_multiple'],$conn);
   $optionname_radio = sanitizeInput($_POST['option_name_radio'],$conn);
   $optionvalue_radio = sanitizeInput($_POST['option_value_radio'],$conn); 
    //$source_link=$_POST['source_link'];

	if (!filter_var($_POST['source_link'], FILTER_VALIDATE_URL)) {
		$_SESSION['status_error'] = "Invalid questionnaire link";
		$_SESSION['status_error_code'] = "warning";
		exit();
	} else {
		$source_link = htmlspecialchars($_POST['source_link'], ENT_QUOTES, 'UTF-8');
	}
	
   $uniqueid = rand(10000000000, 10);

	 $insertSql = "INSERT INTO question_bank set question_bank_name='" . $question_bank_name . "',unique_id='" . $uniqueid . "', question_type='" . $questions_type_id . "',category_id='" . $categoryId . "',theme_id='" . $theme_id . "',data_source='" . $data_source . "',source_link='" . $source_link . "',user_id='" . $user_id . "',$status_type";
	
	$insert = mysqli_query($conn, $insertSql);
   if ($insert!='') {
      $qid = mysqli_insert_id($conn);
      $field_name = "q" . $qid;
      $sqlinsert = mysqli_query($conn, "UPDATE question_bank SET field_name='" . $field_name . "' WHERE question_bank_id='" . $qid . "' ");
   }else{
	   $_SESSION['status_error'] = "Something went wrong!!";
		$_SESSION['status_error_code'] = "warning";
		exit();
   }
   if ($questions_type_id == 'select_one') {
      foreach ($option_names as $optn_key => $optionname) {
         $optionvalue = $option_values[$optn_key];
         $insertoption = "INSERT INTO question_bank_option (question_bank_id, question_option_name,option_value) VALUES ('$qid', '$optionname','$optionvalue')";
         $sqlinsert = mysqli_query($conn, $insertoption);
      }
   }
   if ($questions_type_id == 'select_multiple') {
      foreach ($optionname_multiple as $optnmultiple_key => $optionname_multiples) {
         $optionvalue_multiples = $optionvalue_multiple[$optnmultiple_key];
         $insertoptionmul = "INSERT INTO question_bank_option (question_bank_id, question_option_name,option_value) VALUES ('$qid', '$optionname_multiples','$optionvalue_multiples')";
         $sqlinsert = mysqli_query($conn, $insertoptionmul);
      }
   }
   if ($questions_type_id == 'radio_button') {
      foreach ($optionname_radio as $optns_key => $optionname_radios) {
         $optionvalue_radios = $optionvalue_radio[$optns_key];
         $insertradio = "INSERT INTO question_bank_option (question_bank_id, question_option_name,option_value) VALUES ('$qid', '$optionname_radios','$optionvalue_radios')";
         $sqlinsert = mysqli_query($conn, $insertradio);
      }
   }

   if ($sqlinsert) {
      $_SESSION['status'] = "Question Added Successfully";
      $_SESSION['status_code'] = "success";
      echo "<script>window.location.href='my_question.php'</script>";
   } else {
      $_SESSION['status_error'] = "Something went wrong!!";
      $_SESSION['status_error_code'] = "warning";
	  exit();
   }
}
?>
<style>
select.form-control {
    color: #000;  /* Make sure the text is black */
    background-color: #fff;  /* Ensure it's not greyed out */
}
</style>
<!--main content start-->
<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Question Bank</li>
                  <li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>Add Question </li>
               </ol>
            </nav>
         </div>
      </div>
      <!-- page start-->
      <div class="row">
         <div class="col-lg-12">
            <section class="panel">
               <header class="panel-heading">Add Question</header>
               <div class="panel-body">
                  <div class="form">
                     <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Question: <span style="color:red;">*</span></label>
                           <div class="col-lg-10">
                              <textarea class="form-control" required="" placeholder="" name="question_bank_name" required rows="5"></textarea>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Question Type: <span style="color:red;">*</span></label>
                           <div class="col-lg-10">
                              <select class="form-select select2" name="questions_type_id" required id="questions_type_id" onchange="ShowHideDiv()">
                                 <option value="">Question Type</option>
                                 <option value="select_one">Select One</option>
                                 <!-- <option value="radio_button">Radio button</option> -->
                                 <option value="select_multiple">Select Multiple</option>
                                 <option value="text">Text</option>
                                 <option value="number">Number</option>
                                 <option value="date">Date</option>
                              </select>
                           </div>
                        </div>
                        <!-------------------------select one--------------------------------------------->
                        <div class="mb-3" style="display: none;" id="divoption">
                           <div class="row">
                              <label class="control-label col-lg-2">Options:</label>
                              <div class="col-lg-10">
                                 <div class="table-responsive">
                                    <table class="table table-bordered new-design">
                                       <thead>
                                          <tr>
                                             <th class="sl-no">Sl.No. </th>
                                             <th>Option Name</th>
                                             <th>Option Value </th>
                                             <th>Action </th>
                                          </tr>
                                       </thead>
                                       <tbody id="add_column">
                                          <tr>
                                             <td class="text-center">1</td>
                                             <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name[]"></td>
                                             <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value[]"></td>
                                             <td>
                                                <div onclick="createDivMatch()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-------------------------select multiple--------------------------------------------->
                        <div class="mb-3" style="display: none;" id="divmultipleoption">
                           <div class="row">
                              <label class="control-label col-lg-2">Options:</label>
                              <div class="col-lg-10">
                                 <div class="table-responsive">
                                    <table class="table table-bordered new-design">
                                       <thead>
                                          <tr>
                                             <th class="sl-no">Sl.No. </th>
                                             <th>Option Name</th>
                                             <th>Option Value </th>
                                             <th>Action </th>
                                          </tr>
                                       </thead>
                                       <tbody id="add_columnmultiple">
                                          <tr>
                                             <td class="text-center">1</td>
                                             <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_multiple[]"></td>
                                             <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_multiple[]"></td>
                                             <td>
                                                <div onclick="createDivMatchmultiple()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-----------------------end select multiple------------------------------------->
                        <!----------------------- radio ------------------------------------->
                        <div class="mb-3 row" id="divradio" style="display: none;">
                           <label class="control-label col-lg-2">Options:</label>
                           <div class="col-lg-10">
                              <div class="table-responsive">
                                 <table class="table table-bordered new-design">
                                    <thead>
                                       <tr>
                                          <th class="sl-no">Sl.No. </th>
                                          <th>Option Name</th>
                                          <th>Option Value </th>
                                          <th>Action </th>
                                       </tr>
                                    </thead>
                                    <tbody id="add_columnradio">
                                       <tr>
                                          <td class="text-center">1</td>
                                          <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_radio[]"></td>
                                          <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_radio[]"></td>
                                          <td>
                                             <div onclick="createDivradio()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-----------------------end radio ------------------------------------->
                       <div class="mb-3 row">
							<label for="cname" class="control-label col-lg-2">Thematic Area(s): <span style="color:red;">*</span></label>
							<div class="col-lg-10">
								<select class="form-control select2" multiple name="category_id[]" id="category_id" required>
									<option value="" disabled>Select Thematic Area(s)</option>
									<?php
									$getCategoryname = mysqli_query($conn, "SELECT category_id, category_name FROM `categories` WHERE status='0' ORDER BY sequence ASC");
									while ($categoryid = mysqli_fetch_array($getCategoryname)) { ?>
										<option value="<?php echo $categoryid['category_id']; ?>"><?php echo $categoryid['category_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
		
                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Sub Theme: </label>
                           <div class="col-lg-10">
                              <select class="form-control js-example-basic-single" name="theme_id" id="sub_theme">
                                 <option value="" disabled>Sub Theme</option>
                                 <?php
                                 $themeType = mysqli_query($conn, "SELECT theme_id,theme_name FROM `theme` where status='0' AND category_id='" . $_REQUEST['category_id'] . "' order by theme_name ASC");
                                 while ($theme = mysqli_fetch_array($themeType)) {
                                 ?>
                                    <option value="<?= $theme['category_id'] ?>"><?= $theme['theme_name'] ?></option>
                                 <?php
                                 }
                                 ?>
                              </select>
                           </div>
                        </div>

                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Source: <span style="color:red;">*</span></label>
                           <div class="col-lg-10">
                              <input type="text" name="data_source" placeholder="Please enter name of the source." required class="form-control" />
                           </div>
                        </div>
                        <!-- Add New Field Source Link -->
                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Source Link: </label>
                           <div class="col-lg-10">
                              <input type="text" name="source_link" placeholder="Please enter source link." class="form-control" />
                           </div>
                        </div>

                        <!-- <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Remark/Target Group: <span style="color:red;">*</span></label>
                           <div class="col-lg-10">
                              <input type="text" name="target_group" placeholder="Please enter remark." required class="form-control" />
                           </div>
                        </div> -->


                        <div class="mb-3 row">
                           <div class="col-lg-offset-2 col-lg-12 text-end">
                              <button class="btn btn-primary" type="submit" name="submit_data">Submit</button>
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
   <?php if (isset($_SESSION['status_error']) && $_SESSION['status_error'] != '') { ?>
         <
         script >
         swal.fire({
            title: "<?php echo $_SESSION['status_error']; ?>",
            icon: "<?php echo $_SESSION['status_error_code']; ?>",
            confirmButtonColor: '#449A97',
            confirmButtonText: 'Ok'
         });
</script>
<?php unset($_SESSION['status_error']);
   }  ?>
<script>
  // $(document).ready(function() {
    // $('.js-example-basic-single').select2();
// });
</script>
<script type="text/javascript">
   function ShowHideDiv() {
      var questions_type_id = document.getElementById("questions_type_id");
      var divoption = document.getElementById("divoption");
      divoption.style.display = questions_type_id.value == "select_one" ? "block" : "none";

      var divmultipleoption = document.getElementById("divmultipleoption");
      divmultipleoption.style.display = questions_type_id.value == "select_multiple" ? "block" : "none";

      var divradio = document.getElementById("divradio");
      divradio.style.display = questions_type_id.value == "radio_button" ? "block" : "none";
   }
</script>
<script>
   var count = 1;

   function createDivMatch() {
      count++;
      var adddiv = '<tr><td class="text-center">' + count + '</td><td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name[]"></td><td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value[]"></td><td><div onclick="createDivMatch()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div> <b class="remv" onclick="removeDiv(this)" style="margin-left:6px;margin-top: 0px;float:left;color:white;background-color:red;border-radius:5px;padding:2px;cursor:pointer;height: 30px;width: 40px;text-align: center;font-size: 18px;">x</b></td></tr>';
      $("#add_column").append(adddiv);
   }

   function removeDiv(sss) {

      $(sss).parent().parent().remove();
   }

   var count = 1;

   function createDivMatchmultiple() {
      count++;
      var adddiv = '<tr><td class="text-center">' + count + '</td><td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_multiple[]"></td><td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_multiple[]"></td><td><div onclick="createDivMatchmultiple()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div> <b class="remv" onclick="removeDivMultiple(this)" style="margin-left:6px;margin-top: 0px;float:left;color:white;background-color:red;border-radius:5px;padding:2px;cursor:pointer;height: 30px;width: 40px;text-align: center;font-size: 18px;">x</b></td></tr>';
      $("#add_columnmultiple").append(adddiv);
   }

   function removeDivMultiple(sss) {

      $(sss).parent().parent().remove();
   }


   var count = 1;

   function createDivradio() {
      count++;
      var adddiv = '<tr><td class="text-center">' + count + '</td><td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_radio[]"></td><td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_radio[]"></td><td><div onclick="createDivradio()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div> <b class="remv" onclick="removeDivradio(this)" style="margin-left:6px;margin-top: 0px;float:left;color:white;background-color:red;border-radius:5px;padding:2px;cursor:pointer;height: 30px;width: 40px;text-align: center;font-size: 18px;">x</b></td></tr>';
      $("#add_columnradio").append(adddiv);
   }

   function removeDivradio(sss) {

      $(sss).parent().parent().remove();
   }
</script>
<script>
   $(document).ready(function() {
      $('#category_id').change(function() {
         var category_id = $(this).val();
         $.ajax({
            url: 'ajax/get_sub_themes_ajax.php',
            type: 'POST',
            data: {
               category_id: category_id
            },
            success: function(response) {
               $('#sub_theme').html(response);
            }
         });
      });
   });
</script>