function choicefilter(i=0,enterdata=""){
	let choice_filter = $(".choicefilter"+i).data("choicefilter");
	if(choice_filter!=""){
		$('.choice'+choice_filter).addClass(' d-none');
		$('.'+choice_filter+enterdata+'-choicefilter-option').removeClass(' d-none');
	}
}

function fieldCalculation(i=0,allFields=[]){
	let calculation = $(".calculation"+i).data("calculation");
	if(calculation!="" && calculation!=undefined){
		// console.log(allFields);
		// console.log(calculation);
		let txt = calculation;
		$.each(allFields, function(k, v){
			let fv=$("#"+v).val();
			var new_text = txt.replace(v, fv);
			txt = new_text;
		});
		// console.log(txt);
		// console.log();
		$(".calculation"+i).val(eval(txt));
	}
}