<?php include('includes/config.php'); ?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>
<?php //include('includes/functions.php'); ?>
<?php 
   $survey_id=$_GET['survey_id'];
 ?>
<link rel="stylesheet" href="css/form-builder.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css"/>
<style>
.qField>input[type="checkbox"] {
    margin:0px;
}
	.question-area-container .label {
		display: flex;
	}
	
.question-area-container .label .errors {
    position: relative;
}
.question-area-container .label .errors > i {
    background: #ee5858;
    padding: 5px;
    width: 25px;
    height: 25px;
    margin-left: 10px;
    margin-right: 12px;
    border-radius: 50%;
    border: 2px solid #b71d1d;
    color: #fff;
    font-size: 11px;
    line-height: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    cursor: pointer;
}
.question-area-container .label .errors ul {
    position: absolute;
    left: 100%;
    top: 0;
    background: #ffd9d9;
    border-radius: 3px;
    padding: 10px;
    min-width: 300px;
    text-align: left;
    font-size: 12px;
    color: #c70909;
    box-shadow: 0px 0px 4px rgb(114 0 0 / 31%);
	opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transform: scaleY(0);
    transform-origin: 0 0 0;
	-webkit-transition: all .3s ease-in-out;
    -moz-transition: all .3s ease-in-out;
    -ms-transition: all .3s ease-in-out;
    -o-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
}
.question-area-container .label .errors i:hover + ul{
    opacity: 1;
    visibility: visible;
    transform: translateY(0px);
    transform: scaleY(1);
}
.question-area-container .label .errors ul:before {
    content:"";
    border-style: solid;
    border-width: 10px 15px 10px 0;
    border-color: transparent #ffd9d9 transparent transparent;
    position: absolute;
    left: -11px;
    top: 2px;
}
.question-area-container .label .errors ul li {
    list-style: disc;
    margin-left: 20px;
}
</style>
<section id="main-content">
   <section class="wrapper">
   
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="icon_documents_alt"></i>Form Builder</li>
               <li><i class="icon_document_alt"></i>List Form </a></li> <!-- <a href="survey-list.php"> -->
               <li><i class="fa fa-laptop"></i> Dashboard</li>
            </ol>
         </div>
      </div>
     

      <div class="row">
         <div class="col-md-12">
            <section class="panel">
               <div class="card">
                  <?php 
                     $surveyqry=mysqli_query($conn,"SELECT survey_name,clients.name as client_name FROM `survey` left join clients on survey.client_id=clients.id where survey.id='".$survey_id."'");
                     $surveydata=mysqli_fetch_array($surveyqry);
                     $survey_name=$surveydata['survey_name'];
                     $client_name=$surveydata['client_name'];
                     
                     ?>
                  <header class="panel-heading"> Survey Name: <?php echo $survey_name;?> || Client Name: <?=$client_name?></header>
               </div>
            </section>
         </div>
	</div>
	<div class="row">
		<?php /*?><div class="col-md-3">
			<section class="panel">
				<div class="card">
					<header class="panel-heading">Questions</header>
					
				</div>
			</section>
		</div><?php */?>
		<div class="col-md-10">
			<section class="panel">
              <header class="panel-heading"> Create Form </header>
              <div class="panel-body" id="ssid">
				<img id="loading-image" src="loader.gif" style="display: none;width: 100%;" />
				<div id="questionArea" style="min-height: 400px;" >
					<div class="question-area-container">


					</div>
				</div>
				
              </div>
            </section>
		</div>
		
		<div class="col-md-2">
			<section class="panel">
				<div class="card">
					<header class="panel-heading">Types</header>
					<div class="list-group">
						<a class="list-group-item qTypes number" href="javascript:void(0);"><i class="fa fa-hashtag"></i> Number</a>
						<a class="list-group-item qTypes select-single" href="javascript:void(0);"><i class="fa fa-chevron-circle-down"></i> Select</a>
						<a class="list-group-item qTypes text" href="javascript:void(0);"><i class="fa fa-file-text"></i> Text</a>
						<a class="list-group-item qTypes note" href="javascript:void(0);"><i class="fa fa-sticky-note"></i> Note</a>
						<a class="list-group-item qTypes date" href="javascript:void(0);"><i class="fa fa-calendar"></i> Date</a>
						<a class="list-group-item qTypes b-group" href="javascript:void(0);"><i class="fa fa-object-group"></i> Group Questions</a>
						<a class="list-group-item qTypes b-regroup" href="javascript:void(0);"><i class="fa fa-object-group" ></i> Repeat Group </a>
					</div>
				</div>
			</section>
		</div>
		
		
      </div>
	</section>
</section>
<!--main content end-->

