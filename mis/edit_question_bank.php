<?php include_once('includes/config.php'); ?>
<?php define("title", "Update Question Bank | MQUAD"); ?>
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
if (isset($_REQUEST['qid']) && $_REQUEST['qid'] != '') {
	
   $getsqlques = mysqli_query($conn, "SELECT source_link,question_bank_id,data_source,target_group,question_bank.category_id,question_bank_name,unique_id,question_type,categories.category_name FROM `question_bank` left join categories on question_bank.category_id=categories.category_id where question_bank_id='" . $_REQUEST['qid'] . "'");
   $data = mysqli_fetch_array($getsqlques);
   $category_id = $data['category_id'];
}

?>
<?php
if (isset($_POST['update_data'])) {
   $page = $_GET['page'];
   $quid = $_REQUEST['qid'];
   $user_id = $_SESSION['user_id'];
   $category_id = $_POST['category_id'];
  $categoryid = implode(",", $category_id);
	
   $questions_type_id = $_POST['questions_type_id'];
   $question_bank_name = sanitizeInput($_POST['question_bank_name'],$conn);
   $data_source = sanitizeInput($_POST['data_source'],$conn);
  // $target_group = sanitizeInput($_POST['target_group']);
   $option_names = sanitizeInput($_POST['option_name'],$conn);
   $option_values = sanitizeInput($_POST['option_value'],$conn);
   $optionname_multiple = sanitizeInput($_POST['option_name_multiple'],$conn);
   $optionvalue_multiple = sanitizeInput($_POST['option_value_multiple'],$conn);
   $optionname_radio = sanitizeInput($_POST['option_name_radio'],$conn);
   $optionvalue_radio = sanitizeInput($_POST['option_value_radio'],$conn);
   
   $source_link = filter_input(INPUT_POST, 'source_link', FILTER_SANITIZE_URL);

	if (!filter_var($source_link, FILTER_VALIDATE_URL)) {
		$_SESSION['status_error'] = "Invalid questionnaire link";
		$_SESSION['status_error_code'] = "warning";
		exit();
	} else {
		$source_link = htmlspecialchars($source_link, ENT_QUOTES, 'UTF-8');
	}
   $uniqueid = $_POST['unique_id'];

   $updatesql = "UPDATE question_bank SET source_link='" . $source_link . "',question_bank_name='" . $question_bank_name . "',data_source='" . $data_source . "',$status_type,user_id='" . $user_id . "',question_type='" . $questions_type_id . "',category_id='" . $categoryid . "' WHERE question_bank_id='" . $_REQUEST['qid'] . "'";
 
  $updatequestion = mysqli_query($conn, $updatesql);

   if ($questions_type_id == 'select_one') {
      mysqli_query($conn, "DELETE FROM `question_bank_option` WHERE question_bank_id='" . $_REQUEST['qid'] . "'");

      foreach ($option_names as $optn_key => $optionname) {
         $optionvalue = $option_values[$optn_key];

         $updatequestion = mysqli_query($conn, "insert into question_bank_option SET question_option_name='" . $optionname . "',option_value='" . $optionvalue . "', question_bank_id='" . $_REQUEST['qid'] . "'");
      }
   }
   if ($questions_type_id == 'select_multiple') {
      mysqli_query($conn, "DELETE FROM `question_bank_option` WHERE question_bank_id='" . $_REQUEST['qid'] . "'");

      foreach ($optionname_multiple as $optn_keymultiple => $optionname_multiples) {
         $optionvalue_multiples = $optionvalue_multiple[$optn_keymultiple];
         $updatequestion = mysqli_query($conn, "insert into question_bank_option SET question_option_name='" . $optionname_multiples . "',option_value='" . $optionvalue_multiples . "', question_bank_id='" . $_REQUEST['qid'] . "'");
      }
   }

   if ($questions_type_id == 'radio_button') {
      mysqli_query($conn, "DELETE FROM `question_bank_option` WHERE question_bank_id='" . $_REQUEST['qid'] . "'");
      foreach ($optionname_radio as $optns_key => $optionname_radios) {
         $optionvalue_radios = $optionvalue_radio[$optns_key];

         $updatequestion = mysqli_query($conn, "insert into question_bank_option  SET question_option_name='" . $optionname_radios . "',option_value='" . $optionvalue_radios . "', question_bank_id='" . $_REQUEST['qid'] . "'");
      }
   }
   if ($updatequestion) {
      $_SESSION['status'] = "Question Update Successfully";
      $_SESSION['status_code'] = "success";
      echo "<script>window.location.href='my_question.php?qid=$quid&page=$page'</script>";
   } else {
      $_SESSION['status_error'] = "Something went wrong!!";
      $_SESSION['status_error_code'] = "warning";
   }
}
?>
<!--main content start-->
<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Show Questions</li>
                  <li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>Update Question Bank</li>
               </ol>
            </nav>
         </div>
      </div>
      <!-- page start-->
      <div class="row">
         <div class="col-lg-12">
            <section class="panel">
               <header class="panel-heading">Update Question Bank</header>
               <div class="panel-body">
                  <div class="form">
                     <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" name="qid" value="<?= $_REQUEST['qid']; ?>">
                        <input type="hidden" class="form-control" name="unique_id" value="<?= $data['unique_id']; ?>">

                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Question: </label>
                           <div class="col-lg-10">
                              <textarea class="form-control" required="" placeholder="" name="question_bank_name" rows="5"><?php echo $data['question_bank_name']; ?></textarea>
                           </div>
                        </div>

                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Question Type: </label>
                           <div class="col-lg-10">
                              <select class="form-select select2" name="questions_type_id" id="questions_type_id">
                                 <option value="">Question Type</option>
                                 <option value="select_one" <?php if ($data['question_type'] == "select_one") {
                                                               echo "selected";
                                                            } ?>>Select One</option>
                                 <!-- <option value="radio_button" <?php if ($data['question_type'] == "radio_button") {
                                                                        echo "selected";
                                                                     } ?>>Radio button</option> -->
                                 <option value="select_multiple" <?php if ($data['question_type'] == "select_multiple") {
                                                                     echo "selected";
                                                                  } ?>>Select Multiple</option>
                                 <option value="text" <?php if ($data['question_type'] == "text") {
                                                         echo "selected";
                                                      } ?>>Text</option>
                                 <option value="number" <?php if ($data['question_type'] == "number") {
                                                            echo "selected";
                                                         } ?>>Number</option>
                                 <option value="note" <?php if ($data['question_type'] == "note") {
                                                         echo "selected";
                                                      } ?>>Note</option>
                                 <option value="date" <?php if ($data['question_type'] == "date") {
                                                         echo "selected";
                                                      } ?>>Date</option>
                              </select>
                           </div>
                        </div>

                        <!------------------------------Start select one------------------------------------>
                        <div class="row" id="statediv" style="<?php if ($data['question_type'] == "select_one") {
                                                                  echo "display: block";
                                                               } else {
                                                                  echo "display:none";
                                                               } ?>">
                           <div class="mb-3 row">
                              <label class="control-label col-lg-2">Options:</label>
                              <div class="col-lg-10">
                                 <div class="table-responsive">
                                    <table class="table table-bordered new-design">
                                       <thead>
                                          <tr>
                                             <th class="sl-no">Sl. No. </th>
                                             <th>Option Name</th>
                                             <th>Option Value </th>
                                             <th>Action </th>
                                          </tr>
                                       </thead>

                                       <tbody id="add_column">
                                          <?php
                                          $getoptionsql = mysqli_query($conn, "SELECT question_option_name,option_value FROM `question_bank_option` where question_bank_id='" . $_REQUEST['qid'] . "'");
                                          if (mysqli_num_rows($getoptionsql) > 0) {

                                             while ($optiondata = mysqli_fetch_array($getoptionsql)) {

                                          ?>
                                                <tr>
                                                   <td class="text-center">1</td>
                                                   <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name[]" value="<?= $optiondata['question_option_name']; ?>"></td>
                                                   <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value[]" value="<?= $optiondata['option_value']; ?>"></td>
                                                   <td>
                                                      <div onclick="createDivMatch()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                                   </td>
                                                </tr>
                                             <?php }
                                          } else { ?>
                                             <tr>
                                                <td class="text-center">1</td>
                                                <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name[]"></td>
                                                <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value[]"></td>
                                                <td>
                                                   <div onclick="createDivMatch()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                                </td>
                                             </tr>
                                          <?php } ?>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!------------------------------End select one------------------------------------>
                        <!------------------------------Start select Multiple------------------------------------->
                        <div class="row" id="statedivmultiple" style="<?php if ($data['question_type'] == "select_multiple") {
                                                                           echo "display: block";
                                                                        } else {
                                                                           echo "display:none";
                                                                        } ?>">
                           <div class="mb-3 row">
                              <label class="control-label col-lg-2">Options:</label>
                              <div class="col-lg-10">
                                 <div class="table-responsive">
                                    <table class="table table-bordered new-design">
                                       <thead>
                                          <tr>
                                             <th class="sl-no">Sl. No. </th>
                                             <th>Option Name</th>
                                             <th>Option Value </th>
                                             <th>Action </th>
                                          </tr>
                                       </thead>

                                       <tbody id="add_columnmultiple">
                                          <?php
                                          $getoptionsql = mysqli_query($conn, "SELECT question_option_name,option_value FROM `question_bank_option` where question_bank_id='" . $_REQUEST['qid'] . "'");
                                          if (mysqli_num_rows($getoptionsql) > 0) {

                                             while ($optiondata = mysqli_fetch_array($getoptionsql)) {

                                          ?>
                                                <tr>
                                                   <td class="text-center">1</td>
                                                   <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_multiple[]" value="<?= $optiondata['question_option_name']; ?>"></td>
                                                   <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_multiple[]" value="<?= $optiondata['option_value']; ?>"></td>
                                                   <td>
                                                      <div onclick="createDivMultiple()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                                   </td>
                                                </tr>
                                             <?php }
                                          } else { ?>
                                             <tr>
                                                <td class="text-center">1</td>
                                                <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_multiple[]"></td>
                                                <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_multiple[]"></td>
                                                <td>
                                                   <div onclick="createDivMultiple()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                                </td>
                                             </tr>
                                          <?php } ?>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!------------------------------End select Multiple------------------------------------>
                        <!------------------------------radio button------------------------------------>
                        <div class="mb-3 row" id="statedivradio" style="<?php if ($data['question_type'] == "radio_button") {
                                                                              echo "display: block";
                                                                           } else {
                                                                              echo "display:none";
                                                                           } ?>">
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
                                       <?php
                                       $getoptionradiosql = mysqli_query($conn, "SELECT question_option_name,option_value FROM `question_bank_option` where question_bank_id='" . $_REQUEST['qid'] . "'");
                                       if (mysqli_num_rows($getoptionradiosql) > 0) {
                                          while ($optionradio = mysqli_fetch_array($getoptionradiosql)) {

                                       ?>
                                             <tr>
                                                <td class="text-center">1</td>
                                                <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_radio[]" value="<?= $optionradio['question_option_name']; ?>"></td>
                                                <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_radio[]" value="<?= $optionradio['option_value']; ?>"></td>
                                                <td>
                                                   <div onclick="createDivradio()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                                </td>
                                             </tr>
                                          <?php }
                                       } else { ?>
                                          <tr>
                                             <td class="text-center">1</td>
                                             <td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_radio[]"></td>
                                             <td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_radio[]"></td>
                                             <td>
                                                <div onclick="createDivradio()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div>
                                             </td>
                                          </tr>
                                       <?php } ?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
						
                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Thematic Area(s): </label>
                           <div class="col-lg-10">
                              <select class="form-control select2" multiple name="category_id[]" id="category_id" required>
                                 <option value="">Thematic Area</option>
                                 <?php
									$category_id_array = explode(',', $category_id);

									$categorysql = mysqli_query($conn, "SELECT category_id, category_name FROM categories WHERE status='0'");

									while ($category = mysqli_fetch_array($categorysql)) {
										$selected = in_array($category['category_id'], $category_id_array) ? 'selected' : '';
									?>
										<option value="<?php echo $category['category_id']; ?>" <?php echo $selected; ?>>
											<?php echo $category['category_name']; ?>
										</option>
									<?php } ?>
                              </select>
                           </div>
                        </div>

                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Source: <span style="color:red;">*</span></label>
                           <div class="col-lg-10">
                              <input type="text" name="data_source" value="<?= $data['data_source']; ?>" class="form-control" />
                           </div>
                        </div>

                        <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Source Link: </label>
                           <div class="col-lg-10">
                              <input type="text" name="source_link" value="<?= $data['source_link']; ?>" class="form-control" />
                           </div>
                        </div>

                        <!-- <div class="mb-3 row">
                           <label for="cname" class="control-label col-lg-2">Remark: <span style="color:red;">*</span></label>
                           <div class="col-lg-10">
                              <input type="text" name="target_group" value="<?= $data['target_group']; ?>" class="form-control" />
                           </div>
                        </div> -->


                        <!-----------------------------end------------------------------------>
                        <div class="mb-3 row">
                           <div class="col-lg-offset-2 col-lg-12 text-end">
                              <button class="btn btn-primary" type="submit" name="update_data">Update</button>
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
<?php include_once('includes/footer.php'); ?>
<!--main content end-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<?php if (isset($_SESSION['status_error']) && $_SESSION['status_error'] != '') { ?>
   <script>
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
   $(document).ready(function() {
      $("#questions_type_id").change(function() {
         if ($(this).val() == "select_one") {
            $("#statediv").css("display", "block");
         } else {
            $("#statediv").css("display", "none");
         }
         if ($(this).val() == "select_multiple") {
            $("#statedivmultiple").css("display", "block");
         } else {
            $("#statedivmultiple").css("display", "none");
         }

         if ($(this).val() == "radio_button") {
            $("#statedivradio").css("display", "block");
         } else {
            $("#statedivradio").css("display", "none");
         }

      });
   });
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

   function createDivMultiple() {
      count++;
      var adddivmultiple = '<tr><td class="text-center">' + count + '</td><td><input type="text" class="form-control" placeholder="Enter Option Name" name="option_name_multiple[]"></td><td><input type="text" class="form-control" placeholder="Enter Option Value" name="option_value_multiple[]"></td><td><div onclick="createDivMultiple()" style="height: 30px;width: 40px; background: #394A59; float: left;text-align: center;border-radius: 5px;cursor: pointer;margin-top: 0px;color: white;font-size: 22px;"><b>+</b></div> <b class="remv" onclick="removeDivmultiple(this)" style="margin-left:6px;margin-top: 0px;float:left;color:white;background-color:red;border-radius:5px;padding:2px;cursor:pointer;height: 30px;width: 40px;text-align: center;font-size: 18px;">x</b></td></tr>';
      $("#add_columnmultiple").append(adddivmultiple);
   }

   function removeDivmultiple(sss) {

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
