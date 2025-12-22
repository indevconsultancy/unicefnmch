<?php require_once "PHPMailer/PHPMailer/PHPMailerAutoload.php"; ?>
<?php 
function login($post,$conn)
{

    $message=array();
    $user_name=clear_malicious_data($post['username'],$conn);
    $password=md5(clear_malicious_data($post['password'],$conn));
    $get_authentication_query="SELECT `username`,`user_id`, `client_id`, `role_id`,`registered_as` FROM `users` WHERE  binary username='$user_name' and  password='$password'and  role_id in (1,2,3,4,7,9) and status='0'";
	$authentication_query=mysqli_query($conn,$get_authentication_query)or die(mysqli_error());
    if(mysqli_num_rows($authentication_query)>0){
      $login_data=mysqli_fetch_array($authentication_query,MYSQLI_ASSOC);
	  $user_id=$_SESSION['user_id']=$login_data['user_id'];
      $client_id=$_SESSION['client_id']=$login_data['client_id'];
	  $_SESSION['registered_as']=$login_data['registered_as'];
	
      $user_id = $_SESSION['role_id']=$login_data['role_id'];
      $_SESSION['username'] = $login_data['username'];
	  
	  $getControlRole = mysqli_query($conn,"SELECT GROUP_CONCAT(DISTINCT(role_id)) AS role_id FROM functional_role WHERE user_id='".$_SESSION['user_id']."' ");
      $controlRole = mysqli_fetch_object($getControlRole);
      $userControlRole =  $controlRole->role_id;

      $getPageButtons = mysqli_query($conn,"SELECT GROUP_CONCAT(page_button_id) AS page_button_id  FROM page_control  WHERE role_id IN($userControlRole) AND status='0' ");
      $pageButtons = mysqli_fetch_object($getPageButtons);
      $pagebuttonid = $pageButtons->page_button_id;
      $_SESSION['page_button_id'] = $pagebuttonid;
	  
      $message=array("status"=>1,"message"=>'Successfully login',"login_status"=>$user_id);
    }

    else

    {
	 
      $message=array("status"=>0,"message"=>'Username and Password are incorrect');

    }

    return $message;

}



function clear_malicious_data($user_data,$conn){

    $sanitize_input=trim($user_data);

    $sanitize_input=strip_tags($sanitize_input);

    $sanitize_input=mysqli_real_escape_string($conn,$sanitize_input);

    return $sanitize_input;

}
function getcount($conn,$tablename,$field,$qryfield,$value,$qryfield1,$value1){
	//echo "select count($field) as total from $tablename where $qryfield='".$value."' and $qryfield1='".$value1."'";
	
	$sssn=mysqli_query($conn,"select count($field) as total from $tablename where $qryfield='".$value."',$qryfield1='".$value1."'");
	$dn1=mysqli_fetch_object($sssn);
	
	
	return ($dn1->$field);
    
}
 function paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url)  {
        // echo $current_page."/".$total_pages;
      
        $pagination = '';
        
        $page_url.="&";
        
        if($total_pages > 0 && $current_page <= $total_pages) { //verify total pages and current page number
            $pagination .= '<h5 class="m-0">Total Pages: '.$total_pages.'</h5>'.
            '<ul class="pagination m-0 ml-2">';
            
            $right_links    = $current_page + 3;
            $previous       = $current_page - 1; //previous link
            $next           = $current_page + 1; //next link
            $first_link     = true; //boolean var to decide our first link
            if($current_page > 1) {
                $previous_link = ($previous==0)?1:$previous;
                $pagination .= '<li class="page-item prev"><a class="page-link" href="'.$page_url.'page=1" title="First"><i class="fa fa-angle-double-left"></i></a></li>'; //first link
                $pagination .= '<li class="page-item" ><a class="page-link" href="'.$page_url.'page='.$previous_link.'" title="Previous"><i class="fa fa-angle-left"></i></a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'page='.$i.'">'.$i.'</a></li>';
                    }
                }
                $first_link = false; //set first link to false
            }
            if($first_link){ //if current active page is first link
                $pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'page='.$current_page.'">'.$current_page.'</a></li>';
            } elseif($current_page == $total_pages){ //if it's the last active link
              $pagination .= '<li class="page-item last active"><a class="page-link" href="'.$page_url.'page='.$current_page.'">'.$current_page.'</a></li>';
            }else{ //regular current link
              $pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'page='.$current_page.'">'.$current_page.'</a></li>';
            }
            for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
                if($i<=$total_pages){
                    $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'page='.$i.'">'.$i.'</a></li>';
                }
            }
            if($current_page < $total_pages){
                $next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'page='.$next_link.'" ><i class="fa fa-angle-right"></i></a></li>'; //next link
                $pagination .= '<li class="page-item next"><a class="page-link" href="'.$page_url.'page='.$total_pages.'" title="Last"><i class="fa fa-angle-double-right"></i></a></li>'; //last link
            }
            $pagination .= '</ul>';
        }
        return $pagination; 
        //return pagination links
    }
	///////////////////Notification send///////////////////////
	function sendNotification($firebase_token,$activitis,$message,$json)
    {
        $activitis = str_replace(" ","%20",$activitis);
        $message = str_replace(" ","%20",$message);
        $json = str_replace(" ","%20",$json);
        
        $path="https://mquad.icpl.tech/firebase/?regId=$firebase_token&title=$activitis&message=$message&json=$json&push_type=individual";
        //$path="https://icpl.tech/jubifarm/firebase/?regId=$firebase_token&title=$activitis&message=$message&json=$json&push_type=individual";
        // $path = "https://www.google.com/";
        $send=file_get_contents($path);
        if($send)
        {
            return "success";
        }else{
            echo "failed";
        }
    }
	 
