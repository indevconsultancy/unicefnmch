function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : evt.keyCode
		return !(charCode > 31 && (charCode < 48 || charCode > 57));
	}
	
function validateDefaultResponse(input) {
     
	input.value = input.value.replace(/[^0-9,]/g, '');
	
}

$(document).ready(function(){
		
	$(document).on('change',".languageSelect",function() {
		
		let $this = $(this);
		var languageSelect = $this.val();
		
		let label = $this.next().attr('placeholder','Label ::' + languageSelect + '*');
		let hint = $this.next().next().attr('placeholder','Hint ::' + languageSelect + '*');
		let constraint = $this.next().next().next().attr('placeholder','Constraint message ::' + languageSelect + '*');
		
		
	
	});
	
	$(document).on('change',".languageSelectOne",function() {
		
		let $this = $(this);
		var languageSelectOne = $this.val();
		var rowcount = $this.next().val();	
		let label = $this.next().next().attr('placeholder','Label ::' + languageSelectOne + '*');
		let hint = $this.next().next().next().attr('placeholder','Hint ::' + languageSelectOne + '*');
		let constraint = $this.next().next().next().next().attr('placeholder','Constraint message ::' + languageSelectOne + '*');
		
		$('.option-lang-val'+rowcount+'').attr('placeholder','Label ::' + languageSelectOne + '*');
		$('.option-lang-val'+rowcount+'').attr('data-id',languageSelectOne);
	});
	
	$("#savequesbtn").on("click", function(e){
		
		var totSurvey = $(this).data("id");
		if(totSurvey > 0){
			customAlert('Data collection is underprocess please use replace form for editing the form', icon = 'warning');
			return false;
		}
		
		e.preventDefault();
		let questions = [];
		let choices = [];
		let choicess = [];
		let AllErrors = [];
		let surveyId = $("#surveyId").val();
		$(".ques").each(function(){
			
			let question = {};
			//let error = {};
			//let errors = {};
			//let unid = $(this).data("id");
			let ques_type = $(this).next().children().find(".q_type").val();
			// console.log(ques_type);
			// let ques_type = $('.edit-main-container').length
			let ques_name = $(this).next().children().find(".q_name").val();
			let ques_label = $(this).next().children().find(".q_label").val();
			let ques_dictionary_label = $(this).next().children().find(".q_dictionary_label").val();
			let ques_limit = $(this).next().children().find(".q_limit").val();
			
			let hasError=false;
			
			if (!$('.edit-main-container').length) {
				if(typeof ques_name === "undefined" || typeof ques_label === "undefined" || typeof ques_limit === "undefined" ) {
					$(this).next().find(".groups-header").addClass(' error');
					hasError=true;
				}
			}
			
			let manualRelevant = $(this).next().children().find(".manual_relevant_txt").val();
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
			if(ques_deidentify==undefined){ ques_deidentify=''; }
			if(ques_read_only==undefined){ ques_read_only=''; }
			if(fieldlist==undefined){ fieldlist=''; }
			
			var checkedParadataValues = $(this).next().children().find('input[name="paradata"]:checked').map(function() {
                    return this.value;
                }).get().join(',');
			
			if(fieldlist!=''){
				ques_appearance='onescreen';
			}
			
			if(ques_type=='number' || ques_type == 'text'){
				if (typeof ques_dafault_response === "undefined" || ques_dafault_response === "") {
					ques_dafault_response = "";  
				} else {
					ques_dafault_response = "{" + ques_dafault_response + "}";  
				}
			}
			// console.log(ques_dafault_response);
			// $(".edit-main-container").css("display", "block");
			// $(this).find('.edit-main-container').slideToggle('slow');
			///VALIDATION
			
			$(this).removeClass('error');
			$(this).next().children().find(".q_name").removeClass('error');
			$(this).next().children().find(".q_label").removeClass('error');
			$(this).next().children().find(".q_limit").removeClass('error');
			$(this).next().children().find(".q_constraint_message").removeClass('error');
			$(this).next().children().find(".languageSelect").removeClass('error');
			$(this).next().children().find(".multi-one").removeClass('error');
			$(this).next().children().find(".option-language-label").removeClass('error');
			$(this).next().children().find(".multi-one-option").removeClass('error');
			$(this).next().children().find(".languageSelectOne").removeClass('error');
			$(this).next().children().find(".nameOption").removeClass('error');
			$(this).next().children().find(".valueOption").removeClass('error');
			$(this).next().children().find(".multi-two").removeClass('error');
			$(this).next().children().find(".multi-two-option").removeClass('error');
			$(this).next().children().find(".multi-three").removeClass('error');
			$(this).next().children().find(".multi-three-option").removeClass('error');
			if(ques_name==''){
				hasError=true;
				// console.log('1');
				//error['ques_name_err'] = 'Question name is required.';
				$(this).next().children().find(".q_name").addClass(' error');
			}
			
			if(ques_hint !== ''){
				$(this).next().children().find(".multi-two").each(function() {
					// console.log($(this).val().trim());
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('2');
					}
				});
				$(this).next().children().find(".multi-two-option").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('3');
					}
				});
			}
			if(ques_constraint_message !== ''){
				$(this).next().children().find(".multi-three").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('4');
					}
				});
				$(this).next().children().find(".multi-three-option").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('5');
					}
				});
			}
			
			let isDisNoneOneFirst = true;

			$(this).next().children().find(".languageSelect").each(function() {
				if ($(this).val().trim() === '') {
					if (isDisNoneOneFirst) {
						isDisNoneOneFirst = false; 
					} else {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('6');
					}
				}
			});
			
			let isDisNoneTwoFirst = true;

			$(this).next().children().find(".multi-one").each(function() {
				if ($(this).val().trim() === '') {
					if (isDisNoneTwoFirst) {
						isDisNoneTwoFirst = false; 
					} else {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('7');
					}
				}
			});
			

			$(this).next().children().find(".languageSelectOne").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('8');
				}
			});
			

			$(this).next().children().find(".multi-one-option").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('9');
				}
			});
			
			$(this).next().children().find(".option-language-label").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('10');
				}
			});
			
			$(this).next().children().find(".nameOption").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('11');
				}
			});
			
			$(this).next().children().find(".valueOption").each(function() {
				if ($(this).val().trim() === '') {
						hasError = true;
						$(this).addClass('error');
						
				// console.log('12');
				}
			});
			
			if(ques_label==''){
				hasError=true;
				//error['ques_label_err'] = 'Question Label is required.';
				$(this).next().children().find(".q_label").addClass(' error');
				
				// console.log('13');
			}
			
			if(ques_limit==''){
				hasError=true;
				//error['ques_limit_err'] = 'Limit is required.';
				$(this).next().children().find(".q_limit").addClass(' error');
				
				// console.log('14');
			}
			// console.log(ques_constraint);
			// console.log(ques_constraint_message);
			
			
			if(ques_constraint!='' && ques_constraint_message==''){
				hasError=true;
				$(this).next().children().find(".q_constraint_message").addClass(' error');
				
				// console.log('15');
			}

			
			
			//console.log(error);
			
			
			
			/// DEFINE QUESTIONNAIRE
			question['type'] = ques_type;
			question['name'] = ques_name;
			question['label'] = ques_label;
			question['dictionary_label'] = ques_dictionary_label;
			question['limit'] = ques_limit;
			question['constraint'] = ques_constraint;
			question['constraint_message'] = ques_constraint_message;
			question['hint'] = ques_hint;
			// question['paradata'] = ques_paradata;
			question['paradata'] = checkedParadataValues;
			question['appearance'] = ques_appearance;
			question['choice_filter'] = ques_choice_filter;
			question['repeat_count'] = ques_repeat_count;
			question['calculation'] = ques_calculation;
			question['default_response'] = ques_dafault_response;
			question['deidentify'] = ques_deidentify;
			question['read_only'] = ques_read_only;
			question['preserve'] = ques_preserve;
			question['unique_id'] = ques_unique_id;
			question['required'] = ques_required;
			question['lookups'] = '';
			question['media_file'] = '';
			question['parameters'] = ques_parameters;
			question['choice_relation'] = '';
						
			/// Choises
			if(ques_type=='select_one' || ques_type=='select_multiple')
			{
				let ques_ismultiple = $(this).next().children().find(".q_ismultiple:checked").val();
				if(ques_ismultiple==undefined){ ques_ismultiple=''; }
				if(ques_ismultiple=="yes"){
					question['type'] = 'select_multiple'; 
				}
				else{
					question['type'] = 'select_one'; 
				}
				question['choice_relation'] = ques_name;
				
				// console.log($("[name='"+ques_name+"'] Option"));
				
				let allOptionsValue = $("[name='"+ques_name+"'] Option").map(function() {return $(this).val();}).get();
				let allOptionsLabel = $("[name='"+ques_name+"'] Option").map(function() {return $(this).text();}).get();
				
			
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
				// console.log(langLabels);
				$.each(languageCodeOption, function(k,v){
					if(v!=""){
							question['label::'+v.toUpperCase()+'']=langLabels[k];
							question['hint::'+v.toUpperCase()+'']=langHints[k];
							question['constraint_message::'+v.toUpperCase()+'']=langConstraints[k];
						}
					});
				
								
				/* Select One Multilingual Label end */
				
				console.log(allOptionsValue);
				let xc=0;
				$.each(allOptionsValue, function (key, val) {
					
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
						choice['list_name'] = ques_name;
						choice['value'] = val;
						choice['label'] = allOptionsLabel[key];
						choice['choice_filter_parent'] = ''
						choice['constraint'] = ''
						$.each(languageCodeOption, function (key1, val1) {
						choice['label::'+val1.toUpperCase()+'']=optionval[xc];
						xc++;
						});
						
						choices.push(choice);
					}
					
				});
				
			}
				// console.log(choices);
			
			if(hasError){
				$(this).addClass(' error');
				AllErrors.push(hasError);
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
			
			/* let qrelvant=''; let relevantForm = [];
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
			
			question['relevant'] = qrelvant;
			question['relevant_for_form'] = rlf; */
			question['relevant'] = manualRelevant;
			questions.push(question);
			
		});
		
		console.log(questions);
		// console.log(choices);
		if(questions.length>0){
			// console.log(AllErrors);
			//return false;
			if(AllErrors==false){
				// console.log(questions);
				$.ajax({
					url:"https://mquad.org/mis/webajax.php",
					type:"post",
					// cache : false,
					// processData: false,
					// contentType: false,
					data:{surveyId:surveyId,questions:questions,choices:choices,process:'WEBFORM'},
					beforeSend: function() {
					   //$('.loading-indicator').addClass('active');
					},
					success:function(res){
						
						let ress = JSON.parse(res);
						if(ress.status=="1"){
							let link = 'https://mquad.org/mis/add-question-web-v1.php?survey_id='+surveyId+'&res=success';
							window.location.href=link;
							// customAlert('Question saved successfully',icon='success');
						}
						// }
						//$('.loading-indicator').removeClass('active');
					}
				});
			}
			else{
				return false;
			}
			
		}
		else{
			let link = 'https://mquad.org/mis/add-question-web-v1.php?survey_id='+surveyId+'&res=warning';
			window.location.href=link;
			// customAlert('Please add a question',icon='warning');
		}
		
	});
	
	
	/* $(document).on("click", ".add-relfield", function () {
		let eqw = $(".add-relfield").index(this);
		console.log(eqw); 
	}) */
	
	
	
	$("#downloadExcel").on("click", function(e){
		e.preventDefault();
		let sid = $("#surveyId").val();
		$.ajax({
			url:"https://mquad.org/mis/webajax.php",
			type:"post",
			data:{surveyId:sid,process:'downloadQuestionnaire'},
			success:function(res){
			 console.log(res);
			// let link = 'https://mquad.org/mis/'+res;
			// window.location.href=link;
				if(res != 0){
					let fileName = res;
					// Split the string at ".xls"
					let parts = fileName.split(".xls");

					// Extract the part before ".xls"
					let beforeExtension = parts[0];

					// Add the ".xls" extension back
					beforeExtension += ".xls";
					let fileurl=beforeExtension;
					let link = 'https://mquad.org/mis/'+fileurl;
					window.location.href=link;
				}
				else{
					customAlert('Cannot download!! please add questions to download the excel',icon='warning');
				}
			}
		});
	});
	//PUBLISH WEB FORM KHUSHBOO
	// $("#publishwebform").on("click", function(e){
	$(document).on("click", "#publishwebform", function(e) {
		e.preventDefault();
		let sid = $("#surveyId").val();
		//console.log(sid);
		$.ajax({
			url:"https://mquad.org/mis/webajax.php",
			type:"post",
			data:{surveyId:sid,process:'publishWebForm'},
			//dataType: 'json',
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
					url: "add-survey-ssajax-web.php?url=" + url + "&survey_id=" + sid+"&unique_id="+unique_id,
					type:"GET",
					data:{},
					success:function(res){
						// console.log(res);
						let ress = JSON.parse(res);
						if(ress.status=="1"){
							let link = 'https://mquad.org/mis/survey-list.php?res=success';
							window.location.href=link;
						}
						else if(ress.status=="2"){
							customAlert('Web form is already published!',icon='warning');
						}
						else{
							customAlert('No new changes found!',icon='warning');
						}
					}
				}); 
			}
		});
	});
	
	
	/// CHECK WEB FORM IS ALREADY EXIST
	let surveyId = $("#surveyId").val();
	$.ajax({
		url:"https://mquad.org/mis/webajax.php",
		type:"post",
		// cache : false,
		// processData: false,
		// contentType: false,
		data:{surveyId:surveyId,process:'WEBFORMEXIST'},
		beforeSend: function() {
		   //$('.loading-indicator').addClass('active');
		},
		success:function(res){
			console.log(res);
			$(".question-area-container").html(res);
			
			
			//$('.loading-indicator').removeClass('active');
			// let link = 'https://mquad.org/mis/'+res;
			//window.location.href=link;
		}
	});
});

