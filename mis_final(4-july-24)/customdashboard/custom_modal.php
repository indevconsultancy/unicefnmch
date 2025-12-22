<?php
include_once('Helper.php');
$helper = new Helper();
$fonts = $helper->fonts();
?>

<style>
.panel.actions > .panel-heading {
    margin-bottom: 11px;
}

.panel .panel-body{
	padding-bottom:0px!important;
}

.panel-heading .nav > li > a {
    padding: 9px;
}
</style>



<!-- The Modal -->
<div class="modal fade" id="chartCustomiseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered " role="document" style="width: 95%;" >
		<div class="modal-content">
			<div class="modal-header">
				
				<h3 class="modal-title" id="exampleModalLabel">
				<span>Graph Customization</span></h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<!-- Modal body -->
            <div class="modal-body">
                <div class="container mt-3" style="height: 500px;">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="row">
								<section class="panel actions">
								  <header class="panel-heading tab-bg-primary ">
									<ul class="nav nav-tabs">
									  <li class="active">
										<a data-toggle="tab" href="#v-tabs-home">Titles</a>
									  </li>
									  <!--
									  <li class="">
										<a data-toggle="tab" href="#v-tabs-profile">Generals</a>
									  </li>
									  -->
									  <li class="">
										<a data-toggle="tab" href="#v-tabs-messages">Appearance</a>
									  </li>
									  <li class="">
										<a data-toggle="tab" href="#axes">Axes</a>
									  </li>
									  <li class="">
										<a data-toggle="tab" href="#value-labels">Value Labels</a>
									  </li>
									  <li class="">
										<a data-toggle="tab" href="#legend">Legend</a>
									  </li>
									  <li class="">
										<a data-toggle="tab" href="#tooltip">Tooltip</a>
									  </li>
									  <li class="">
										<a data-toggle="tab" href="#exporting">Save</a>
									  </li>
									  <!--
									  <li class="">
										<a data-toggle="tab" href="#contact">Advance</a>
									  </li>
									  -->
									</ul>
								  </header>
								  <div class="panel-body" style="height: 450px; overflow: auto;">
									<div class="tab-content">
									  <div id="v-tabs-home" class="tab-pane active">
										<div class="panel">
											<div class="panel-heading">Chart Title</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>Title</th>
														<td>
															<input type="text" class="form-control" id="titles-maintitle" placeholder="Enter Title" />
														</td>
													</tr>
												</table>
											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Sub-Title</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>Sub Title</th>
														<td>
															<input type="text" id="titles-subtitle" class="form-control" placeholder="Enter Title" />
														</td>
													</tr>
												</table>
											</div>
										</div>
									  </div>
									  <!--
									  <div id="v-tabs-profile" class="tab-pane">
										<div class="panel mt-3">
											<div class="panel-heading">Generals</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>Chart Width</th>
														<td>
															<input type="text" id="general-chartwidth" class="form-control" placeholder="Enter Title" />
														</td>
													</tr>
													<tr>
														<th>Chart Height</th>
														<td>
															<input type="text" id="general-chartheight" class="form-control" placeholder="Enter Title" />
														</td>
													</tr>
												</table>
											</div>
										</div>
									  </div>
									  -->
									  <div id="v-tabs-messages" class="tab-pane">
										<div class="panel mt-3">
											<div class="panel-heading">Appearance Fonts</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th rowspan="2" class="vcenter" >Font family</th>
														<td>
															<select name="" id="appearanceFontFamily" class="form-control" >
																<?php 
																	foreach($fonts as $font){
																		echo '<option value="'.$font.'" >'.$font.'</option>';
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															<select name="" id="appearanceFontSize" class="form-control" >
																<?php 
																	for($i=2; $i<=34; $i+=2){
																		echo '<option value="'.$i.'px">'.$i.'</option>';
																	}
																?>
															</select>
															<input type="checkbox" value="bold" id="appearanceFontBold" > Bold &nbsp;&nbsp;&nbsp;
															<input type="checkbox" value="italic" id="appearanceFontItalic" > Italic &nbsp;&nbsp;&nbsp;
															<!--<input type="color" id="appearanceFontColor" > -->
														</td>
													</tr>
												</table>
											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Title Style</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th rowspan="2" class="vcenter" >Main title style</th>
														<td>
															<select name="" id="appearanceTitleFont" class="form-control" >
																<?php 
																	foreach($fonts as $font){
																		echo '<option value="'.$font.'" >'.$font.'</option>';
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															<select name="" id="appearanceTitleFontSize" class="form-control" >
																<?php 
																	for($i=2; $i<=34; $i+=2){
																		echo '<option value="'.$i.'px">'.$i.'</option>';
																	}
																?>
															</select>
															<input type="checkbox" value="bold" id="appearanceTitleFontBold"> Bold &nbsp;&nbsp;&nbsp;
															<input type="checkbox" value="italic" id="appearanceTitleFontItalic" > Italic &nbsp;&nbsp;&nbsp;
															<!--<input type="color" id="appearanceTitleFontColor" >-->
														</td>
													</tr>
													<tr>
														<th rowspan="2" class="vcenter" >Subtitle style</th>
														<td>
															<select name="" id="appearanceSubTitleFont" class="form-control" >
																<?php 
																	foreach($fonts as $font){
																		echo '<option value="'.$font.'" >'.$font.'</option>';
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															<select name="" id="appearanceSubTitleFontSize" class="form-control" >
																<?php 
																	for($i=2; $i<=34; $i+=2){
																		echo '<option value="'.$i.'">'.$i.'</option>';
																	}
																?>
															</select>
															<input type="checkbox" value="bold" id="appearanceSubTitleFontBold"> Bold &nbsp;&nbsp;&nbsp;
															<input type="checkbox" value="italic" id="appearanceSubTitleFontItalic" > Italic &nbsp;&nbsp;&nbsp;
															<!--<input type="color" id="appearanceSubTitleFontColor" >-->
														</td>
													</tr>
												</table>
											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Series/Bar Colors</div>
											<div class="panel-body">
												<table class="table" id="addSeriesColor" >
													<tr>
														<th class="vcenter scolorhead" rowspan="3" colspan="2" >Colors</th>
													</tr>
													<tr>
														<td>
															<input type="color" value="#a9d18e" class="form-control seriesColors" />
														</td>
														<td>
															<i class="fa fa-trash-o rmseriesColor"></i>
														</td>
													</tr>
													<tr>
														<td>
															<input type="color" value="#a9d18e" class="form-control seriesColors" />
														</td>
														<td>
															<i class="fa fa-trash-o rmseriesColor"></i>
														</td>
													</tr>
												</table>

												<button class="btn btn-dark btn-sm float-end addMoreSeriesColor">+</button>

											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Chart Area</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>Background color</th>
														<td>
															<input type="color" value="#a9d18e" id="chartAreaBgColor" class="form-control"  />
														</td>
													</tr>
													<tr>
														<th>Border width</th>
														<td>
															<input type="number" id="chartAreaBorderWidth" class="form-control" />
														</td>
													</tr>
													<tr>
														<th>Border corner radius</th>
														<td>
															<input type="number" id="chartAreaBorderRadius" class="form-control" >
														</td>
													</tr>
													<tr>
														<th>Border color</th>
														<td>
															<input type="color" value="#a9d18e" id="chartAreaBorderColor" class="form-control" />
														</td>
													</tr>
												</table>
											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Plot Area</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>Background color</th>
														<td>
															<input type="color" value="#a9d18e" id="plotAreaBgColor" class="form-control"  />
														</td>
													</tr>
													<tr>
														<th>Background image online URL</th>
														<td>
															<input type="text" id="plotAreaBgImage" placeholder="Image URL">
														</td>
													</tr>
													<tr>
														<th>Border width</th>
														<td>
															<input type="number" id="plotAreaBorderWidth" class="form-control" />
														</td>
													</tr>
													<tr>
														<th>Border color</th>
														<td>
															<input type="color" value="#a9d18e" id="plotAreaBorderColor" />
														</td>
													</tr>
												</table>
											</div>
										</div>
									  </div>
									  <div id="axes" class="tab-pane">
										<div class="panel mt-3">
											<div class="panel-heading">Axes Setup</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>Inverted axes</th>
														<td>
															<input type="checkbox" id="AxesInverted" />
														</td>
													</tr>
												</table>
											</div>
										</div>
										
										<div class="panel mt-3">
											<div class="panel-heading">X-Axis</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>GridLine Width</th>
														<td>
															<input type="number" class="form-control" id="xAxesGridLineWidth" >
														</td>
													</tr>
													<tr>
														<th>Text</th>
														<td>
															<input type="text" class="form-control" id="xAxesText" >
														</td>
													</tr>
													<tr>
														<th rowspan="2">Font family</th>
														<td>
															<select name="" id="xAxesFontFamily" class="form-control" >
																<?php 
																	foreach($fonts as $font){
																		echo '<option value="'.$font.'" >'.$font.'</option>';
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															<select name="" id="xAxesFontSize" class="form-control" >
																<?php 
																	for($i=2; $i<=34; $i+=2){
																		echo '<option value="'.$i.'px">'.$i.'</option>';
																	}
																?>
															</select>
															<input type="checkbox" value="bold" id="xAxesFontBold" > Bold
															<input type="checkbox" value="italic" id="xAxesFontItalic" > Italic
															<input type="color" value="#a9d18e" id="xAxesFontColor" >
														</td>
													</tr>
													
													<!--
													<tr>
														<th>Type</th>
														<td>
															<select name="" id="xAxesType" class="form-control" >
																<option value="linear">linear</option>
																<option value="logarithmic">logarithmic</option>
																<option value="datetime">datetime</option>
																<option value="category">category</option>
															</select>
														</td>
													</tr>
													-->
													<tr>
														<th>Opposite side of chart</th>
														<td>
															<input type="checkbox" id="xAxesOpposite" />
														</td>
													</tr>
													<tr>
														<th>Reversed direction</th>
														<td>
															<input type="checkbox" id="xAxesDirection" />
														</td>
													</tr>
													<!-- <tr>
														<th>Axis labels format</th>
														<td>
															<input type="text" id="xAxesFormat" value="{value}" placeholder="{value}">
														</td>
													</tr> -->
												</table>
											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Y-Axis</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>GridLine Width</th>
														<td>
															<input type="number" class="form-control" id="yAxesGridLineWidth" >
														</td>
													</tr>
													<tr>
														<th>Text</th>
														<td>
															<input type="text" class="form-control" id="yAxesText" >
														</td>
													</tr>
													<tr>
														<th rowspan="2">Font family</th>
														<td>
															<select name="" id="yAxesFontFamily" class="form-control" >
																<?php 
																	foreach($fonts as $font){
																		echo '<option value="'.$font.'" >'.$font.'</option>';
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															<select name="" id="yAxesFontSize" class="form-control" >
																<?php 
																	for($i=2; $i<=34; $i+=2){
																		echo '<option value="'.$i.'px">'.$i.'</option>';
																	}
																?>
															</select>
															<input type="checkbox" value="bold" id="yAxesFontBold" > Bold
															<input type="checkbox" value="italic" id="yAxesFontItalic" > Italic
															<input type="color" value="#a9d18e" id="yAxesFontColor" >
														</td>
													</tr>
													
													<!--
													<tr>
														<th>Type</th>
														<td>
															<select name="" id="yAxesType" class="form-control" >
																<option value="linear">linear</option>
																<option value="logarithmic">logarithmic</option>
																<option value="datetime">datetime</option>
																<option value="category">category</option>
															</select>
														</td>
													</tr>
													-->
													<tr>
														<th>Opposite side of chart</th>
														<td>
															<input type="checkbox" id="yAxesOpposite" />
														</td>
													</tr>
													<tr>
														<th>Reversed direction</th>
														<td>
															<input type="checkbox" id="yAxesDirection" />
														</td>
													</tr>
													<!-- <tr>
														<th>Axis labels format</th>
														<td>
															<input type="text" id="xAxesFormat" value="{value}" placeholder="{value}">
														</td>
													</tr> -->
												</table>
											</div>
										</div>
										
									  </div>
									  <div id="value-labels" class="tab-pane">
										<div class="panel mt-3">
											<div class="panel-heading">Value Labels</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th colspan="2" >Enable data labels for all series</th>
														<td>
															<input type="checkbox" id="valuesEnableDataLabels" />
														</td>
													</tr>

													<tr>
														<th rowspan="5">Text style</th>
													</tr>
													<tr>
														<th>Color</th>
														<td>
															<input type="color" value="#a9d18e" id="valuesTextColor" >
														</td>
													</tr>
													<tr>
														<th>Font Size</th>
														<td>
															<input type="number"  id="valuesFontSize"  >
														</td>
													</tr>
													<tr>
														<th>Font Weight</th>
														<td>
															<select name="" id="valuesFontWeight">
																<option value="normal">Normal</option>
																<option value="bold">Bold</option>
															</select>
														</td>
													</tr>
													<tr>
														<th>Text Outline</th>
														<td>
															<input type="text" value="1px 1px contrast" id="valuesOutline" >
														</td>
													</tr>
												</table>
											</div>
										</div>
									  </div>
									  <div id="legend" class="tab-pane">
										<div class="panel mt-3">
											<div class="panel-heading">General</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>Enable legends</th>
														<td>
															<input type="checkbox" id="legendEnabled" />
														</td>
													</tr>
													<tr>
														<th>Legend layout</th>
														<td>
															<select name="" id="legendLayout">
																<option value="horizontal">horizontal</option>
																<option value="vertical">vertical</option>
															</select>
														</td>
													</tr>
												</table>
											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Placement</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th>Horizontal alignment</th>
														<td>
															<select name="" id="legendHrAlignment">
																<option value="left">left</option>
																<option value="center">center</option>
																<option value="right">right</option>
															</select>
														</td>
													</tr>
													<!--
													<tr>
														<th>Horizontal offset</th>
														<td>
															<input type="number" id="legendHrOffset"  />
														</td>
													</tr>
													-->
													<tr>
														<th>Vertical alignment</th>
														<td>
															<select name="" id="legendVrAlignment">
																<option value="top">top</option>
																<option value="middle">middle</option>
																<option value="bottom">bottom</option>
															</select>
														</td>
													</tr>
													<!--
													<tr>
														<th>Vertical offset</th>
														<td>
															<input type="number" id="legendVrOffset" >
														</td>
													</tr>
													-->
													<tr>
														<th>Float on top of plot area</th>
														<td>
															<input type="checkbox" id="legendFloating">
														</td>
													</tr>
												</table>
											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Appearance</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<th rowspan="2">Text style</th>
														<td>
															<select name="" id="legendAppFont" class="form-control" >
																<?php 
																	foreach($fonts as $font){
																		echo '<option value="'.$font.'" >'.$font.'</option>';
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															<select name="" id="legendAppFontSize" class="form-control" >
																<?php 
																	for($i=2; $i<=34; $i+=2){
																		echo '<option value="'.$i.'px">'.$i.'</option>';
																	}
																?>
															</select>
															<input type="checkbox" value="bold" id="legendAppFontBold" > Bold
															<input type="checkbox" value="italic" id="legendAppFontItalic" > Italic
															<input type="color" value="#a9d18e" id="legendAppFontColor" >
														</td>
													</tr>

													<!-- <tr>
														<th rowspan="2">Text style hidden</th>
														<td>
															<select name="" id="" class="form-control" >
																<?php 
																	// foreach($fonts as $font){
																	//     echo '<option value="'.$font.'" >'.$font.'</option>';
																	// }
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															<select name="" id="legendHiddenFontSize">
																<?php 
																	// for($i=2; $i<=34; $i+=2){
																	//     echo '<option value="'.$i.'px">'.$i.'</option>';
																	// }
																?>
															</select>
															<input type="checkbox" value="bold"> Bold
															<input type="checkbox" value="italic"> Italic
															<input type="color" value="#a9d18e">
														</td>
													</tr> -->

													

													<tr>
														<th>Border width</th>
														<td>
															<input type="number" value="0" id="legendBorderWidth" class="form-control" >
														</td>
													</tr>

													<tr>
														<th>Border corner radius</th>
														<td>
															<input type="number" name="" value="0" id="legendBorderRadius">
														</td>
													</tr>

													<tr>
														<th>Border color</th>
														<td>
															<input type="color" value="#a9d18e" name="" id="legendBorderColor">
														</td>
													</tr>
													<tr>
														<th>Background color</th>
														<td>
															<input type="color" value="#a9d18e" id="legendBgColor" class="form-control" >
														</td>
													</tr>

												</table>
											</div>
										</div>
									  </div>
									  <div id="tooltip" class="tab-pane">
										<div class="panel mt-3">
											<div class="panel-heading">General</div>
											<div class="panel-body">
												<table class="table">

													<tr>
														<th>Enable tooltip</th>
														<td>
															<input type="checkbox" id="tooltipEnabled">
														</td>
													</tr>
													<!--
													<tr>
														<th>Shared between series</th>
														<td>
															<input type="checkbox" id="tooltipShared" >
														</td>
													</tr>
													-->
												</table>
											</div>
										</div>

										<div class="panel mt-3">
											<div class="panel-heading">Color and Border</div>
											<div class="panel-body">
												<table class="table">

													<tr>
														<th>Background color</th>
														<td>
															<input type="color" value="#a9d18e" id="tooltipBgColor" >
														</td>
													</tr>

													<tr>
														<th>Border width</th>
														<td>
															<input type="number" value="1" id="tooltipBorderWidth" >
														</td>
													</tr>
													<tr>
														<th>Border corner radius</th>
														<td>
															<input type="number" value="3" id="tooltipBorderRadius">
														</td>
													</tr>
													<tr>
														<th>Border color</th>
														<td>
															<input type="color" value="#a9d18e" id="tooltipBorderColor" >
														</td>
													</tr>
												</table>
											</div>
										</div>
									  </div>
									  <div id="exporting" class="tab-pane">
										<div class="panel mt-3">
											<div class="panel-heading">Exporting</div>
											<div class="panel-body">
												<table class="table">

													<tr>
														<th>Enable exporting</th>
														<td>
															<input type="checkbox" id="exportEnabled" >
														</td>
													</tr>
													<tr>
														<th>Exported width</th>
														<td>
															<input type="number" value="150" id="exportWidth" >
														</td>
													</tr>
													<tr>
														<th>Scaling factor</th>
														<td>
															<input type="number" value="3" id="exportScalingFactor" >
														</td>
													</tr>
												</table>
											</div>
										</div>
										
										<div class="panel mt-3">
											<div class="panel-heading" style="background-color: #002d4c!important;">Save Customized Graph</div>
											<div class="panel-body">
												<table class="table">
													<tr>
														<td>
															<div class="form-group ">
																<label>Title *</label> 
																<input type="text" class="form-control" placeholder="Graph Title" id="graph_title"> 
																<span class="text-danger" id="graph_title_err" style="display:none;">Please add the Title and Save.</span>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="form-group"> 
																<label>Description</label> 
																<textarea class="form-control" placeholder="Graph Description" id="graph_description" rows="5" spellcheck="false"></textarea>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="text-right">
																<button type="button" id="savegraphdetails" class="btn btn-primary">Save</button>
															</div>
														</td>
													</tr>
												</table>
											</div>
										</div>
										
										
									  </div>
									  <!--<div id="contact" class="tab-pane">Contact</div>-->
									</div>
								  </div>
								</section>
								
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div id="customdemo"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div> -->

            </div>
		</div>
	</div>
</div>