function pichartdesign($conn,$unique_id,$survey_id,$question_id,$field_function='',$filed_group_by='')
{  
   $data='';

   $sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $fields='$.'.$dataquestion->field_name;
   $chartid=$dataquestion->field_name.$unique_id;
   $fct='count(*)';
   $fldss="JSON_EXTRACT(survey_data_json, '".$fields."')";
   if($field_function=="sum") { $fct='sum('.$fldss.')'; }
   if($field_function=="avg") { $fct='avg('.$fldss.')'; }
   $filedgrpby='$.'.$filed_group_by;
   $groupby="JSON_EXTRACT(survey_data_json, '".$filedgrpby."')";
   $i=0;
 //  echo  "select $groupby as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category";
   
	$sqlquestionqry=mysqli_query($conn,"select $groupby as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category");
	while($rowdata=mysqli_fetch_object($sqlquestionqry))
	{
		if($i>0)
		{
			$data.=',';
		}
	$data.='{ 
        name: '.$rowdata->category.',
        y: '.$rowdata->total.'
      }';
	$i++; }
	$dataprint="<div id='".$chartid."' style='width:100%; height:400px;'></div>
	<script>
Highcharts.chart('".$chartid."', { colors: ['#29809b','#f39c12','#808080'],
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
	credits: {
    enabled: false
	},
    legend: {
              itemStyle: {
                fontSize:'10px',
                 fontFamily: 'Muli, sans-serif',
                 color: '#333333'
              },
              itemHoverStyle: {
                 color: '#333333',
                 fontWeight: 'bold'
              },
              itemHiddenStyle: {
                 color: '#444'
              }

        },
    title: {
        text: '".$dataquestion->question_name."'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
        // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                distance: -30,
                format: '{point.y}'
            },
            showInLegend: true
        },
        
    },
    series: [{
        name: 'Total',
        colorByPoint: true,
        data: [".$data."]
    }]
});

</script>";

