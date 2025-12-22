<?php include('../includes/config.php'); ?>
<?php include('../includes/functions.php'); ?>

<?php
// max date
$maxdate = date('Y-m-d');
// Update Follow-Up
if (isset($_POST['fol_id']) && isset($_POST['type'])) {
    $regg_id = $_POST['fol_id'];
    $fol_type = $_POST['type'];
    // for start feeding
    $st_fd = ($fol_type != '8 Days' && $fol_type != '1 Month' && $fol_type != '3 Month') ? '' : 'd-none';

    $up_sch_qry = mysqli_query($conn, "SELECT date_of_admission FROM monitoring_data WHERE registration_id = $regg_id AND type_of_monitoring = 'Discharge Day' AND status = '1'");
    $up_sch_date = mysqli_fetch_assoc($up_sch_qry);

    $new_query     = "SELECT id AS reg_id,user_id,unique_id_of_body,sncu_id,boby_of_mothers_name FROM registration_form  WHERE id = $regg_id";
    $new_query_res = mysqli_query($conn, $new_query);
    $label_data    = mysqli_fetch_assoc($new_query_res);
    $sncu_name =   getname($conn, 'sncu_master', 'sncu_name', 'id', $label_data['sncu_id']);


    $fol_qry = "SELECT id,type_of_time,schedule_follow_up_date,date_of_visit,baby_weight,baby_length,mode_of_feeding,baby_head_circumference,immunization_status,type_of_feed,other_feed,times_baby_breastfed,check_health_examination,mention_identified_health,how_many_times_asha_visit,how_many_times_aww_viited,anyone_counselled,times_baby_breastfed,aww_visit_infant_after_birth,asha_check_following_infant,asha_counsel_mother_family,aww_visit_baby_from_sncu,how_many_times_aww_visited,how_many_times_asha_visited,asha_visit_baby_from_sncu,started_feeding_at_home,offered_food_items FROM follow_up WHERE registration_id = $regg_id and type_of_time = '$fol_type'";
    $fol_exe = mysqli_query($conn, $fol_qry);
    $fol_data = mysqli_fetch_assoc($fol_exe);

    // type of time
    $type1 = ($fol_data['type_of_time']) == '8 Days' ? 'selected' : '';
    $type2 = ($fol_data['type_of_time']) == '1 Month' ? 'selected' : '';
    $type3 = ($fol_data['type_of_time']) == '3 Month' ? 'selected' : '';
    $type4 = ($fol_data['type_of_time']) == '6 Month' ? 'selected' : '';
    $type5 = ($fol_data['type_of_time']) == '1 Year' ? 'selected' : '';

    // immunozation status
    $up = ($fol_data['immunization_status']) == 'Up to date' ? 'selected' : '';
    $not_up = ($fol_data['immunization_status']) == 'Not Updated' ? 'selected' : '';

    // Type of Feed
    $type_of_feed_arr = explode(',', $fol_data['type_of_feed']);
    $bm   = in_array('Breast Milk', $type_of_feed_arr) ? 'checked' : '';
    $am   = in_array('Animal Milk', $type_of_feed_arr) ? 'checked' : '';
    $fm   = in_array('Formula Milk', $type_of_feed_arr) ? 'checked' : '';
    $par  = in_array('Parenteral Feed', $type_of_feed_arr) ? 'checked' : '';
    $oth  = in_array('Others', $type_of_feed_arr) ? 'checked' : '';
    $dnone = ($oth != '') ? '' : 'd-none';

    // start food at home
    $s_sel = ($fol_data['started_feeding_at_home'] == 'Yes') ? 'selected' : '';
    $n_sel = ($fol_data['started_feeding_at_home'] == 'No') ? 'selected' : '';
    // offred food
    $offred_food = explode(',', $fol_data['offered_food_items']);
    $tub   = in_array('Cereals/Tuber', $offred_food) ? 'checked' : '';
    $ln   = in_array('Legume & Nuts', $offred_food) ? 'checked' : '';
    $vtm   = in_array('Vitamin-A rich fruits and vegetables ( Red/ Yellow/Orange)', $offred_food) ? 'checked' : '';
    $vegi  = in_array('Other fruits & Vegetables', $offred_food) ? 'checked' : '';
    $milk  = in_array('Milk & Milk products - Dahi or Lassi or Paneer)', $offred_food) ? 'checked' : '';
    $egg  = in_array('Egg', $offred_food) ? 'checked' : '';
    $meat  = in_array('Meat/ Poultry/ Fish', $offred_food) ? 'checked' : '';
    $junk  = in_array('Junk items - Chips or Biscuits or chocolate etc', $offred_food) ? 'checked' : '';
    $fdhmnn = ($n_sel != '') ? 'd-none' : '';

    // Child Feeding Bottle
    $yes = ($fol_data['mode_of_feeding']) == 'Yes' ? 'selected' : '';
    $no = ($fol_data['mode_of_feeding']) == 'No' ? 'selected' : '';

    // Promoting Breastfeeding
    $pb1 = ($fol_data['anyone_counselled']) == 'Yes' ? 'selected' : '';
    $pb2 = ($fol_data['anyone_counselled']) == 'No' ? 'selected' : '';

    // Health Explanation
    $he1 = ($fol_data['check_health_examination']) == 'Has health issue' ? 'selected' : '';
    $he2 = ($fol_data['check_health_examination']) == 'No health issue' ? 'selected' : '';
    $henone = ($he1 == 'selected') ? '' : 'd-none';

    // times baby brestfeed
    $tbf1 = ($fol_data['times_baby_breastfed']) == 'No breastfeeding' ? 'selected' : '';
    $tbf2 = ($fol_data['times_baby_breastfed']) == 'Breastfed - less than 4 times' ? 'selected' : '';
    $tbf3 = ($fol_data['times_baby_breastfed']) == '5 times a day' ? 'selected' : '';
    $tbf4 = ($fol_data['times_baby_breastfed']) == '6 times a day' ? 'selected' : '';
    $tbf5 = ($fol_data['times_baby_breastfed']) == '7 times a day' ? 'selected' : '';
    $tbf6 = ($fol_data['times_baby_breastfed']) == '8 times a day' ? 'selected' : '';
    $tbf7 = ($fol_data['times_baby_breastfed']) == '9 times a day' ? 'selected' : '';
    $tbf8 = ($fol_data['times_baby_breastfed']) == '10 times a day' ? 'selected' : '';
    $tbf9 = ($fol_data['times_baby_breastfed']) == '11 times a day' ? 'selected' : '';
    $tbf10 = ($fol_data['times_baby_breastfed']) == '12 times a day' ? 'selected' : '';
    $tbf11 = ($fol_data['times_baby_breastfed']) == 'More than 12 times a day' ? 'selected' : '';

    // asha visit
    $asha1 = ($fol_data['asha_visit_baby_from_sncu']) == 'Yes' ? 'selected' : '';
    $asha2 = ($fol_data['asha_visit_baby_from_sncu']) == 'No' ? 'selected' : '';
    $ashanone = ($asha1 == 'selected') ? '' : 'd-none';

    // aww visit
    $aww1 = ($fol_data['aww_visit_baby_from_sncu']) == 'Yes' ? 'selected' : '';
    $aww2 = ($fol_data['aww_visit_baby_from_sncu']) == 'No' ? 'selected' : '';
    $awwnone = ($aww1 == 'selected') ? '' : 'd-none';
    // asha check infant
    $asha_check_infant_arr = explode(',', $fol_data['asha_check_following_infant']);
    $inf1   = in_array('Weight', $asha_check_infant_arr) ? 'checked' : '';
    $inf2   = in_array('Temperature', $asha_check_infant_arr) ? 'checked' : '';
    $inf3   = in_array('Respiratory Rate', $asha_check_infant_arr) ? 'checked' : '';
    $inf4   = in_array('Pus on umbilicus', $asha_check_infant_arr) ? 'checked' : '';
    $inf5   = in_array('Eyes', $asha_check_infant_arr) ? 'checked' : '';
    $inf6   = in_array('Urine Frequency', $asha_check_infant_arr) ? 'checked' : '';
    $inf7   = in_array('None', $asha_check_infant_arr) ? 'checked' : '';
    $a_check = ($inf7 != '') ? 'disabled' : '';

    // asha check counsel
    $asha_check_counsel_arr = explode(',', $fol_data['asha_counsel_mother_family']);
    $coun1   = in_array('Enquiry on whether breastfeeding is continued', $asha_check_counsel_arr) ? 'checked' : '';
    $coun2   = in_array('Enquiry on frequency of breastfeeding', $asha_check_counsel_arr) ? 'checked' : '';
    $coun3   = in_array('Enquiry on Exclusive breastfeeding', $asha_check_counsel_arr) ? 'checked' : '';
    $coun4   = in_array('Enquiry on any challenges related to breasfeeding', $asha_check_counsel_arr) ? 'checked' : '';
    $coun5   = in_array('Observation of positioning and attachment', $asha_check_counsel_arr) ? 'checked' : '';
    $coun6   = in_array('None', $asha_check_counsel_arr) ? 'checked' : '';
    $c_check = ($coun6 != '') ? 'disabled' : '';



    echo <<<data
            <div class="row">
                <input type="hidden" name="child_id" id="childid" value="{$regg_id}">
                <input type="hidden" name="foll_id" id="foll_id" value="{$fol_data['id']}">

                <div class="mb-3">
                    <label for="firstNameinput" class="form-label fs-6"><b>Reg_Id :</b> {$label_data['unique_id_of_body']}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="firstNameinput" class="form-label fs-6"><b>SNCU Name :</b> {$sncu_name}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="firstNameinput" class="form-label fs-6"><b>Mother Name :</b> {$label_data['boby_of_mothers_name']}</label>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Type of Time</label>
                        <select id='type_of_time' name="type_of_time" class="form-control" onchange="upsch_date(this.value,'{$up_sch_date['date_of_admission']}')">
                            <option value="">--select--</option>
                            <option value="8 Days" {$type1}>8 Days</option>
                            <option value="1 Month" {$type2}>1 Month</option>
                            <option value="3 Month" {$type3}>3 Month</option>
                            <option value="6 Month" {$type4}>6 Month</option>
                            <option value="1 Year" {$type5}>1 Year</option>
                        </select> 
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Schedule Date</label>
                        <input type="date" id='sch_date' name="sch_date" class="form-control" value="{$fol_data['schedule_follow_up_date']}" max="{$maxdate}" readonly>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Visit Date</label>
                        <input type="date" id='vsi_date' name="vsi_date" class="form-control" value="{$fol_data['date_of_visit']}" max="{$maxdate}">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Baby Weight</label>
                        <input type="text" id='b_weight' name="b_weight" class="form-control" value="{$fol_data['baby_weight']}">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Baby Length</label>
                        <input type="text" id='b_length' name="b_length" class="form-control" value="{$fol_data['baby_length']}">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Head Circumference</label>
                        <input type="text" id='h_circum' name="h_circum" class="form-control" value="{$fol_data['baby_head_circumference']}">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Immunization Status</label>
                        <select id='im_status' name="im_status" class="form-control">
                        <option value="">--select--</option>
                        <option value="Up to date" {$up}>Up to date</option>
                        <option value="Not Updated" {$not_up}>Not Updated</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Times Baby Breastfed</label>
                        <select id='times_b_feed' name="times_b_feed" class="form-control">
                            <option value="">--select--</option>
                            <option value="No breastfeeding" {$tbf1}>No breastfeeding</option>
                            <option value="Breastfed - less than 4 times" {$tbf2}>Breastfed - less than 4 times</option>
                            <option value="5 times a day" {$tbf3}>5 times a day</option>
                            <option value="6 times a day" {$tbf4}>6 times a day</option>
                            <option value="7 times a day" {$tbf5}>7 times a day</option>
                            <option value="8 times a day" {$tbf6}>8 times a day</option>
                            <option value="9 times a day" {$tbf7}>9 times a day</option>
                            <option value="10 times a day" {$tbf8}>10 times a day</option>
                            <option value="11 times a day" {$tbf9}>11 times a day</option>
                            <option value="12 times a day" {$tbf10}>12 times a day</option>
                            <option value="More than 12 times a day" {$tbf11}>More than 12 times a day</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Child Feeding Bottle</label>
                        <select id='feed_bot' name="feed_bot" class="form-control">
                        <option value="">--select--</option>
                        <option value="Yes" {$yes}>Yes</option>
                        <option value="No" {$no}>No</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Promoting Breastfeeding</label>
                        <select id='prm_feed' name="prm_feed" class="form-control">
                        <option value="">--select--</option>
                        <option value="Yes" {$pb1}>Yes</option>
                        <option value="No" {$pb2}>No</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Health Examination</label>
                        <select id='hlt_exp' name="hlt_exp" class="form-control" onchange="hel_field(this.value)">
                        <option value="">--select--</option>
                        <option value="Has health issue" {$he1}>Has health issue</option>
                        <option value="No health issue" {$he2}>No health issue</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3 {$henone}" id="iden_hlt">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mention Identified Health Issue</label>
                        <input type="text" id='m_h_issue' name="m_h_issue" class="form-control" value="{$fol_data['mention_identified_health']}">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">ASHA Visit on Discharge</label>
                        <select id='dis_sncu' name="dis_sncu" class="form-control" onchange="dis_sncuppp(this.value)">
                            <option value="">--select--</option>
                            <option value="Yes" {$asha1}>Yes</option>
                            <option value="No" {$asha2}>No</option>
                        </select> 
                    </div>
                </div>

                <div id="dissncu" class="col-3 {$ashanone}">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Times of ASHA Visited</label>
                        <input type="text" id='asa_vsi_time' name="asa_vsi_time" class="form-control" value="{$fol_data['how_many_times_asha_visited']}">
                    </div>
                </div>

                <div class="col-6">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Type of Feed</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed1" type="checkbox" name="addtype_of_feed[]" value="Breast Milk" $bm>
                                    <label class="form-check-label" for="feed1">Breast Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed2" type="checkbox" name="addtype_of_feed[]" value="Animal Milk" $am>
                                    <label class="form-check-label" for="feed2">Animal Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed3" type="checkbox" name="addtype_of_feed[]" value="Formula Milk" $fm>
                                    <label class="form-check-label" for="feed3">Formula Milk</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed4" type="checkbox" name="addtype_of_feed[]" value="Parenteral Feed" $par>
                                    <label class="form-check-label" for="feed4">Parenteral</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="ty_feed_oth_fol" name="addtype_of_feed[]" value="Others" onchange="oth_feed(this)" id="feed3" $oth>
                                    <label class="form-check-label" for="ty_feed_oth_fol">Other</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 {$dnone}" id="feed_oth">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Other Reason</label>
                            <input type="text" id='oth_res' name="oth_res" class="form-control" value="{$fol_data['other_feed']}">
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">AWW Visit on Discharge</label>
                            <select id='aww_visit' name="aww_visit" class="form-control" onchange="aww_visit_fun(this.value)">
                                <option value="">--select--</option>
                                <option value="Yes" {$aww1}>Yes</option>
                                <option value="No" {$aww2}>No</option>
                            </select> 
                        </div>
                    </div>

                    <div class="col-3 {$awwnone}" id="aww_visit1">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Times of AWW Visited</label>
                            <input type="text" id='aww_time' name="aww_time" class="form-control" value="{$fol_data['how_many_times_aww_visited']}">
                        </div>
                    </div>

                    <div id="up_st_fd" class="col-3 $st_fd">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Started Feeding at Home</label>
                            <select id='start_feed' name="add_start_feed" class="form-control" onchange="start_feeding(this.value)">
                                <option value="">--select--</option>
                                <option value="Yes" $s_sel>Yes</option>
                                <option value="No" $n_sel>No</option>
                            </select> 
                        </div>
                    </div>
                                            
                    <div id="up_of_fd" class="col-12 $st_fd">
                        <div id="offered_food" class="mb-3 $fdhmnn">    
                            <label for="firstNameinput" class="form-label">Offered Food Items</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Cereals/Tuber" id="off_fd1" $tub>
                                    <label class="form-check-label" for="off_fd1">Cereals/Tuber</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Legume & Nuts" id="off_fd2" $ln>
                                    <label class="form-check-label" for="off_fd2">Legume & Nuts</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Vitamin-A rich fruits and vegetables ( Red/ Yellow/Orange)" id="off_fd3" $vtm>
                                    <label class="form-check-label" for="off_fd3">Vitamin-A rich fruits and vegetables</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Other fruits & Vegetables" id="off_fd4" $vegi>
                                    <label class="form-check-label" for="off_fd4">Other fruits & Vegetables</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Milk & Milk products - Dahi or Lassi or Paneer)" id="off_fd5" $milk>
                                    <label class="form-check-label" for="off_fd5">Milk & Milk products</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Egg" id="off_fd6" $egg>
                                    <label class="form-check-label" for="off_fd6">Egg</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Meat/ Poultry/ Fish" id="off_fd7" $meat>
                                    <label class="form-check-label" for="off_fd7">Meat/ Poultry/ Fish</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Junk items - Chips or Biscuits or chocolate etc" id="off_fd8" $junk>
                                    <label class="form-check-label" for="off_fd8">Junk items</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Did ASHA Check the following in the infant?</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Weight" id="check1" $inf1 $a_check>
                                    <label class="form-check-label" for="check1">Weight</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Temperature" id="check2" $inf2 $a_check>
                                    <label class="form-check-label" for="check2">Temperature</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Respiratory Rate" id="check3" $inf3 $a_check>
                                    <label class="form-check-label" for="check3">Respiratory Rate</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Pus on umbilicus" id="check4" $inf4 $a_check>
                                    <label class="form-check-label" for="check4">Pus on umbilicus</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Eyes" id="check5" $inf5 $a_check>
                                    <label class="form-check-label" for="check5">Eyes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Urine Frequency" id="check6" $inf6 $a_check>
                                    <label class="form-check-label" for="check6">Urine Frequency</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="infentnone" type="checkbox" name="addinfant[]" value="None" $inf7 onchange="uncheck_field2(this)">
                                    <label class="form-check-label" for="infentnone">None</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Did ASHA Counsel the Mother and Family on any of the below Areas Related to Newborn?</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl1" type="checkbox" name="addcounnsel[]" value="Enquiry on whether breastfeeding is continued" $coun1 $c_check>
                                    <label class="form-check-label" for="cunl1">Enquiry on whether breastfeeding is continued</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl2" type="checkbox" name="addcounnsel[]" value="Enquiry on frequency of breastfeeding" $coun2 $c_check>
                                    <label class="form-check-label" for="cunl2">Enquiry on frequency of breastfeeding</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl3" type="checkbox" name="addcounnsel[]" value="Enquiry on Exclusive breastfeeding" $coun3 $c_check>
                                    <label class="form-check-label" for="cunl3">Enquiry on Exclusive breastfeeding</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl4" type="checkbox" name="addcounnsel[]" value="Enquiry on any challenges related to breasfeeding" $coun4 $c_check>
                                    <label class="form-check-label" for="cunl4">Enquiry on any challenges related to breasfeeding</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl5" type="checkbox" name="addcounnsel[]" value="Observation of positioning and attachment" $coun5 $c_check>
                                    <label class="form-check-label" for="cunl5">Observation of positioning and attachment</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cusl6" type="checkbox" name="addcounnsel[]" value="None" $coun6 onchange="uncheck_field(this)">
                                    <label class="form-check-label" for="cusl6">None</label>
                                </div>
                            </div>
                        </div>
                    </div>
        data;
}

