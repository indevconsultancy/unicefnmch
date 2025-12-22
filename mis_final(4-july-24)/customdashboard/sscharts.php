<?php
/*  
    ==============================================================================================================
	Stack Chart Highcharts 
	*chartId => Chart Id should be String like 'chartdiv'
	*categories => Categories should be Array like ['category -1','category -2','category -3']
	*seriesData => seriesData should be Object like  [{ name: 'Female',  data: [ 15,17,32,21,30,43,15,27,30,] },{ name: 'Male',  data: [ 12,17,7,17,12,17,14,10,13,] }]
	*graphPlotOptions => graphPlotOptions should be also Object like plotOptions: { column: { pointPadding: 0.2, borderWidth: 0 } }
	*chartType => chartType should be String like spline, line, bar, column, area
	*colors => colors should be Array like ['#429de0','#3dae10']
	*$others=> $others should be an array
	==============================================================================================================
*/
function sscolor(){
	$str = 'abcdef0123456789';
	return $color_code = "#".substr(str_shuffle($str), 0,6) ;
} 
function stackColumnCharts($chartId,$title, $categories, $seriesData, $graphPlotOptions, $chartType, $colors,$others=[]){
	//$xAxisTitle="SSS";
	//if(count($others)>0){ $xAxisTitle=$others['xAxisTitle']; $yAxisTitle=$others['yAxisTitle']; }
	
	return $options =" <script> var customChartOptions = {
			colors:  ['$colors'],
			chart: {
				//type: 'column' //spline, line, bar, column, area
				type: '$chartType', 
			},
			title: { text: '$title', align: 'left' },
			subtitle: { text: '' },
			xAxis: {
				categories: $categories
			},
			yAxis: {
				title: {
					text: ''
				}
			},
			
			tooltip: { 
				shared: true,
				useHTML: true
			},
			exporting:{ 
				enabled:true, 
			},
			plotOptions: {
				column: {
				  stacking: '$graphPlotOptions',  //normal, overlap, percent, stream /// IF UN-COMMENT THIS LINE THEN ITS DISPLAY IN STACK, ** IF STACKING IS percent THEN DISPLAY 100% IN STACK 
				},
				series:{
					dataLabels:{
						enabled:true,
					},
				},
			},
			series: $seriesData	
  } </script>";
}


/*
	========================================================================================================================
	Bubble Chart
	========================================================================================================================
*/

function bubbleChart($chartId,$title,$seriesData,$colors,$others=[]){
	
	return $options =" <script> var customChartOptions = {
		colors: ['$colors'],
		chart: {
		  type: 'packedbubble',
		},
		title: { text: '$title', align: 'left' },
		tooltip: {
		  useHTML: true,
		  pointFormat: '<b>{point.name}:</b> {point.value}'
		},
		exporting:{
			enabled: true,
		},
		xAxis: {
			title: {
				text: ''
			}
		},
		yAxis: {
			title: {
				text: ''
			}
		},
		plotOptions: {
		  packedbubble: {
			minSize: '20%',
			maxSize: '100%',
			zMin: 0,
			zMax: 1000,
			layoutAlgorithm: {
			  gravitationalConstant: 0.05,
			  splitSeries: true,
			  seriesInteraction: false,
			  dragBetweenSeries: true,
			  parentNodeLimit: true
			},
			dataLabels: {
			  enabled: true,
			  format: '{point.name}',
			  filter: {
				property: 'y',
				operator: '>',
				value: 250
			  },
			  style: {
				color: 'black',
				textOutline: 'none',
				fontWeight: 'normal'
			  }
			}
		  },
		  series:{
			dataLabels:{ },
		  },
		},
		series: $seriesData
	} </script>";
}