return($dataprint);
}
function basic_line($conn,$unique_id,$survey_id,$question_id,$field_function='',$filed_group_by=''){ 
$data='';
   $sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $fields='$.'.$dataquestion->field_name;
   $chartid=$dataquestion->field_name.$unique_id;
   $fct='count(*)';
   $fldss="JSON_EXTRACT(survey_data_json, '".$fields."')";
   if($field_function=="sum") { $fct='sum('.$fldss.')'; }
   if($field_function=="avg") { $fct='avg('.$fldss.')'; }
   $filedgrpby='$.'.$filed_group_by;
   $groupby="JSON_EXTRACT(survey_data_json, '".$filedgrpby."')";
	  //echo  "select date(created_on) as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category";
	$sqlquestionqry=mysqli_query($conn,"select date(created_on) as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category order by date(created_on) asc");
	while($rowdata=mysqli_fetch_object($sqlquestionqry))
	 {
		 $total[]=$rowdata->total;
		 $category[]=$rowdata->category;
	 }
	 $datacat=implode("','",$category);
	 $totdata=implode(',',$total);
	$dataline="<div id='".$chartid."' style='width:100%; height:400px;'></div>
	<script>
	Highcharts.chart('".$chartid."', {
  chart: {
    type: 'spline'
  },
  title: {
    text: '".$dataquestion->question_name."'
  },
  subtitle: {
    text: ''
  },
  credits: {
    enabled: false
	},
  xAxis: {
	  
    categories: ['".$datacat."'],
    accessibility: {
      description: ''
    }
  },
  yAxis: {
	 
    title: {
      text: ''
    },
    labels: {
      formatter: function () {
        return this.value + '';
      }
    }
  },
  tooltip: {
    crosshairs: true,
    shared: true
  },
  plotOptions: {
    spline: {
      marker: {
        radius: 4,
        lineColor: '#666666',
        lineWidth: 1
      }
    }
  },
  series: [{
    name: 'Total',
    marker: {
      symbol: 'point'
    },
    data: [".$totdata."]

  }]
});
</script>";
return($dataline);
}
function drilldowndesign($conn,$survey_id,$question_id)
{  
   $data='';
   $sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $fields='$.'.$dataquestion->field_name;
   $chartid=$dataquestion->field_name;
	$dataprint='<div id="'.$chartid.'" style="width:100%; height:400px;"></div>
	<script>
Highcharts.chart("'.$chartid.'", {
  chart: {
    type: "column"
  },
  title: {
    text: "Browser market shares. January, 2018"
  },
  subtitle: {
    text: "Click the columns to view versions. Source: "
  },
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    type: "category"
  },
  yAxis: {
    title: {
      text: "Total percent market share"
    }

  },
  legend: {
    enabled: false
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: "{point.y:.1f}%"
      }
    }
  },

  

  series: [
    {
      name: "Browsers",
      colorByPoint: true,
      data: [
        {
          name: "Chrome",
          y: 62.74,
          drilldown: "Chrome"
        },
        {
          name: "Firefox",
          y: 10.57,
          drilldown: "Firefox"
        },
        {
          name: "Internet Explorer",
          y: 7.23,
          drilldown: "Internet Explorer"
        },
        {
          name: "Safari",
          y: 5.58,
          drilldown: "Safari"
        },
        {
          name: "Edge",
          y: 4.02,
          drilldown: "Edge"
        },
        {
          name: "Opera",
          y: 1.92,
          drilldown: "Opera"
        },
        {
          name: "Other",
          y: 7.62,
          drilldown: null
        }
      ]
    }
  ],
  drilldown: {
    series: [
      {
        name: "Chrome",
        id: "Chrome",
        data: [
          [
            "v65.0",
            0.1
          ],
          [
            "v64.0",
            1.3
          ],
          [
            "v63.0",
            53.02
          ],
          [
            "v62.0",
            1.4
          ],
          [
            "v61.0",
            0.88
          ],
          [
            "v60.0",
            0.56
          ],
          [
            "v59.0",
            0.45
          ],
          [
            "v58.0",
            0.49
          ],
          [
            "v57.0",
            0.32
          ],
          [
            "v56.0",
            0.29
          ],
          [
            "v55.0",
            0.79
          ],
          [
            "v54.0",
            0.18
          ],
          [
            "v51.0",
            0.13
          ],
          [
            "v49.0",
            2.16
          ],
          [
            "v48.0",
            0.13
          ],
          [
            "v47.0",
            0.11
          ],
          [
            "v43.0",
            0.17
          ],
          [
            "v29.0",
            0.26
          ]
        ]
      },
      {
        name: "Firefox",
        id: "Firefox",
        data: [
          [
            "v58.0",
            1.02
          ],
          [
            "v57.0",
            7.36
          ],
          [
            "v56.0",
            0.35
          ],
          [
            "v55.0",
            0.11
          ],
          [
            "v54.0",
            0.1
          ],
          [
            "v52.0",
            0.95
          ],
          [
            "v51.0",
            0.15
          ],
          [
            "v50.0",
            0.1
          ],
          [
            "v48.0",
            0.31
          ],
          [
            "v47.0",
            0.12
          ]
        ]
      },
      {
        name: "Internet Explorer",
        id: "Internet Explorer",
        data: [
          [
            "v11.0",
            6.2
          ],
          [
            "v10.0",
            0.29
          ],
          [
            "v9.0",
            0.27
          ],
          [
            "v8.0",
            0.47
          ]
        ]
      },
      {
        name: "Safari",
        id: "Safari",
        data: [
          [
            "v11.0",
            3.39
          ],
          [
            "v10.1",
            0.96
          ],
          [
            "v10.0",
            0.36
          ],
          [
            "v9.1",
            0.54
          ],
          [
            "v9.0",
            0.13
          ],
          [
            "v5.1",
            0.2
          ]
        ]
      },
      {
        name: "Edge",
        id: "Edge",
        data: [
          [
            "v16",
            2.6
          ],
          [
            "v15",
            0.92
          ],
          [
            "v14",
            0.4
          ],
          [
            "v13",
            0.1
          ]
        ]
      },
      {
        name: "Opera",
        id: "Opera",
        data: [
          [
            "v50.0",
            0.96
          ],
          [
            "v49.0",
            0.82
          ],
          [
            "v12.1",
            0.14
          ]
        ]
      }
    ]
  }
});
	</script>';

