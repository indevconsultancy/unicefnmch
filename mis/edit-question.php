<?php include_once('includes/config.php'); ?>
<?php define("title","Edit Item | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<script src="package/jquery.min.js"></script>
<script src="package/dist/form-builder.min.js"></script>
<script src="package/dist/form-render.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <style type="text/css">
  #build-wrap {
  padding: 0;
  margin: 10px 0;
  background: #f2f2f2;
}
.frmb{
  width: width: 50%;;
}
.pull-left{
  float: right;
}
.pointer{
  cursor: pointer;
  background: #394a59;
  color: white;
  /* font-size: 16px; */
  font-weight: bold;
}
.pointer:hover{
  color: black;
}
.frmb{
	color:white;
	font-size: 25px;
}
.form-field{
	color:black;
	font-size: 13px;
}
.form-wrap.form-builder .frmb-control li {
    cursor: move;
    list-style: none;
    margin: 0 0 -1px 0;
    padding: 10px;
    text-align: left;
    /*background: #c9a37d;*/
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
	display:none!important;
}
.repeat{
    background-color: #8cc640;
    padding-left: 10%!important;
    color: black;
}
.hint-wrap, .limit-wrap, .constraint-wrap, .constraint_msg-wrap, .appearance-wrap, .relevant-wrap, .repeat_count-wrap, .read_only-wrap{
  display: none;
  margin-left: 50px!important;
  width: 90%!important;
}
.paudio-wrap, .gps-wrap, .timestamp-wrap, .duration-wrap, .keystrok-wrap{
	margin-left: 50px!important;
}
.paradata-wrap, .timestamp-wrap, .duration-wrap, .keystrok-wrap{
	pointer-events: none;
	opacity: 0.4;
}
.form-group.check-inline {
    max-width: 115px !important;
    display: inline-flex;
    align-items: center;
}
.form-group.check-inline label {
    min-width: 52px !important;
    margin-right: 7px;
    text-align: right !important;
    width: auto !important;
}

