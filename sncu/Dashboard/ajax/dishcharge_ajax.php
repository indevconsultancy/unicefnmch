<?php include('../includes/config.php'); ?>
<?php include('../includes/functions.php'); ?>

<?php
// max date
$maxdate = date('Y-m-d');

// Add Addmission Data
if (isset($_POST['new_adm_reg'])) {
    $new_mon_id = $_POST['new_adm_reg'];
    $new_query     = "SELECT id AS reg_id,user_id,unique_id_of_body,sncu_id,boby_of_mothers_name FROM registration_form  WHERE id = $new_mon_id";

    $new_query_res = mysqli_query($conn, $new_query);
    $new_row       = mysqli_fetch_assoc($new_query_res);

    // get sncu_name
    $sncu_name = getname($conn, 'sncu_master', 'sncu_name', 'id', $new_row['sncu_id']);
    echo <<<data
                <div class="row">
                    <input type="hidden" name="type" value="Date of Admission">
                    <input type="hidden" name="user_id" value="{$new_row['user_id']}">
                    <input type="hidden" name="reg_id" value="{$new_row['reg_id']}">
                    
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label fs-6"><b>Reg_Id :</b> {$new_row['unique_id_of_body']}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="firstNameinput" class="form-label fs-6"><b>SNCU Name :</b> {$sncu_name}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="firstNameinput" class="form-label fs-6"><b>Mother Name :</b> {$new_row['boby_of_mothers_name']}</label>
                    </div>

                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Date of Admission</label>
                            <input type="date" id='doa' name="doa" class="form-control" max="{$maxdate}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Admission Weight (kg)</label>
                            <input type="text" id='adm_wt' name="adm_wt" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Admission Length (cm)</label>
                            <input type="text" id='adm_lt' name="adm_lt" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Head Circumference (cm)</label>
                            <input type="text" id='hed_cir' name="hed_cir" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Mode of Feeding</label>
                            <select id='md_of_feed' name="md_of_feed" class="form-control">
                                <option value="">--select feeding--</option>
                                <option value="Breast Feed">Breast Feed</option>
                                <option value="Bottle Feed">Bottle Feed</option>
                                <option value="Tube Feeding">Tube Feeding</option>
                                <option value="Spoon Feed">Spoon Feed</option>
                                <option value="Parenteral Feed">Parenteral Feed</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Growth Chart Used</label>
                            <select id='g_c_usd' name="g_c_usd" class="form-control">
                                <option value="">--select chart--</option>
                                <option value="Fenton's Chart">Fenton's Chart</option>
                                <option value="WHO chart">WHO Chart</option>
                                <option value="Intergrowth">Intergrowth</option>
                                <option value="None">None</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Type of Feed</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed1" type="checkbox" name="type_of_feed[]" value="Breast Milk">
                                    <label class="form-check-label" for="feed1">Breast Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed2" type="checkbox" name="type_of_feed[]" value="Animal Milk">
                                    <label class="form-check-label" for="feed2">Animal Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed3" type="checkbox" name="type_of_feed[]" value="Formula Milk">
                                    <label class="form-check-label" for="feed3">Formula Milk</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed4" type="checkbox" name="type_of_feed[]" value="Parenteral">
                                    <label class="form-check-label" for="feed4">Parenteral</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="othercheckbox" type="checkbox" name="type_of_feed[]" value="Other" onchange="oth_field(this)">
                                    <label class="form-check-label" for="othercheckbox">Other</label>
                                </div>

                                <div class="col-3 text-end" id="otherFeedInput" style="display: none;">
                                    <div class="mb-3">
                                        <input type="text" id='oth_fd' name="oth_fd" class="form-control" placeholder="Specify Others">
                                    </div>
                                </div
                            </div>
                        </div>
                    </div>
        data;
}
// Add Discharge Data
if (isset($_POST['new_dis_reg'])) {
    $new_mon_id = $_POST['new_dis_reg'];
    $new_query     = "SELECT id AS reg_id,user_id,unique_id_of_body,sncu_id,boby_of_mothers_name FROM registration_form  WHERE id = $new_mon_id";

    $new_query_res = mysqli_query($conn, $new_query);
    $new_row       = mysqli_fetch_assoc($new_query_res);

    // get sncu_name
    $sncu_name = getname($conn, 'sncu_master', 'sncu_name', 'id', $new_row['sncu_id']);
    echo <<<data
            <div class="row">
                <input type="hidden" name="type"    value="Discharge Day">
                <input type="hidden" name="user_id" value="{$new_row['user_id']}">
                <input type="hidden" name="reg_id"  value="{$new_row['reg_id']}">
                
                <div class="mb-3">
                    <label for="firstNameinput" class="form-label fs-6"><b>Reg_Id :</b> {$new_row['unique_id_of_body']}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="firstNameinput" class="form-label fs-6"><b>SNCU Name :</b> {$sncu_name}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="firstNameinput" class="form-label fs-6"><b>Mother Name :</b> {$new_row['boby_of_mothers_name']}</label>
                </div>

                 <div class="col-12">
                    <div class="mb-3 me-4">
                        <label for="poc" class="form-label">Progress of Child</label>
                        <select id="pocadd" name="pocadd" class="form-control" onchange="toggleFields(this.value)">
                            <option value="Discharge">Discharge</option>
                            <option value="LAMA">LAMA</option>
                            <option value="Referred">Referred</option>
                            <option value="Death">Death</option>
                        </select>
                    </div>
                </div>
                <div class="row tab-field"> 
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label" id="label1">Date of Discharge</label>
                            <input type="date" id='doa' name="doa" class="form-control" max="{$maxdate}">
                        </div>
                    </div>
                    <div id="hide1" class="col-4 {$death}">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label" id="label2">Discharge Weight(kg)</label>
                            <input type="text" id='adm_wt' name="adm_wt" class="form-control">
                        </div>
                    </div>
                    <div id="hide2" class="col-4 {$death}">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label" id="label3">Discharge Length(cm)</label>
                            <input type="text" id='adm_lt' name="adm_lt" class="form-control">
                        </div>
                    </div>
                    <div id="hide3" class="col-4 {$death}">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label" id="label4">Discharge Head Circumference(cm)</label>
                            <input type="text" id='hed_cir' name="hed_cir" class="form-control">
                        </div>
                    </div>
                    <div id="hide4" class="col-4 {$death}">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Mode of Feeding</label>
                            <select id='md_of_feed' name="md_of_feed" class="form-control">
                                <option value="">--select--</option>
                                <option value="Breast Feed">Breast Feed</option>
                                <option value="Bottle Feed">Bottle Feed</option>
                                <option value="Tube Feeding">Tube Feeding</option>
                                <option value="Spoon Feed">Spoon Feed</option>
                                <option value="Parenteral Feed">Parenteral Feed</option>
                            </select> 
                        </div>
                    </div>
                    <div id="hide5" class="col-4 {$death}">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Growth Chart Used</label>
                            <select id='g_c_usd' name="g_c_usd" class="form-control">
                                <option value="">--select--</option>
                                <option value="Fenton's chart">Fenton's Chart</option>
                                <option value="WHO chart">WHO Chart</option>
                                <option value="Intergrowth">Intergrowth</option>
                                <option value="None">None</option>
                            </select> 
                        </div>
                    </div>
                    <div id="hide6" class="col-12 {$death}">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Type of Feed</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed1" type="checkbox" name="type_of_feed[]" value="Breast Milk">
                                    <label class="form-check-label" for="feed1">Breast Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed2" type="checkbox" name="type_of_feed[]" value="Animal Milk">
                                    <label class="form-check-label" for="feed2">Animal Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed3" type="checkbox" name="type_of_feed[]" value="Formula Milk">
                                    <label class="form-check-label" for="feed3">Formula Milk</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="feed4" type="checkbox" name="type_of_feed[]" value="Parenteral">
                                    <label class="form-check-label" for="feed4">Parenteral</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="othercheckbox" type="checkbox" name="type_of_feed[]" value="Other" onchange="oth_fielddd(this)">
                                    <label class="form-check-label" for="othercheckbox">Other</label>
                                </div>
                                <div id="otherfl" class="mb-3 d-none">
                                    <input type="text" id='oth_fd' placeholder="specify_other" name="oth_fd" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        data;
}
// Update Addmission Data
if (isset($_POST['adm_regg_id'])) {
    $regg_id = $_POST['adm_regg_id'];

    $label_qry = "SELECT unique_id_of_body,sncu_id,boby_of_mothers_name FROM registration_form WHERE id = $regg_id";
    $label_exe = mysqli_query($conn, $label_qry);
    $label_data = mysqli_fetch_assoc($label_exe);
    // get sncu_name
    $sncu_name = getname($conn, 'sncu_master', 'sncu_name', 'id', $label_data['sncu_id']);

    $reg_query     = "SELECT date_of_admission,admission_weight,admission_length,admission_head_circumference,type_of_feed,other_feed,mode_of_feeding,growth_chart_used 
                          FROM monitoring_data WHERE registration_id = $regg_id and type_of_monitoring = 'Date of Admission'";
    $reg_query_res = mysqli_query($conn, $reg_query);
    $reg_row       = mysqli_fetch_assoc($reg_query_res);

    // Type of Feed
    $type_of_feed_arr = explode(',', $reg_row['type_of_feed']);
    $bm   = in_array('Breast Milk', $type_of_feed_arr) ? 'checked' : '';
    $am   = in_array('Animal Milk', $type_of_feed_arr) ? 'checked' : '';
    $fm   = in_array('Formula Milk', $type_of_feed_arr) ? 'checked' : '';
    $par  = in_array('Parenteral', $type_of_feed_arr) ? 'checked' : '';
    $oth  = in_array('Other', $type_of_feed_arr) ? 'checked' : '';
    $othnone = ($oth != '') ? '' : 'd-none';

    // Select mode_of_feeding
    $breast   = ($reg_row['mode_of_feeding']) == 'Breast Feed' ? 'selected' : '';
    $bottal   = ($reg_row['mode_of_feeding']) == 'Bottle Feed' ? 'selected' : '';
    $tube     = ($reg_row['mode_of_feeding']) == 'Tube Feeding' ? 'selected' : '';
    $spoon    = ($reg_row['mode_of_feeding']) == 'Spoon Feed' ? 'selected' : '';
    $prantral = ($reg_row['mode_of_feeding']) == 'Parenteral Feed' ? 'selected' : '';

    // Select mode_of_feeding
    $fenton      = ($reg_row['growth_chart_used'] == "Fenton's chart") ? 'selected' : '';
    $who         = ($reg_row['growth_chart_used'] == 'WHO chart') ? 'selected' : '';
    $intergrowth = ($reg_row['growth_chart_used'] == 'Intergrowth') ? 'selected' : '';
    $none        = ($reg_row['growth_chart_used'] == 'None') ? 'selected' : '';


    echo <<<data
                <div class="row">
                    <input type="hidden" name="child_id" id="childid" value="{$regg_id}">
                    <input type="hidden" name="type" id="type" value="Date of Admission">

                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label fs-6"><b>Reg_Id :</b> {$label_data['unique_id_of_body']}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="firstNameinput" class="form-label fs-6"><b>SNCU Name :</b> {$sncu_name}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="firstNameinput" class="form-label fs-6"><b>Mother Name :</b> {$label_data['boby_of_mothers_name']}</label>
                    </div>
                    
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Date of Admission</label>
                            <input type="date" id='doa' name="doa" class="form-control" value="{$reg_row['date_of_admission']}" max="{$maxdate}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Admission Weight (kg)</label>
                            <input type="text" id='adm_wt' name="adm_wt" class="form-control" value="{$reg_row['admission_weight']}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Admission Length (cm)</label>
                            <input type="text" id='adm_lt' name="adm_lt" class="form-control" value="{$reg_row['admission_length']}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Head Circumference (cm)</label>
                            <input type="text" id='hed_cir' name="hed_cir" class="form-control" value="{$reg_row['admission_head_circumference']}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Mode of Feeding</label>
                            <select id='md_of_feed' name="md_of_feed" class="form-control">
                                <option value="">--select feed--</option>
                                <option value="Breast Feed" {$breast}>Breast Feed</option>
                                <option value="Bottle Feed" {$bottal}>Bottle Feed</option>
                                <option value="Tube Feeding" {$tube}>Tube Feeding</option>
                                <option value="Spoon Feed" {$spoon}>Spoon Feed</option>
                                <option value="Parenteral Feed" {$prantral}>Parenteral Feed</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Growth Chart Used</label>
                            <select id='g_c_usd' name="g_c_usd" class="form-control">
                                <option value="">--select chart--</option>
                                <option value="Fenton's chart" {$fenton}>Fenton's Chart</option>
                                <option value="WHO chart" {$who}>WHO Chart</option>
                                <option value="Intergrowth" {$intergrowth}>Intergrowth</option>
                                <option value="None" {$none}>None</option>
                            </select> 
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="firstNameinput" class="form-label">Type of Feed</label>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Breast Milk" id="feed1" $bm>
                                    <label class="form-check-label" for="feed1">Breast Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Animal Milk" id="feed2" $am>
                                    <label class="form-check-label" for="feed2">Animal Milk</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Formula Milk" id="feed3" $fm>
                                    <label class="form-check-label" for="feed3">Formula Milk</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Parenteral" id="feed4" $par>
                                    <label class="form-check-label" for="feed4">Parenteral</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Other" id="othercheckbox" $oth onchange="other_fld(this)">
                                    <label class="form-check-label" for="othercheckbox">Other</label>
                                </div>
                                <div id="otherfl" class="mb-3 {$othnone}">
                                    
                                    <input type="text" id='oth_fd' name="oth_fd" placeholder="other_feed" class="form-control" value="{$reg_row['other_feed']}">
                                </div>
                            </div>
                        </div>
                    </div>

            data;
}
// Update Discharge Data
if (isset($_POST['dis_regg_id'])) {
    $regg_id = $_POST['dis_regg_id'];

    $label_qry = "SELECT unique_id_of_body,sncu_id,boby_of_mothers_name FROM registration_form WHERE id = $regg_id";
    $label_exe = mysqli_query($conn, $label_qry);
    $label_data = mysqli_fetch_assoc($label_exe);
    // get sncu_name
    $sncu_name = getname($conn, 'sncu_master', 'sncu_name', 'id', $label_data['sncu_id']);

    $reg_query = "SELECT * FROM monitoring_data WHERE registration_id = $regg_id and type_of_monitoring = 'Discharge Day' and status = '1'";
    $reg_query_res = mysqli_query($conn, $reg_query);
    $reg_row = mysqli_fetch_assoc($reg_query_res);

    // Type of Feed
    $type_of_feed_arr = explode(',', $reg_row['type_of_feed']);
    $bm   = in_array('Breast Milk', $type_of_feed_arr) ? 'checked' : '';
    $am   = in_array('Animal Milk', $type_of_feed_arr) ? 'checked' : '';
    $fm   = in_array('Formula Milk', $type_of_feed_arr) ? 'checked' : '';
    $par  = in_array('Parenteral', $type_of_feed_arr) ? 'checked' : '';
    $oth  = in_array('Other', $type_of_feed_arr) ? 'checked' : '';
    $othnone = ($oth != '') ? '' : 'd-none';

    $dis1 = ($reg_row['progress_of_child'] == 'Discharge') ? 'selected' : '';
    $lama = ($reg_row['progress_of_child'] == 'LAMA') ? 'selected' : '';
    $ref1 = ($reg_row['progress_of_child'] == 'Referred') ? 'selected' : '';
    $dth  = ($reg_row['progress_of_child'] == 'Death') ? 'selected' : '';
    // for label
    if ($dis1 != '') {
        $death = '';
        $label1 = 'Date of Discharge';
        $label2 = 'Discharge Weight(kg)';
        $label3 = 'Discharge Length(cm)';
        $label4 = 'Head Circumference(cm)';
    } else if ($lama != '') {
        $death = '';
        $label1 = 'Last Date at SNCU';
        $label2 = 'Last Measured Weight(kg)';
        $label3 = 'Last Measured Length(cm)';
        $label4 = 'Last Measured Head Circumference(cm)';
    } else if ($ref1 != '') {
        $death = '';
        $label1 = 'Date of Referred';
        $label2 = 'Referred Measured Weight(kg)';
        $label3 = 'Referred Measured Length(cm)';
        $label4 = 'Referred Measured Head Circumference(cm)';
    } else {
        $label1 = 'Date of Death';
        $death = 'd-none';
    }
    // Select mode_of_feeding
    $breast   = ($reg_row['mode_of_feeding'] == 'Breast Feed') ? 'selected' : '';
    $bottal   = ($reg_row['mode_of_feeding'] == 'Bottle Feed') ? 'selected' : '';
    $tube     = ($reg_row['mode_of_feeding'] == 'Tube Feeding') ? 'selected' : '';
    $spoon    = ($reg_row['mode_of_feeding'] == 'Spoon Feed') ? 'selected' : '';
    $prantral = ($reg_row['mode_of_feeding'] == 'Parenteral Feed') ? 'selected' : '';

    // Select mode_of_feeding
    $fenton      = ($reg_row['growth_chart_used'] == "Fenton's chart") ? 'selected' : '';
    $who         = ($reg_row['growth_chart_used'] == 'WHO chart') ? 'selected' : '';
    $intergrowth = ($reg_row['growth_chart_used'] == 'Intergrowth') ? 'selected' : '';
    $none        = ($reg_row['growth_chart_used'] == 'None') ? 'selected' : '';


    echo <<<data
                <div class="row">
                    <input type="hidden" name="child_id" id="childid" value="{$regg_id}">
                    <input type="hidden" name="type" id="type" value="Discharge Day">

                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label fs-6"><b>Reg_Id :</b> {$label_data['unique_id_of_body']}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="firstNameinput" class="form-label fs-6"><b>SNCU Name :</b> {$sncu_name}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="firstNameinput" class="form-label fs-6"><b>Mother Name :</b> {$label_data['boby_of_mothers_name']}</label>
                    </div>
                    
                   <div class="col-12">
                        <div class="mb-3 me-4">
                            <label for="poc" class="form-label">Progress of Child</label>
                            <select id="pocadd" name="poc" class="form-control" onchange="toggleFields(this.value)">
                                <option value="Discharge" {$dis1}>Discharge</option>
                                <option value="LAMA" {$lama}>LAMA</option>
                                <option value="Referred" {$ref1}>Referred</option>
                                <option value="Death" {$dth}>Death</option>
                            </select>
                        </div>
                    </div>
                    <div class="row tab-field"> 
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="firstNameinput" class="form-label" id="label1">{$label1}</label>
                                <input type="date" id='doa' name="doa" class="form-control"  value="{$reg_row['date_of_admission']}" max="{$maxdate}">
                            </div>
                        </div>
                        <div id="hide1" class="col-4 {$death}">
                            <div class="mb-3">
                                <label for="firstNameinput" class="form-label" id="label2">{$label2}</label>
                                <input type="text" id='adm_wt' name="adm_wt" class="form-control" value="{$reg_row['admission_weight']}">
                            </div>
                        </div>
                        <div id="hide2" class="col-4 {$death}">
                            <div class="mb-3">
                                <label for="firstNameinput" class="form-label" id="label3">{$label3}</label>
                                <input type="text" id='adm_lt' name="adm_lt" class="form-control" value="{$reg_row['admission_length']}">
                            </div>
                        </div>
                        <div id="hide3" class="col-4 {$death}">
                            <div class="mb-3">
                                <label for="firstNameinput" class="form-label" id="label4">{$label4}</label>
                                <input type="text" id='hed_cir' name="hed_cir" class="form-control" value="{$reg_row['admission_head_circumference']}">
                            </div>
                        </div>
                        <div id="hide4" class="col-4 {$death}">
                            <div class="mb-3">
                                <label for="firstNameinput" class="form-label">Mode of Feeding</label>
                                <select id='md_of_feed' name="md_of_feed" class="form-control">
                                    <option value="">--select--</option>
                                    <option value="Breast Feed" {$breast}>Breast Feed</option>
                                    <option value="Bottle Feed" {$bottal}>Bottle Feed</option>
                                    <option value="Tube Feeding" {$tube}>Tube Feeding</option>
                                    <option value="Spoon Feed" {$spoon}>Spoon Feed</option>
                                    <option value="Parenteral Feed" {$prantral}>Parenteral Feed</option>
                                </select> 
                            </div>
                        </div>
                        <div id="hide5" class="col-4 {$death}">
                            <div class="mb-3">
                                <label for="firstNameinput" class="form-label">Growth Chart Used</label>
                                <select id='g_c_usd' name="g_c_usd" class="form-control">
                                    <option value="">--select--</option>
                                    <option value="Fenton's chart" {$fenton}>Fenton's Chart</option>
                                    <option value="WHO chart" {$who}>WHO Chart</option>
                                    <option value="Intergrowth" {$intergrowth}>Intergrowth</option>
                                    <option value="None" {$none}>None</option>
                                </select> 
                            </div>
                        </div>
                        <div id="hide6" class="col-12 {$death}">
                            <div class="mb-3">
                                <label for="firstNameinput" class="form-label">Type of Feed</label>
                                <div class="d-flex">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"  type="checkbox" name="type_of_feed[]" value="Breast Milk" id="feed1" $bm>
                                        <label class="form-check-label" for="feed1">Breast Milk</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Animal Milk" id="feed2" $am>
                                        <label class="form-check-label" for="feed2">Animal Milk</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Formula Milk" id="feed3" $fm>
                                        <label class="form-check-label" for="feed3">Formula Milk</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Parenteral" id="feed4" $par>
                                        <label class="form-check-label" for="feed4">Parenteral</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_of_feed[]" value="Other" id="othercheckbox" $oth onchange="other_fld(this)">
                                        <label class="form-check-label" for="othercheckbox">Other</label>
                                    </div>
                                    <div id="otherfl" class="mb-3 {$othnone}">
                                        <input type="text" id='oth_fd' name="oth_fd" placeholder="other_feed" class="form-control" value="{$reg_row['other_feed']}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            data;
}
// Show dishcharge data
if (isset($_POST['dish_id']) && isset($_POST['uniq_id'])) {

    $id    = $_POST['dish_id'];
    $uniqueid    = $_POST['uniq_id'];

    $query = mysqli_query($conn, "SELECT r.unique_id_of_body,m.date_of_admission,m.admission_weight,m.admission_length,m.admission_head_circumference,m.type_of_feed,m.type_of_feed,m.other_feed,m.mode_of_feeding,m.growth_chart_used FROM monitoring_data AS m JOIN registration_form AS r ON m.registration_id = r.id WHERE m.id = $id and  r.unique_id_of_body = '$uniqueid'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        echo "<table cellpadding='6' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";

        echo "<tr><td><strong>Unique Id</strong></td><td>:</td><td>" . $uniqueid . "</td></tr>";
        echo "<tr><td><strong>Date of Discharge</strong></td><td>:</td><td>" . $data['date_of_admission'] . "</td></tr>";
        echo "<tr><td><strong>Admission Weight</strong></td><td>:</td><td>" . $data['admission_weight'] . "</td></tr>";
        echo "<tr><td><strong>Admission Length</strong></td><td>:</td><td>" . $data['admission_length'] . "</td></tr>";
        echo "<tr><td><strong>Admission Head Circumference</strong></td><td>:</td><td>" . $data['admission_head_circumference'] . "</td></tr>";
        echo "<tr><td><strong>Type of Feed</strong></td><td>:</td><td>" . $data['type_of_feed'] . "</td></tr>";
        echo "<tr><td><strong>Other Feed</strong></td><td>:</td><td>" . $data['other_feed'] . "</td></tr>";
        echo "<tr><td><strong>Mode of Feeding</strong></td><td>:</td><td>" . $data['mode_of_feeding'] . "</td></tr>";
        echo "<tr><td><strong>Growth Chart Used</strong></td><td>:</td><td>" . $data['growth_chart_used'] . "</td></tr>";

        echo "</table>";
    } else {
        echo "<p>No data found.</p>";
    }
}
?>