/*
	========================================================================================================================
	Radial Chart
	========================================================================================================================
*/
function radialChart($chartId, $title, $cType, $categories, $seriesData, $colors,$others=[]){
	return $options =" <script> var customChartOptions = {
		colors: ['$colors'],
		chart: {
		  type: '$cType', //bar,column
		  inverted: true,
		  polar: true
		},
		title: {
		  text: '$title',
		  align: 'left'
		},
		subtitle: {
		  text: '',
		  align: 'left'
		},
		tooltip: {
		  outside: true
		},
		pane: {
		  size: '85%',
		  innerSize: '20%',
		  endAngle: 270
		},
		xAxis: {
		  tickInterval: 1,
		  labels: {
			align: 'right',
			useHTML: true,
			allowOverlap: true,
			step: 1,
			y: 3,
			style: {
			  fontSize: '13px'
			}
		  },
		  lineWidth: 0,
		  categories: $categories
		},
		yAxis: {
		  crosshair: {
			enabled: true,
			color: '#333'
		  },
		  lineWidth: 0,
		  tickInterval: 25,
		  reversedStacks: false,
		  endOnTick: true,
		  showLastLabel: true
		},
		exporting:{
			enabled: true,
		},
		plotOptions: {
			column: {
				stacking: 'normal',
				borderWidth: 0,
				pointPadding: 0,
				groupPadding: 0.15
			},
			series:{
				dataLabels:{
					
				},
			},
		},
		series: $seriesData
	} </script>";
	
}




/*
	========================================================================================================================
	Spider Chart
	========================================================================================================================
*/
function spiderChart($chartId, $title, $cType, $categories, $seriesData, $colors,$others=[]){
	return $options =" <script> var customChartOptions = {
		colors: ['$colors'],
		chart: {
		  polar: true,
		  type: '$cType' ///line, spline, column, bar, area
		},
		accessibility: {
		  description: ' '
		},
		title: { text: '$title', align: 'left'},
		
		pane: {
		  size: '80%'
		},
		xAxis: {
		  tickmarkPlacement: 'on',
		  lineWidth: 0,
		  categories: $categories
		},
		yAxis: {
		  gridLineInterpolation: 'polygon',
		  lineWidth: 0,
		  min: 0
		},
		tooltip: {
		  shared: true,
		  useHTML: true
		},
		legend: {
		  align: 'right',
		  verticalAlign: 'middle',
		  layout: 'vertical'
		},
		plotOptions: {
			series:{
				dataLabels:{
					
				},
			},
		},
		exporting:{
			enabled: true,
		},
		series: $seriesData,
		responsive: {
		rules: [{
			condition: {
			  maxWidth: 500
			},
			chartOptions: {
			  legend: {
				align: 'center',
				verticalAlign: 'bottom',
				layout: 'horizontal'
			  },
			  pane: {
				size: '70%'
			  }
			}
		  }]
		}
	} </script>";
	
}



/*
	========================================================================================================================
	Basic Chart
	========================================================================================================================
*/
function basicChart($chartId, $title, $ctype, $seriesData, $colors,$others=[]){
	return $options =" <script> var customChartOptions = {
		colors: ['$colors'],
		chart: {
		  type: '$ctype' // line, bar, column, area
		},
		title: { text: '$title', align: 'left' },
		subtitle: { text: '' },
		xAxis: {
			type: 'category',
			labels: {
				rotation: -45,
			}
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Total '
			}
		},
		legend: {
		  enabled: false
		},
		tooltip: {
		  pointFormat: 'Total: <b>{point.y:.0f} </b>'
		},
		exporting:{ 
			enabled:true, 
		},
		plotOptions: {
			series:{
				dataLabels:{
					enabled:true,
				},
			}
		},
		series: $seriesData
	} </script>";
	
}


/*
	========================================================================================================================
	Basic Pie Chart
	========================================================================================================================
*/

function basicPieChart($chartId,$title,$seriesData,$colors,$others=[]){
	
	return $options =" <script> var customChartOptions = {
		colors: ['$colors'],
		chart: {
			type: 'pie'
		},
		title: {
			text: '$title',
			align: 'left'
		},
		xAxis: {
			type: 'category',
		},
		yAxis: {},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.y} ({point.percentage:.0f}%)</b>'
		},
		plotOptions: {
			pie: {
				showInLegend: true,
			},
			series:{
				dataLabels:{
					enabled:false,
				},
			}
		},
		legend:{},
		tooltip:{},
		exporting:{ 
			enabled:true, 
		},
		series: $seriesData
	} </script>";
}