return($dataprint);
}


function dynamicdrilldowndesign($conn,$unique_id,$survey_id,$question_id,$field_function='',$filed_group_by='')
{  
 $data='';
   $sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $fields='$.'.$dataquestion->field_name;
   $chartid=$dataquestion->field_name;
   $fct='count(*)';
   $fldss="JSON_EXTRACT(survey_data_json, '".$fields."')";
   if($field_function=="sum") { $fct='sum('.$fldss.')'; }
   if($field_function=="avg") { $fct='avg('.$fldss.')'; }
   $filedgrpby='$.'.$filed_group_by;
   $groupby="JSON_EXTRACT(survey_data_json, '".$filedgrpby."')";
   $i=0;
   //echo "select $groupby as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category";
	$sqlquestionqry=mysqli_query($conn,"select $groupby as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category");

	while($rowdata=mysqli_fetch_object($sqlquestionqry))
	{
		if($i>0)
		{
			$data.=',';
			$subsubdata.=',';
		}
		$data.='{ 
			name: '.$rowdata->category.',
			y: '.$rowdata->total.',
			drilldown: '.$rowdata->category.'
		  }';
		$subsubdata.='{
			name: '.$rowdata->category.',
			id: '.$rowdata->category.',
			data: [';
			$i++;
			$n=0;
			//echo "select user_id as users, count(*) as total from survey_data_monitoring where survey_name_id='".$survey_id."' and $groupby=$rowdata->category group by user_id";
			$subsqlquestionqry=mysqli_query($conn,"select user_id as users, count(*) as total from survey_data_monitoring where survey_name_id='".$survey_id."' and $groupby=$rowdata->category group by user_id");
			while($subrowdata=mysqli_fetch_object($subsqlquestionqry))
			{
				$datauserids='user'.$subrowdata->users;
				$datausertotal=$subrowdata->total;
				if($n>0)
					{
						$subsubdata.=',';
					}
					$subsubdata.='[
						"'.$datauserids.'",
						'.$datausertotal.'
					  ]';
				$i++;
			}
				$subsubdata.=']
		}';
	}
	//print_r($data);
	$chartid="drill".$question_id;
	$dataprint='<div id="'.$chartid.'" style="width:100%; height:400px;"></div>
	<script>
