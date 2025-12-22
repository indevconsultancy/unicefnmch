<?php include('includes/config.php'); ?>
<script src="package/jquery.min.js"></script>
<!-- Core -->
<script src="package/dist/form-builder.min.js"></script>
<!-- Render form templates created with formBuilder -->
<script src="package/dist/form-render.min.js"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

<!-- <textarea name="formBuilder" id="formBuilder"></textarea> -->
<style type="text/css">
	body {
  padding: 0;
  margin: 10px 0;
  background: #f2f2f2;
}
</style>
<h2>
  Set Data <a href="<?=base_url();?>survey-list.php">Survey List</a></h2>
<select name="formTemplates" id="formTemplates" class="form-control">
  <option value=''>Select Survey</option>
  <?php 
    $getSurvey = mysqli_query($conn,"SELECT id,survey_name FROM survey WHERE del_action='N'");
    while($survey = mysqli_fetch_array($getSurvey) ){ ?>
      <option value='<?=$survey['id']?>'><?=$survey['survey_name']?></option>
    <?php
    }
  ?>
</select>

<hr/>

<div id="build-wrap"></div>
<script type="text/javascript">
	var fbEditor = document.getElementById('build-wrap');
  var formBuilder = $(fbEditor).formBuilder();
// var formData = $('#formTemplates').val();
// var formData = '[{"type":"text","label":"Full Name","subtype":"text","className":"form-control","name":"text-1476748004559"},{"type":"select","label":"Occupation","className":"form-control","name":"select-1476748006618","values":[{"label":"Street Sweeper","value":"option-1","selected":true},{"label":"Moth Man","value":"option-2"},{"label":"Chemist","value":"option-3"}]},{"type":"textarea","label":"Short Bio","rows":"5","className":"form-control","name":"textarea-1476748007461"}]';

document.getElementById('formTemplates').addEventListener('change', function() {
    //var formData = $('#formTemplates').val();
    // formBuilder.actions.setData(formData);
    var survey_id=$('#formTemplates').val();
    $.ajax({
    url:"question_json.php",
    // contentType: "application/json",
    // dataType: "json",
    type:"post",
    data:{survey_id:survey_id},
    success:function(data){
      formBuilder.actions.setData(data);
    }
  });


});



</script>