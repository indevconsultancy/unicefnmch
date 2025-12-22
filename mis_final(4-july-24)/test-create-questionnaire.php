<?php

    $jsondata11='{"questions":[{"type":"text","name":"name","label":"Name","dictionary_label":"name","limit":"20","constraint":"","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":""},{"type":"select_one","name":"gender","label":"Select Gender","dictionary_label":"","limit":"1","constraint":"","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"gender","relevant":""},{"type":"number","name":"age","label":"Age","dictionary_label":"age","limit":"2","constraint":"Selected=Y & count>1","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":"gender=1"},{"type":"number","name":"nofmem","label":"Number of family members","dictionary_label":"Family number member","limit":"2","constraint":"","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":""},{"type":"begin_group","name":"Member_group","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":""},{"type":"text","name":"member_name","label":"Member Name","dictionary_label":"","limit":"20","constraint":"","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":""},{"type":"text","name":"mem_edu","label":"Member Education","dictionary_label":"","limit":"20","constraint":"","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":""},{"type":"number","name":"mem_age","label":"Member Age","dictionary_label":"Member Age","limit":"2","constraint":"","constraint_message":"","hint":"","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":""},{"type":"end_group","read_only":"","preserve":"","unique_id":"","required":"","lookups":"","media_file":"","parameters":"","choice_relation":"","relevant":""},{"type":"number","name":"Mobile Nos","label":"Mobile Nos","dictionary_label":"","limit":"10","constraint":"","constraint_message":"","hint":"Mobile Nos","paradata":"T,G,K","appearance":"","choice_filter":"","repeat_count":"","calculation":"","dafault_response":"","deidentify":"yes","read_only":"","preserve":"","unique_id":"","required":"yes","lookups":"","media_file":"","parameters":"","choice_relation":"","label::HI":"test","hint::HI":"test","constraint_message::HI":"test","label::TA":"test1","hint::TA":"test1","constraint_message::TA":"test1","relevant":"age>30 &"}],"choices":[{"list_name":"gender","value":"1","label":"Male","choice_filter_parent":"","constraint":""},{"list_name":"gender","value":"2","label":"Female","choice_filter_parent":"","constraint":""}]}';
    echo createExcelSheet($jsondata11);
	function createExcelSheet($jsondata){
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);

		ini_set('memory_limit', '-1');
		require realpath(dirname(__FILE__)) . '/PHPExcel/Classes/PHPExcel.php';
    
		$objPHPExcel = new PHPExcel();
		$data1 = json_decode($jsondata,true);
		$data = json_decode($jsondata);
		$key = "questions"; //$_POST['key'];
		//$cols = array('type','choice_relation','name','label','dictionary_label','deidentify','hint','limit','relevant','constraint','constraint_message','required','appearance','paradata','default_response','choice_filter','repeat_count','read_only','preserve','lookups','media_file','unique_id','parameters','calculation');
		//$cols = array('type','choice_relation','name','label','deidentify','dictionary_label','lookups','media_file','preserve','unique_id','default_response','hint','limit','constraint','constraint_message','required','paradata','appearance','choice_filter','relevant','parameters','repeat_count','read_only','calculation','label::HI','hint::HI','constraint_message::HI');
      
		$allKeys = [];
		
		foreach ($data1['questions'] as $object) {
			$allKeys = array_merge($allKeys, array_keys($object));
		}

		// Get unique keys
		$acb = array_unique($allKeys);
	    $dma=implode(',',$acb);
		$cols=explode(',',$dma);
		
		echo "<pre>";
		print_r($allKeys);
		die();
		///CREATING QUESTIONNAIRE SURVEY SHEET
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
    
		// TODO deal with more than 26 columns... does Excel double letters up or what?
		function getColLetter ($i) {
		$COLS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZAAABACADAEAF';
		$ct = ($i > 31) ? floor($i / 32) : 0;
		$ret = $COLS[$i % 32];
			while ($ct--)
				$ret .= $ret;
		return $ret;
		}
		// prepare header row
		foreach ($cols as $i=>$col) {
			$activeSheet->setCellValue(getColLetter($i) . 1, $col);
			
		}
        
		// prepare the rest
		foreach ($data->$key as $i=>$row) {
			//print_r($row);
			foreach ($cols as $j=>$col) {
				$activeSheet->setCellValue(getColLetter($j) . ($i + 2), $row->$col);
				echo $row->$col."<br/>";
			}
		}
		$objPHPExcel->getActiveSheet()->setTitle('Survey');
	
		
		/// CREATING QUESTIONNAIRE CHOICES SHEET
		$data = json_decode($jsondata);
		$key = "choices";
		$cols = array('list_name','value','label','choice_filter_parent','media_file','constraint');
    
	
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$activeSheet1 = $objPHPExcel->getActiveSheet();
    
		// prepare header row
		foreach ($cols as $i=>$col) {
			$activeSheet1->setCellValue(getColLetter($i) . 1, $col);
		}

		// prepare the rest
		foreach ($data->$key as $i=>$row) {
			foreach ($cols as $j=>$col) {
				$activeSheet1->setCellValue(getColLetter($j) . ($i + 2), $row->$col);
			}
		}
		$objPHPExcel->getActiveSheet()->setTitle('Choices');
		
		
		$file_name='web-form';
		$file_names='web-form';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $file_name . '.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter->save('php://output');
		//khushboo//
		$clientid = $_SESSION['client_id'];
		$clientId = "C" . $clientid;
		$digits = 10;
		 $unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
		$objWriter->save('uploaded_questionnaire/'.$clientId.'/'.$file_name.'-'.$unique_id.'.xls');
		//////
		$objWriter->save('sampling/'.$file_names.'.xls');
		echo $resname =  'sampling/'.$file_names.'.xls';
		echo $unique_id ;
		 $filename =  ''.$file_name.'.xls';
		
		$resArr = array("status"=>1,"message"=>"success","url"=>$filename);
		echo json_decode($resArr);
		
	}
	
?>