// Add Follow-Up
if (isset($_POST['addfol_id'])) {
    $regg_id = $_POST['addfol_id'];

    $sch_qry = mysqli_query($conn, "SELECT date_of_admission FROM monitoring_data WHERE registration_id = $regg_id AND type_of_monitoring = 'Discharge Day' AND status = '1'");
    $sch_date = mysqli_fetch_assoc($sch_qry);

    $new_query     = "SELECT id AS reg_id,user_id,unique_id_of_body,sncu_id,boby_of_mothers_name FROM registration_form  WHERE id = $regg_id AND status='1'";
    $new_query_res = mysqli_query($conn, $new_query);
    $label_data    = mysqli_fetch_assoc($new_query_res);
    $sncu_name =   getname($conn, 'sncu_master', 'sncu_name', 'id', $label_data['sncu_id']);

    $today = date('Y-m-d');
    echo <<<data
            <div class="row">
                <input type="hidden" name="addchild_id" id="childid" value="{$regg_id}">
                <input type="hidden" name="adduser_id" id="adduser_id" value="{$label_data['user_id']}">

                <div class="mb-3">
                    <label for="firstNameinput" class="form-label fs-6"><b>Reg_Id :</b> {$label_data['unique_id_of_body']}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="firstNameinput" class="form-label fs-6"><b>SNCU Name :</b> {$sncu_name}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="firstNameinput" class="form-label fs-6"><b>Mother Name :</b> {$label_data['boby_of_mothers_name']}</label>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Type of Time</label>
                        <select id='type_of_time' name="addtype_of_time" class="form-control" onchange="gen_sch_date(this.value,'{$sch_date['date_of_admission']}')">
                            <option value="">--select--</option>
                            <option value="8 Days">8 Days</option>
                            <option value="1 Month">1 Month</option>
                            <option value="3 Month">3 Month</option>
                            <option value="6 Month">6 Month</option>
                            <option value="1 Year">1 Year</option>
                        </select> 
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Schedule Date</label>
                        <input type="date" id='sch_date' name="addsch_date" class="form-control" readonly>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Visit Date</label>
                        <input type="date" id='vsi_date' name="addvsi_date" class="form-control" value="<?php echo {$today} ?>" max="{$maxdate}">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Baby Weight</label>
                        <input type="text" id='b_weight' name="addb_weight" class="form-control">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Baby Length</label>
                        <input type="text" id='b_length' name="addb_length" class="form-control">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Head Circumference</label>
                        <input type="text" id='h_circum' name="addh_circum" class="form-control">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Immunization Status</label>
                        <select id='im_status' name="addim_status" class="form-control">
                            <option value="">--select--</option>
                            <option value="Up to date">Up to date</option>
                            <option value="Not Updated">Not Updated</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Times Baby Breastfed</label>
                        <select id='times_b_feed' name="addtimes_b_feed" class="form-control">
                            <option value="">--select--</option>
                            <option value="No breastfeeding">No breastfeeding</option>
                            <option value="Breastfed - less than 4 times">Breastfed - less than 4 times</option>
                            <option value="5 times a day">5 times a day</option>
                            <option value="6 times a day">6 times a day</option>
                            <option value="7 times a day">7 times a day</option>
                            <option value="8 times a day">8 times a day</option>
                            <option value="9 times a day">9 times a day</option>
                            <option value="10 times a day">10 times a day</option>
                            <option value="11 times a day">11 times a day</option>
                            <option value="12 times a day">12 times a day</option>
                            <option value="More than 12 times a day">More than 12 times a day</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Child Feeding Bottle</label>
                        <select id='feed_bot' name="addfeed_bot" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Promoting Breastfeeding</label>
                        <select id='prm_feed' name="addprm_feed" class="form-control">
                        <option value="">--select--</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Health Examination</label>
                        <select id='hlt_exp' name="addhlt_exp" class="form-control" onchange="hel_field(this.value)">
                        <option value="">--select--</option>>
                        <option value="Has health issue">Has health issue</option>
                        <option value="No health issue">No health issue</option>
                        </select> 
                    </div>
                </div>

                <div class="col-3 d-none" id="iden_hlt">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mention Identified Health Issue</label>
                        <input type="text" id='m_h_issue' name="addm_h_issue" class="form-control">
                    </div>
                </div>

                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">ASHA Visit on Discharge</label>
                        <select id='dis_sncu' name="adddis_sncu" class="form-control" onchange="dis_sncuppp(this.value)">
                            <option value="">--select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select> 
                    </div>
                </div>

                <div id="dissncu" class="col-3 d-none">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Times of ASHA Visited</label>
                        <input type="text" id='asa_vsi_time' name="addasa_vsi_time" class="form-control">
                    </div>
                </div>

                <div class="col-6">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Type of Feed</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addtype_of_feed[]" value="Breast Milk">
                                    <label class="form-check-label" for="feed1">Breast Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addtype_of_feed[]" value="Animal Milk">
                                    <label class="form-check-label" for="feed2">Animal Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addtype_of_feed[]" value="Formula Milk">
                                    <label class="form-check-label" for="feed3">Formula Milk</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addtype_of_feed[]" value="Parenteral Feed">
                                    <label class="form-check-label" for="feed3">Parenteral</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="ty_feed_oth_fol" type="checkbox" name="addtype_of_feed[]" value="Others" onchange="oth_feed(this)" id="feed3">
                                    <label class="form-check-label" for="feed3">Other</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 d-none" id="feed_oth">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Other Reason</label>
                            <input type="text" id='oth_res' name="addoth_res" class="form-control">
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">AWW Visit on Discharge</label>
                            <select id='aww_visit' name="addaww_visit" class="form-control" onchange="aww_visit_fun(this.value)">
                                <option value="">--select--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select> 
                        </div>
                    </div>

                    <div class="col-3 d-none" id="aww_visit1">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Times of AWW Visited</label>
                            <input type="text" id='aww_time' name="addaww_time" class="form-control">
                        </div>
                    </div>

                    <div id="st_fd_hm" class="col-3 d-none">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Started Feeding at Home</label>
                            <select id='start_feed' name="add_start_feed" class="form-control" onchange="start_feeding(this.value)">
                                <option value="">--select--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select> 
                        </div>
                    </div>
                                            
                    <div id="of_fd_hm" class="col-12 d-none">
                    <div id="offered_food" class="mb-3">    
                        <label for="firstNameinput" class="form-label">Offered Food Items</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Cereals/Tuber" id="off_fd1">
                                    <label class="form-check-label" for="off_fd1">Cereals/Tuber</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Legume & Nuts" id="off_fd2">
                                    <label class="form-check-label" for="off_fd2">Legume & Nuts</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Vitamin-A rich fruits and vegetables ( Red/ Yellow/Orange)" id="off_fd3">
                                    <label class="form-check-label" for="off_fd3">Vitamin-A rich fruits and vegetables</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Other fruits & Vegetables" id="off_fd4">
                                    <label class="form-check-label" for="off_fd4">Other fruits & Vegetables</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Milk & Milk products - Dahi or Lassi or Paneer)" id="off_fd5">
                                    <label class="form-check-label" for="off_fd5">Milk & Milk products</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Egg" id="off_fd6">
                                    <label class="form-check-label" for="off_fd6">Egg</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Meat/ Poultry/ Fish" id="off_fd7">
                                    <label class="form-check-label" for="off_fd7">Meat/ Poultry/ Fish</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="add_offered_food[]" value="Junk items - Chips or Biscuits or chocolate etc" id="off_fd8">
                                    <label class="form-check-label" for="off_fd8">Junk items</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Did ASHA Check the following in the infant?</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Weight" id="check1">
                                    <label class="form-check-label" for="asha1">Weight</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Temperature" id="check2">
                                    <label class="form-check-label" for="asha2">Temperature</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Respiratory Rate" id="check3">
                                    <label class="form-check-label" for="asha3">Respiratory Rate</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Pus on umbilicus" id="check4">
                                    <label class="form-check-label" for="asha4">Pus on umbilicus</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Eyes" id="check5">
                                    <label class="form-check-label" for="asha5">Eyes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="Urine Frequency" id="check6">
                                    <label class="form-check-label" for="asha6">Urine Frequency</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addinfant[]" value="None"        onchange="uncheck_field2(this)">
                                    <label class="form-check-label" for="asha7">None</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Did ASHA Counsel the Mother and Family on any of the below Areas Related to Newborn?</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl1" type="checkbox" name="addcounnsel[]" value="Enquiry on whether breastfeeding is continued">
                                    <label class="form-check-label" for="cusl1">Enquiry on whether breastfeeding is continued</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl2" type="checkbox" name="addcounnsel[]" value="Enquiry on frequency of breastfeeding">
                                    <label class="form-check-label" for="cusl2">Enquiry on frequency of breastfeeding</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl3" type="checkbox" name="addcounnsel[]" value="Enquiry on Exclusive breastfeeding">
                                    <label class="form-check-label" for="cusl3">Enquiry on Exclusive breastfeeding</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl4" type="checkbox" name="addcounnsel[]" value="Enquiry on any challenges related to breasfeeding">
                                    <label class="form-check-label" for="cusl4">Enquiry on any challenges related to breasfeeding</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="cunl5" type="checkbox" name="addcounnsel[]" value="Observation of positioning and attachment">
                                    <label class="form-check-label" for="cusl5">Observation of positioning and attachment</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="addcounnsel[]" value="None" onchange="uncheck_field(this)">
                                    <label class="form-check-label" for="cusl6">None</label>
                                </div>
                            </div>
                        </div>
                    </div>
        data;
}