.form-group.check-inline .input-wrap {
    width: auto !important;
}
.statusMsg{
    background: lightgreen;
    color: black;
    border-radius: 5px;
    width: 85%;
    font-weight: bold;
.mb5{
	margin-bottom: 5px!important;
}
.form-wrap.form-builder .frmb .form-elements .input-wrap>input[type='checkbox'] {
    margin-top: 10px;
}
</style>
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <!-- <h3 class="page-header"><i class="fa fa fa-bars"></i> Pages</h3> -->
            <ol class="breadcrumb">
             <!-- <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li> -->
			  <li><i class="icon_documents_alt"></i>Form</li>
              <li><i class="fa fa-list"></i>List Form</li>
              <li><i class='fa fa-edit'></i>Edit Item</li>
			 
            </ol>
          </div>
        </div>
<!-- page start-->
        <div class="row">
          <div class="col-sm-12">
            <section class="panel">
              <header class="panel-heading">
                Edit Item <a href="javascript:" class="btn btn-secondary btn-sm" title="Add Category" data-toggle="modal" data-target="#exampleModal" data-backdrop="static" data-keyboard="false" ><i class="fa fa-plus"></i> Add Item From Question Bank </a>
              </header>
            
              <div class="row" style="margin-top: 10px;">
                <div class="col-lg-4" style="height: 555px;overflow: overlay;">
                 <!-- <select name="survey_id" id="survey_id" onchange="getQues(this.value)" class="form-control">
                    <option value=''>Select Form</option>
                    <?php
                      // $getSurvey = mysqli_query($conn,"SELECT id,survey_name FROM survey WHERE del_action='N' and survey_id='".$_REQUEST['survey_id']."'");
                      // while($survey = mysqli_fetch_array($getSurvey) ){  
                          // $selected = $survey['id'] == $_REQUEST['survey_id'] ? 'selected' : '';
						?>
							<option value='<?=$survey['id']?>' <?=$selected?>><?=$survey['survey_name']?></option>
						<?php
						//}
                    ?>
                  </select>-->
                  <table class="table table-hover">
                    <thead>
                      <th>Item of the selected form <button type="button" id="updateQues" class="btn btn-primary pull-right">Save</button> </th>
                    </thead>
                    <tbody id="questns">
                    </tbody>
                  </table>
                </div>
                <div class="col-sm-8">
                  <div id="build-wrap" style="background:#1a2732;padding:3px;"></div>
                  <br></br>
					<div class="panel-group m-bot20" id="accordion"> 
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
<div class="text-end">
  <div class="credits">
    Technology Partner: <a href="https://www.indevconsultancy.com" target="_blank"> Indev Consultancy Pvt. Ltd.</a>
  </div>
</div>
</section>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width: 75%">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title" id="exampleModalLabel" style="color:#394A59;">Add New Item</h1>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<input type="hidden" name="qb_sid" value="<?=@$_REQUEST['survey_id'];?>" />
					<div class="panel-group m-bot20" id="accordion">
					<?php
						$getCategories = mysqli_query($conn,"SELECT categories.category_id, categories.category_name, COUNT(question_bank.question_bank_id) AS totQuestions FROM categories INNER JOIN question_bank ON categories.category_id=question_bank.category_id  WHERE categories.status='0' GROUP BY question_bank.category_id ");
						while($category = mysqli_fetch_object($getCategories)){ 
							
						?>
							<div class="panel panel-default mb5">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle showQuesList" data-id="<?=$category->category_id;?>" data-toggle="collapse" data-parent="#accordion" href="#collapseCat<?=$category->category_id;?>">
										  <?=$category->category_name;?>  <span class="badge bg-important pull-right" style="margin-top: 12px;" >Total Question: <?=$category->totQuestions;?> </span>
										</a>
									</h4>
								</div>
								<div id="collapseCat<?=$category->category_id;?>" class="panel-collapse collapse">
								  <div class="panel-body">
									
                    <div id="questionBankData<?=$category->category_id;?>"></div>

									<span class="pull-left statusMsg" id="statusMsg<?=$category->category_id;?>"></span>
									<button type="button" class="btn btn-primary pull-right" onclick="SaveQuestionData(<?=$category->category_id;?>)" value="submit">Save</button>
								  </div>
								</div>
							</div>
							<script>
							$("#ckbCheckAll<?=$category->category_id;?>").click(function () {
								$(".checkBoxClass<?=$category->category_id;?>").prop('checked', $(this).prop('checked'));

							});
              $(document).ready( function () {
                $("#table_id<?=$category->category_id;?>").DataTable();
              } );

              
              </script>
						<?php	
						}
					?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>





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


function uniqueArr(arr){
	return arr.sort(function(a,b){
        return (a > b) ? 1 : -1;
    }).filter(function(el,i,a) {
        return (i==a.indexOf(el));
    });
}
var clickedCategories = [];
$(".showQuesList").on("click", function(e){
	e.preventDefault();
	var qbCatId = $(this).data("id");
	
  //alert('hello');
	if($.inArray(qbCatId, clickedCategories) !== -1){
		//console.log('already loaded...');
		//return false;
	}else{
		//console.log('New loaded...');
		var process="get-question-bank-data";
		$.ajax({
			url:"ajax_page.php",
			type:"post",
			data:{process:process,qbCatId:qbCatId},
			success:function(res){
				//console.log(res);
				var tblId = '#questionBankData'+qbCatId;
        var sssss = '#sss'+qbCatId;

				$(tblId).html(res);
        $('#example').DataTable();
        
       // $(sssss).html('hello');
       // $(tblId).DataTable().clear().destroy();

			}
		});
	}
	clickedCategories.push(qbCatId);
	clickedCategories = uniqueArr(clickedCategories);
	//console.log(clickedCategories);
})


  function getQues(survey_id){
    // alert(survey_id);
    $.ajax({
    url:"question_json.php",
    // contentType: "application/json",
    // dataType: "json",
    type:"post",
    data:{surveyid:survey_id},
    success:function(data){
      $('#questns').html(data);
      freezTable();
      // alert(data);
    }
  });
  }
  document.body.onload = getQues("<?= $_REQUEST['survey_id']; ?>");
</script>
<script>
//ADD QUESTION FROM QUESTION BANK
function SaveQuestionData(cateId){
	//alert(val);
	var selQues = '.checkBoxClass'+cateId;
	var qb_sid = <?=$_REQUEST['survey_id'];?>;
	 var bqId = new Array();
	 $(selQues+':checked').each(function() {
		bqId.push($(this).val());
    });
	//alert(qb_sid);
	if(bqId!=''){
		$.ajax({
			url:"ajax_page.php",
			type:"post",
			data:{qb_questions:bqId,cateId:cateId,qb_sid:qb_sid},
			success:function(data){
			  $('#statusMsg'+cateId).html(data);
			  getQues(qb_sid);
			}
		 });
	}else{
		$('#statusMsg'+cateId).html('Please Select Question');
	}
	$(selQues).prop('checked', false);
}
</script>
<script>
function getQuest(catId){
	if(catId!=''){
		$.ajax({
			url:"ajax_page.php",
			type:"post",
			data:{categoryId:catId},
			success:function(data){
			  $('#qbdata').html(data);
			}
		 });
	}
}
</script>
<script type="text/javascript">

var options = {
    disabledAttrs: ["value","access", "placeholder","subtype","description","maxlength","className","min","max","step"],
    disableFields: ['autocomplete','hidden','checkbox-group','radio-group','button','textarea','file','header','paragraph','select'],
    //sortableControls: true,
    controlOrder: [
        'choices',
        'date',
		'note',
		'number',
		'text'
      ],
    //ADD New
    inputSets:[
		{
			label: 'Note',
			fields: [
              {
                type: 'paragraph',
                label: 'Note'
              }
            ]
		},
		{
			label: 'Choices',
			fields: [
              {
                type: 'select',
                label: 'Choices',
				values: [
					{
					  label: 'Option-1',
					  value: 'value-1',
					  selected: false
					},
					{
					  label: 'Option-2',
					  value: 'value-2',
					  selected: false
					},
					{
					  label: 'Option-3',
					  value: 'value-3',
					  selected: false
					}
				]
              }
            ]
		},
		{
			label: 'Text',
			fields: [
              {
                type: 'text',
                label: 'Text'
              }

            ]

		},

		{

			label: 'Number',

			fields: [

              {

                type: 'number',

                label: 'Number'

              }

            ]

		},

		{

			label: 'Date',

			fields: [

              {

                type: 'date',

                label: 'Date'

              }

            ]

		}

	

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
          } */
      ],
      typeUserAttrs: {
      // ADD CUSTOM ATTRIBUTES IN TEXT BOX
          text: {
            label:{
              label:'Label',
              value:'Enter Field Label'
            },
            name:{
              label:'Field Name',
              value:'field_name'
            },
            multiline:{
              type:'checkbox',
              label:'Mult Line'
            },

			paradata:{

              type:'checkbox',

              label:'Paradata',

			  value: 'true'

            },

			//PARADATA OPTIONS

			paudio:{

				type:'checkbox',

				label:'audio'

			},

			gps:{

				type:'checkbox',

				label:'GPS',

			},

			

			timestamp:{

				type:'checkbox',

				label:'timestamp',

				value: 'true'

			},

			duration:{

				type:'checkbox',

				label:'duration',

				value: 'true'

			},

			keystrok:{

				type:'checkbox',

				label:'Keystroke',

				value: 'true'

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
              label:'Field Name',
              value:'field_name'
            },
			paradata:{
              type:'checkbox',
              label:'Paradata',
			  value: 'true'
            },
			//PARADATA OPTIONS

			paudio:{

				type:'checkbox',

				label:'audio'

			},

			gps:{

				type:'checkbox',

				label:'GPS',

			},

			

			timestamp:{

				type:'checkbox',

				label:'timestamp',

				value: 'true'

			},

			duration:{

				type:'checkbox',

				label:'duration',

				value: 'true'

			},

			keystrok:{

				type:'checkbox',

				label:'Keystroke',

				value: 'true'
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

              label:'Field Name',

              value:'field_name'

            },

            paradata:{

              type:'checkbox',

              label:'Paradata',

			  value: 'true'

            },

			//PARADATA OPTIONS

			paudio:{

				type:'checkbox',

				label:'audio'

			},

			gps:{

				type:'checkbox',

				label:'GPS',

			},

			

			timestamp:{

				type:'checkbox',

				label:'timestamp',

				value: 'true'

			},

			duration:{

				type:'checkbox',

				label:'duration',

				value: 'true'

			},

			keystrok:{

				type:'checkbox',

				label:'Keystroke',

				value: 'true'

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

              label:'Field Name',

              value:'field_name'

            },

            paradata:{

              type:'checkbox',

              label:'Paradata',

			  value: 'true'

            },

			

			//PARADATA OPTIONS

			paudio:{

				type:'checkbox',

				label:'audio'

			},

			gps:{

				type:'checkbox',

				label:'GPS',

			},

			

			timestamp:{

				type:'checkbox',

				label:'timestamp',

				value: 'true'

			},

			duration:{

				type:'checkbox',

				label:'duration',

				value: 'true'

			},

			keystrok:{

				type:'checkbox',

				label:'Keystroke',

				value: 'true'

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

var question_id;

function getId(val){

  question_id = $(val).data("id");

  $.ajax({

    url:"question_json.php",

    // contentType: "application/json",

    // dataType: "json",

    type:"post",

    data:{questionId:question_id},

    success:function(data){

      formBuilder.actions.setData(data);

      //console.log(data);

    }

  });
}

</script>
<script type="text/javascript">
  jQuery(($) => {
    // const fbEditor = document.getElementById("build-wrap");

    // const formBuilder = $(fbEditor).formBuilder();

    document.getElementById("updateQues").addEventListener("click", () => {

      // console.log("external save clicked");

      const result = formBuilder.actions.save();

      // console.log("result:", result);

      // alert(question_id);

      var survey_id = $('#survey_id').val();

      $.ajax({

        url:"question_json.php",

        // contentType: "application/json",

        // dataType: "json",

        type:"post",

        data:{survey_ideditQues:survey_id,question_id:question_id,form_data:result},

        success:function(data){

          if(data='success'){

            toastr.success('Question Updated Successfully..!', 'Success Alert', {timeOut: 5000});

          }else{

            toastr.success('Somthing Went Wrong!!!', 'Success Alert', {timeOut: 5000});

          }

        }

      });

    });

  });
$(document).ready(function(){ 

    //alert("success");

	// $('.audio-wrap').addClass('check-inline');

	// $('.gps-wrap').addClass('check-inline');

	// $('.timestamp-wrap').addClass('check-inline');

	// $('.duration-wrap').addClass('check-inline');

	// $('.keystrok-wrap').addClass('check-inline');

});
$(document).on('click', '.fld-paradata', function() {
	var paradataval = $(this).prop('checked');

	if(paradataval==true){

		$('.paudio-wrap').show('slow');

		$('.gps-wrap').show('slow');

		$('.timestamp-wrap').show('slow');

		$('.duration-wrap').show('slow');

		$('.keystrok-wrap').show('slow');

	}else{

		$('.paudio-wrap').hide('slow');

		$('.gps-wrap').hide('slow');

		$('.timestamp-wrap').hide('slow');

		$('.duration-wrap').hide('slow');

		$('.keystrok-wrap').hide('slow');

	}

});
$(document).on('click', '.fld-advance', function() {

    // alert('hello');

    var advanceval = $(this).prop('checked'); //$(this).val();

    if(advanceval==true){

      $('.hint-wrap').show('slow');

      $('.limit-wrap').show('slow');

      $('.constraint-wrap').show('slow');

      $('.constraint_msg-wrap').show('slow');

      $('.appearance-wrap').show('slow');

      $('.relevant-wrap').show('slow');

      $('.repeat_count-wrap').show('slow');

      $('.read_only-wrap').show('slow');

      

    }else{

      $('.hint-wrap').hide('slow');

      $('.limit-wrap').hide('slow');

      $('.constraint-wrap').hide('slow');

      $('.constraint_msg-wrap').hide('slow');

      $('.appearance-wrap').hide('slow');

      $('.relevant-wrap').hide('slow');

      $('.repeat_count-wrap').hide('slow');

      $('.read_only-wrap').hide('slow');

    }

});

</script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