<?php include_once('includes/footer.php'); ?>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
   $(document).ready(function(){

    $(".question-area-container, .question-area-container > fieldset").sortable({
      revert: true
    });
    
   
   
   
   
     var edit_number=`<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div>
						<label class="form-check-label">
							Required 
						</label>
						<input class="form-check-input mb-3" type="checkbox" name="required">
					</div>
					<div>
						<label class="form-check-label">
							Label
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="label">
					</div>
					<div>
						<label class="form-check-label">
							Help Text
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="help-text">
					</div>
					<div>
						<label class="form-check-label">
							Placeholder
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="placeholder">
					</div>
					<div>
						<label class="form-check-label" for="">
							class
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="class">
					</div>
					<div>
						<label class="form-check-label">
							Name
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="name">
					</div>
					<div>
						<label class="form-check-label">
							Min
						</label>
						<input type="number" class="form-control mt-1 mb-3" name="minimum">
					</div>
					<div>
						<label class="form-check-label">
							Max
						</label>
						<input type="number" class="form-control mt-1 mb-3" name="maximum">
					</div>
					<div>
						<div>
							<label class="form-check-label">
								Relevant
							</label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="multiinputs-div">
									<ul>
										<li>
											<select name="relevant[]" class="form-control">
												<option>Select Question</option>
												<option value="1">Question 1</option>
												<option value="2">Question 2</option>
												<option value="3">Question 3</option>
												<option value="4">Question 4</option>
											</select>
											<select name="operator[]" class="form-control">
												<option>Select Operator</option>
												<option value="1"><</option>
												<option value="2">></option>
												<option value="3"><=</option>
												<option value="4">>=</option>
												<option value="5">==</option>
											</select>
											<select name="condition1[]" class="form-control">
												<option>Select Condition</option>
												<option value="1">End</option>
												<option value="2">OR</option>
											</select>
											<input type="text" class="form-control" name="rel-value[]" placeholder="Value txt"/>
											<div class="del-blank">
												&nbsp;
											</div>
										</li>

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Options	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>`;
	   var edit_text=`<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div>
						<label class="form-check-label">
							Required 
						</label>
						<input class="form-check-input mb-3" type="checkbox" name="required">
					</div>
					<div>
						<label class="form-check-label">
							Label
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="label">
					</div>
					<div>
						<label class="form-check-label">
							Help Text
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="help-text">
					</div>
					<div>
						<label class="form-check-label">
							Placeholder
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="placeholder">
					</div>
					<div>
						<label class="form-check-label" for="">
							class
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="class">
					</div>
					<div>
						<label class="form-check-label">

							Name
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="name">
					</div>
					<div>
						<div>
							<label class="form-check-label">
								Relevant
							</label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="multiinputs-div">
									<ul>
										<li>
											<select name="relevant[]" class="form-control">
												<option>Select Question</option>
												<option value="1">Question 1</option>
												<option value="2">Question 2</option>
												<option value="3">Question 3</option>
												<option value="4">Question 4</option>
											</select>
											<select name="operator[]" class="form-control">
												<option>Select Operator</option>
												<option value="1"><</option>
												<option value="2">></option>
												<option value="3"><=</option>
												<option value="4">>=</option>
												<option value="5">==</option>
											</select>
											<select name="condition1[]" class="form-control">
												<option>Select Condition</option>
												<option value="1">End</option>
												<option value="2">OR</option>
											</select>
											<input type="text" class="form-control" name="rel-value[]" placeholder="Value txt"/>
											<div class="del-blank">
												&nbsp;
											</div>
										</li>

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Options	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>`;
	   
	   var edit_note=`<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div>
						<label class="form-check-label">
							Required 
						</label>
						<input class="form-check-input mb-3" type="checkbox" name="required">
					</div>
					<div>
						<label class="form-check-label">
							Label
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="label">
					</div>
					<div>
						<label class="form-check-label">
							Placeholder
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="placeholder">
					</div>
					<div>
						<label class="form-check-label" for="">
							class
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="class">
					</div>
					<div>
						<label class="form-check-label">
							Name
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="name">
					</div>
					<div>
						<div>
							<label class="form-check-label">
								Relevant
							</label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="multiinputs-div">
									<ul>
										<li>
											<select name="relevant[]" class="form-control">
												<option>Select Question</option>
												<option value="1">Question 1</option>
												<option value="2">Question 2</option>
												<option value="3">Question 3</option>
												<option value="4">Question 4</option>
											</select>
											<select name="operator[]" class="form-control">
												<option>Select Operator</option>
												<option value="1"><</option>
												<option value="2">></option>
												<option value="3"><=</option>
												<option value="4">>=</option>
												<option value="5">==</option>
											</select>
											<select name="condition1[]" class="form-control">
												<option>Select Condition</option>
												<option value="1">End</option>
												<option value="2">OR</option>
											</select>
											<input type="text" class="form-control" name="rel-value[]" placeholder="Value txt"/>
											<div class="del-blank">
												&nbsp;
											</div>
										</li>

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Options	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>`;
	   
	   var edit_select_single=`<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div>
						<label class="form-check-label">
							Required 
						</label>
						<input class="form-check-input mb-3" type="checkbox" name="required">
					</div>
					<div>
						<label class="form-check-label">
							Label
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="label">
					</div>
					<div>
						<label class="form-check-label">
							Help Text
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="help-text">
					</div>
					<div>
						<label class="form-check-label">
							Placeholder
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="placeholder">
					</div>
					<div>
						<label class="form-check-label" for="">
							class
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="class">
					</div>
					<div>
						<label class="form-check-label">
							Name
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="name">
					</div>
					<div>
						<input class="form-check-input mb-3" type="checkbox" name="multiple-selects">
						<label class="form-check-label">
							Allow Multiple Selection
						</label>
					</div>
					<div>
						<div class="row">
							<div class="col-md-2">
								<label class="form-check-label">
									Options
								</label>
							</div>
							<div class="col-md-10">
								<div class="multiinputs-div">
									<ul>
										<li>
											<input type="radio" class="option-selected" checked="checked" class="form-checkbox">
											<input type="text" class="form-control" name="option-name[]" placeholder="Option Name">
											<input type="text" class="form-control" name="option-value[]" placeholder="Option Value">
											
										</li>

									</ul>
								</div>
							</div>
						</div>
						<div class="add-field">
							Add Options	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div>
					</div>

					<div>
						<div>
							<label class="form-check-label">
								Relevant
							</label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="multiinputs-div">
									<ul>
										<li>
											<select name="relevant[]" class="form-control">
												<option>Select Question</option>
												<option value="1">Question 1</option>
												<option value="2">Question 2</option>
												<option value="3">Question 3</option>
												<option value="4">Question 4</option>
											</select>
											<select name="operator[]" class="form-control">
												<option>Select Operator</option>
												<option value="1"><</option>
												<option value="2">></option>
												<option value="3"><=</option>
												<option value="4">>=</option>
												<option value="5">==</option>
											</select>
											<select name="condition1[]" class="form-control">
												<option>Select Condition</option>
												<option value="1">End</option>
												<option value="2">OR</option>
											</select>
											<input type="text" class="form-control" name="rel-value[]" placeholder="Value txt"/>
											<div class="del-blank">
												&nbsp;
											</div>
										</li>

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Options	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div>
					</div>


				</div>
			</div>`;
	   
	   var edit_date=`<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div>
						<label class="form-check-label">
							Required 
						</label>
						<input class="form-check-input mb-3" type="checkbox" name="required">
					</div>
					<div>
						<label class="form-check-label">
							Label
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="label">
					</div>
					<div>
						<label class="form-check-label">
							Help Text
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="help-text">
					</div>
					<div>
						<label class="form-check-label">
							Placeholder
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="placeholder">
					</div>
					<div>
						<label class="form-check-label" for="">
							class
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="class">
					</div>
					<div>
						<label class="form-check-label">
							Name
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="name">
					</div>
					<div>
						<div>
							<label class="form-check-label">
								Relevant
							</label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="multiinputs-div">
									<ul>
										<li>
											<select name="relevant[]" class="form-control">
												<option>Select Question</option>
												<option value="1">Question 1</option>
												<option value="2">Question 2</option>
												<option value="3">Question 3</option>
												<option value="4">Question 4</option>
											</select>
											<select name="operator[]" class="form-control">
												<option>Select Operator</option>
												<option value="1"><</option>
												<option value="2">></option>
												<option value="3"><=</option>
												<option value="4">>=</option>
												<option value="5">==</option>
											</select>
											<select name="condition1[]" class="form-control">
												<option>Select Condition</option>
												<option value="1">End</option>
												<option value="2">OR</option>
											</select>
											<input type="text" class="form-control" name="rel-value[]" placeholder="Value txt"/>
											<div class="del-blank">
												&nbsp;
											</div>
										</li>

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Options	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>`;
	   
	   var edit_group=`<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div>
						<label class="form-check-label">
							Group Name
						</label>
						<input type="text" class="form-control mt-1 mb-3" name="group-name">
					</div>
					<div>
					<div>
							<label class="form-check-label">
								Field List
								<input type="checkbox" name="field-list[]">
							</label>
						</div>
						<div>
							<label class="form-check-label">
								Relevant
							</label>
						
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="multiinputs-div">
									<ul>
										<li>
											<select name="relevant[]" class="form-control">
												<option>Select Question</option>
												<option value="1">Question 1</option>
												<option value="2">Question 2</option>
												<option value="3">Question 3</option>
												<option value="4">Question 4</option>
											</select>
											<select name="operator[]" class="form-control">
												<option>Select Operator</option>
												<option value="1"><</option>
												<option value="2">></option>
												<option value="3"><=</option>
												<option value="4">>=</option>
												<option value="5">==</option>
											</select>
											<select name="condition1[]" class="form-control">
												<option>Select Condition</option>
												<option value="1">End</option>
												<option value="2">OR</option>
											</select>
											<input type="text" class="form-control" name="rel-value[]" placeholder="Value txt"/>
											<div class="del-blank">
												&nbsp;
											</div>
										</li>

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Options	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>`;
                                        
                                       

	var x = 0; 
	var x1 = 0; //Initial field counter is 1
	var x2 = 0; 
	var x3 = 0;
	var x4 = 0;
	var x5 = 0;
	var x6 = 0;
	var x7 = 0;
	   
	var w = 0; 
	var w1 = 0; //Initial field counter is 1
	var w2 = 0; 
	var w3 = 0;
	var w4 = 0;
	var w5 = 0;
	var w6 = 0;
	var w7 = 0;
	var w8 = 0;
	var w9 = 0;
    
      
	$(document).on("click", ".number", function () {
		var parent_div = $(this).parent().parent().parent().get(0).tagName;
		//alert(parent_div);
		if(parent_div == 'FIELDSET'){
			w++
			var numbersec = `<div class="number-seaction">
				<div class="button">
					<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
					<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="label"><span class="label-text">Number-`+ w + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<input class="form-control" name="number-`+w+`" type="number" id="number-`+w+`" placeholder="Enter a Number">
				</div>
			</div>`;
			$(this).parent().parent().parent().find('> .add-fields').before(numbersec);
		}else{
			x++; 
			var numbersec = `<div class="number-seaction">
				<div class="button">
					<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
					<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="label"><span class="label-text">Number-`+ x + `</span><span class="text-danger required_star">*</span>
					<div class="errors">
						<i class="fa fa-info"></i>
						<ul>
							<li>
								It is required field.
							</li>
							<li>
								Enter only numeric value
							</li>
						</ul>
					</div>
				</div> 
				<div class="input-seaction">
					<input class="form-control" name="number-`+x+`" type="number" id="number-`+x+`" placeholder="Enter a Number">
				</div>
			</div>`;
			$(".question-area-container").append(numbersec);
		}
	});

    /*$(".number").click(function(){
		var parent_div = $(this);//.parent().parent().parent();
		console.log(parent_div);
    });*/
	   
	$(document).on("click", ".edit-form", function () {
		if($(this).parent().parent().hasClass('number-seaction')){
			if(!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0){
				//alert('class not found');
				$(this).parent().parent().find('.input-seaction').append(edit_number);
			}else{
				$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
			}
		}else if($(this).parent().parent().hasClass('select-seaction')){
			if(!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0){
				//alert('class not found');
				$(this).parent().parent().find('.input-seaction').append(edit_select_single);
			}else{
				$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
			}
		}else if($(this).parent().parent().hasClass('text-seaction')){
			if(!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0){
				//alert('class not found');
				$(this).parent().parent().find('.input-seaction').append(edit_text);
			}else{
				$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
			}
		}else if($(this).parent().parent().hasClass('note-seaction')){
			if(!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0){
				//alert('class not found');
				$(this).parent().parent().find('.input-seaction').append(edit_note);
			}else{
				$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
			}
		}
		else if($(this).parent().parent().hasClass('date-seaction')){
			if(!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0){
				//alert('class not found');
				$(this).parent().parent().find('.input-seaction').append(edit_date);
			}else{
				$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
			}
		}
		
	});
	   
	   
	/*$(".select-single").click(function(){
		
    });  */
	   
	   
	$(document).on("click", ".select-single", function () {
		var parent_div = $(this).parent().parent().parent().get(0).tagName;
		//alert(parent_div);
		if(parent_div == 'FIELDSET'){
			w1++ 
			var select_single = `<div class="select-seaction">
			<div class="button">
				<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
				<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
			</div>
			<div class="label"><span class="label-text">Select-`+ w1 + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<select class="form-control" name="select-`+ w1 + `" id="select-`+ w1 + `">	
						<option class="select-placeholder">Select Option</option>

					</select>
				</div>
			</div>`;
			$(this).parent().parent().parent().find('> .add-fields').before(select_single);
		}else{
			x1++; //Increment field counter
			var select_single = `<div class="select-seaction">
			<div class="button">
				<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
				<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
			</div>
			<div class="label"><span class="label-text">Select-`+ x1 + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<select class="form-control" name="select-`+ x1 + `" id="select-`+ x1 + `">	
						<option class="select-placeholder">Select Option</option>

					</select>
				</div>
			</div>`;
			$(".question-area-container").append(select_single)
		}
	});
	   
	   
	$(document).on("click", ".text", function () {
		var parent_div = $(this).parent().parent().parent().get(0).tagName;
		//alert(parent_div);
		if(parent_div == 'FIELDSET'){
			w3++; //Increment field counter
			var textsec = `<div class="text-seaction">
				<div class="button">
					<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
					<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="label"><span class="label-text">Text-`+ w3 + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<input class="form-control" name="text-`+w3+`" type="text" id="text-`+w3+`" placeholder="Enter a text value">
				</div>
			</div>`;
			$(this).parent().parent().parent().find('> .add-fields').before(textsec);
		}else{
			x3++; //Increment field counter
			var textsec = `<div class="text-seaction">
				<div class="button">
					<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
					<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="label"><span class="label-text">Text-`+ x3 + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<input class="form-control" name="text-`+x3+`" type="text" id="text-`+x3+`" placeholder="Enter a text value">
				</div>
			</div>`;
			$(".question-area-container").append(textsec);
		}
	});
	   
	/*$(".text").click(function(){
		
    });
	   */
	/*$(".note").click(function(){
		
    });*/
	   
	$(document).on("click", ".note", function () {
		var parent_div = $(this).parent().parent().parent().get(0).tagName;
		//alert(parent_div);
		if(parent_div == 'FIELDSET'){
			w5++; //Increment field counter
			var notesec = `<div class="note-seaction">
				<div class="button">
					<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
					<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="label"><span class="label-text">Note-`+ w5 + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<textarea class="form-control" name="note-`+w5+`" id="note-`+w5+`" placeholder="Enter a note paragraph"></textarea>
				</div>
			</div>`;
			$(this).parent().parent().parent().find('> .add-fields').before(notesec);
		}else{
			x5++; //Increment field counter
			var notesec = `<div class="note-seaction">
				<div class="button">
					<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
					<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="label"><span class="label-text">Note-`+ x5 + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<textarea class="form-control" name="note-`+x5+`" id="note-`+x5+`" placeholder="Enter a note paragraph"></textarea>
				</div>
			</div>`;
			$(".question-area-container").append(notesec);
		}
	});   
	   
	   
	   
	/*$(".date").click(function(){
		 x6++; //Increment field counter
		var datesec = `<div class="date-seaction">
			<div class="button">
				<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
				<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
			</div>
			<div class="label"><span class="label-text">Date-`+ x6 + `</span><span class="text-danger required_star">*</span></div> 
			<div class="input-seaction">
				<input class="form-control" name="date-`+x6+`" type="date" id="date-`+x6+`" placeholder="Select a Date">
			</div>
		</div>`;
        $(".question-area-container").append(datesec);
    }); 
	*/
	   
	$(document).on("click", ".date", function () {
		var parent_div = $(this).parent().parent().parent().get(0).tagName;
		//alert(parent_div);
		if(parent_div == 'FIELDSET'){
			w6++; //Increment field counter
			var datesec = `<div class="date-seaction">
				<div class="button">
					<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
					<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="label"><span class="label-text">Date-`+ w6 + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<input class="form-control" name="date-`+w6+`" type="date" id="date-`+w6+`" placeholder="Select a Date">
				</div>
			</div>`;
			$(this).parent().parent().parent().find('> .add-fields').before(datesec);
		}else{
			x6++; //Increment field counter
			var datesec = `<div class="date-seaction">
				<div class="button">
					<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
					<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="label"><span class="label-text">Date-`+ x6 + `</span><span class="text-danger required_star">*</span></div> 
				<div class="input-seaction">
					<input class="form-control" name="date-`+x6+`" type="date" id="date-`+x6+`" placeholder="Select a Date">
				</div>
			</div>`;
			$(".question-area-container").append(datesec);
		}
	});   
	   
	$(document).on("click", ".close-form", function () {
		if(confirm('Are you sure?')==true){
			$(this).parent().parent().remove();
		}
	});
	$(document).on("click", ".close-group", function () {
		if(confirm('Are you sure?')==true){
			$(this).parent().parent().parent().remove();
		}
	});
	
	/*$(".b-group").click(function(){
		
    });*/ 
	   
	   
	$(document).on("click", ".b-group", function () {
		var parent_div = $(this).parent().parent().parent().get(0).tagName;
		//alert(parent_div);
		if(parent_div == 'FIELDSET'){
			w7++; //Increment field counter
			var datagroup = `<fieldset class="dragto">
				  <div class="groups-header">
					<h4 class="group-name">Group - `+ w7 +`</h4>
					<div class="button">
						<div class="close-group"><i class="fa-solid fa-xmark"></i></div>
						<div class="edit-group"><i class="fa-solid fa-pen-to-square"></i></div>
					</div>
				  </div>




				  <div class="add-fields">
					 <h5>Add Fields: </h5>

					  <div>
						<a href="javascript:void(0);" class="number"><i class="fa fa-hashtag"></i> Number </a>
						<a href="javascript:void(0);" class="select-single"><i class="fa fa-chevron-circle-down"></i> Select </a>
						<a href="javascript:void(0);" class="text"><i class="fa fa-file-text"></i> Text </a>
						<a href="javascript:void(0);" class="note"><i class="fa fa-sticky-note"></i> Note </a>
						<a href="javascript:void(0);" class="date"><i class="fa fa-calendar"></i> Date </a>
						<a href="javascript:void(0);" class="b-group"><i class="fa fa-object-group"></i> Group Questions </a>
						<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-object-group" ></i> Repeat Group </a>
					  </div>

				  </div>
			  </fieldset>`;
			$(this).parent().parent().parent().find('> .add-fields').before(datagroup);
			
			$('.dragto').sortable({
				  revert: true
			 });
			$(".dragto > div:last-child, .dragto > div:first-child").draggable({ opacity: 0.7, helper: "clone" });
		}else{
			x7++; //Increment field counter

			var datagroup = `<fieldset class="ui-sortable">
				  <div class="groups-header">
					<h4 class="group-name">Group - `+ x7 +`</h4>
					<div class="button">
						<div class="close-group"><i class="fa-solid fa-xmark"></i></div>
						<div class="edit-group"><i class="fa-solid fa-pen-to-square"></i></div>
					</div>
				  </div>




				  <div class="add-fields">
					 <h5>Add Fields: </h5>

					  <div>
						<a href="javascript:void(0);" class="number"><i class="fa fa-hashtag"></i> Number </a>
						<a href="javascript:void(0);" class="select-single"><i class="fa fa-chevron-circle-down"></i> Select </a>
						<a href="javascript:void(0);" class="text"><i class="fa fa-file-text"></i> Text </a>
						<a href="javascript:void(0);" class="note"><i class="fa fa-sticky-note"></i> Note </a>
						<a href="javascript:void(0);" class="date"><i class="fa fa-calendar"></i> Date </a>
						<a href="javascript:void(0);" class="b-group"><i class="fa fa-object-group"></i> Group Questions </a>
						<a href="javascript:void(0);" class="b-regroup"><i class="fa fa-object-group"></i> Repeat Group </a>
					  </div>

				  </div>
			  </fieldset>`;
			$(".question-area-container").append(datagroup);
			$("fieldset").sortable({
				  revert: true
			 });
			$("fieldset > div:first-child, fieldset > div:last-child").draggable({ opacity: 0.7, helper: "clone" });
		}
	});   
	   
	$(document).on("click", ".b-regroup", function () {
		var parent_div = $(this).parent().parent().parent().get(0).tagName;
		//alert(parent_div);
		if(parent_div == 'FIELDSET'){
			w8++; //Increment field counter
			var datagroup = `<fieldset class="dragto">
				  <div class="groups-header">
					<h4 class="group-name">Repeat Group - `+ w8 +`</h4>
					<div class="button">
						<div class="close-group"><i class="fa-solid fa-xmark"></i></div>
						<div class="edit-group"><i class="fa-solid fa-pen-to-square"></i></div>
					</div>
				  </div>




				  <div class="add-fields">
					 <h5>Add Fields: </h5>

					  <div>
						<a href="javascript:void(0);" class="number"><i class="fa fa-hashtag"></i> Number </a>
						<a href="javascript:void(0);" class="select-single"><i class="fa fa-chevron-circle-down"></i> Select </a>
						<a href="javascript:void(0);" class="text"><i class="fa fa-file-text"></i> Text </a>
						<a href="javascript:void(0);" class="note"><i class="fa fa-sticky-note"></i> Note </a>
						<a href="javascript:void(0);" class="date"><i class="fa fa-calendar"></i> Date </a>
						<a href="javascript:void(0);" class="b-group"><i class="fa fa-object-group"></i> Group Questions </a>
						<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-object-group" ></i> Repeat Group </a>
					  </div>

				  </div>
			  </fieldset>`;
			$(this).parent().parent().parent().find('> .add-fields').before(datagroup);
			
			$('.dragto').sortable({
				  revert: true
			 });
			$(".dragto > div:last-child, .dragto > div:first-child").draggable({ opacity: 0.7, helper: "clone" });
		}else{
			w8++; //Increment field counter

			var datagroup = `<fieldset class="ui-sortable">
				  <div class="groups-header">
					<h4 class="group-name">Repeat Group - `+ w8 +`</h4>
					<div class="button">
						<div class="close-group"><i class="fa-solid fa-xmark"></i></div>
						<div class="edit-group"><i class="fa-solid fa-pen-to-square"></i></div>
					</div>
				  </div>




				  <div class="add-fields">
					 <h5>Add Fields: </h5>

					  <div>
						<a href="javascript:void(0);" class="number"><i class="fa fa-hashtag"></i> Number </a>
						<a href="javascript:void(0);" class="select-single"><i class="fa fa-chevron-circle-down"></i> Select </a>
						<a href="javascript:void(0);" class="text"><i class="fa fa-file-text"></i> Text </a>
						<a href="javascript:void(0);" class="note"><i class="fa fa-sticky-note"></i> Note </a>
						<a href="javascript:void(0);" class="date"><i class="fa fa-calendar"></i> Date </a>
						<a href="javascript:void(0);" class="b-group"><i class="fa fa-object-group"></i> Group Questions </a>
						<a href="javascript:void(0);" class="b-regroup"><i class="fa fa-object-group"></i> Repeat Group </a>
					  </div>

				  </div>
			  </fieldset>`;
			$(".question-area-container").append(datagroup);
			$("fieldset").sortable({
				  revert: true
			 });
			$("fieldset > div:first-child, fieldset > div:last-child").draggable({ opacity: 0.7, helper: "clone" });
		}
	});  
	   
	$(document).on("click", ".edit-group", function () {
		if($(this).parent().parent().hasClass('groups-header')){
			if(!$(this).parent().parent().find('.edit-main-container').length > 0){
				$(this).parent().parent().append(edit_group);
			}else{
				$(this).parent().parent().find('.edit-main-container').slideToggle('slow');
			}
		}		
	});
	   
	$(document).on("keyup", "input[name='placeholder']", function () {
		var new_value = $(this).val();
		$(this).parent().parent().parent().parent().find('> input').attr('placeholder', new_value);
		$(this).parent().parent().parent().parent().find('> textarea').attr('placeholder', new_value);
		if(new_value.length>0){
			$(this).parent().parent().parent().parent().find('> select .select-placeholder').html(new_value);//.attr('placeholder', new_value);//.append("<option>"+new_value+"</option>");
			
		}else{
			$(this).parent().parent().parent().parent().find('> select .select-placeholder').html('Select Option');
		}
		
	}); 
	$(document).on("click", ".add-field", function () {	
		x2++; //Increment field counter
		var default_input = 'radio';
		if($(this).parent().prev().find('input[name="multiple-selects"]').prop('checked')==true){
			default_input = 'checkbox';
		}
		var list = `<li>
						<input type="`+default_input+`" class="option-selected" checked="checked" class="form-checkbox">
						<input type="text" class="form-control" name="option-name[]" placeholder="Option Name `+ (x2+1)+`">
						<input type="text" class="form-control" name="option-value[]" placeholder="Option Value `+ (x2+1) +`">
						<div class="del-btn">
							<i class="fa fa-trash"></i>
						</div>
					</li>`;
		$(this).prev().find('.multiinputs-div ul').append(list);
		$(this).parent().parent().parent().parent().find('> select').append("<option value='' selected=''> Option " + (x2+1) + "</option>");
		
	});
	$(document).on("change", "input[name='multiple-selects']", function () {	  
		//alert('hi...');
		if($(this).prop('checked')==true){
		   $(this).parent().next().find('.multiinputs-div ul li').each(function(){
			   $(this).find('.option-selected').attr('type','checkbox');
		   })
		}else{
			$(this).parent().next().find('.multiinputs-div ul li').each(function(){
			   $(this).find('.option-selected').attr('type','radio');
		   })
		}
	});
	$(document).on("click", ".del-btn", function () {	 
		//alert('hi...')
		//$(this).parent().remove();
		var listItem = $(this).parent();
		index_num = listItem.index( "li" );
		//alert(index_num);
		$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option:eq('+ (index_num+1) +')').remove();
		$(this).parent().remove();
	});
	   
	$(document).on("click", ".add-relfield", function () {	
		x4++; //Increment field counter
		
		var list = `<li>
						<select name="relevant[]" class="form-control">
							<option>Select Question</option>
							<option value="1">Question 1</option>
							<option value="2">Question 2</option>
							<option value="3">Question 3</option>
							<option value="4">Question 4</option>
						</select>
						<select name="operator[]" class="form-control">
							<option>Select Operator</option>
							<option value="1">&lt;</option>
							<option value="2">&gt;</option>
							<option value="3">&lt;=</option>
							<option value="4">&gt;=</option>
							<option value="5">==</option>
						</select>
						<input type="text" class="form-control" name="rel-value[]" placeholder="Value txt">
						<div class="del-relbtn">
							<i class="fa fa-trash"></i>
						</div>
					</li>`;
		$(this).prev().find('.multiinputs-div ul').append(list);
		
		
	});
	$(document).on("click", ".del-relbtn", function () {	 
		$(this).parent().remove();
	});
	 
	$(document).on("keyup", "input[name='option-name[]']", function () {
		
		var entered_value = $(this).val();
		
		var listItem = $(this).parent();
		index_num = listItem.index();
		
		var total_list = $(this).parent().parent().find('li').length;
		
		var select_length = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option').length;
		//alert(total_list);
		//alert(index_num);
		//alert(select_length);
		if((select_length == 1) ){
			var optioni = "<option value='' selected=''>" + entered_value + "</option>";
			$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select').append(optioni);
			
		}else if((select_length == total_list)){
			if(index_num ==0){
				var optioni = "<option value='' selected=''>" + entered_value + "</option>";
				$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option:eq(0)').after(optioni);
			}else{
				var optioni = "<option value=''  selected=''> Option 1 </option>";
				$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option:eq(0)').after(optioni);
			}
						
		}else{
			$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option').eq((index_num+1)).html(entered_value);
		}
    	
	});
    $(document).on("keyup", "input[name='option-value[]']", function () {
		
		var entered_value = $(this).val();
		
		var listItem = $(this).parent();
		index_num = listItem.index();
		
		var total_list = $(this).parent().parent().find('li').length;
		
		var select_length = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option').length;
		//alert(total_list);
		
		//alert(index_num);
		//alert(select_length);
		//alert(total_list);
		//console.log();
		//var prev_value = $(this).prev().val();
		
		//alert(select_length);
		//alert(total_list);
		
		
		if((select_length == 1) ){
			if(total_list == 1 &&  (!$(this).prev().val())){
				var optioni = "<option class='select-placeholder' selected='' value='"+entered_value+"'>  Option 1 </option>";
				$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select').append(optioni);
				//alert(prev_value);
			}
		}else{
			$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option').eq(index_num+1).attr('value', entered_value);
			//alert('hi...');
		}
    	
	});
	$(document).on("change", "input[name='required']", function () {
		var new_value = $(this).parent().parent().parent().parent().prev().html();
		//alert();
		if($(this).prop('checked')==true){
			$(this).parent().parent().parent().parent().prev().find('.required_star').show();
		}else{
			$(this).parent().parent().parent().parent().prev().find('.required_star').hide();
		}
	}); 
	$(document).on("keyup", "input[name='label']", function () {
		var new_value = $(this).val();
		$(this).parent().parent().parent().parent().prev().find('.label-text').html(new_value);
	}); 
	$(document).on("keyup", "input[name='minimum']", function () {
		var new_value = $(this).val();
		$(this).parent().parent().parent().parent().find('> input').attr('min', new_value);
	}); 
	   $(document).on("keyup", "input[name='maximum']", function () {
		var new_value = $(this).val();
		$(this).parent().parent().parent().parent().find('> input').attr('max', new_value);
	}); 
	$(document).on("keyup", "input[name='name']", function () {
		var new_value = $(this).val();
		$(this).parent().parent().parent().parent().find('> input').attr('name', new_value);
		$(this).parent().parent().parent().parent().find('> select').attr('name', new_value);
		$(this).parent().parent().parent().parent().find('> textarea').attr('name', new_value);
	}); 
	   
	$(document).on("keyup", "input[name='group-name']", function () {
		var new_value = $(this).val();
		$(this).parent().parent().parent().parent().find('.group-name').html(new_value);
	}); 
	
	
	   
//	$(document).on("mouseover", ".errors > i", function () {
//		//alert('hi...');
//		var error_count = $(this).next().find("li").length;
//		//alert(error_count);
//		if(error_count > 0){
//			$(this).next().addClass('active')	
//		}
//	});
//	$(document).on("mouseout", ".errors > ul", function () {
//		//alert('hi...');
//		var error_count = $(this).next().find("li").length;
//		//alert(error_count);
//		if(error_count > 0){
//			$(this).next().removeClass('active')	
//		}
//	});
	   
	   
    
   
});
</script>