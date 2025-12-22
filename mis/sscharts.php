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
function stackColumnCharts($chartId, $categories, $seriesData, $graphPlotOptions, $chartType, $colors,$others=[]){
	//$xAxisTitle="SSS";
	if(count($others)>0){ $xAxisTitle=$others['xAxisTitle']; $yAxisTitle=$others['yAxisTitle']; }
	?>
	<div id="<?=$chartId;?>" style="height:400px" ></div>
	<script type="text/javascript">
		Highcharts.chart('<?=$chartId?>', {
			colors:  ['<?=$colors;?>'],
			chart: {
				//type: 'column' //spline, line, bar, column, area
				type: '<?=$chartType?>', 
			},
			title: {
				text: ''
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				categories: <?=$categories;?>,
				crosshair: true,
				title: {
					text: '<?=$xAxisTitle;?>'
				},
				labels: {
						rotation: -45,
						style: {
							fontSize: '13px',
							fontFamily: 'Verdana, sans-serif'
						}
					}
			},
			yAxis: {
				min: 0,
				title: {
					text: '<?=$yAxisTitle;?>'
				}
			},
			plotOptions: {
				dataLabels: {
                  enabled: false
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},

			<?=$graphPlotOptions;?>,
			series: <?=$seriesData;?>
		});
	</script>
	<?php
}


/*
	========================================================================================================================
	Bubble Chart
	========================================================================================================================
*/

function bubbleChart($chartId,$title,$seriesData,$colors,$others=[]){
	?>
	<div id="<?=$chartId;?>" style="height:400px" ></div>
		<script>
			Highcharts.chart('<?=$chartId?>', {
				colors: ['<?=$colors;?>'],
				chart: {
					type: 'packedbubble',
					//height: '100%'
				},
				title: {
					text: '<?=$title?>',
					align: 'left'
				},
				tooltip: {
					useHTML: true,
					pointFormat: '<b>{point.name}:</b> {point.value}'
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
					}
				},
				series: [<?=$seriesData;?>]
			});
		</script>
	<?php
}