?>


<?php
/*
====================================================================================
CREATE DYNAMIC CHARTS USING HIGHCHARTS LIBRARY

FUNCTION DESCRIPTION
 *************
 This function use for create the dynamic graph. Use this function you can create multiple type graphs like.
 - PIE
 - COLUMN
 - LINE
 - BAR
 - AREA
 - BUBBLE
 - RADIAL
 - SPIDER
 - STACK
 *************
====================================================================================

*/

function findData($array, $search){
	$result = array();
	foreach ($array as $key => $value){
	  foreach ($search as $k => $v){
		if (!isset($value[$k]) || $value[$k] != $v){
		  continue 2;
		}
	  }
	  $result[] = $key;
	}
	//return $result;
	$res=0;
	if(isset($result[0])){
		$res = isset($array[$result[0]]['totRecords']) ? $array[$result[0]]['totRecords'] : "0" ;
	}
	return $res;
}

function decrypt_field($survey_id, $mcrypt, $conn) {
    $SqlQ="SELECT survey_data_json, survey_status FROM survey_data_monitoring WHERE survey_name_id=".$survey_id;
	$getSurveyData = mysqli_query($conn,$SqlQ);
	
	$dropSql = "DROP TEMPORARY TABLE IF EXISTS temp_table".$survey_id;
	mysqli_query($conn,$dropSql);
	
    $tempTableSql = "CREATE TEMPORARY TABLE IF NOT EXISTS temp_table".$survey_id." (
		id INT AUTO_INCREMENT PRIMARY KEY,
		survey_data_json TEXT,
		survey_status VARCHAR(20),
		survey_name_id VARCHAR(255)
	)";
	mysqli_query($conn,$tempTableSql);

	while($surData = mysqli_fetch_object($getSurveyData)){

		$full_json = $mcrypt->decrypt($surData->survey_data_json);
		$survey_status = $surData->survey_status;
		$inser_sql = "INSERT INTO `temp_table".$survey_id."` set `survey_data_json`='" . $full_json . "', survey_status='".$survey_status."', `survey_name_id`='" . $survey_id ."'";
		
		$insertquery = mysqli_query($conn, $inser_sql);
		$last_id = mysqli_insert_id($conn);
	}	
}

