<?php include_once('includes/config.php'); ?>
<?php define("title","Question Edit | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<script src="package/jquery.min.js"></script>
<script src="package/dist/form-builder.min.js"></script>
<script src="package/dist/form-render.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<!--main content start-->
<style type="text/css">
	#build-wrap{
	padding: 3px;
	background: #1a2732!important;
	}
	.frmb{
	color:white;
	font-size: 25px;
	/*background-color:#1a2732!important;
	padding: 10px!important;*/
	}
	.form-field{
	color:black;
	font-size: 15px;
	}
	.form-wrap.form-builder .frmb-control li {
	cursor: move;
	list-style: none;
	margin: 0 0 -1px 0;
	padding: 10px;
	text-align: left;
	background: #c9a37d;
	-webkit-user-select: none;
	user-select: none;
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;
	box-shadow: inset 0 0 0 1px #c5c5c5;
	color: black;
	font-weight:bold;
	}
	.btn-group{
	// display:none!important;
	}
	.form-wrap.form-builder .frmb-control li {
    cursor: move;
    list-style: none;
    margin: 0 0 -1px 0;
    padding: 10px;
    text-align: left;
    background: white;
    -webkit-user-select: none;
    user-select: none;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    box-shadow: inset 0 0 0 1px #c5c5c5;
    color: black;
    font-weight: bold;
}
</style>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<!-- <h3 class="page-header"><i class="fa fa fa-bars"></i> Pages</h3> -->
				<ol class="breadcrumb">
					<li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
					<li><i class="icon_documents_alt"></i>Question</li>
					<li><i class="fa fa-bars"></i>Question Edit</li>
				</ol>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<section class="panel">
					<header class="panel-heading">
						Edit Question 
					</header>
					<!-- <input type="text" id="setData" name=""> -->
					<!-- <div id="build-wrap"></div> -->
					<div class="row" style="margin-top: 10px;">
						<div class="col-lg-7">
							<select name="formTemplates" id="formTemplates" class="form-control">
								<option value=''>Select Survey</option>
								<?php $clnt='';
									if($_SESSION['role_id']=="3"){
										$clnt = " and client_id='".$_SESSION['client_id']."' ";
									}
									$getSurvey = mysqli_query($conn,"SELECT survey.id, survey.survey_name, survey.created_at, survey.status, survey.user_id
									FROM survey where status='0' $clnt ");
									while($survey = mysqli_fetch_array($getSurvey) ){ ?>
								<option value='<?=$survey['id']?>'><?=$survey['survey_name']?></option>
								<?php
									}
									
									
									?>
							</select>
						</div>
						<div class="col-lg-5">
							<button id="updateSeq" type="button" class="btn btn-secondary">Update Sequence</button>
						</div>
						<br><br>
						<div class="col-sm-12">
							<div id="build-wrap"></div>
						</div>
					</div>
				</section>
			</div>
		</div>
		<!-- page end-->
	</section>
</section>
<!--main content end-->
<?php //include_once('includes/footer.php'); ?>
<div class="text-end">
	<div class="credits">
		Technology Partner: <a href="javascript:"> Indev Consultancy Pvt. Ltd.</a>
	</div>
</div>
</section>
<!-- container section end -->
<!-- javascripts -->
<!-- <script src="js/jquery.js"></script> -->
<script src="js/bootstrap.min.js"></script>
<!-- nice scroll -->
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
<!--custome script for all page-->
<script src="js/scripts1.js"></script>
</body>
</html>
<script type="text/javascript">
	var options = {
	    disabledAttrs: ["value","access", "placeholder","subtype","description","maxlength","className","min","max","step"],
	    disableFields: ['autocomplete','hidden','checkbox-group','radio-group','button','textarea','file'],
	    //sortableControls: true,
	    
	    //ADD New
	    inputSets:[
			
			/*
	        {
	          label: 'Camera',
	          fields: [
	              {
	                type: 'text',
	                subtype: "camera",
	                label: 'Camera',
	                className: 'open-camera'
	              }
	            ]
	        },
	          {
	            label: 'Video',
	            fields: [
	            {
	              type: 'text',
	              label: 'Video',
	              className: 'form-control'
	          }
	          ]
	          },
	          {
	            label: 'Audio',
	            fields: [
	            {
	              type: 'text',
	              label: 'Audio',
	              className: 'form-control'
	          }
	          ]
	          }
			  */
	      ],
	    
	    
	      typeUserAttrs: {
	      
	      // ADD CUSTOM ATTRIBUTES IN TEXT BOX
	          text: {
	        
	            label:{
	              label:'Label',
	              value:'Enter Field Label'
	            },
	            name:{
	              label:'Filed Name',
	              value:'field_name'
	            },
	            multiline:{
	              type:'checkbox',
	              label:'Mult Line'
	            },
	
	            advance:{
	              type:'checkbox',
	              label:'Advance'
	            },
	        
	            // Advanced Options
	            hint:{
	              label:'Hints',
	              value:''
	            },
	            limit:{
	              label:'Limit',
	              value:''
	            },
	            constraint: {
	              label: 'Constraint',
	              value: ''
	            },
	
	            constraint_msg: {
	              label: 'Constraint Message',
	              value: '',
	            },
	
	            appearance: {
	              label: 'Appearance',
	              value: '',
	            },
	            relevant: {
	              label: 'Relevant',
	              value: '',
	            },
	
	            repeat_count: {
	                label: 'Repeat Count',
	                options: {
	                'No':'No Repeat',
	                'begin_repeat':'Begin Repeat',
	                'end_repeat':'End Repeat'
	              }
	            },
	            read_only: {
					type: 'checkbox',
					label: 'Read Only',
	              
	            }
	
	            /*
	              choice_filter: {
	            label: 'Choice Filter',
	            value: '',
	          },
	          
	          parameters: {
	            label: 'Parameters',
	            value: '',
	          },
	          
	          calculation: {
	            label: 'Calculation',
	            value: '',
	          },
	        */
	        
	          },
	      
	      //ADD CUSTOM ATTRIBUTES IN SELECT BOX
	      select: {
	
	            name:{
	              label:'Filed Name',
	              value:'field_name'
	            },
	          
	
	            advance:{
	              type:'checkbox',
	              label:'Advance'
	            },
	        
	            // Advanced Options
	            hint:{
	              label:'Hints',
	              value:''
	            },
	            limit:{
	              label:'Limit',
	              value:''
	            },
	            constraint: {
	              label: 'Constraint',
	              value: ''
	            },
	
	            constraint_msg: {
	              label: 'Constraint Message',
	              value: '',
	            },
	
	            appearance: {
	              label: 'Appearance',
	              value: '',
	            },
	
	            relevant: {
	              label: 'Relevant',
	              value: '',
	            },
	
	            repeat_count: {
	                label: 'Repeat Count',
	                options: {
	                'No':'No Repeat',
	                'begin_repeat':'Begin Repeat',
	                'end_repeat':'End Repeat'
	              }
	            },
	            read_only: {
	              label: 'Read Only',
	              type: 'checkbox',
	            }
	          },
	
	          date:{
	            label:{
	              label:'Label',
	              value:'Enter Field Label'
	            },
	            name:{
	              label:'Filed Name',
	              value:'field_name'
	            },
	            
	            advance:{
	              type:'checkbox',
	              label:'Advance'
	            },
	        
	            // Advanced Options
	            hint:{
	              label:'Hints',
	              value:''
	            },
	            limit:{
	              label:'Limit',
	              value:''
	            },
	            constraint: {
	              label: 'Constraint',
	              value: ''
	            },
	
	            constraint_msg: {
	              label: 'Constraint Message',
	              value: '',
	            },
	
	            relevant: {
	              label: 'Relevant',
	              value: '',
	            },
	
	            appearance: {
	              label: 'Appearance',
	              value: '',
	            },
	
	            repeat_count: {
	                label: 'Repeat Count',
	                options: {
	                'No':'No Repeat',
	                'begin_repeat':'Begin Repeat',
	                'end_repeat':'End Repeat'
	              }
	            },
	            read_only: {
	              label: 'Read Only',
	              type: 'checkbox',
	            }
	          },
	
	          //ADD CUSTOM ATTRIBUTES IN NUMBER
	          number:{
	            label:{
	              label:'Label',
	              value:'Enter Field Label'
	            },
	            name:{
	              label:'Filed Name',
	              value:'field_name'
	            },
	            
	            advance:{
	              type:'checkbox',
	              label:'Advance'
	            },
	        
	            // Advanced Options
	            hint:{
	              label:'Hints',
	              value:''
	            },
	            limit:{
	              label:'Limit',
	              value:''
	            },
	            constraint: {
	              label: 'Constraint',
	              value: ''
	            },
	
	            constraint_msg: {
	              label: 'Constraint Message',
	              value: '',
	            },
	
	            relevant: {
	              label: 'Relevant',
	              value: '',
	            },
	
	            appearance: {
	              label: 'Appearance',
	              value: '',
	            },
	
	            repeat_count: {
	                label: 'Repeat Count',
	                options: {
	                'No':'No Repeat',
	                'begin_repeat':'Begin Repeat',
	                'end_repeat':'End Repeat'
	              }
	            },
	            read_only: {
	              label: 'Read Only',
	              type: 'checkbox',
	            }
	          }
	      }
	  };
	
	  var fbEditor = document.getElementById('build-wrap');
	  var formBuilder = $(fbEditor).formBuilder(options);
	
	document.getElementById('formTemplates').addEventListener('change', function() {
	
	    var survey_id=$('#formTemplates').val();
	
	    $.ajax({
			url:"question_json.php",
			type:"post",
			data:{survey_id:survey_id},
			success:function(data){
			   console.log(data);
			  formBuilder.actions.setData(data);
			}
		});
	});
	
	
	document.getElementById("updateSeq").addEventListener("click", () => {

		const result = formBuilder.actions.save();
		var survey_id = $('#formTemplates').val();
		$.ajax({
			url:"question_json.php",
			type:"post",
			data:{secuence:survey_id,form_data:result},
			success:function(data){
				if(data='success'){
					toastr.success('Sequence Updated Successfully..!', 'Success Alert', {timeOut: 5000});
				}else{
					toastr.success('Somthing Went Wrong!!!', 'Success Alert', {timeOut: 5000});
				}
			}
		});
	});
	
</script>
</script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">