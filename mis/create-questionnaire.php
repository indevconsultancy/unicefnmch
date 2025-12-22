<?php
	/* function createExcelSheet($jsondata){
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);

		ini_set('memory_limit', '-1');
		require realpath(dirname(__FILE__)) . '/PHPExcel/Classes/PHPExcel.php';
    
		$objPHPExcel = new PHPExcel();
		$data = json_decode($jsondata);
		$key = "questions"; //$_POST['key'];
		//$cols = array('type','choice_relation','name','label','dictionary_label','deidentify','hint','limit','relevant','constraint','constraint_message','required','appearance','paradata','default_response','choice_filter','repeat_count','read_only','preserve','lookups','media_file','unique_id','parameters','calculation');
		$cols = array('type','choice_relation','name','label','deidentify','dictionary_label','lookups','media_file','preserve','unique_id','default_response','hint','limit','constraint','constraint_message','required','paradata','appearance','choice_filter','relevant','parameters','repeat_count','read_only','calculation','label::HI','hint::HI','constraint_message::HI','label::TA','hint::TA','constraint_message::TA');
    
		///CREATING QUESTIONNAIRE SURVEY SHEET
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
    
		// TODO deal with more than 26 columns... does Excel double letters up or what?
		
		
		function getColLetter ($i) {
		$COLS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$ct = ($i > 25) ? floor($i / 26) : 0;
		$ret = $COLS[$i % 26];
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
			foreach ($cols as $j=>$col) {
				$activeSheet->setCellValue(getColLetter($j) . ($i + 2), $row->$col);
			}
		}
		$objPHPExcel->getActiveSheet()->setTitle('Survey');
	
	
		/// CREATING QUESTIONNAIRE CHOICES SHEET
		$data = json_decode($jsondata);
		$key = "choices";
		$cols = array('list_name','value','label','choice_filter_parent','media_file','constraint','label::HI','label::TA');
    
	
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
		
	} */
	function createExcelSheet($jsondata,$surveyFormName="",$surveyUniqueId=""){
		
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
		
		///CREATING QUESTIONNAIRE SURVEY SHEET
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
    
		// TODO deal with more than 26 columns... does Excel double letters up or what?
		/* function getColLetter ($i) {
			$COLS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZAAABACADAEAF';
			$ct = ($i > 31) ? floor($i / 32) : 0;
			$ret = $COLS[$i % 32];
				while ($ct--)
					$ret .= $ret;
			return $ret;
		} */ 
		for ($i = 'A'; $i <= 'Z'; $i++) {
			$setValuePosiotion[] = $i;
		}
		
        foreach ($cols as $headKey => $colheder) {
			$activeSheet->setCellValue($setValuePosiotion[$headKey] . 1, strtolower($colheder));
		}
		foreach ($data->$key as $i => $row) {
			 foreach ($cols as $j => $col) {
				// echo $col;
				$activeSheet->setCellValue($setValuePosiotion[$j] . ($i + 2), $row->$col);
			} 
		} 
		$objPHPExcel->getActiveSheet()->setTitle('Survey');
	
		/// CREATING QUESTIONNAIRE CHOICES SHEET
		$dataChoice = json_decode($jsondata,true);
		$data = json_decode($jsondata);
		
		$key = "choices";
		//$cols = array('list_name','value','label','choice_filter_parent','media_file','constraint');
		$allKeychoice=[];
		foreach ($dataChoice['choices'] as $object) {
			$allKeychoice = array_merge($allKeychoice, array_keys($object));
		}
		$acbChoice = array_unique($allKeychoice);
	    $dmaChoice=implode(',',$acbChoice);
		$colChoices=explode(',',$dmaChoice);
		
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$activeSheet1 = $objPHPExcel->getActiveSheet();
    
		// prepare header row
		foreach ($colChoices as $choiceKey=>$colChoice) {
			//echo $choiceKey;
			$activeSheet1->setCellValue($setValuePosiotion[$choiceKey] . 1, strtolower($colChoice));
		}
		// prepare the rest
		foreach ($data->$key as $i=> $rowChoice) {
			 foreach ($colChoices as $j=>$colOption) {
				//echo $rowChoice->$colOption;
				$activeSheet1->setCellValue($setValuePosiotion[$j] . ($i + 2), $rowChoice->$colOption);
			} 
		} 
		$objPHPExcel->getActiveSheet()->setTitle('Choices');
		
		if($surveyFormName==''){
			$surveyFormName == 'web-form';
		}

		$file_name= $surveyFormName;
		$file_names= 'web-form';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $file_name . '.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter->save('php://output');
		//khushboo//
		$clientid = $_SESSION['client_id'];
		$clientId = "C" . $clientid;
		$digits = 10;
		 // $unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
		 if($surveyUniqueId==''){
			 $unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
		 }
		 $unique_id = $surveyUniqueId;
		$objWriter->save('uploaded_questionnaire/'.$clientId.'/'.$file_name.'-'.$unique_id.'.xls');
		//////
		$objWriter->save('sampling/'.$file_names.'.xls');
		// echo $resname =  'sampling/'.$file_names.'.xls';
		echo $resname =  'uploaded_questionnaire/'.$clientId.'/'.$file_name.'-'.$unique_id.'.xls';
		echo $unique_id ;
		 $filename =  ''.$file_name.'.xls';
		
		$resArr = array("status"=>1,"message"=>"success","url"=>$filename);
		echo json_decode($resArr);
		
	}
	
	function generateExcelSheet($jsondata){
		
		ini_set('memory_limit', '-1');
		require realpath(dirname(__FILE__)) . '/PHPExcel/Classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();
		$data = (array) json_decode($jsondata);
		
		$getkeys = (array) $data[0];
		$cols = array_keys($getkeys);
		
		///CREATING QUESTIONNAIRE SURVEY SHEET
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();

		// TODO deal with more than 26 columns... does Excel double letters up or what?
		function getColLetter ($i) {
		$COLS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$ct = ($i > 25) ? floor($i / 26) : 0;
		$ret = $COLS[$i % 26];
			while ($ct--)
				$ret .= $ret;
		return $ret;
		}
		// prepare header row
		foreach ($cols as $i=>$col) {
			$activeSheet->setCellValue(getColLetter($i) . 1, $col);
		}

		// prepare the rest
		// foreach ($data->$key as $i=>$row) {
		foreach ($data as $i=>$row) {
			foreach ($cols as $j=>$col) {
				$activeSheet->setCellValue(getColLetter($j) . ($i + 2), $row->$col);
			}
		}
		$objPHPExcel->getActiveSheet()->setTitle('Survey');


		/// CREATING QUESTIONNAIRE CHOICES SHEET
		/*
		$file_name='Exported';
		header('Content-Type: application/vnd.ms-excel;  charset=UTF-8');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'. $file_name . '.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// $objWriter->save('php://output');
		$objWriter->save('stata/'.$file_name.'.xlsx');
		//echo 'stata/'.$file_name.'.xls';
		return true;
		*/
		
		$file_name='Exported.xlsx';
		ob_end_clean();
		ob_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$file_name.'" ');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		// $objWriter->save('php://output');
		$objWriter->save('stata/'.$file_name);
		echo "success";
		//return true;
	}
	
	function generateCSV($headers, $jsondata)
	{
		ini_set('memory_limit', '-1');
		//Write the row to the CSV file. fputcsv(file,fields,separator,enclosure)
		$jsonDecoded = json_decode($jsondata, true); // add true, will handle as associative array
		$fh = fopen('stata/data.csv', 'w');
		header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment;filename="data.csv"');

    // Add BOM to the beginning of the file to ensure UTF-8 is recognized by Excel
    fprintf($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));
		fputcsv($fh,$headers);
		if (is_array($jsonDecoded)) {  
		  foreach ($jsonDecoded as $line) {
			// with this foreach, if value is array, replace it with first array value
			$missed_keys=array_values(array_diff($headers,array_keys($line)));
			foreach($missed_keys as $missed_key){
				$line[$missed_key]="";
			}
			$exportArr=[];
			foreach ($headers as $key => $value) {
				$exportArr[$value] = $line[$value];
			}
			// no need for foreach, as fputcsv expects array, which we already have
			fputcsv($fh,$exportArr);
		  }
		}
		fclose($fh);
		$result = array("status"=>1, "msg"=>"created");
		json_encode($result);
	}
	

?>