// Post Discharge Follow-Up
if (isset($_POST['id']) && isset($_POST['field'])) {

    $id    = $_POST['id'];
    $field = $_POST['field'];

    $query = mysqli_query($conn, "SELECT * FROM follow_up WHERE type_of_time='$field' and registration_id = $id");
    $data = mysqli_fetch_assoc($query);

    $idd = getone($conn, 'registration_form', 'unique_id_of_body', 'id', $data['registration_id']);

    if ($data) {
        echo "<p><strong>Reg_Id:</strong> " . $idd . "</p>";
        echo "<p><strong>Follow-Up Perioud:</strong> " . $data['type_of_time'] . "</p>";
        echo "<p><strong>Follow_Up Date:</strong> " . $data['schedule_follow_up_date'] . "</p>";
        echo "<p><strong>Date of Visit:</strong> " . $data['date_of_visit'] . "</p>";
        echo "<p><strong>Baby Waight:</strong> " . $data['baby_weight'] . "</p>";
        echo "<p><strong>Baby Length:</strong> " . $data['baby_length'] . "</p>";
        echo "<p><strong>Baby Head Circumfrence:</strong> " . $data['baby_head_circumference'] . "</p>";
        echo "<p><strong>Immunization Status:</strong> " . $data['immunization_status'] . "</p>";
        echo "<p><strong>Type of Feed:</strong> " . $data['type_of_feed'] . "</p>";
        echo "<p><strong>Other Feed:</strong> " . $data['other_feed'] . "</p>";
        echo "<p><strong>Times Baby Breastfed:</strong> " . $data['times_baby_breastfed'] . "</p>";
        echo "<p><strong>Health Examination:</strong> " . $data['check_health_examination'] . "</p>";
        echo "<p><strong>Mention Identified Health:</strong> " . $data['mention_identified_health'] . "</p>";
    } else {
        echo "<p>No data found.</p>";
    }
}