Highcharts.chart("'.$chartid.'", { colors: ["#29809b","#f39c12","#808080"],
  chart: {
    type: "column"
  },
  title: {
    text: "'.$dataquestion->question_name.'"
  },
  subtitle: {
    text: ""
  },
  credits: {
    enabled: false
	},
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    type: "category"
  },
  yAxis: {
    title: {
      text: ""
    }

  },
  legend: {
    enabled: false
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: "{point.y}"
      }
    }
  },

  

  series: [
    {
      name: "'.$dataquestion->question_name.'",
      colorByPoint: true,
      data: ['.$data.']
    }
  ],
  drilldown: {
    series: ['.$subsubdata.']
  }
});
</script>';

return($dataprint);
}
function barchartdesign($conn,$unique_id,$survey_id,$question_id,$field_function='',$filed_group_by='')
{  
   $data='';
   $sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $fields='$.'.$dataquestion->field_name;
   $chartid=$dataquestion->field_name;
   $fct='count(*)';
   $fldss="JSON_EXTRACT(survey_data_json, '".$fields."')";
   if($field_function=="sum") { $fct='sum('.$fldss.')'; }
   if($field_function=="avg") { $fct='avg('.$fldss.')'; }
   $filedgrpby='$.'.$filed_group_by;
   $groupby="JSON_EXTRACT(survey_data_json, '".$filedgrpby."')";
   $i=0;
  // echo  "select $groupby as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category";
	$sqlquestionqry=mysqli_query($conn,"select $groupby as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category");
	while($rowdata=mysqli_fetch_object($sqlquestionqry))
	{
		if($i>0)
		{
			$data.=',';
		}
	$data.='{ 
        name: '.$rowdata->category.',
        y: '.$rowdata->total.'
      }';
	$i++; }
	$dataprint="<div id='bar".$chartid."' style='width:100%; height:400px;'></div>
	<script>
		Highcharts.chart('bar".$chartid."', {
    chart: {
        type: 'column'
    },
    title: {
        text: '".$dataquestion->question_name."'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
	credits: {
    enabled: false
	},
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: ''
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
			color:'#29809b',
            dataLabels: {
                enabled: true,
                format: '{point.y:.0f}'
            }
        }
    },
    tooltip: {
        headerFormat: '<span style=\"font-size:11px\">{series.name}</span><br>',
        pointFormat: '<span style=\"color:{point.color}\">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
    },

    series: [
        {
            name: 'Total',
            data: [ ".$data." ]
        }
    ]
});

</script>";

return($dataprint);
}
function basicarea($conn, $unique_id, $survey_id, $question_id, $field_function = '', $filed_group_by = ''){ 
$datarea = '';
  $sqlquestion = mysqli_query($conn, "select question_name,field_name from questions where survey_id='" . $survey_id . "' and field_name='" . $question_id . "'");
  $dataquestion = mysqli_fetch_object($sqlquestion);
  $fields = '$.' . $dataquestion->field_name;
  $chartid = $dataquestion->field_name . $unique_id;
  $fct = 'count(*)';
  $fldss = "JSON_EXTRACT(survey_data_json, '" . $fields . "')";
  if ($field_function == "sum") {
    $fct = 'sum(' . $fldss . ')';
  }
  if ($field_function == "avg") {
    $fct = 'avg(' . $fldss . ')';
  }
  $filedgrpby = '$.' . $filed_group_by;
  $groupby = "JSON_EXTRACT(survey_data_json, '" . $filedgrpby . "')";
  $sqlquestionqry = mysqli_query($conn, "select month(created_on) as category ,$fct as total from survey_data_monitoring where survey_name_id='" . $survey_id . "' group by category order by month(created_on) asc");
	$months = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
	while ($rowdata = mysqli_fetch_object($sqlquestionqry)) {
    $total[] = $rowdata->total;
    $categorys[] = $months[$rowdata->category];		
	}
    $datacat = implode("','", $categorys);
	$totaldata = implode(',', $total);
 	$datarea="<div id='".$chartid."' style='width:100%; height:400px;'></div>
	<script>
	Highcharts.chart('".$chartid."', {
    chart: {
        type: 'area'
    },
    title: {
        text: '".$dataquestion->question_name."'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: ['".$datacat."'],
        tickmarkPlacement: 'on',
        title: {
            enabled: false
        }
    },
	credits: {
    enabled: false
	},
    yAxis: {
        title: {
            text: ''
        },
        labels: {
            formatter: function () {
                return this.value ;
            }
        }
    },
    tooltip: {
        split: true,
        valueSuffix: ' '
    },
    plotOptions: {
        area: {
            stacking: 'normal',
            lineColor: '#666666',
            lineWidth: 1,
            marker: {
                lineWidth: 1,
                lineColor: '#666666'
            }
        }
    },
    series: [{
        name: 'Total',
		//data: [502, 635, 809, 947, 1402, 3634, 5268]
        data: [".$totaldata."]
    }]
});
</script> ";
return($datarea);
}

