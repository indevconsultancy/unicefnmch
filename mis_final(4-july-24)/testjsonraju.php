<?php
function array_unique_names($array) {
    // Copy our input as a temporary new array
			$output = $array;
			// Keep track of which names we've seen before
			$seen_names = [];
			$seen_names1 = [];
            for($i=0; $i<count($output); $i++)
			{
			foreach ($output[$i] as $key) {
				// If we've seen a name before, remove it from our output
				if (in_array($key[0], $seen_names1)) {
					unset($output[$key]);
				}
				// Otherwise, keep it but add it to the list of seen names
				else {
					$seen_names[] = $key[0];
				}
			}
			$seen_names1[] = $seen_names;
			$seen_names=new array();
			}

			// Return a re-indexed array
			//return array_keys($output);
			
			echo print_r($seen_names1);
		}
		
		$data = '{"questions":[{"type":"text","name":"q2","label":"Mobile No","dictionary_label":"Mobile No","limit":"","constraint":"number","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","label::HI":"\u091b\u093e\u0924\u094d\u0930 \u0915\u093e \u0928\u093e\u092e","hint::HI":"\u0930\u093e\u091c\u0940\u0935","constraint_message::HI":"\u0905\u0928\u093f\u0935\u093e\u0930\u094d\u092f","relevant":"","relevant_for_form":""},{"type":"select_one","name":"gender","label":"Gender","dictionary_label":"Gender","limit":"","constraint":"","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"gender","relevant":"","relevant_for_form":""},{"type":"number","name":"Age","label":"Enter Age","dictionary_label":"age","limit":"","constraint":"age constraint msg","constraint_message":"","hint":"eg.10","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","deidentify":"yes","read_only":"yes","preserve":"","unique_id":"yes","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":"","relevant_for_form":""},{"type":"text","name":"school","label":"School Name","dictionary_label":"school","limit":"100","constraint":"","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"yes","preserve":"yes","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":"","relevant_for_form":""},{"type":"number","name":"number","label":"numberx","dictionary_label":"qwerty","limit":"","constraint":"","constraint_message":"","hint":"qazxcg","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","deidentify":"yes","read_only":"yes","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":"","relevant_for_form":""},{"type":"text","name":"texttt","label":"texttt","dictionary_label":"sasdf","limit":"","constraint":"sadf","constraint_message":"","hint":"sdf","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"yes","unique_id":"yes","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","label::HI":"\u091b\u093e\u0924\u094d\u0930 \u0915\u093e \u0928\u093e\u092e","hint::HI":"\u0930\u093e\u091c\u0940\u0935","constraint_message::HI":"\u0905\u0928\u093f\u0935\u093e\u0930\u094d\u092f","label::TA":"tamil name","hint::TA":"tamil hint","constraint_message::TA":"tamil mandatory","relevant":"","relevant_for_form":""}],"choices":[{"list_name":"gender","value":"1","label":"Male","choice_filter_parent":"","constraint":""},{"list_name":"gender","value":"2","label":"Female","choice_filter_parent":"","constraint":""}]}';

		$array = json_decode($data, TRUE);
		//print_r($array['questions']);
		$array = array_unique_names($array['questions']);
		echo $result = json_encode($array);
		
?>