<?php include('../includes/config.php'); ?>

<?php
// max date
$maxdate = date('Y-m-d');

// registration details
if (isset($_POST['reg_id']) && isset($_POST['unique1'])) {

    $id    = $_POST['reg_id'];
    $uniqueid    = $_POST['unique1'];

    $query = mysqli_query($conn, "SELECT d.district_name,r.sncu_id,r.monitor_institution,r.boby_of_mothers_name,r.fathers_name,r.phone_number_of_mother_father_caregivers,r.sex,r.delivery_type,r.birth_weight_kg,r.growth_chart_used,r.immunization_status,r.monitoring_status,r.status FROM registration_form AS r JOIN district_master AS d ON r.district_id = d.district_id WHERE r.id = $id and  r.unique_id_of_body = '$uniqueid'");
    $data = mysqli_fetch_assoc($query);

    // sncu name
    $sncu_qry = mysqli_query($conn, "SELECT sncu_name FROM sncu_master WHERE id = {$data['sncu_id']}");
    $sncu_data = mysqli_fetch_assoc($sncu_qry);

    if ($data) {
        echo "<table cellpadding='6' cellspacing='0' style='border-collapse: collapse;'>";

        echo "<tr><td><strong>Unique Id</strong></td><td>:</td><td>" . $uniqueid . "</td></tr>";
        echo "<tr><td><strong>SNCU Name</strong></td><td>:</td><td>" . $sncu_data['sncu_name'] . "</td></tr>";
        echo "<tr><td><strong>Monitor Institution</strong></td><td>:</td><td>" . $data['monitor_institution'] . "</td></tr>";
        echo "<tr><td><strong>Baby Mother Name</strong></td><td>:</td><td>" . $data['boby_of_mothers_name'] . "</td></tr>";
        echo "<tr><td><strong>Baby Father Name</strong></td><td>:</td><td>" . $data['fathers_name'] . "</td></tr>";
        echo "<tr><td><strong>Phone Number</strong></td><td>:</td><td>" . $data['phone_number_of_mother_father_caregivers'] . "</td></tr>";
        echo "<tr><td><strong>Gender</strong></td><td>:</td><td>" . $data['sex'] . "</td></tr>";
        echo "<tr><td><strong>Delivery Type</strong></td><td>:</td><td>" . $data['delivery_type'] . "</td></tr>";
        echo "<tr><td><strong>Baby Birth Weight</strong></td><td>:</td><td>" . $data['birth_weight_kg'] . "</td></tr>";
        echo "<tr><td><strong>Growth Chart Used</strong></td><td>:</td><td>" . $data['growth_chart_used'] . "</td></tr>";
        echo "<tr><td><strong>Immunization Status</strong></td><td>:</td><td>" . $data['immunization_status'] . "</td></tr>";
        echo "<tr><td><strong>Monitoring Status</strong></td><td>:</td><td>" . $data['monitoring_status'] . "</td></tr>";

        echo "</table>";
    } else {
        echo "<p>No data found.</p>";
    }
}

// Follow up details
if (isset($_POST['followup_id']) && isset($_POST['unique2'])) {
    $id = $_POST['followup_id'];
    $unique2 = $_POST['unique2'];

    $query = mysqli_query($conn, "SELECT f.schedule_follow_up_date,f.baby_weight,f.baby_length,f.baby_head_circumference,f.immunization_status,f.type_of_feed,f.type_of_feed,f.other_feed,f.times_baby_breastfed,f.check_health_examination,f.mention_identified_health,f.asha_check_following_infant FROM follow_up AS f JOIN registration_form AS r ON f.registration_id=r.id WHERE f.registration_id = $id and r.unique_id_of_body = '$unique2'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        echo "<table cellpadding='6' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";

        echo "<tr><td><strong>Unique Id</strong></td><td>:</td><td>" . $unique2 . "</td></tr>";
        echo "<tr><td><strong>Schedule Follow-Up Date</strong></td><td>:</td><td>" . $data['schedule_follow_up_date'] . "</td></tr>";
        echo "<tr><td><strong>Baby Weight</strong></td><td>:</td><td>" . $data['baby_weight'] . "</td></tr>";
        echo "<tr><td><strong>Baby Length</strong></td><td>:</td><td>" . $data['baby_length'] . "</td></tr>";
        echo "<tr><td><strong>Baby Head Circumference</strong></td><td>:</td><td>" . $data['baby_head_circumference'] . "</td></tr>";
        echo "<tr><td><strong>Immunization Status</strong></td><td>:</td><td>" . $data['immunization_status'] . "</td></tr>";
        echo "<tr><td><strong>Type of Feed</strong></td><td>:</td><td>" . $data['type_of_feed'] . "</td></tr>";
        echo "<tr><td><strong>Other Feed</strong></td><td>:</td><td>" . $data['other_feed'] . "</td></tr>";
        echo "<tr><td><strong>Times of Baby Breastfed</strong></td><td>:</td><td>" . $data['times_baby_breastfed'] . "</td></tr>";
        echo "<tr><td><strong>Health Examination</strong></td><td>:</td><td>" . $data['check_health_examination'] . "</td></tr>";
        echo "<tr><td><strong>Mention Identified Health</strong></td><td>:</td><td>" . $data['mention_identified_health'] . "</td></tr>";
        echo "<tr><td><strong>Asha Checked</strong></td><td>:</td><td>" . $data['asha_check_following_infant'] . "</td></tr>";

        echo "</table>";
    } else {
        echo "<p>No data found.</p>";
    }
}

