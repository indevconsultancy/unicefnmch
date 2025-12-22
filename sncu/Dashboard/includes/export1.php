  <?php include('config.php') ?>
  <?php
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=growth_data.csv");
  session_start();
  
  $filename = $_REQUEST['filename'];
  
  $today = date('dmyhis');
  //$bid=$_SESSION['bid'];
  
  $ssColumn = '';
  $db_column = $_SESSION['db_column'];
  $columnArr = explode(",", $db_column);
  
  $sql = $_SESSION['query'];
  $head_column = $_SESSION['header_column'];
  
  // header_column,db_column, and query are store in session from the list page 
  
  $qry = mysqli_query($conn, $sql);
  ?>
  <?php
  $output='';
												   $output.="<table>
													<tr>
													<th>ID</th>
                                                    <th>Sex</th>
                                                    <th>GA</th>
                                                    <th>Weight</th>
                                                    <th>Length</th>
                                                    <th>Headcircumference</th>
                                                   </tr>";
                                                while ($datafacilitator = mysqli_fetch_object($qry)) { 
                                               
                                                   $output.= "<tr>";
                                                       
                                                        $mon_type = '';
                                                        $ga='';
                                                        if ($datafacilitator->type_of_monitoring == 'Date of Admission') {
                                                            $ga=$datafacilitator->gestational_age_LBW * 7;
                                                        }
                                                        if ($datafacilitator->type_of_monitoring == 'Discharge Day') {
                                                            $ga=($datafacilitator->gestational_age_LBW * 7)+($datafacilitator->age_in_days);
                                                        }
                                                       
$output.= "<td>".$datafacilitator->mon_id."</td><td>".$datafacilitator->sex."</td><td>".$ga."</td><td>".$datafacilitator->admission_weight."</td><td>".$datafacilitator->admission_length."</td><td>".$datafacilitator->admission_head_circumference ."</td></tr>";
                                                 } 
                                             $output.="</tbody></table>";
											 echo $output;
											 ?>
  