function basicbar($conn,$survey_id,$question_id){ 
	$sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
    $dataquestion=mysqli_fetch_object($sqlquestion);  
    $chartid1="basic_bar".$dataquestion->field_name;
    $dataprint2='';
 	$dataprint2="<div id='".$chartid1."' style='width:100%; height:400px;'></div>
	<script>
		Highcharts.chart('".$chartid1."', {
		  chart: {
			type: 'bar'
		  },
		  title: {
			text: '".$dataquestion->question_name."'
		  },
		  
		  xAxis: {
			categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
			title: {
			  text: null
			}
		  },
		  yAxis: {
			min: 0,
			title: {
			  text: '',
			  align: 'high'
			},
			labels: {
			  overflow: 'justify'
			}
		  },
		  tooltip: {
			valueSuffix: 'Total'
		  },
		  plotOptions: {
			bar: {
			  dataLabels: {
				enabled: true
			  }
			}
		  },
		  legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -40,
			y: 80,
			floating: true,
			borderWidth: 1,
			backgroundColor:
			  Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
			shadow: true
		  },
		  credits: {
			enabled: false
		  },
		  series: [{
			name: 'Year 1800',
			data: [107, 31, 635, 203, 2]
		  }, {
			name: 'Year 1900',
			data: [133, 156, 947, 408, 6]
		  }, {
			name: 'Year 2000',
			data: [814, 841, 3714, 727, 31]
		  }, {
			name: 'Year 2016',
			data: [1216, 1001, 4436, 738, 40]
		  }]
		});
	</script>";
	return($dataprint2);
}
function basiccolumn($conn,$survey_id,$question_id){ 
	$sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $chartid1="basic_column".$dataquestion->field_name;
   $dataprint2='';
 	$dataprint2="<div id='".$chartid1."' style='width:100%; height:400px;'></div>
	<script>
		Highcharts.chart('".$chartid1."', {
		  chart: {
			type: 'column'
		  },
		  title: {
			text: ''
		  },
		  
		  xAxis: {
			categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
			title: {
			  text: null
			}
		  },
		  yAxis: {
			min: 0,
			title: {
			  text: 'Population (millions)',
			  align: 'high'
			},
			labels: {
			  overflow: 'justify'
			}
		  },
		  tooltip: {
			valueSuffix: ' millions'
		  },
		  plotOptions: {
			bar: {
			  dataLabels: {
				enabled: true
			  }
			}
		  },
		  legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -40,
			y: 80,
			floating: true,
			borderWidth: 1,
			backgroundColor:
			  Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
			shadow: true
		  },
		  credits: {
			enabled: false
		  },
		  series: [{
			name: 'Year 1800',
			data: [107, 31, 635, 203, 2]
		  }, {
			name: 'Year 1900',
			data: [133, 156, 947, 408, 6]
		  }, {
			name: 'Year 2000',
			data: [814, 841, 3714, 727, 31]
		  }, {
			name: 'Year 2016',
			data: [1216, 1001, 4436, 738, 40]
		  }]
		});
	</script>";
	return($dataprint2);
}
/*
function stackedbar($conn,$unique_id,$survey_id,$question_id,$field_function='',$filed_group_by=''){ 
	$sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $chartid1="stacked_bar".$dataquestion->field_name;
   ////////////////////////////
   $fields='$.'.$dataquestion->field_name;
   $chartid="stacked_bar".$dataquestion->field_name;
   $fct='count(*)';
   $fldss="JSON_EXTRACT(survey_data_json, '".$fields."')";
   if($field_function=="sum") { $fct='sum('.$fldss.')'; }
   if($field_function=="avg") { $fct='avg('.$fldss.')'; }
   $filedgrpby='$.'.$filed_group_by;
   $groupby="JSON_EXTRACT(survey_data_json, '".$filedgrpby."')";
   $i=0;
  // echo  "select $groupby as category ,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category";
	$sqlquestionqry=mysqli_query($conn,"select $groupby as category,$fldss as indicator,$fct as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category,indicator");
	while($rowdata=mysqli_fetch_object($sqlquestionqry))
	{
		if($i>0)
		{
			$data.=',';
		}
	$data.='{ 
        name: '.$rowdata->category.',
        y: '.$rowdata->total.'
      }';
	$i++; }
	$dataprint='';
   //////////////////////////////////
   
   $dataprint2='';
 	$dataprint2="<div id='".$chartid1."' style='width:100%; height:400px;'></div>
	<script>
	Highcharts.chart('".$chartid1."', {
	  chart: {
		type: 'bar'
	  },
	  title: {
		text: ''
	  },
	  xAxis: {
		categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
	  },
	  yAxis: {
		min: 0,
		title: {
		  text: 'Total fruit consumption'
		}
	  },
	  legend: {
		reversed: true
	  },
	  plotOptions: {
		series: {
		  stacking: 'normal'
		}
	  },
	  series: [{
		name: 'John',
		data: [5, 3, 4, 7, 2]
	  }, {
		name: 'Jane',
		data: [2, 2, 3, 2, 1]
	  }, {
		name: 'Joe',
		data: [3, 4, 4, 2, 5]
	  }]
	});
	</script>";
	return($dataprint2);
}*/