function clean($string) {
   //return preg_replace('/[^A-Za-z0-9 \-]/', '', $string); 
   return preg_replace('/[^A-Za-z0-9 ?!@%() \-]/', '', $string);
}
function dynamic_graph($conn, $survey_id, $chart_name, $chart_type, $colors, $field_name, $groupby="", $chartId="",$others=[],$mcrypt){
	$chartType = $chart_name;
	$legendColors = $colors;
	$title="";
	$gquestionFName=$groupby;
	$questionFName = $field_name;
	$cType=$chart_type;
	$clientId = $others['cid'];
	$isId = false;
	$mainCategoryIsId = false;
	if($field_name=='user_id'){
		$isId=true;
		$getUsers = mysqli_query($conn,"SELECT user_id, name FROM users WHERE client_id='".$clientId."' ");
		$allUsers = mysqli_fetch_all($getUsers, MYSQLI_ASSOC);
		$users=[];
		foreach($allUsers as $allUser){
			$users[$allUser['user_id']]=$allUser['name'];
		}
	}
	
	if($gquestionFName=='user_id'){
		$mainCategoryIsId=true;
		$getUsers = mysqli_query($conn,"SELECT user_id, name FROM users WHERE client_id='".$clientId."' ");
		$allUsers = mysqli_fetch_all($getUsers, MYSQLI_ASSOC);
		$users=[];
		foreach($allUsers as $allUser){
			$users[$allUser['user_id']]=$allUser['name'];
		}
	}
	
	// temp DB created
	decrypt_field($survey_id, $mcrypt, $conn);
	
	if(empty($chartId)){ $chartId = "ss".sscolor(); }
	/// CREATE PIE CHART
	if($chartType=="pie"){ 
		// $pieSql="SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) as category,COUNT(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY category  HAVING category IS NOT null AND category!=''; ";
		$pieSql="SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) as category,COUNT(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) AS totRecords FROM temp_table".$survey_id." WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY category  HAVING category IS NOT null AND category!=''; ";
		$getPieDatas = mysqli_query($conn,$pieSql);
		$final_pie_charts="";
		$colors = [];
		while($pieData = mysqli_fetch_object($getPieDatas)){
			$colors[] = sscolor();
			$category = clean($pieData->category);
			$totRecords = $pieData->totRecords;
			$totalRecords[]=$totRecords;
			$final_pie_charts.="{ 'name': '".$category."', 'y': ".$totRecords." },";
		}
		
		$final_pie_charts = substr($final_pie_charts,0,-1);
		$series_final_pie_charts='[{ "name": "Total", "data": ['.$final_pie_charts.'] }]';
		$title="(N=".array_sum($totalRecords).")";
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		$cOptions = basicPieChart($chartId,$title,$series_final_pie_charts,$clrs,$others);	
		return $resArr = array(
			'categories' => '',
			'title' => $title,
			'dataseries' =>$series_final_pie_charts,
			'chartOptions' =>$cOptions
		);
	}
	
	
	/*================================================================================================*/
											///CREATE STACK CHARTS
	/*================================================================================================*/
	if($chartType=="basicClumnAdvance" || $chartType=="stackClumnAdvance" || $chartType=="stackClumnAdvance100"){ 
		$graphPlotOptions='';
		if($chartType=="stackClumnAdvance"){
			$graphPlotOptions="normal";
		}
		if($chartType=="stackClumnAdvance100"){
			$graphPlotOptions="percent";
		}
		
		$category=$othercategory=$advtotRecords=[];
		// $graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status in(1,6) GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null AND Maincategory!='' AND othercategory!='' ";
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM temp_table".$survey_id." WHERE survey_name_id='".$survey_id."' and survey_status in(1,6) GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		//$allRows = mysqli_fetch_all($getPieDatas, MYSQLI_ASSOC);
		$allRows=[];
		while($pdataObj=mysqli_fetch_object($getPieDatas)){
			$alldata['Maincategory'] = clean($pdataObj->Maincategory);
			$alldata['othercategory'] = clean($pdataObj->othercategory);
			$alldata['totRecords'] = $pdataObj->totRecords;
			$allRows[] = $alldata;
		}
		
		//print_r($allRows);
		foreach($allRows  as $barData){
			$Maincategory = $barData['Maincategory'];
			$mothercategory = $barData['othercategory'];
			$totRecords = $barData['totRecords'];
			
			if($mainCategoryIsId){ $mainCate = $users[$Maincategory]; }else{ $mainCate = $Maincategory; }
			if($isId){ $oterCate = $users[$mothercategory]; }else{ $oterCate = $mothercategory; }
			$category[] = $mainCate;
			$othercategory[] = $oterCate;
			$advtotRecords[] = $totRecords;
		}
		
		$serdd="";
		$colors = [];
		$uniqMaincategorys = array_unique($category);
		$uniqOthercategorys = array_unique($othercategory);
		$title="(N=".array_sum($advtotRecords).")";
		foreach($uniqMaincategorys as $uniqMaincategory){
			$colors[] = sscolor();
			$serdd.="{ 'name': '".$uniqMaincategory."',  'data': [ ";
			foreach($uniqOthercategorys as $uniqOthercategory){
				$search = array("Maincategory"=>$uniqMaincategory,"othercategory"=>$uniqOthercategory);
				$output = findData($allRows, $search);
				$serdd.= $output.",";
			}
			$serdd = substr($serdd,0,-1);
			$serdd.="] },";
		}
		$serdd = substr($serdd,0,-1);
		$seriesdata="[".$serdd."]";
		$clrs = implode("','",$colors);
		// if(count($legendColors)>0){ 
			// $clrs = implode("','",$legendColors);
			// $colors = $legendColors; 
		// }
		
		$categories = "['".implode("','",$uniqOthercategorys)."'],";
		
		$cOptions = stackColumnCharts($chartId,$title, $categories, $seriesdata, $graphPlotOptions, $cType, $clrs, $others);
		return $resArr = array(
			'title' => $title,
			'categories' => $categories,
			'dataseries' =>$seriesdata,
			'chartOptions' =>$cOptions
		);
	}
	
	
	/*================================================================================================*/
											///BUBBLE CHART
	/*================================================================================================*/
	if($chartType=="splitBubbleAdvance"){
		
		$category=$othercategory=$advtotRecords=[];
		// $graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM temp_table".$survey_id." WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		
		$allRows=[];
		while($pdataObj=mysqli_fetch_object($getPieDatas)){
			$alldata['Maincategory'] = clean($pdataObj->Maincategory);
			$alldata['othercategory'] = clean($pdataObj->othercategory);
			$alldata['totRecords'] = $pdataObj->totRecords;
			$allRows[] = $alldata;
		}
		
		foreach($allRows  as $barData){
			$Maincategory = $barData['Maincategory'];
			$mothercategory = $barData['othercategory'];
			$totRecords = $barData['totRecords'];
			if($mainCategoryIsId){ $mainCate = $users[$Maincategory]; }else{ $mainCate = $Maincategory; }
			if($isId){ $oterCate = $users[$mothercategory]; }else{ $oterCate = $mothercategory; }
			$category[] = $mainCate;
			$othercategory[] = $oterCate;
			$advtotRecords[] = $totRecords;
		}
		
		$serdd="";
		$colors = [];
		$uniqMaincategorys = array_unique($category);
		$uniqOthercategorys = array_unique($othercategory);
		$title="(N=".array_sum($advtotRecords).")";
		foreach($uniqMaincategorys as $uniqMaincategory){
			$colors[] = sscolor();
			$serdd.="{ 'name': '".$uniqMaincategory."',  'data': [ ";
			foreach($uniqOthercategorys as $uniqOthercategory){
				$search = array("Maincategory"=>$uniqMaincategory,"othercategory"=>$uniqOthercategory);
				$output = findData($allRows, $search);
				//$serdd.= $output.",";
				$serdd.="{
				  name: '".$uniqOthercategory."',
				  value: ".$output."
				},";
			}
			$serdd = substr($serdd,0,-1);
			$serdd.="] },";
		}
		 
		$serdd = substr($serdd,0,-1);
		$series_serdd="[".$serdd."]";
		$clrs = implode("','",$colors);
		
		// if(count($legendColors)>0){ 
			// $clrs = implode("','",$legendColors);
			// $colors = $legendColors; 
		// }
		
		//$categories = "['".implode("','",$uniqOthercategorys)."'],";
		
		/*
		while($barData = mysqli_fetch_object($getPieDatas)){
			$category[] = clean($barData->Maincategory);
			$othercategory[] = clean($barData->othercategory);
			$advtotRecords[] = $barData->totRecords;
		} 
		$uniqcategory = array_unique($category);
		$totalOthCate = count($othercategory);
		$totalMainCate  = count($uniqcategory);
		$nofgroup = $totalOthCate/$totalMainCate;
		foreach($uniqcategory as $uniqcate){
			$uniqcateArr[] = $uniqcate;
		}
		$grpcategory = array_unique($othercategory);
		$categories = implode("','",$grpcategory);
		//array_chunk($othercategory,$totalMainCate);
		$chunksArr = array_chunk($advtotRecords,$nofgroup);
		$serdd="";
		$colors = [];
		foreach($chunksArr as $keys=>$othcategory){
			$colors[] = sscolor();
			$serdd.="{
				name: '".$uniqcateArr[$keys]."', 
				data: [
				";
			foreach($othcategory as $okey=>$othcate){
					//$serdd.= $othcate.",";
				$serdd.="{
				  name: '".$grpcategory[$okey]."',
				  value: ".$othcate."
				},";
			}
			$serdd.="] },";
		}
		$serdd = substr($serdd,0,-1);
		$series_serdd = "[$serdd]";
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		
		*/
		
		$cOptions = bubbleChart($chartId,$title,$series_serdd,$clrs,$others);
		return $resArr = array(
			'categories' => '',
			'title' => '',
			'dataseries' =>$series_serdd,
			'chartOptions' =>$cOptions
		);
	}
	
	
	
	/*================================================================================================*/
											///RADIAL CHART
	/*================================================================================================*/
	if($chartType=="RadialAdvance"){
		$category=$othercategory=$advtotRecords=[];
		// $graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM temp_table".$survey_id." WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		
		$allRows=[];
		while($pdataObj=mysqli_fetch_object($getPieDatas)){
			$alldata['Maincategory'] = clean($pdataObj->Maincategory);
			$alldata['othercategory'] = clean($pdataObj->othercategory);
			$alldata['totRecords'] = $pdataObj->totRecords;
			$allRows[] = $alldata;
		}
		
		//print_r($allRows);
		foreach($allRows  as $barData){
			$Maincategory = $barData['Maincategory'];
			$mothercategory = $barData['othercategory'];
			$totRecords = $barData['totRecords'];
			if($mainCategoryIsId){ $mainCate = $users[$Maincategory]; }else{ $mainCate = $Maincategory; }
			if($isId){ $oterCate = $users[$mothercategory]; }else{ $oterCate = $mothercategory; }
			$category[] = $mainCate;
			$othercategory[] = $oterCate;
			$advtotRecords[] = $totRecords;
		}
		
		$serdd="";
		$colors = [];
		$uniqMaincategorys = array_unique($category);
		$uniqOthercategorys = array_unique($othercategory);
		$title="(N=".array_sum($advtotRecords).")";
		foreach($uniqMaincategorys as $uniqMaincategory){
			$colors[] = sscolor();
			$serdd.="{ 'name': '".$uniqMaincategory."',  'data': [ ";
			foreach($uniqOthercategorys as $uniqOthercategory){
				$search = array("Maincategory"=>$uniqMaincategory,"othercategory"=>$uniqOthercategory);
				$output = findData($allRows, $search);
				$serdd.= $output.",";
			}
			$serdd = substr($serdd,0,-1);
			$serdd.="] },";
		}
		$serdd = substr($serdd,0,-1);
		$series_serdd="[".$serdd."]";
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		
		$categories = "['".implode("','",$uniqOthercategorys)."'],";
		
		
		/*
		while($barData = mysqli_fetch_object($getPieDatas)){
			$category[] = clean($barData->Maincategory);
			$othercategory[] = clean($barData->othercategory);
			$advtotRecords[] = $barData->totRecords;
		}
		$uniqcategory = array_unique($category);
		$totalOthCate = count($othercategory);
		$totalMainCate  = count($uniqcategory);
		$nofgroup = $totalOthCate/$totalMainCate;
		
		foreach($uniqcategory as $uniqcate){
			$uniqcateArr[] = $uniqcate;
		}
		
		$grpcategory = array_unique($othercategory);
		//$categories = implode("','",$grpcategory);
		$categories = "['".implode("','",$grpcategory)."'],";
		//array_chunk($othercategory,$totalMainCate);
		$chunksArr = array_chunk($advtotRecords,$nofgroup);
		$serdd="";
		$colors = [];
		foreach($chunksArr as $keys=>$othcategory){
			$colors[] = sscolor();
			$serdd.="{
				name: '".$uniqcateArr[$keys]."', 
				data: [
				";
			foreach($othcategory as $othcate){
					$serdd.= $othcate.",";
			}
			$serdd.="] },";
		}
		$serdd = substr($serdd,0,-1);
		$series_serdd = "[$serdd]";
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		*/
		
		$cOptions = radialChart($chartId,$title,$cType,$categories,$series_serdd,$clrs, $others);
		return $resArr = array(
			'categories' =>$categories,
			'title' =>$title,
			'dataseries' =>$series_serdd,
			'chartOptions' =>$cOptions
		);
	}
	
	
	/*================================================================================================*/
											///SPIDER CHART
	/*================================================================================================*/
	if($chartType=="spiderAdvance"){
		$category=$othercategory=$advtotRecords=[];
		// $graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory  HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM temp_table".$survey_id." WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory  HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		
		$allRows=[];
		while($pdataObj=mysqli_fetch_object($getPieDatas)){
			$alldata['Maincategory'] = clean($pdataObj->Maincategory);
			$alldata['othercategory'] = clean($pdataObj->othercategory);
			$alldata['totRecords'] = $pdataObj->totRecords;
			$allRows[] = $alldata;
		}
		
		//print_r($allRows);
		foreach($allRows  as $barData){
			$Maincategory = $barData['Maincategory'];
			$mothercategory = $barData['othercategory'];
			$totRecords = $barData['totRecords'];
			if($mainCategoryIsId){ $mainCate = $users[$Maincategory]; }else{ $mainCate = $Maincategory; }
			if($isId){ $oterCate = $users[$mothercategory]; }else{ $oterCate = $mothercategory; }
			$category[] = $mainCate;
			$othercategory[] = $oterCate;
			$advtotRecords[] = $totRecords;
		}
		
		$serdd="";
		$colors = [];
		$uniqMaincategorys = array_unique($category);
		$uniqOthercategorys = array_unique($othercategory);
		$title="(N=".array_sum($advtotRecords).")";
		foreach($uniqMaincategorys as $uniqMaincategory){
			$colors[] = sscolor();
			$serdd.="{ 'name': '".$uniqMaincategory."',  'data': [ ";
			foreach($uniqOthercategorys as $uniqOthercategory){
				$search = array("Maincategory"=>$uniqMaincategory,"othercategory"=>$uniqOthercategory);
				$output = findData($allRows, $search);
				$serdd.= $output.",";
			}
			$serdd = substr($serdd,0,-1);
			$serdd.="] },";
		}
		$serdd = substr($serdd,0,-1);
		$seriesdata_serdd="[".$serdd."]";
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		
		$categories = "['".implode("','",$uniqOthercategorys)."'],";
		
		/*
		while($barData = mysqli_fetch_object($getPieDatas)){
			$category[] = clean($barData->Maincategory);
			$othercategory[] = clean($barData->othercategory);
			$advtotRecords[] = $barData->totRecords;
		}
		$uniqcategory = array_unique($category);
		$totalOthCate = count($othercategory);
		$totalMainCate  = count($uniqcategory);
		$nofgroup = $totalOthCate/$totalMainCate;
		
		foreach($uniqcategory as $uniqcate){
			$uniqcateArr[] = $uniqcate;
		}
		
		$grpcategory = array_unique($othercategory);
		//$categories = implode("','",$grpcategory);
		$categories = "['".implode("','",$grpcategory)."'],";
		//array_chunk($othercategory,$totalMainCate);
		$chunksArr = array_chunk($advtotRecords,$nofgroup);
		$serdd="";
		$colors = [];
		foreach($chunksArr as $keys=>$othcategory){
			$colors[] = sscolor();
			$serdd.="{
				name: '".$uniqcateArr[$keys]."', 
				data: [
				";
			foreach($othcategory as $othcate){
					$serdd.= $othcate.",";
			}
			$serdd.="] },";
		}
		
		$serdd = substr($serdd,0,-1);
		$seriesdata_serdd="[$serdd]";
		
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		*/
		
		$cOptions = spiderChart($chartId, $title, $cType, $categories, $seriesdata_serdd, $clrs, $others);
		return $resArr = array(
			'categories' =>$categories,
			'title' =>$title,
			'dataseries' =>$seriesdata_serdd,
			'chartOptions' =>$cOptions
		);
	}
	
	
	/*================================================================================================*/
											///CREATE TABLE
	/*================================================================================================*/
	if($chartType=="tableAdvance"){
		
		$category=$othercategory=$advtotRecords=[];
		// $graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM temp_table".$survey_id." WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		while($barData = mysqli_fetch_object($getPieDatas)){
			$category[] = clean($barData->Maincategory);
			$othercategory[] = clean($barData->othercategory);
			$advtotRecords[] = $barData->totRecords;
		}
		$uniqcategory = array_unique($category);
		$totalOthCate = count($othercategory);
		$totalMainCate  = count($uniqcategory);
		$nofgroup = $totalOthCate/$totalMainCate;
		
		
		foreach($uniqcategory as $uniqcate){
			$uniqcateArr[] = $uniqcate;
		}
		
		$grpcategory = array_unique($othercategory);
		//$categories = implode("','",$grpcategory);
		//array_chunk($othercategory,$totalMainCate);
		$chunksArr = array_chunk($advtotRecords,$nofgroup);
		
		?>
		
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th> </th>
						<?php foreach($grpcategory as $grpcate){ ?>  <th><?=$grpcate;?></th> <?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach($uniqcateArr as $sk=>$uniqcateVal){ ?> 
						<tr>
							<td><?=$uniqcateVal;?></td>
							<?php foreach($grpcategory as $ssk=>$grpcate){ ?>  <td><?=$chunksArr[$sk][$ssk];?></td> <?php } ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php				
	}
	
	
	/*================================================================================================*/
											///BAR LINE COLUMN AREA SINGLE LINE CHART
	/*================================================================================================*/
	if($chartType=="basicbar" || $chartType=="basicline" || $chartType=="basiccolumn" || $chartType=="basicarea"){ 
	
		if($chartType==="basicbar"){ $ctype="bar"; }else if($chartType==="basicline"){ $ctype="line"; }else if($chartType==="basiccolumn"){ $ctype="column"; }else if($chartType==="basicarea"){ $ctype="area"; }
		// $pieSql="SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) as category,COUNT(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status in(1,6) GROUP BY category  HAVING category IS NOT null AND category!='';";
		$pieSql="SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) as category,COUNT(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) AS totRecords FROM temp_table".$survey_id." WHERE survey_name_id='".$survey_id."' and survey_status in(1,6) GROUP BY category  HAVING category IS NOT null AND category!='';";
		$getPieDatas = mysqli_query($conn,$pieSql);
		$final_bar_charts="";
		$colors = $totalRecords=[];
		while($pieData = mysqli_fetch_object($getPieDatas)){
			//print_r($pieData);
			$colors[] = sscolor();
			$category = clean($pieData->category);
			$totRecords = $pieData->totRecords;
			$totalRecords[]=$totRecords;
			if($isId){ $oterCate = $users[$category]; }else{ $oterCate = $category; }
			$final_bar_charts.="['".$oterCate."', ".$totRecords."],";
		}
		$final_bar_charts = substr($final_bar_charts,0,-1);
		$final_bar_charts_series = '[{ "name": "Total", "data": ['.$final_bar_charts.']}]';
		
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		$title="(N=".array_sum($totalRecords).")";
		//echo '<input type="hidden" id="glegend_color" value="'.implode(",", $colors).'" />';
		//echo '<input type="hidden" id="glegend_lbl" value="'.implode(",", $uniqcateArr).'" />';
		$cOptions = basicChart($chartId, $title, $ctype, $final_bar_charts_series, $clrs, $others);
		return $resArr = array(
			'categories' => '',
			'title' => $title,
			'dataseries' =>$final_bar_charts_series,
			'chartOptions' =>$cOptions
		);
	}
}
?>