// Mother and Newborn at MNCU update
if (isset($_POST['mncu_child_id'])) {

    $mncu_id    = $_POST['mncu_child_id'];

    $label_qry = mysqli_query($conn, "SELECT user_id,unique_id_of_body,sncu_id,boby_of_mothers_name FROM registration_form  WHERE id = $mncu_id AND status='1'");
    $label_data = mysqli_fetch_assoc($label_qry);
    $sncu_name =   getname($conn, 'sncu_master', 'sncu_name', 'id', $label_data['sncu_id']);

    $mncu_qry = mysqli_query($conn, "SELECT date_of_admission,new_born_admitted,is_ikmc_provided,number_of_hours_MNCU,family_participatory_care,developmental_supportive,continuous_positive_airway_CPAC,kmc_binders FROM monitoring_data WHERE type_of_monitoring='Mother and Newborn at MNCU' and registration_id = $mncu_id AND status = '1'");

    $mncu_data = mysqli_fetch_assoc($mncu_qry);

    // New Born Admitted
    $nb1 = ($mncu_data['new_born_admitted']) == 'Yes' ? 'selected' : '';
    $nb2 = ($mncu_data['new_born_admitted']) == 'No' ? 'selected' : '';

    // Is IKMC Provided
    $kmc1 = ($mncu_data['is_ikmc_provided']) == 'Yes' ? 'selected' : '';
    $kmc2 = ($mncu_data['is_ikmc_provided']) == 'No' ? 'selected' : '';
    $kmcnone = ($kmc1 != '') ? '' : 'd-none';

    // Family Participatory Care
    $fpc1 = ($mncu_data['family_participatory_care']) == 'Yes' ? 'selected' : '';
    $fpc2 = ($mncu_data['family_participatory_care']) == 'No' ? 'selected' : '';

    // Developmental Supportive Care
    $dsc1 = ($mncu_data['developmental_supportive']) == 'Yes' ? 'selected' : '';
    $dsc2 = ($mncu_data['developmental_supportive']) == 'No' ? 'selected' : '';

    // Positive Airway CPAC
    $cpac1 = ($mncu_data['continuous_positive_airway_CPAC']) == 'Yes' ? 'selected' : '';
    $cpac2 = ($mncu_data['continuous_positive_airway_CPAC']) == 'No' ? 'selected' : '';

    // KMC Binders
    $binder1 = ($mncu_data['kmc_binders']) == 'Yes' ? 'selected' : '';
    $binder2 = ($mncu_data['kmc_binders']) == 'No' ? 'selected' : '';

    echo <<<data
            <div class="row">
                <input type="hidden" name="childidm" id="childidm" value="{$mncu_id}">

                <div class="mb-3">
                    <label class="form-label fs-6"><b>Reg_Id :</b> {$label_data['unique_id_of_body']}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="form-label fs-6"><b>SNCU Name :</b> {$sncu_name}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="form-label fs-6"><b>Mother Name :</b> {$label_data['boby_of_mothers_name']}</label>
                </div>
                
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">MNCU Admission Date</label>
                        <input type="date" id='mncudoa' name="mncudoa" class="form-control" value="{$mncu_data['date_of_admission']}" max="{$maxdate}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">New Born Admitted</label>
                        <select id='brn_admt' name="brn_admt" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes" {$nb1}>Yes</option>
                            <option value="No" {$nb2}>No</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Is IKMC Provided</label>
                        <select id='ikmc' name="ikmc" class="form-control" onchange='updmncuhide(this.value)'>
                            <option value="">--select--</option>
                            <option value="Yes" {$kmc1}>Yes</option>
                            <option value="No" {$kmc2}>No</option>
                        </select> 
                    </div>
                </div>
                <div id="updhidekmc" class="col-3 {$kmcnone}">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Hours in MNCU</label>
                        <input type="text" id='mncu_hrs' name="mncu_hrs" class="form-control" value="{$mncu_data['number_of_hours_MNCU']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Family Participatory Care</label>
                        <select id='fmly_care' name="fmly_care" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes" {$fpc1}>Yes</option>
                            <option value="No" {$fpc2}>No</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Developmental Supportive Care</label>
                        <select id='dpmt_sprt' name="dpmt_sprt" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes" {$dsc1}>Yes</option>
                            <option value="No" {$dsc2}>No</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Positive Airway CPAC</label>
                        <select id='cpac' name="cpac" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes" {$cpac1}>Yes</option>
                            <option value="No" {$cpac2}>No</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">KMC Binders</label>
                        <select id='kmc_bind' name="kmc_bind" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes" {$binder1}>Yes</option>
                            <option value="No" {$binder2}>No</option>
                        </select> 
                    </div>
                </div>

                <script>
                    function updmncuhide(updkmcc){
                            var ikmcupd_fld = document.getElementById('updhidekmc');
                            if(updkmcc == 'Yes') {
                                ikmcupd_fld.classList.remove('d-none');
                            }else{
                                ikmcupd_fld.classList.add('d-none');
                            }
                        }
                </script>
            data;
}

