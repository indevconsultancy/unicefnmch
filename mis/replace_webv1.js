

function validateDefaultResponse(input) {
     
	input.value = input.value.replace(/[^0-9,]/g, '');
	
}


$(document).ready(function(){
	
	$('.publishwebform').hide();
	
		
	$(document).on('change',".languageSelect",function() {
		
		let $this = $(this);
		var languageSelect = $this.val();
		
		let label = $this.next().attr('placeholder','Label ::' + languageSelect + '*');
		let hint = $this.next().next().attr('placeholder','Hint ::' + languageSelect);
		let constraint = $this.next().next().next().attr('placeholder','Constraint message ::' + languageSelect);
		
		
	
	});
	
	$(document).on('change',".languageSelectOne",function() {
		
		let $this = $(this);
		var languageSelectOne = $this.val();
		var rowcount = $this.next().val();	
		let label = $this.next().next().attr('placeholder','Label ::' + languageSelectOne + '*');
		let hint = $this.next().next().next().attr('placeholder','Hint ::' + languageSelectOne);
		let constraint = $this.next().next().next().next().attr('placeholder','Constraint message ::' + languageSelectOne);
		
		$('.option-lang-val'+rowcount+'').attr('placeholder','Label ::' + languageSelectOne + '*');
		$('.option-lang-val'+rowcount+'').attr('data-id',languageSelectOne);
	});
	
	$(".savequesbtn").on("click", function(e){
		
	 	
		e.preventDefault();
		let questions = [];
		let choices = [];
		let choicess = [];
		let AllErrors = [];
		let listNameArr = [];
		let surveyId = $("#surveyId").val();
		// console.log(surveyId);
		// $('.loading-indicator').addClass('active');
		// customAlert(' Please hold on, your form is being prepared...', icon='success');
		
		$(".ques").each(function(){
			let question = {};
			let hasError=false;
			//let error = {};
			//let errors = {};
			//let unid = $(this).data("id");
			let ques_type = $(this).next().children().find(".q_type").val();
			if(typeof ques_type === "undefined"){
				hasError=true;
			}
			let ques_name = $(this).next().children().find(".q_name").val();
			let ques_label = $(this).next().children().find(".q_label").val();
			// let ques_dictionary_label = $(this).next().children().find(".q_dictionary_label").val();
			let ques_limit = $(this).next().children().find(".q_limit").val();
			let old_limit = $(this).next().children().find(".q_limit").attr("data-limit");
			
			
			
			
			// $(this).next().children().find('.multi-one').each(function() {
					// console.log($(this).val());
					// console.log('asdf');
			// });
			
			// $(this).parent().parent().find('.form-error').hide();
			// $(this).parent().parent().find('.form-success-notice').hide();
			
			if(ques_type != 'end_group'){
				if(ques_type != 'end_repeat'){
					//var errorDiv = '<div style="color: red;">Error in this row</div>';
					if(typeof ques_name === "undefined") {
						// console.log(ques_name);
						// $(this).next().find(".groups-header").addClass(' error');
						// $(this).next().find(".form-success-notice").hide();
						// $(this).next().find(".form-error").show();
						hasError=true;
					}
					else{
						// $(this).next().find(".form-error").hide();
						// $(this).next().find(".form-success-notice").show();
						hasError=false;
					}
					
				}	
			}
			
			// console.log(counterror);
			let modifiedQues = $(this).next().children().find(".modified_ques").val();
			let manualRelevant = $(this).next().children().find(".manual_relevant_txt").val();
			let q_choice_relation = $(this).next().children().find(".q_choice_relation").val();
			let q_media_file = $(this).next().children().find(".q_media_file").val();
			let q_lookup = $(this).next().children().find(".q_lookup").val();
			let ques_constraint = $(this).next().children().find(".q_constraint").val();
			let ques_constraint_message = $(this).next().children().find(".q_constraint_message").val();
			let ques_hint = $(this).next().children().find(".q_hint").val();
			// let ques_paradata = $(this).next().children().find(".q_paradata").val();
			let ques_appearance = $(this).next().children().find(".q_appearance").val();
			let ques_choice_filter = $(this).next().children().find(".q_choice_filter").val();
			let ques_repeat_count = $(this).next().children().find(".q_repeat_count").val();
			let ques_calculation = $(this).next().children().find(".q_calculation").val();
			let ques_dafault_response = $(this).next().children().find(".q_dafault_response").val();
			let ques_deidentify = $(this).next().children().find(".q_deidentify:checked").val();
			let ques_read_only = $(this).next().children().find(".q_read_only:checked").val();
			let ques_preserve = $(this).next().children().find(".q_preserve:checked").val();
			let ques_unique_id = $(this).next().children().find(".q_unique_id:checked").val();
			let ques_required = $(this).next().children().find(".q_required:checked").val();
			let fieldlist = $(this).next().children().find(".fieldlist:checked").val();
			let ques_parameters = $(this).next().children().find(".parameters:checked").val();
			if(ques_required==undefined){ ques_required=''; }
			if(ques_unique_id==undefined){ ques_unique_id=''; }
			if(ques_preserve==undefined){ ques_preserve=''; }
			if(ques_read_only==undefined){ ques_read_only=''; }
			if(ques_deidentify==undefined){ ques_deidentify=''; }
			if(fieldlist==undefined){ fieldlist=''; }
			if(ques_parameters==undefined){ ques_parameters=''; }
			if(q_choice_relation==undefined){ q_choice_relation=''; }
			if(modifiedQues==undefined){ modifiedQues= 0; }
			
			
			var checkedParadataValues = $(this).next().children().find('input[name="paradata"]:checked').map(function() {
                    return this.value;
                }).get().join(',');
			
			
			if(fieldlist!=''){
				ques_appearance='onescreen';
			}
			
			if (typeof q_media_file === "undefined" || q_media_file === "") {
					q_media_file = "";  
			}
			
			if (typeof q_lookup === "undefined" || q_lookup === "") {
					q_lookup = "";  
			}
			
			///VALIDATION
			
			$(this).removeClass('error');
			$(this).next().children().find(".q_name").removeClass('error');
			$(this).next().children().find(".q_label").removeClass('error');
			$(this).next().children().find(".q_limit").removeClass('error');
			$(this).next().children().find(".q_constraint_message").removeClass('error');
			$(this).next().children().find(".languageSelect").removeClass('error');
			$(this).next().children().find(".multi-one").removeClass('error');
			$(this).next().children().find(".languageSelectOne").removeClass('error');
			$(this).next().children().find(".multi-one-option").removeClass('error');
			$(this).next().children().find(".option-language-label").removeClass('error');
			if(ques_name==''){
				hasError=true;
				//error['ques_name_err'] = 'Question name is required.';
				$(this).next().children().find(".q_name").addClass(' error');
				
				
			}
			if(ques_label==''){

				hasError=true;
				//error['ques_label_err'] = 'Question Label is required.';
				$(this).next().children().find(".q_label").addClass(' error');
			}
			
			if(parseInt(ques_limit) < parseInt(old_limit)){
				hasError=true;
				$(this).next().children().find(".q_limit").addClass(' error');
			}
			if(ques_limit==''){
				// console.log(ques_name);
				hasError=true;
				//error['ques_limit_err'] = 'Limit is required.';
				$(this).next().children().find(".q_limit").addClass(' error');
			}
			
			if(ques_constraint!='' && ques_constraint_message==''){
				$(this).next().children().find(".q_constraint_message").addClass(' error');
			}
			
			let isDisNoneOneFirst = true;

			$(this).next().children().find(".languageSelect").each(function() {
				if ($(this).val().trim() === '') {
					if (isDisNoneOneFirst) {
						isDisNoneOneFirst = false; 
					} else {
						hasError = true;
						$(this).addClass('error');
					}
				}
			});
			
			let isDisNoneTwoFirst = true;

			$(this).next().children().find(".multi-one").each(function() {
				//console.log($(this).val().trim());
				if ($(this).val().trim() === '') {
					if (isDisNoneTwoFirst) {
						isDisNoneTwoFirst = false; 
					} else {
						hasError = true;
						$(this).addClass('error');
					}
				}
			});
			

			$(this).next().children().find(".languageSelectOne").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
				}
			});
			

			$(this).next().children().find(".multi-one-option").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
				}
			});
			
			$(this).next().children().find(".option-language-label").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
				}
			});
			

		
			if(ques_type !== "end_group"){
				if(ques_type !== "end_repeat"){
					if(hasError){
						// $(this).addClass(' error');
						$(this).parent().parent().find('.form-success-notice').hide();
						$(this).parent().parent().find('.form-error').show();
						AllErrors.push(hasError);
					}
					else{
						$(this).parent().parent().find('.form-error').hide();
						$(this).parent().parent().find('.form-success-notice').show();
					}
				}
			}
			
			//console.log(error);
			
			if(ques_type=='number' || ques_type == 'text'){
				if (typeof ques_dafault_response === "undefined" || ques_dafault_response === "") {
					ques_dafault_response = "";  
				} else {
					ques_dafault_response = "{" + ques_dafault_response + "}";  
				}
			}
			
			
			/// DEFINE QUESTIONNAIRE
			question['type'] = ques_type;
			question['choice_relation'] = q_choice_relation;
			question['name'] = ques_name;
			question['dictionary_label'] = '';
			question['label'] = ques_label;
			question['limit'] = ques_limit;
			question['hint'] = ques_hint;
			question['relevant'] = manualRelevant;
			question['default_response'] = ques_dafault_response;
			question['constraint'] = ques_constraint;
			question['constraint_message'] = ques_constraint_message;
			// question['paradata'] = ques_paradata;
			question['required'] = ques_required;
			question['paradata'] = checkedParadataValues;
			question['appearance'] = ques_appearance;
			question['read_only'] = ques_read_only;
			question['unique_id'] = ques_unique_id;
			question['choice_filter'] = ques_choice_filter;
			question['repeat_count'] = ques_repeat_count;
			question['calculation'] = ques_calculation;
			question['lookups'] = q_lookup;
			question['media_file'] = q_media_file;
			question['deidentify'] = ques_deidentify;
			question['preserve'] = ques_preserve;
			question['parameters'] = ques_parameters;
			question['modifiedques'] = modifiedQues;
			
			/// Choises
			if(ques_type=='select_one' || ques_type=='select_multiple')
			{
				let ques_ismultiple = $(this).next().children().find(".q_ismultiple:checked").val();
				if(ques_ismultiple==undefined){ ques_ismultiple=''; }
				if(ques_ismultiple=="yes"){ question['type'] = 'select_multiple'; }
				// question['choice_relation'] = q_choice_relation;
				if(q_choice_relation==''){
					question['choice_relation'] = ques_name;
					q_choice_relation = ques_name;
				}
				
				let allOptionsValue = $("[name='"+ques_name+"'] Option").map(function() {return $(this).val();}).get();
				let allOptionsLabel = $("[name='"+ques_name+"'] Option").map(function() {return $(this).text();}).get();
				let choiceFilterOptionValues = $(this).next().children().find(".choice-filter-val").map(function() {
					return $(this).val();
				}).get();
				let choiceConstraintValue = $(this).next().children().find(".choice_constraint").map(function() {
					return $(this).val();
				}).get();
				let choiceMediaFile = $(this).next().children().find(".choice_mediafile").map(function() {
					return $(this).val();
				}).get();
				
				let filteredChoiceFilterValues = [''];
				let choiceConstraintArr = [''];
				let choiceMediaFileArr = [''];

				for (let i = 0; i < choiceFilterOptionValues.length; i++) {
					filteredChoiceFilterValues.push(choiceFilterOptionValues[i]);
				}
				
				for (let i = 0; i < choiceConstraintValue.length; i++) {
					choiceConstraintArr.push(choiceConstraintValue[i]);
				}
				
				for (let i = 0; i < choiceMediaFile.length; i++) {
					choiceMediaFileArr.push(choiceMediaFile[i]);
				}

			
			// console.log(optionval);
				let languageCodeOption = $(this).next().children().find(".languageSelectOne").map(function(){return this.value;}).get();
				let optionval = $(this).next().children().find(".option-language-label").map(function(){return this.value;}).get();
				let langCode = $(this).next().children().find(".option-language-label").map(function() {
					return $(this).attr('data-id');
				}).get();
							
							/* Select One Multilingual Label start */
				let langLabels = $(this).next().children().find(".multi-one-option").map(function(){return this.value;}).get();
				let langHints = $(this).next().children().find(".multi-two-option").map(function(){return this.value;}).get();
				let langConstraints = $(this).next().children().find(".multi-three-option").map(function(){return this.value;}).get();
							
				$.each(languageCodeOption, function(k,v){
					if(v!=""){
							question['label::'+v.toUpperCase()+'']=langLabels[k];
							question['hint::'+v.toUpperCase()+'']=langHints[k];
							question['constraint_message::'+v.toUpperCase()+'']=langConstraints[k];
						}
					});
				
				/* Select One Multilingual Label end */
				
				$.each(choices, function (key, val) {
					listNameArr.push(val['list_name']);
				});	

				// console.log(allOptionsValue);
				let xc=0;
				$.each(allOptionsValue, function (key, val) {
					if (listNameArr.includes(q_choice_relation)) {
						return;
					}
					/* if(val==''){
						hasError=true;
						$(this).next().children().find("[name='"+ques_name+"'] Option").addClass(' error');
					}
					
					if(hasError){
						$(this).addClass(' error');
						AllErrors.push(hasError);
					} */
					
					if(val!=''){
						let choice = {};
						choice['list_name'] = q_choice_relation;
						choice['value'] = val;
						choice['label'] = allOptionsLabel[key];
						choice['choice_filter_parent'] = filteredChoiceFilterValues[key] ? filteredChoiceFilterValues[key] : '';
						choice['constraint'] = choiceConstraintArr[key] ? choiceConstraintArr[key] : '';
						choice['media_file'] = choiceMediaFileArr[key] ? choiceMediaFileArr[key] : '';
						choice['modified_ques'] = modifiedQues;
						$.each(languageCodeOption, function (key1, val1) {
							choice['label::'+val1.toUpperCase()+'']=optionval[xc];
							xc++;
						});
						
						choices.push(choice);
					}
					
				});
				
				// console.log(choices);
			}
			
			
			/// Multilingual
			let languageCodes = $(this).next().children().find(".languageSelect").map(function(){return this.value;}).get();
			let langLabels = $(this).next().children().find(".multi-one").map(function(){return this.value;}).get();
			let langHints = $(this).next().children().find(".multi-two").map(function(){return this.value;}).get();
			let langConstraints = $(this).next().children().find(".multi-three").map(function(){return this.value;}).get();
			
			// console.log(languageCodes,langLabels,langHints,langConstraints);
			// console.log(languageCodes);
			
			$.each(languageCodes, function(k,v){
				if(v!=""){
						question['label::'+v.toUpperCase()+'']=langLabels[k];
						question['hint::'+v.toUpperCase()+'']=langHints[k];
						question['constraint_message::'+v.toUpperCase()+'']=langConstraints[k];
					}
				});
				
				
			
			/// Relevants
			let relqnames = $(this).next().children().find(".rel_qnames").map(function(){return this.value;}).get();
			let reloperators = $(this).next().children().find(".rel_operators").map(function(){return this.value;}).get();
			let relvalues = $(this).next().children().find(".rel_values").map(function(){return this.value;}).get();
			let relandOrs = $(this).next().children().find(".rel_andOr").map(function(){return this.value;}).get();
			
			//console.log(reloperators);
		/*	let qrelvant=''; let relevantForm = [];
			$.each(relqnames, function(k,v){
				if(v!=""){
					let relForm = {};
					qrelvant+=v+''+reloperators[k]+''+relvalues[k]+' '+relandOrs[k]+' ';
					relForm['qname']=v;
					relForm['operator']=reloperators[k];
					relForm['relevant_value']=relvalues[k];
					relForm['rel_and_or']=relandOrs[k];
					relevantForm.push(relForm);
				}
			});
			
			let rlf='';
			if(qrelvant!=''){
				qrelvant=qrelvant.trim();
				rlf = relevantForm;
			}
			
			// console.log(question);
			question['relevant'] = qrelvant;
			question['relevant_for_form'] = rlf; */
			
			questions.push(question);
			
		});
		
		// console.log(questions);
		var finalQuestions = JSON.stringify(questions);
		var finalChoices = JSON.stringify(choices);
		// console.log(choices);
		if(questions.length>0){
			// console.log(AllErrors);
			//return false;
			if(AllErrors==false){
				// console.log('qwerty');
				$.ajax({
					url:"https://unicef.indevconsultancy.in/mis/replace_webajax.php",
					type:"post",
					// cache : false,
					// processData: false,
					// contentType: false,
					data:{surveyId:surveyId,questions:finalQuestions,choices:finalChoices,process:'WEBFORM'},
					success:function(res){
						// console.log(res);
						// $('.loading-indicator').removeClass('active');
						let ress = JSON.parse(res);
						if(ress.status=="1"){
							$('.publishwebform').show();
							customAlert('Updated Successfully, Please Publish the Form',icon='success');
						}
					}
				});
			}
			else{
				// $('.loading-indicator').removeClass('active');
				return false;
			}
			
		}
		else{
			console.log('Please enter a question');
		}
		
	});
	
	
	/* $(document).on("click", ".add-relfield", function () {
		let eqw = $(".add-relfield").index(this);
		console.log(eqw); 
	}) */
	
	
	
	$(".downloadExcel").on("click", function(e){
		e.preventDefault();
		let sid = $("#surveyId").val();
		$.ajax({
			url:"https://unicef.indevconsultancy.in/mis/replace_webajax.php",
			type:"post",
			data:{surveyId:sid,process:'downloadQuestionnaire'},
			success:function(res){
			 //console.log(res);
			// let link = 'https://unicef.indevconsultancy.in/mis/'+res;
			// window.location.href=link;
			let fileName = res;

			// Split the string at ".xls"
			let parts = fileName.split(".xls");

			// Extract the part before ".xls"
			let beforeExtension = parts[0];

			// Add the ".xls" extension back
			beforeExtension += ".xls";
			let fileurl=beforeExtension;
			let link = 'https://unicef.indevconsultancy.in/mis/'+fileurl;
			window.location.href=link;
			}
		});
	});
	//PUBLISH WEB FORM KHUSHBOO
	$(".publishwebform").on("click", function(e){
		e.preventDefault();
		let sid = $("#surveyId").val();
		//console.log(sid);
		$.ajax({
			url:"https://unicef.indevconsultancy.in/mis/replace_webajax.php",
			type:"post",
			data:{surveyId:sid,process:'publishWebForm'},
			//dataType: 'json',
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success:function(res){
				// console.log(res);
				var fileName = res;
				// Split the string at ".xls"
				var parts = fileName.split(".xls");
				// Extract the part before ".xls"
				var beforeExtension = parts[0];
				// Add the ".xls" extension back
				beforeExtension += ".xls";
				// Extract the part after ".xls"
				var afterExtension = parts[1];
				let unique_id=afterExtension;
				let url=beforeExtension;
				
				$.ajax({
					url: "update_replace_questionnaire_ajax.php?url=" + url + "&survey_id=" + sid+"&unique_id="+unique_id,
					type:"GET",
					data:{},
					success:function(res){
						$('.loading-indicator').removeClass('active');
						// console.log(res);
						let ress = JSON.parse(res);
						if(ress.status=="1"){
							let link = 'https://unicef.indevconsultancy.in/mis/survey-list.php?res=update_success';
							window.location.href=link;
						}else{
							customAlert('Form not updated. please try again!!',icon='warning');
						}
					}
				}); 
			}
		});
	});
	
	
	/// CHECK WEB FORM IS ALREADY EXIST
	let surveyId = $("#surveyId").val();
	$.ajax({
		url:"https://unicef.indevconsultancy.in/mis/replace_webajax.php",
		type:"post",
		// cache : false,
		// processData: false,
		// contentType: false,
		data:{surveyId:surveyId,process:'WEBFORMEXIST'},
		beforeSend: function() {
		   $('.loading-indicator').addClass('active');
		},
		success:function(res){
			// console.log(res);
			$(".question-area-container").html(res);
			
			
			$('.loading-indicator').removeClass('active');
			//let link = 'https://unicef.indevconsultancy.in/mis/'+res;
			//window.location.href=link;
		}
	});
});

