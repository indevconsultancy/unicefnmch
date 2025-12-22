<?php 
function verifySurvey($file_name, $tmp_name) {
	$fieldNameArr = $selectOneChoisesArr = [];
	$typeArr = ['text', 'number', 'date', 'time', 'datetime', 'picture', 'select_one', 'select_multiple', 'begin_repeat', 'end_repeat', 'begin_group', 'end_group', 'calculate', 'audio', 'video', 'hidden', 'gps-button', 'timeline_start', 'timeline_end', 'note'];
	$typeLblBlankArr = ['begin_repeat', 'end_repeat', 'begin_group', 'end_group', 'timeline_start', 'timeline_end', 'note'];

	$file_check = 1; // This can be set dynamically or through form input as needed
	if ($file_check == 1) {
		set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
		include 'PHPExcel/Classes/PHPExcel/IOFactory.php';
		$file = $tmp_name;
		$inputFileType = PHPExcel_IOFactory::identify($file);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($file);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$CurrentWorkSheetIndex = 0;

		$rowsss = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();
		$cellIterator = $rowsss->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		$fixedHeaders = ['type', 'choice_relation', 'name', 'label', 'deidentify', 'dictionary_label', 'lookups', 'media_file', 'preserve', 'unique_id', 'default_response', 'hint', 'limit', 'constraint', 'constraint_message', 'required', 'paradata', 'appearance', 'choice_filter', 'relevant', 'parameters', 'repeat_count', 'read_only', 'calculation'];

		$excelFormat = [];
		foreach ($cellIterator as $cell) {
			$excelFormat[] = $cell->getValue();
		}
		$excelFormat1 = array_filter($excelFormat, 'strlen');
		$headers = array_diff($fixedHeaders, $excelFormat1);

		if (count($headers) > 0) {
			$_SESSION['status_error'] = "Invalid Excel Format.";
			$_SESSION['status_error_code'] = "warning";
		}

		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$sheetSno = $objPHPExcel->getIndex($worksheet);
			$highestRow = $worksheet->getHighestDataRow();
			$highestColumn = $worksheet->getHighestDataColumn();
			$headings = $worksheet->rangeToArray('A1:' . $highestColumn . 1, NULL, TRUE, FALSE);

			if ($sheetSno == "0") {
				$screen_no = 1;
				$sequence_no = 1;
				$group = 0;
				$question_id = 0;

				for ($row = 2; $row <= $highestRow; $row++) {
					$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
					$rowData[0] = array_combine($headings[0], $rowData[0]);
					$pos = '';
					$sqltext = 'test';
					$questions_type_id = 0;
					$question_input_type_id = 0;
					$validation_id = 0;
					$groupid = 0;

					if ($row == 2) {
						$fixedHeaders = ['type', 'choice_relation', 'name', 'label', 'deidentify', 'dictionary_label', 'lookups', 'media_file', 'preserve', 'unique_id', 'default_response', 'hint', 'limit', 'constraint', 'constraint_message', 'required', 'paradata', 'appearance', 'choice_filter', 'relevant', 'parameters', 'repeat_count', 'read_only', 'calculation'];
						$fixedHeaders1 = ['type', 'choice_relation', 'name', 'label', 'deidentify', 'dictionary_label', 'lookups', 'media_file', 'preserve', 'unique_id', 'default_response', 'hint', 'limit', 'constraint', 'constraint_message', 'required', 'paradata', 'appearance', 'choice_filter', 'relevant', 'parameters', 'repeat_count', 'read_only', 'calculation'];
						$language_masters = [];
						$allQuesKeys = array_keys($rowData[0]);
						foreach ($allQuesKeys as $allQuesKey) {
							$search = 'label::';
							if (preg_match("/{$search}/i", $allQuesKey)) {
								$language_masters[] = str_replace("label::", "", $allQuesKey);
							}
						}
						$getLaguages = mysqli_query($conn, "SELECT language_id, language_name, language_code FROM languages WHERE status='0' ORDER BY language_id ASC");
						$languagemasters = mysqli_fetch_all($getLaguages, MYSQLI_ASSOC);
						foreach ($languagemasters as $language_master) {
							$fixedHeaders[] =  "hint::" . $language_master['language_code'];
							$fixedHeaders[] =  "label::" . $language_master['language_code'];
							$fixedHeaders[] =  "constraint_message::" . $language_master['language_code'];
						}
						$headers = array_diff($allQuesKeys, $fixedHeaders);
						$headers1 = array_diff($fixedHeaders1, $allQuesKeys);
						foreach ($headers as $header) {
							if ($header != "") {
								$error_arr[] = "Header/ Column does not exist: " . $header;
							}
						}

						foreach ($headers1 as $header1) {
							if ($header1 != "") {
								$error_arr[] = "Header/ Column is missing: " . $header1;
							}
						}
					}

					foreach ($rowData as $key => $rowvalue) {
						$category = '';
						$type = safe_var($conn, $rowvalue['type']);
						$type = strtolower($type);

						$question_input_type_id = 1;
						$groupid = $group;
						$field_name = trim(safe_var($conn, str_replace(" ", "", $rowvalue['name'])));
						$question_name = isset($rowvalue['label']) ? safe_var($conn, $rowvalue['label']) : safe_var($conn, $rowvalue['label::English']);
						$question_name = trim($question_name);
						$question_description = safe_var($conn, $rowvalue['hint']);
						$relevant = safe_var($conn, $rowvalue['relevant']);
						$constraints = safe_var($conn, $rowvalue['constraint']);
						$constraint_message = safe_var($conn, $rowvalue['constraint_message']);
						$parameters = safe_var($conn, $rowvalue['parameters']);
						$read_only = safe_var($conn, $rowvalue['read_only']);
						$calculation = safe_var($conn, $rowvalue['calculation']);
						$required = strtolower(safe_var($conn, $rowvalue['required']));
						$limit = $rowvalue['limit'];
						$repeat_count = safe_var($conn, $rowvalue['repeat_count']);
						$appearance = safe_var($conn, $rowvalue['appearance']);
						$choice_filter = safe_var($conn, $rowvalue['choice_filter']);
						$choice_relation = safe_var($conn, $rowvalue['choice_relation']);
						$default_response = safe_var($conn, $rowvalue['default_response']);
						$paradata = safe_var($conn, $rowvalue['paradata']);

						$category = $field_name;
						$sn = $sequence_no;
						$sn = $sn + 1;

						if ($type == "" && $field_name == "" && $question_name == "") {
							break;
						}

						$allFiels[] = $field_name;
						if (!in_array($choice_filter, $allFiels) && $choice_filter != "") {
							$error_arr[] =  "Choice filter is invalid in line No.: " . $sn;
						}

						if ($type != "begin_repeat") {
							$fieldNameArr[] = $field_name;
						}

						if ($type == "select_one") {
							$selectOneChoisesArr[$field_name] = $choice_relation;
							$selectOnelimitsArr[$field_name] = $limit;
						}

						if ($type == "select_multiple") {
							$selectMultiChoisesArr[$field_name] = $choice_relation;
							$selectMultilimitsArr[$field_name] = $limit;
						}

						if (!in_array($type, $typeArr)) {
							$error_arr[] = "Type " . $type . " Not Found line No.: " . $sn;
						}

						if ($type == 'select_one' && $choice_relation == "") {
							$error_arr[] =  "Choice relation required in line No.: " . $sn;
						}

						if ($type == 'select_one' && !is_numeric($limit) && $limit != "") {
							$error_arr[] =  "Please specify limit in numeric for type select_one in line No.: " . $sn;
						}

						if ($type == 'select_multiple' && $choice_relation == "") {
							$error_arr[] =  "Choice relation required in line No.: " . $sn;
						}

						if ($type == 'select_multiple' && !is_numeric($limit) && $limit != "") {
							$error_arr[] =  "Please specify limit in numeric for type select_multiple in line No.: " . $sn;
						}

						if (strlen($field_name) > 70) {
							$error_arr[] =  "Field name should not be greater than 70 characters line No.: " . $sn;
						}

						if ($field_name == "") {
							$error_arr[] =  "Field name is required line No.: " . $sn;
						}

						if (in_array($field_name, $fieldNameArr)) {
							$error_arr[] = "Field name already exists line No.: " . $sn;
						}

						if (!in_array($type, $typeLblBlankArr)) {
							if ($question_name == "") {
								$error_arr[] = "Question name is required line No.: " . $sn;
							}
						}
						
						
					}
				}
			}
		}

	}
	return $error_arr; 
}


?>