/*
	========================================================================================================================
	Bubble Chart
	========================================================================================================================
*/
function radialChart($chartId, $title, $cType, $categories, $seriesData, $colors,$others=[]){
	?>
		<div id="<?=$chartId;?>" style="height:400px" ></div>
		<script>
			Highcharts.chart('<?=$chartId;?>', {
				colors: ['<?=$colors;?>'],
				chart: {
					type: '<?=$cType;?>', //bar,column
					inverted: true,
					polar: true
				},
				title: {
					text: '<?=$title;?>',
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
					categories: ['<?=$categories;?>']
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
				plotOptions: {
					column: {
						stacking: 'normal',
						borderWidth: 0,
						pointPadding: 0,
						groupPadding: 0.15
					}
				},
				series: [<?=$seriesData;?>]
			});
		</script>
	<?php
}




/*
	========================================================================================================================
	Spider Chart
	========================================================================================================================
*/
function spiderChart($chartId, $title, $cType, $categories, $seriesData, $colors,$others=[]){
	?>
		<div id="<?=$chartId;?>" style="height:400px" ></div>
		<script>
			Highcharts.chart('<?=$chartId;?>', {
				colors: ['<?=$colors;?>'],
				chart: {
					polar: true,
					type: '<?=$cType;?>' ///line, spline, column, bar, area
				},
				accessibility: {
					description: ' '
				},
				title: {
					text: '<?=$title;?>',
					x: -80
				},
				pane: {
					size: '80%'
				},
				xAxis: {
					categories: ['<?=$categories;?>'],
					tickmarkPlacement: 'on',
					lineWidth: 0
				},
				yAxis: {
					gridLineInterpolation: 'polygon',
					lineWidth: 0,
					min: 0
				},
				tooltip: {
					shared: true,
					pointFormat: '<span style="color:{series.color}">{series.name}: <b> {point.y:,.0f}</b><br/>'
				},
				legend: {
					align: 'right',
					verticalAlign: 'middle',
					layout: 'vertical'
				},
				series: [<?=$seriesData;?>],
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
			});
		</script>
	<?php
}



/*
	========================================================================================================================
	Basic Chart
	========================================================================================================================
*/
function basicChart($chartId, $title, $ctype, $seriesData, $colors,$others=[]){
	?>
		<div id="<?=$chartId;?>" style="height:400px" ></div>
		<script>
			Highcharts.chart('<?=$chartId?>', {
				colors: ['<?=$colors;?>'],
				chart: {
					type: '<?=$ctype;?>' // line, bar, column, area
				},
				title: {
					text: '<?=$title;?>'
				},
				subtitle: {
					text: ''
				},
				xAxis: {
					type: 'category',
					labels: {
						rotation: -45,
						style: {
							fontSize: '13px',
							fontFamily: 'Verdana, sans-serif'
						}
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
				series: [{
					name: 'Total',
					data: [ <?=$seriesData;?> ],
					dataLabels: {
						enabled: true,
						//rotation: -90,
						color: '#FFFFFF',
						align: 'center',
						format: '{point.y:.0f}', // one decimal
						y: 10, // 10 pixels down from the top
						style: {
							fontSize: '13px',
							fontFamily: 'Verdana, sans-serif'
						}
					}
				}]
			});
		</script>
	<?php
}


/*
	========================================================================================================================
	Basic Pie Chart
	========================================================================================================================
*/

function basicPieChart($chartId,$title,$seriesData,$colors,$others=[]){
	?>
		<div id="<?=$chartId;?>" style="height:400px" ></div>
		<script>
		Highcharts.chart('<?=$chartId;?>', {
			colors: ['<?=$colors;?>'],
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: '<?=$title?>'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.y} ({point.percentage:.0f}%)</b>'
			},
			accessibility: {
				point: {
					valueSuffix: ''
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: true
				}
			},
			series: [{
				name: 'Total',
				colorByPoint: true,
				data: [<?=$seriesData;?> ]
			}]
		});
		</script>
	<?php
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
function dynamic_graph($conn, $survey_id, $chart_name, $chart_type, $colors, $field_name, $groupby="", $chartId="",$others=[]){
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
	
	//print_r($users);
	
	if(empty($chartId)){ $chartId = "ss".sscolor(); }
	/// CREATE PIE CHART
	if($chartType=="pie"){ 
		$pieSql="SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) as category,COUNT(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY category  HAVING category IS NOT null AND category!=''; ";
		$getPieDatas = mysqli_query($conn,$pieSql);
		$final_pie_charts="";
		$colors = [];
		
		while($pieData = mysqli_fetch_object($getPieDatas)){
			$colors[] = sscolor();
			if($isId){ $c = $users[$pieData->category]; }else{ $c = $pieData->category; }
			$category = $c;
			$totRecords = $pieData->totRecords;
			$final_pie_charts.="{ name: '".$category."', y: ".$totRecords." },";
		}
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		basicPieChart($chartId,$title,$final_pie_charts,$clrs,$others);	
	}
	
	
	/*================================================================================================*/
											///CREATE STACK CHARTS
	/*================================================================================================*/
	if($chartType=="basicClumnAdvance" || $chartType=="stackClumnAdvance" || $chartType=="stackClumnAdvance100"){ 
		$graphPlotOptions='plotOptions: { column: { pointPadding: 0.2, borderWidth: 0 } }';
		if($chartType=="stackClumnAdvance"){
			$graphPlotOptions="plotOptions: {
				column: {
				  stacking: 'normal',
				  dataLabels: {
					enabled: true
				  }
				}
			  }";
		}
		if($chartType=="stackClumnAdvance100"){
			$graphPlotOptions="plotOptions: {
				column: {
				  stacking: 'percent',
				  dataLabels: {
					enabled: true
				  }
				}
			  }";
		}
		
		$category=$othercategory=$advtotRecords=[];
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		while($barData = mysqli_fetch_object($getPieDatas)){
			if($mainCategoryIsId){ $mainCate = $users[$barData->Maincategory]; }else{ $mainCate = $barData->Maincategory; }
			if($isId){ $oterCate = $users[$barData->othercategory]; }else{ $oterCate = $barData->othercategory; }
			$category[] = $mainCate;
			$othercategory[] = $oterCate;
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
		$categories = "['".implode("','",$grpcategory)."']";
		//array_chunk($othercategory,$totalMainCate);
		$chunksArr = array_chunk($advtotRecords,$nofgroup);
		$serdd="";
		$colors = [];
		foreach($chunksArr as $keys=>$othcategory){
			$colors[] = sscolor();
			$serdd.="{ name: '".$uniqcateArr[$keys]."',  data: [ ";
			foreach($othcategory as $othcate){
				$serdd.= $othcate.",";
			}
			$serdd.="] },";
		}
		$seriesdata="[".$serdd."]";
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		echo '<input type="hidden" id="glegend_color" value="'.implode(",", $colors).'" />';
		echo '<input type="hidden" id="glegend_lbl" value="'.implode(",", $uniqcateArr).'" />';
		stackColumnCharts($chartId, $categories, $seriesdata, $graphPlotOptions, $cType, $clrs, $others);
	}
	
	
	/*================================================================================================*/
											///BUBBLE CHART
	/*================================================================================================*/
	if($chartType=="splitBubbleAdvance"){
		
		$category=$othercategory=$advtotRecords=[];
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		while($barData = mysqli_fetch_object($getPieDatas)){
			$category[] = $barData->Maincategory;
			$othercategory[] = $barData->othercategory;
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
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		echo '<input type="hidden" id="glegend_color" value="'.implode(",", $colors).'" />';
		echo '<input type="hidden" id="glegend_lbl" value="'.implode(",", $uniqcateArr).'" />';
		bubbleChart($chartId,$title,$serdd,$clrs,$others);
	}
	
	
	
	/*================================================================================================*/
											///RADIAL CHART
	/*================================================================================================*/
	if($chartType=="RadialAdvance"){
		$category=$othercategory=$advtotRecords=[];
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		while($barData = mysqli_fetch_object($getPieDatas)){
			$category[] = $barData->Maincategory;
			$othercategory[] = $barData->othercategory;
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
			foreach($othcategory as $othcate){
					$serdd.= $othcate.",";
			}
			$serdd.="] },";
		}
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		echo '<input type="hidden" id="glegend_color" value="'.implode(",", $colors).'" />';
		echo '<input type="hidden" id="glegend_lbl" value="'.implode(",", $uniqcateArr).'" />';
		radialChart($chartId,$title,$cType,$categories,$serdd,$clrs, $others);
	}
	
	
	/*================================================================================================*/
											///SPIDER CHART
	/*================================================================================================*/
	if($chartType=="spiderAdvance"){
		$category=$othercategory=$advtotRecords=[];
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory  HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		while($barData = mysqli_fetch_object($getPieDatas)){
			$category[] = $barData->Maincategory;
			$othercategory[] = $barData->othercategory;
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
			foreach($othcategory as $othcate){
					$serdd.= $othcate.",";
			}
			$serdd.="] },";
		}
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		echo '<input type="hidden" id="glegend_color" value="'.implode(",", $colors).'" />';
		echo '<input type="hidden" id="glegend_lbl" value="'.implode(",", $uniqcateArr).'" />';
		spiderChart($chartId, $title, $cType, $categories, $serdd, $clrs, $others);
	}
	
	
	/*================================================================================================*/
											///CREATE TABLE
	/*================================================================================================*/
	if($chartType=="tableAdvance"){
		
		$category=$othercategory=$advtotRecords=[];
		$graphSql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$gquestionFName."')) as Maincategory,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) as othercategory,COUNT(JSON_EXTRACT(survey_data_json, '$.".$questionFName."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY Maincategory, othercategory HAVING Maincategory IS NOT null  AND Maincategory!='' AND othercategory!='' ";
		$getPieDatas = mysqli_query($conn,$graphSql);
		while($barData = mysqli_fetch_object($getPieDatas)){
			$category[] = $barData->Maincategory;
			$othercategory[] = $barData->othercategory;
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
		$pieSql="SELECT JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) as category,COUNT(JSON_EXTRACT(survey_data_json, '$.".$field_name."')) AS totRecords FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."' and survey_status!='7' GROUP BY category  HAVING category IS NOT null AND category!='';";
		$getPieDatas = mysqli_query($conn,$pieSql);
		$final_bar_charts="";
		$colors = [];
		while($pieData = mysqli_fetch_object($getPieDatas)){
			$colors[] = sscolor();
			if($isId){ $c = $users[$pieData->category]; }else{ $c = $pieData->category; }
			$category = $c;
			$totRecords = $pieData->totRecords;
			$final_bar_charts.="['".$category."', ".$totRecords."],";
		}
		$clrs = implode("','",$colors);
		if(count($legendColors)>0){ 
			$clrs = implode("','",$legendColors);
			$colors = $legendColors; 
		}
		echo '<input type="hidden" id="glegend_color" value="'.implode(",", $colors).'" />';
		echo '<input type="hidden" id="glegend_lbl" value="'.implode(",", $uniqcateArr).'" />';
		basicChart($chartId, $title, $ctype, $final_bar_charts, $clrs, $others);
	}
}
?>