function stackedbar($conn,$survey_id,$question_id){ 
	$sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $chartid1="stacked_bar".$dataquestion->field_name;
   $dataprint2='';
 	$dataprint2="<div id='".$chartid1."' style='width:100%; height:400px;'></div>
	<script>
	Highcharts.chart('".$chartid1."', {
	  chart: {
		type: 'bar'
	  },
	  title: {
		text: ''
	  },
	  xAxis: {
		categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
	  },
	  yAxis: {
		min: 0,
		title: {
		  text: 'Total fruit consumption'
		}
	  },
	  legend: {
		reversed: true
	  },
	  plotOptions: {
		series: {
		  stacking: 'normal'
		}
	  },
	  series: [{
		name: 'John',
		data: [5, 3, 4, 7, 2]
	  }, {
		name: 'Jane',
		data: [2, 2, 3, 2, 1]
	  }, {
		name: 'Joe',
		data: [3, 4, 4, 2, 5]
	  }]
	});
	</script>";
	return($dataprint2);
}

function Scatterplot($conn,$survey_id,$question_id){ 
	$sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $chartid1="Scatter_plot".$dataquestion->field_name;
   $dataprint2='';
 	$dataprint2="<div id='".$chartid1."' style='width:100%; height:400px;'></div>
	<script>
		Highcharts.chart('".$chartid1."', {
  chart: {
    type: 'scatter',
    zoomType: 'xy'
  },
  title: {
    text: ''
  },
  subtitle: {
    text: 'Source: Heinz  2003'
  },
  xAxis: {
    title: {
      enabled: true,
      text: 'Height (cm)'
    },
    startOnTick: true,
    endOnTick: true,
    showLastLabel: true
  },
  yAxis: {
    title: {
      text: 'Weight (kg)'
    }
  },
  legend: {
    layout: 'vertical',
    align: 'left',
    verticalAlign: 'top',
    x: 100,
    y: 70,
    floating: true,
    backgroundColor: Highcharts.defaultOptions.chart.backgroundColor,
    borderWidth: 1
  },
  plotOptions: {
    scatter: {
      marker: {
        radius: 5,
        states: {
          hover: {
            enabled: true,
            lineColor: 'rgb(100,100,100)'
          }
        }
      },
      states: {
        hover: {
          marker: {
            enabled: false
          }
        }
      },
      tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x} cm, {point.y} kg'
      }
    }
  },
  series: [{
    name: 'Female',
    color: 'rgba(223, 83, 83, .5)',
    data: [[161.2, 51.6], [167.5, 59.0], [159.5, 49.2], [157.0, 63.0], [155.8, 53.6],
      [170.0, 59.0], [159.1, 47.6], [166.0, 69.8], [176.2, 66.8], [160.2, 75.2],
      [172.5, 55.2], [170.9, 54.2], [172.9, 62.5], [153.4, 42.0], [160.0, 50.0],
      [147.2, 49.8], [168.2, 49.2], [175.0, 73.2], [157.0, 47.8], [167.6, 68.8],
      [159.5, 50.6], [175.0, 82.5], [166.8, 57.2], [176.5, 87.8], [170.2, 72.8],
	  [152.4, 67.3], [168.9, 63.0], [170.2, 73.6], [175.2, 62.3], [175.2, 57.7],
      [160.0, 55.4], [165.1, 104.1], [174.0, 55.5], [170.2, 77.3], [160.0, 80.5],
      [167.6, 64.5], [167.6, 72.3], [167.6, 61.4], [154.9, 58.2], [162.6, 81.8],
      [175.3, 63.6], [171.4, 53.4], [157.5, 54.5], [165.1, 53.6], [160.0, 60.0],
      [174.0, 73.6], [162.6, 61.4], [174.0, 55.5], [162.6, 63.6], [161.3, 60.9],
      [156.2, 60.0], [149.9, 46.8], [169.5, 57.3], [160.0, 64.1], [175.3, 63.6],
      [169.5, 67.3], [160.0, 75.5], [172.7, 68.2], [162.6, 61.4], [157.5, 76.8],
      [176.5, 71.8], [164.4, 55.5], [160.7, 48.6], [174.0, 66.4], [163.8, 67.3]]

  }, {
    name: 'Male',
    color: 'rgba(119, 152, 191, .5)',
    data: [[174.0, 65.6], [175.3, 71.8], [193.5, 80.7], [186.5, 72.6], [187.2, 78.8],
      [181.5, 74.8], [184.0, 86.4], [184.5, 78.4], [175.0, 62.0], [184.0, 81.6],
      [180.0, 76.6], [177.8, 83.6], [192.0, 90.0], [176.0, 74.6], [174.0, 71.0],
      [184.0, 79.6], [192.7, 93.8], [171.5, 70.0], [173.0, 72.4], [176.0, 85.9],
      [176.0, 78.8], [180.5, 77.8], [172.7, 66.2], [176.0, 86.4], [173.5, 81.8],
      [178.0, 89.6], [180.3, 82.8], [180.3, 76.4], [164.5, 63.2], [173.0, 60.9],
      [183.5, 74.8], [175.5, 70.0], [188.0, 72.4], [189.2, 84.1], [172.8, 69.1],
      [170.0, 59.5], [182.0, 67.2], [170.0, 61.3], [177.8, 68.6], [184.2, 80.1],
      [186.7, 87.8], [171.4, 84.7], [172.7, 73.4], [175.3, 72.1], [180.3, 82.6],
      [182.9, 88.7], [188.0, 84.1], [177.2, 94.1], [172.1, 74.9], [167.0, 59.1],
      [177.8, 102.5], [184.2, 77.3], [179.1, 71.8], [176.5, 87.9], [188.0, 94.3],
      [174.0, 70.9], [167.6, 64.5], [170.2, 77.3], [167.6, 72.3], [188.0, 87.3],
      [174.0, 80.0], [176.5, 82.3], [180.3, 73.6], [167.6, 74.1], [188.0, 85.9],
      [180.3, 73.2], [167.6, 76.3], [183.0, 65.9], [183.0, 90.9], [179.1, 89.1],
      [170.2, 62.3], [177.8, 82.7], [179.1, 79.1], [190.5, 98.2], [177.8, 84.1],
      [180.3, 83.2], [180.3, 83.2]]
  }]
});
	</script>";
	return($dataprint2);
	
}

?>