// Mother and Newborn at MNCU Add
if (isset($_POST['madd_child_id'])) {

    $addmncu_id    = $_POST['madd_child_id'];

    $mncu_qry = mysqli_query($conn, "SELECT user_id,unique_id_of_body,sncu_id,boby_of_mothers_name FROM registration_form  WHERE id = $addmncu_id AND status='1'");

    $mncu_data = mysqli_fetch_assoc($mncu_qry);
    $sncu_name =   getname($conn, 'sncu_master', 'sncu_name', 'id', $mncu_data['sncu_id']);

    echo <<<data
            <div class="row">
                <input type="hidden" name="addchildidm" id="addchildidm" value="{$addmncu_id}">
                <input type="hidden" name="addmncuuser_id" id="addmncuuser_id" value="{$mncu_data['user_id']}">

                <div class="mb-3">
                    <label for="firstNameinput" class="form-label fs-6"><b>Reg_Id :</b> {$mncu_data['unique_id_of_body']}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="firstNameinput" class="form-label fs-6"><b>SNCU Name :</b> {$sncu_name}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="firstNameinput" class="form-label fs-6"><b>Mother Name :</b> {$mncu_data['boby_of_mothers_name']}</label>
                </div>
                
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">MNCU Admission Date</label>
                        <input type="date" id='mncudoa' name="addmncudoa" class="form-control" max="{$maxdate}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">New Born Admitted</label>
                        <select id='brn_admt' name="addbrn_admt" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Is IKMC Provided</label>
                        <select id='ikmc' name="addikmc" class="form-control" onchange="ikmvhide(this.value)">
                            <option value="">--select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select> 
                    </div>
                </div>
                <div id="hrs_fld" class="col-3 d-none">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Hours in MNCU</label>
                        <input type="text" id='mncu_hrs' name="addmncu_hrs" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Family Participatory Care</label>
                        <select id='fmly_care' name="addfmly_care" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Developmental Supportive Care</label>
                        <select id='dpmt_sprt' name="adddpmt_sprt" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Positive Airway CPAC</label>
                        <select id='cpac' name="addcpac" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">KMC Binders</label>
                        <select id='kmc_bind' name="addkmc_bind" class="form-control">
                            <option value="">--select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select> 
                    </div>
                </div>
            data;
}
?> 
