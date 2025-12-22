<?php include_once('includes/config.php'); ?>
<?php define("title", "Upload question bank | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php $user_id = $_SESSION['user_id']; ?>
<!--main content start-->
<style>
    .panel-heading {
        background: #394a59;
        color: white;
        font-weight: unset;
    }

    .btn:not(:disabled):not(.disabled) {
        cursor: pointer;
    }

    .add-button-bg a {
        position: fixed;
        bottom: 54px;
        right: 50px;
        background: rgb(57, 74, 89);
        z-index: 99999;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        color: #fff;
        line-height: 46px;
        font-size: 22px;
        transition: all .3s ease-in-out;
    }

    .add-button-bg a:hover {
        background: rgb(4 39 60);
        color: #ffffff;
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
        box-shadow: 1px 1px 1px 17px rgb(255 192 192 / 28%);

    }

    .sm_value1 {
        padding: 3px;
        color: #ffffff;
        border-radius: 5px;
        min-width: 45px;
        text-align: center;
        font-size: 20px;
        font-weight: 700;
        background: #033d66;
        width: 20px;
    }
</style>
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Quesion bank</li>
                        <li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>Upload question bank </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- page start-->
        <?php
        $status_type = '';
        if ($_SESSION['role_id'] == '1') {
            $status_type = "status_type='1',";
        } else if ($_SESSION['role_id'] == '3') {
            $status_type = "status_type='0',";
        }
        ?>
        <?php
        /* if(isset($_POST['upload'])){
				
				$file_name = $_FILES["file_upload"]["name"];
				$size = $_FILES["file_upload"]["size"];
				$tmp_name = $_FILES["file_upload"]["tmp_name"];
				 $uniqueid = rand(10000000000,10);
				
                
                    set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
                    include 'PHPExcel/Classes/PHPExcel/IOFactory.php';
					
                    $file = $tmp_name;//'/media/sf_E_DRIVE/om/newData.xlsx';
                    $inputFileType = PHPExcel_IOFactory::identify($file);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objReader->setReadDataOnly(true);
                     $objPHPExcel = $objReader->load($file);
					
                    $objWorksheet = $objPHPExcel->getActiveSheet();
                    $CurrentWorkSheetIndex = 0;
					
                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
						
                        // echo 'WorkSheet' . $CurrentWorkSheetIndex++ . "\n";
                        // echo 'Worksheet number - ', $objPHPExcel->getIndex($worksheet), PHP_EOL;
					
                        $sheetSno = $objPHPExcel->getIndex($worksheet);//, PHP_EOL;
                        $highestRow = $worksheet->getHighestDataRow();
                        $highestColumn = $worksheet->getHighestDataColumn();
                         $headings = $worksheet->rangeToArray('A1:' . $highestColumn . 1, NULL,TRUE, FALSE);
						
                        if($sheetSno=='0'){
                          
                            for ($row = 2; $row <= $highestRow; $row++) {
                                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                                $rowData[0] = array_combine($headings[0], $rowData[0]);
                                
                                foreach ($rowData as $key => $rowvalue) {
								
								
                                    $question_name = trim(safe_var($conn,$rowvalue['question_bank_name']));
									$question_bank_name=htmlentities($question_name);
									$field_name = trim(safe_var($conn,$rowvalue['field_name']));
                                   // $target_group = trim(safe_var($conn,$rowvalue['target_group']));
                                    $question_type = trim(safe_var($conn,$rowvalue['question_type']));
									$category_id = safe_var($conn,$rowvalue['category_id']);
									$theme_id = safe_var($conn,$rowvalue['theme_id']);
                                    $data_source = trim(safe_var($conn,$rowvalue['data_source']));
                                    $source_link = $rowvalue['source_link'];
									
									$getCategory=mysqli_query($conn,"SELECT category_id FROM categories where category_id='".$category_id."'");
									$dataCategory=mysqli_fetch_array($getCategory);
									$category_value=$dataCategory['category_id'];
								 
									    //if($question_bank_name!='' && $field_name!='' && $target_group!='' && $category_id!='' && $data_source!=''){
										//if($question_bank_name!='' && $field_name!=''){	
											$getquestion="INSERT INTO question_bank SET $status_type unique_id='".$uniqueid."', question_bank_name='".$question_bank_name."', field_name='".$field_name."', question_type='".$question_type."', category_id='".$category_id."',theme_id='".$theme_id."', common_use='0', data_source='".$data_source."',source_link='".$source_link."',user_id='".$user_id."'";
											
											$dataQuestion=mysqli_query($conn,$getquestion);
											$lastQid = mysqli_insert_id($conn);
										// }else{
											// echo "<script>alert('Your data is empty in question bank sheet, please fill in all your data !!');
											// window.location.href='upload_question_bank.php';</script>";
										// }
									
                                }
                            }
						}
						// die();
                        //OPTIONS
                        
						if($sheetSno=='1'){
                           
                            for ($row = 2; $row <= $highestRow; $row++) {
								
                                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
								
                                $rowData[0] = array_combine($headings[0], $rowData[0]);
                               
                                foreach ($rowData as $key => $rowvalue) {
								
                                    $field_name = trim(safe_var($conn,$rowvalue['field_name']));
                                    $option_value = safe_var($conn,$rowvalue['option_value']);
									$option_name = safe_var($conn,$rowvalue['question_option_name']);
									
									$question_option_name=htmlentities($option_name);
                                    
									$getunique = mysqli_query($conn,"SELECT unique_id FROM question_bank where question_bank_id='".$lastQid."'");
									$unique_data = mysqli_fetch_array($getunique);
									$unique_ids = $unique_data['unique_id'];
									
									//if($option_value!='' && $question_option_name!='' && $field_name!=''){
										
										$getQuestiondb = mysqli_query($conn,"SELECT question_bank_id FROM question_bank where field_name='".$field_name."' and unique_id='".$unique_ids."'");
										while($question_data = mysqli_fetch_array($getQuestiondb)){
											$question_bank_id = $question_data['question_bank_id'];
											 $option_sql = "INSERT INTO question_bank_option SET question_bank_id='".$question_bank_id."', question_option_name='".$question_option_name."', option_value='".$option_value."' ";
											$resultData=mysqli_query($conn,$option_sql);
											
										}
									// }else{
										// echo "<script>alert('Your data is empty in option sheet, please fill in all your data !!');
										// window.location.href='upload_question_bank.php';</script>";
									// }
										
                                } 
								
                            }
							if($resultData!=''){
								$_SESSION['status'] = "Question bank uploaded successfully";
								$_SESSION['status_code'] = "success";
								echo "<script>window.location.href='question-bank-list.php';</script>";
							}else{
								$_SESSION['status_error'] = "Something went wrong!!";
								$_SESSION['status_error_code'] = "warning";
							}
							
							
                        }
						
						
                    }
					
            } */

        ?>
        <?php
        if (isset($_POST['upload'])) {

            // File details
            $file_name = $_FILES["file_upload"]["name"];
            $size = $_FILES["file_upload"]["size"];
            $tmp_name = $_FILES["file_upload"]["tmp_name"];
            $uniqueid = rand(10000000000, 10);

            // Include PHPExcel
            set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
            include 'PHPExcel/Classes/PHPExcel/IOFactory.php';

            // Load the file
            $file = $tmp_name;
            $inputFileType = PHPExcel_IOFactory::identify($file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($file);

            $objWorksheet = $objPHPExcel->getActiveSheet();

            // Start transaction
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

            $error = false;

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $sheetSno = $objPHPExcel->getIndex($worksheet);
                $highestRow = $worksheet->getHighestDataRow();
                $highestColumn = $worksheet->getHighestDataColumn();
                $headings = $worksheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);

                // Question Bank Sheet
                if ($sheetSno == 0) {
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $rowData[0] = array_combine($headings[0], $rowData[0]);

                        $question_name = trim(safe_var($conn, $rowData[0]['question_bank_name']));
                        $question_bank_name = htmlentities($question_name);
                        $field_name = trim(safe_var($conn, $rowData[0]['field_name']));
                        $question_type = trim(safe_var($conn, $rowData[0]['question_type']));
                        $category_id = safe_var($conn, $rowData[0]['category_id']); 
						if (is_array($category_id)) {
							$categoryid = implode(",", $category_id); 
							//echo $categoryid; 
						} else {
							$categoryid=$category_id;
							//echo $category_id;  // If it's not an array, just echo the scalar value
						}
                        $theme_id = safe_var($conn, $rowData[0]['theme_id']);
                        $data_source = trim(safe_var($conn, $rowData[0]['data_source']));
                        $source_link = $rowData[0]['source_link'];

                        // Validation
                        if (!empty($question_bank_name) && !empty($field_name) && !empty($category_id) && !empty($data_source)) {
                            $getCategory = mysqli_query($conn, "SELECT category_id FROM categories WHERE category_id='$category_id'");
                            if (mysqli_num_rows($getCategory) > 0) {
                                $getquestion = "INSERT INTO question_bank SET $status_type unique_id='$uniqueid', question_bank_name='$question_bank_name', field_name='$field_name', question_type='$question_type', category_id='$categoryid', theme_id='$theme_id', common_use='0', data_source='$data_source', source_link='$source_link', user_id='$user_id'";
                                if (!mysqli_query($conn, $getquestion)) {
                                    $error = true;
                                    break 2; 
                                }
                                $lastQid = mysqli_insert_id($conn);
                            } else {
                                $error = true;
                                break 2; 
                            }
                        } else {
                            $error = true;
                            break 2; 
                        }
                    }
                }

                // Options Sheet
                if ($sheetSno == 1 && !$error) {
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $rowData[0] = array_combine($headings[0], $rowData[0]);

                        $field_name = trim(safe_var($conn, $rowData[0]['field_name']));
                        $option_value = safe_var($conn, $rowData[0]['option_value']);
                        $option_name = safe_var($conn, $rowData[0]['question_option_name']);
                        $question_option_name = htmlentities($option_name);

                        if (!empty($option_value) && !empty($question_option_name) && !empty($field_name)) {
                            $getunique = mysqli_query($conn, "SELECT unique_id FROM question_bank WHERE question_bank_id='$lastQid'");
                            $unique_data = mysqli_fetch_array($getunique);
                            $unique_ids = $unique_data['unique_id'];

                            $getQuestiondb = mysqli_query($conn, "SELECT question_bank_id FROM question_bank WHERE field_name='$field_name' AND unique_id='$unique_ids'");
                            while ($question_data = mysqli_fetch_array($getQuestiondb)) {
                                $question_bank_id = $question_data['question_bank_id'];
                                $option_sql = "INSERT INTO question_bank_option SET question_bank_id='$question_bank_id', question_option_name='$question_option_name', option_value='$option_value'";
                                if (!mysqli_query($conn, $option_sql)) {
                                    $error = true;
                                    break 2; 
                                }
                            }
                        } else {
                            $error = true;
                            break 2;
                        }
                    }
                }
            }

            // Check for errors and commit or rollback transaction
            if ($error) {
                mysqli_rollback($conn);
                $_SESSION['status_error'] = "Something went wrong!!";
                $_SESSION['status_error_code'] = "warning";
                echo "<script>window.location.href='upload_question_bank.php';</script>";
            } else {
                mysqli_commit($conn);
                $_SESSION['status'] = "Question bank uploaded successfully";
                $_SESSION['status_code'] = "success";
                echo "<script>window.location.href='my_question.php';</script>";
            }
        }
        ?>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12" style="min-height:420px;">
                                <header class="panel-heading">Upload question bank</header>
                                <section class="panel mb-0">
                                    <div class="panel-body">

                                        <div class="form">
                                            <form class="form-validate form-horizontal row" method="post" enctype="multipart/form-data">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <h4><strong>Instruction for uploading question bank</strong></h4>
                                                        <p><strong>Note: </strong>Field name for should be unique in every question bank for Question.<br>So Please verify data before uploading it. And please avoid putting duplicate field value records in the file.</p>
                                                        <p><strong>Step-1: </strong>Match the category name in the master data. Click on the button and check the category list</p>
                                                        <p><strong>Step-2: </strong>Match the Sub Theme in the master data. Click on the button and check the Sub Theme list</p>
                                                        <p><strong>Step-3: </strong>Download question bank template file.</p>
                                                        <p><strong>Step-4: </strong>Put your data according to column name.</p>
                                                        <p><strong>Step-5: </strong>Save the file in .xlsx format.</p>
                                                        <p><strong>Step-6: </strong>Upload the saved .xlsx file.</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <a href="question_bank_sample.xlsx" class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download Template</a>
                                                        <a href="categories.csv" class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> Category</a>
                                                        <a href="sub_theme.csv" class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> Sub Theme</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mt-3">
                                                    <label for="cname" class="control-label">Select File: </label>
                                                    <div class="form-group" style="border-bottom: none !important;">

                                                        <input class="form-control tooltips" name="file_upload" accept=".xlsx,.xls," data-bs-placement="top" data-bs-toggle="tooltip" required data-original-title="Upload Question bank" type="file" />
                                                        <span style="color:red;">( Support only .xlsx file )<span>

                                                    </div>
                                                    <div class="col-lg-2 col-lg-10">
                                                        <button class="btn btn-secondary" type="submit" name="upload" id="upload" value="Upload">Upload</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </section>
                            </div>
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