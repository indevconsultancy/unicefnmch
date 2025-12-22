<?php 
	if(isset($_REQUEST['readmoreid'])){
		$sss=$_REQUEST['sss'];
	//echo "ssss";
	?>
		<fieldset class="field_set" id="more">
				<!--<legend>Data Repository data:</legend>-->
				<button href="javascript:" class="btn btn-danger btn-sm remv " style="float: right; margin-left:10px;">Remove</button>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Data Name: <span style="color:red;">*</span></label>
                  <div class="col-lg-9">
                    <input class="form-control" id="data_name" required name="data_name[]" type="text" />
					
                  </div>
                </div>
				 
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Data Access: <span style="color:red; ">*</span></label>
					<div class="col-lg-10">
							<select class="form-control" required name="data_access[]">
								<option value="">Select Data Type</option>
								<option value="Public">Public</option>
								<option value="Private">Private</option>
							</select>
					</div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Data Format: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
						<div class=" row abcd">
							<div class="col-lg-2">
								<div class="form-check">
								<input type="checkbox"  value="SAS" name="data_format_name[<?php echo $sss; ?>][]"> SAS
								</div>
							</div>
							<div class="col-lg-10">
								<input type="file" name="ch_for[<?php echo $sss; ?>][]" accept=".sas" accept=".sas"  style="display:none" class="form-control xyz ">
							</div>
						</div>
						<div class=" row abcd">
							<div class="col-lg-2">
								<div class="form-check">
								<input type="checkbox"  value="JSON" name="data_format_name[<?php echo $sss; ?>][]"> JSON
								</div>
							</div>
							<div class="col-lg-10">
								<input type="file" name="ch_for[<?php echo $sss; ?>][]" accept=".json"  style="display:none" class="form-control xyz ">
							</div>
						</div>
						<div class=" row abcd">
							<div class="col-lg-2">
								<div class="form-check">
								<input type="checkbox"  value="Excel"  name="data_format_name[<?php echo $sss; ?>][]"> Excel
								</div>
							</div>
							<div class="col-lg-10">
								<input type="file" name="ch_for[<?php echo $sss; ?>][]" accept=".xlxs,.xls" style="display:none" class="form-control xyz">
							</div>
						</div>
						<div class=" row abcd">
							<div class="col-lg-2">
								<div class="form-check">
								<input type="checkbox"  value="SPSS" name="data_format_name[<?php echo $sss; ?>][]"> SPSS
								</div>
							</div>
							<div class="col-lg-10">
								<input type="file" name="ch_for[<?php echo $sss; ?>][]" style="display:none" accept=".spss" class="form-control xyz ">
							</div>
						</div>
						<div class=" row abcd">
							<div class="col-lg-2">
								<div class="form-check">
								<input type="checkbox"  value="Stata" name="data_format_name[<?php echo $sss; ?>][]"> Stata
								</div>
							</div>
							<div class="col-lg-10">
								<input type="file" name="ch_for[<?php echo $sss; ?>][]" style="display:none" accept=".stata" class="form-control xyz ">
							</div>
						</div>
						
                  </div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Upload Code Book: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
                    <input class="form-control codebookFile" required id="meta_data" name="upload_codebook[]" accept=".pdf,.xls,.xlsx,.doc,.docx,.csv" type="File"/>
					
                  </div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Email: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
                    <input class="form-control" required  name="contact_email[]"  type="email"/>
                  </div>
                </div>
				<div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Author: <span style="color:red;">*</span></label>
                  <div class="col-lg-10">
                    <input class="form-control" required id="author_name" name="author_name[]"  type="text"/>
                  </div>
                </div>
			</fieldset>
			
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
			<script>
				
			$(document).ready(function () {
			  $('.abcd input:checkbox').on('click', function(){
				$(this).closest('.abcd').find('.xyz').toggle();
			  })
			});
			$(document).ready(function() {
			  $(".codebookFile").change(function() { 
				var codebookFile = $(this);
				var filePath = codebookFile.val();
				var file_error = $("#file_error");
				var allowedExtensions = /(\.xlsx|\.xls|\.pdf|\.doc|\.docx|\.csv)$/i;
				
				if (!allowedExtensions.test(filePath)) {
					//file_error.html("Please upload a valid file (xlsx, xls, pdf, doc, docx, csv)");
				  alert("Please upload a valid file (xlsx, xls, pdf, doc, docx, csv).");
				  //return false;
				  codebookFile.val(''); // Clear the file input field
				}
				
			  });
			});
			</script>
			
		<?php
	}
?>
  