// Add registration
if (isset($_POST['add_reg'])) {

    $dis = mysqli_query($conn, "SELECT district_id,district_name FROM district_master WHERE status='1'");
    $m_name = mysqli_query($conn, "SELECT id,username FROM users WHERE status='1'");

    echo <<<data
            <div class="row">
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">District Name</label>
                        <select id='dist_name' name="dist_name" class="form-control" onchange="sel_disct(this.value)">
                            <option value="">--select--</option>
    data;
    while ($row = mysqli_fetch_assoc($dis)) {
        $district = htmlspecialchars($row['district_name']);
        $district_id = htmlspecialchars($row['district_id']);
        echo "<option value='$district_id'>$district</option>";
    }
    echo <<<data
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">SNCU Name</label>
                        <input type="hidden" id='real_sncuid' name="real_sncuid" class="form-control">
                        <input type="text" id='sncu_name' name="sncu_name" class="form-control" placeholder="SNCU Name" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <input type="hidden" id='mtr_namee' name="mtr_namee" class="form-control">
                        <label for="firstNameinput" class="form-label">Monitor Name</label>
                        <select id='mon_name' name="mon_name" class="form-control" onchange="sel_monoter(this)">
                            <option value="">--select--</option>
    data;
    while ($rows = mysqli_fetch_assoc($m_name)) {
        $m_nme = htmlspecialchars($rows['username']);
        $m_id = htmlspecialchars($rows['id']);
        echo "<option value='$m_id' data-name='$m_nme'>$m_nme</option>";
    }
    echo <<<data
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Monitor Institution</label>
                        <select id='mn_ins' name="monitor_insti" class="form-control">
                            <option value="">--select--</option>
                            <option value="UNICEF"> UNICEF</option>
                            <option value="AIIMS Patna"> AIIMS Patna</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Year of Registration</label>
                        <select id='dateofyear' name="reg_year" onchange="yearofreg(this.value)" class="form-control">
                            <option value="">--select--</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">SNCU Registration Serial No</label>
                        <input type="text" id='serialno' name="serialno" oninput="serialnofn(this.value)" class="form-control" placeholder="Serial No">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Unique ID of Baby</label>
                        <input type="text" id='unq_id' name="unq_id" class="form-control" placeholder="Unique Id" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Baby Name</label>
                        <input type="text" id='bb_name' name="baby_name" class="form-control" placeholder="Optional Field">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Baby of(Mother's Name)</label>
                        <input type="text" id='mdr_name' name="mdr_name" class="form-control" placeholder="Enter mother Name">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Father's Name</label>
                        <input type="text" id='fdr_name' name="fdr_name" class="form-control" placeholder="Enter father Name">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Phone Number</label>
                        <input type="text" id='ph_no' name="phone_number" class="form-control" maxlength="10" placeholder="Enter Ph.No. Name">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Baby Date of Birth(DOB)</label>
                        <input type="date" id='b_dob' name="b_dob" class="form-control" placeholder="Enter Baby DOB" max="{$maxdate}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Gender</label>
                        <select id='gender' name="gender" class="form-control">
                            <option value="">--select--</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Delivery Type</label>
                        <select id='dlry_type' name="dlry_type" class="form-control">
                            <option value="">--select--</option>
                            <option value="C-section"> C-section</option>
                            <option value="Normal"> Normal</option>
                        </select>                    
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Birth weight(Kg)</label>
                        <input type="text" id='bbwt' oninput="cal_lbw(this.value)" name="baby_wt" class="form-control" placeholder="Enter Baby Weight">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Was baby LBW or Not</label>
                        <input type="text" id='lbw_val' name="lbw_val" class="form-control" placeholder="Was Baby LBW or Not" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Gestational Age</label>
                        <input type="text" id='gest_age' name="gest_age" class="form-control" placeholder="Enter Your Gestational Age">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Immunization Status</label>
                        <select id='immu_status' name="immu_status" class="form-control" onchange="imm_vfcn(this.value)">
                            <option value="">--select--</option>
                            <option value="Up to date"> Up to date</option>
                            <option value="Not updated"> Not updated</option>
                        </select>                    
                    </div>
                </div>
                <div id="means_vrfcn" class="col-3 d-none">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Means for Varification</label>
                        <input type="text" id='vrfi_mean' name="vrfi_mean" class="form-control" placeholder="Enter Immunization Status">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mother Age</label>
                        <input type="number" id='mdr_age' name="mdr_age" class="form-control" placeholder="Enter Mother Age">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mother Weight</label>
                        <input type="text" id='mdr_wt' name="mdr_wt" class="form-control" placeholder="Enter Mother Weight">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mother Age at Marriage</label>
                        <input type="number" id='age_marrige' name="age_marrige" class="form-control" placeholder="Enter Mother Age at Marriage">
                    </div>
                </div>
                <div class="col-3">
                    <label for="firstNameinput" class="form-label">Reason of Admit</label>
                    <div class="d-flex">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="otr1" type="checkbox" name="admt_res[]" value="LBW">
                            <label class="form-check-label" for="otr1">LBW</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="otr2" type="checkbox" name="admt_res[]" value="Preterm">
                            <label class="form-check-label" for="otr2">Preterm</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="otrr3" type="checkbox" name="admt_res[]" value="Others" onchange='oth_field(this)'>
                            <label class="form-check-label" for="otrr3">Others</label>
                        </div>
                    </div>
                </div>
                <div class="col-3 d-none" id="other_field">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mention Other Reason</label>
                        <input type="text" id='oth_res' name="oth_res" class="form-control" placeholder="specify_other">
                    </div>
                </div>
            </div> 
 data;
}
// sncu_data fetch
if (isset($_POST['ss_id'])) {
    $sd = $_POST['ss_id'];
    $dis = mysqli_query($conn, "SELECT id,sncu_name,sncu_code FROM sncu_master WHERE district_id = '$sd' AND status='1'");
    $dis_data = mysqli_fetch_assoc($dis);
    $finaldataarray = [];
    $finaldataarray['id'] = $dis_data['id'];
    $finaldataarray['sncu_name'] = $dis_data['sncu_name'];
    $finaldataarray['sncu_code'] = $dis_data['sncu_code'];
    $arraytojson = json_encode($finaldataarray, true);
    echo $arraytojson;
}

// update registration
if (isset($_POST['reg_id']) && isset($_POST['reg_uniq_id'])) {
    $reg_id = $_POST['reg_id'];
    $reg_uniq_id = $_POST['reg_uniq_id'];

    $reg_query = "SELECT * FROM registration_form WHERE id = $reg_id AND unique_id_of_body = '$reg_uniq_id'";
    $reg_query_res = mysqli_query($conn, $reg_query);
    $reg_row = mysqli_fetch_assoc($reg_query_res);

    $reason_for_admission = $reg_row['reason_for_admission'];
    if ($reason_for_admission == 'Others') {
        $dnone = '';
    } else {
        $dnone = 'd-none';
    }

    // Select Institution
    $unicef = ($reg_row['monitor_institution']) == 'UNICEF' ? 'selected' : '';
    $ams_patna = ($reg_row['monitor_institution']) == 'AIIMS Patna' ? 'selected' : '';
    // Select Delivery_type
    $c_sec_type = ($reg_row['delivery_type']) == 'C-section' ? 'selected' : '';
    $normal_type = ($reg_row['delivery_type']) == 'Normal' ? 'selected' : '';
    // select immunization status
    $im_sta_not = ($reg_row['immunization_status']) == 'Not updated' ? 'selected' : '';
    $im_sta_up  = ($reg_row['immunization_status']) == 'Up to date' ? 'selected' : '';
    $immunone = ($im_sta_up != '') ? '' : 'd-none';

    // select immunization varification
    $im_sta_not = ($reg_row['immunization_status']) == 'Not updated' ? 'selected' : '';
    $im_sta_up  = ($reg_row['immunization_status']) == 'Up to date' ? 'selected' : '';
    // admit reason
    $res_adm = explode(',', $reg_row['reason_for_admission']);
    $lbw = (in_array('LBW', $res_adm)) ? 'checked' : '';
    $preterm = (in_array('Preterm', $res_adm)) ? 'checked' : '';
    $other = (in_array('Others', $res_adm)) ? 'checked' : '';

    // other reason value
    $reason_for_admission = $reg_row['reason_for_admission'];
    $dnone = ($reason_for_admission == 'Others') ? '' : 'd-none';
    // Select Gender
    $male1 = ($reg_row['sex']) == 'Male' ? 'selected' : '';
    $female1 = ($reg_row['sex']) == 'Female' ? 'selected' : '';
    $other1 = ($reg_row['sex']) == 'Other' ? 'selected' : '';

    echo <<<data
            <div class="row">
                <input type="hidden" name="child_id" id="childid" value="{$reg_id}">
                
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Monitor Institution</label>
                        <select id='mn_ins' name="monitor_institution" class="form-control">
                            <option value="UNICEF" {$unicef}> UNICEF</option>
                            <option value="AIIMS Patna" {$ams_patna}> AIIMS Patna</option>
                        </select> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Baby Name</label>
                        <input type="text" id='bb_name' name="baby_name" class="form-control" placeholder="Optional Field" value="{$reg_row['boby_name_optional']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mother Name</label>
                        <input type="text" id='mdr_name' name="monitor_name" class="form-control" placeholder="Enter your Facility Name" value="{$reg_row['boby_of_mothers_name']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Father Name</label>
                        <input type="text" id='fdr_name' name="father_name" class="form-control" placeholder="Enter your Facility Name" value="{$reg_row['fathers_name']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Phone Number</label>
                        <input type="text" id='ph_no' name="phone_number" class="form-control" maxlength="10" placeholder="Enter your Facility Name" value="{$reg_row['phone_number_of_mother_father_caregivers']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">DOB</label>
                        <input type="date" id='dob' name="dob" class="form-control" placeholder="Enter your Facility Name" value="{$reg_row['baby_date_of_birth']}" max="{$maxdate}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Gender</label>
                        <select id='gen' name="gender" class="form-control">
                            <option value="Male" {$male1}>Male</option>
                            <option value="Female" {$female1}>Female</option>
                            <option value="Other" {$other1}>Other</option>
                        </select>
                        </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Delivery Type</label>
                        <select id='dlry_type' name="delivery_type" class="form-control">
                            <option value="C-section" {$c_sec_type}> C-section</option>
                            <option value="Normal" {$normal_type}> Normal</option>
                        </select>                    
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Gestational Age</label>
                        <input type="text" id='gest_age' name="gest" class="form-control" placeholder="Update your gestational age" value="{$reg_row['gestational_age_LBW']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Immunization Status</label>
                        <select id='immu_status' name="immunization_status" class="form-control" onchange="mng_immu(this.value)">
                            <option value="Up to date" {$im_sta_up}> Up to date</option>
                            <option value="Not updated" {$im_sta_not}> Not updated</option>
                        </select>                    
                    </div>
                </div>
                <div id="means_vrfcn" class="col-3 {$immunone}">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Means for Varification</label>
                        <input type="text" id='updvrfi_mean' name="updvrfi_mean" class="form-control" value="{$reg_row['means_for_verification_immunization']}" placeholder="Enter Immunization Status">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Birth Waight</label>
                        <input type="text" id='updbrth_wt' name="birth_waight" class="form-control" oninput="calregwt(this.value)" placeholder="Enter your Birth Weight" value="{$reg_row['birth_weight_kg']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Was baby LBW or Not</label>
                        <input type="text" id='updlbw_val' name="updlbw_val" class="form-control" value="{$reg_row['was_baby_lbw_at_time_of_birth_kg']}" placeholder="Was Baby LBW or Not" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mother Age</label>
                        <input type="number" id='updmdr_age' name="mother_age" class="form-control" placeholder="Enter your Facility Name" value="{$reg_row['mothers_age_years']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mother Weight</label>
                        <input type="text" id='updmdr_wt' name="mother_weight" class="form-control" placeholder="Enter your Facility Name" value="{$reg_row['mother_weight_kg']}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="firstNameinput" class="form-label">Mother Age at Marrage</label>
                        <input type="number" id='updage_marrige' name="marrage_weight" class="form-control" placeholder="Enter your Facility Name" value="{$reg_row['age_at_marrage_years']}">
                    </div>
                </div>
                <div class="col-3">
                    <label for="firstNameinput" class="form-label">Reason of Admit</label>
                    <div class="d-flex">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="otr1" type="checkbox" name="admit_reason[]" value="LBW" {$lbw}>
                            <label class="form-check-label" for="otr1">LBW</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="otr2" type="checkbox" name="admit_reason[]" value="Preterm" {$preterm}>
                            <label class="form-check-label" for="otr2">Preterm</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="otr3" type="checkbox" name="admit_reason[]" value="Others" onchange='oth_field(this)' {$other}>
                            <label class="form-check-label" for="otr3">Others</label>
                        </div>
                    </div>
                </div>
                <div id="other_field" class="col-3 mb-3 {$dnone}">
                    <label for="firstNameinput" class="form-label">Mention Other Reason</label>
                    <input type="hidden" name="checkother" id="checkother" value="$dnone">
                    <input type="text" id='updoth_res' name="other_reason" class="form-control" placeholder="Enter your Facility Name" value="{$reg_row['mention_other_reason']}">
                </div>
            </div>
        data;
}
?>