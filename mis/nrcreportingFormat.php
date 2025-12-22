<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
include_once('includes/config.php');


if (isset($_REQUEST['submit'])) {
    $health_facility = $_REQUEST['health_facility'];
    $district = $_REQUEST['district'];
    $mode_of_operationalizatio = $_REQUEST['mode_of_operationalizatio'];
    $month_year = $_REQUEST['month_year'];
    $number_of_beds_male = $_REQUEST['number_of_beds_male'];
    $number_of_beds_female = $_REQUEST['number_of_beds_female'];
    $number_of_beds_total = $_REQUEST['number_of_beds_total'];
    $total_admission_male = $_REQUEST['total_admission_male'];
    $total_admission_female = $_REQUEST['total_admission_female'];
    $total_admission_total = $_REQUEST['total_admission_total'];
    $caste_distribution_SC_male = $_REQUEST['caste_distribution_SC_male'];
    $caste_distribution_SC_female = $_REQUEST['caste_distribution_SC_female'];
    $caste_distribution_SC_total = $_REQUEST['caste_distribution_SC_total'];
    $caste_distribution_ST_male = $_REQUEST['caste_distribution_ST_male'];
    $caste_distribution_ST_female = $_REQUEST['caste_distribution_ST_female'];
    $caste_distribution_ST_total = $_REQUEST['caste_distribution_ST_total'];
    $caste_distribution_OBC_male = $_REQUEST['caste_distribution_OBC_male'];
    $caste_distribution_OBC_female = $_REQUEST['caste_distribution_OBC_female'];
    $caste_distribution_OBC_total = $_REQUEST['caste_distribution_OBC_total'];
    $caste_distribution_EBC_male = $_REQUEST['caste_distribution_EBC_male'];
    $caste_distribution_EBC_female = $_REQUEST['caste_distribution_EBC_female'];
    $caste_distribution_EBC_total = $_REQUEST['caste_distribution_EBC_total'];
    $caste_distribution_GEN_male = $_REQUEST['caste_distribution_GEN_male'];
    $caste_distribution_GEN_female = $_REQUEST['caste_distribution_GEN_female'];
    $caste_distribution_GEN_total = $_REQUEST['caste_distribution_GEN_total'];
    $economical_status = "";
    $economical_status_BPL_male = $_REQUEST['economical_status_BPL_male'];
    $economical_status_BPL_female = $_REQUEST['economical_status_BPL_female'];
    $economical_status_BPL_total = $_REQUEST['economical_status_BPL_total'];
    $economical_status_APL_male = $_REQUEST['economical_status_APL_male'];
    $economical_status_APL_female = $_REQUEST['economical_status_APL_female'];
    $economical_status_APL_total = $_REQUEST['economical_status_APL_total'];
    $economical_status_other_male = $_REQUEST['economical_status_other_male'];
    $economical_status_other_female = $_REQUEST['economical_status_other_female'];
    $economical_status_other_total = $_REQUEST['economical_status_other_total'];
    $age_range = "";
    $age_range_0_5_month_male = $_REQUEST['age_range_0_5_month_male'];
    $age_range_0_5_month_female = $_REQUEST['age_range_0_5_month_female'];
    $age_range_0_5_month_total = $_REQUEST['age_range_0_5_month_total'];
    $age_range_6_23_month_total = $_REQUEST['age_range_6_23_month_total'];
    $age_range_6_23_month_female = $_REQUEST['age_range_6_23_month_female'];
    $age_range_6_23_month_male = $_REQUEST['age_range_6_23_month_male'];
    $age_range_24_59_month_male = $_REQUEST['age_range_24_59_month_male'];
    $age_range_24_59_month_female = $_REQUEST['age_range_24_59_month_female'];
    $age_range_24_59_month_total = $_REQUEST['age_range_24_59_month_total'];
    $admission_criteria = '';
    $admission_criteria_3SD_WFH_total = $_REQUEST['admission_criteria_3SD_WFH_total'];
    $admission_criteria_3SD_WFH_female = $_REQUEST['admission_criteria_3SD_WFH_female'];
    $admission_criteria_3SD_WFH_male = $_REQUEST['admission_criteria_3SD_WFH_male'];
    $admission_MUAC_male = $_REQUEST['admission_MUAC_male'];
    $admission_MUAC_female = $_REQUEST['admission_MUAC_female'];
    $admission_MUAC_other = $_REQUEST['admission_MUAC_other'];

    $admission_bilateral_male = $_REQUEST['admission_bilateral_male'];
    $admission_bilateral_female = $_REQUEST['admission_bilateral_female'];
    $admission_bilateral_total = $_REQUEST['admission_bilateral_total'];

    $admission_criteria_diarrhoea_total = $_REQUEST['admission_criteria_diarrhoea_total'];
    $admission_criteria_diarrhoea_female = $_REQUEST['admission_criteria_diarrhoea_female'];
    $admission_criteria_diarrhoea_male = $_REQUEST['admission_criteria_diarrhoea_male'];

    $admission_criteria_ARI_pneumonia_total = $_REQUEST['admission_criteria_ARI_pneumonia_total'];
    $admission_criteria_ARI_pneumonia_female = $_REQUEST['admission_criteria_ARI_pneumonia_female'];
    $admission_criteria_ARI_pneumonia_male = $_REQUEST['admission_criteria_ARI_pneumonia_male'];

    $admission_criteria_TB_total = $_REQUEST['admission_criteria_TB_total'];
    $admission_criteria_TB_female = $_REQUEST['admission_criteria_TB_female'];
    $admission_criteria_TB_male = $_REQUEST['admission_criteria_TB_male'];
    $admission_criteria_HIV_total = $_REQUEST['admission_criteria_HIV_total'];
    $admission_criteria_HIV_female = $_REQUEST['admission_criteria_HIV_female'];
    $admission_criteria_HIV_male = $_REQUEST['admission_criteria_HIV_male'];
    $admission_criteria_fever_total = $_REQUEST['admission_criteria_fever_total'];
    $admission_criteria_fever_female = $_REQUEST['admission_criteria_fever_female'];
    $admission_criteria_fever_male = $_REQUEST['admission_criteria_fever_male'];
    $admission_criteria_nutrition_related_disorder_total = $_REQUEST['admission_criteria_nutrition_related_disorder_total'];
    $admission_criteria_nutrition_related_disorder_female = $_REQUEST['admission_criteria_nutrition_related_disorder_female'];
    $admission_criteria_nutrition_related_disorder_male = $_REQUEST['admission_criteria_nutrition_related_disorder_male'];
    $admission_criteria_other_male = $_REQUEST['admission_criteria_other_male'];
    $admission_criteria_other_female = $_REQUEST['admission_criteria_other_female'];
    $admission_criteria_other_total = $_REQUEST['admission_criteria_other_total'];
    $referral_by = '';
    $referral_by_self_total = $_REQUEST['referral_by_self_male'];
    $referral_by_self_female = $_REQUEST['referral_by_self_female'];
    $referral_by_self_male = $_REQUEST['referral_by_self_total'];

    $referral_by_frontline_worker_male = $_REQUEST['referral_by_frontline_worker_male'];
    $referral_by_frontline_worker_female = $_REQUEST['referral_by_frontline_worker_female'];
    $referral_by_frontline_worker_total = $_REQUEST['referral_by_frontline_worker_total'];

    $referral_by_pediatric_ward_total = $_REQUEST['referral_by_pediatric_ward_total'];
    $referral_by_pediatric_ward_female = $_REQUEST['referral_by_pediatric_ward_female'];
    $referral_by_pediatric_ward_male = $_REQUEST['referral_by_pediatric_ward_male'];

    $referral_by_RBSK_team_total = $_REQUEST['referral_by_RBSK_team_total'];
    $referral_by_RBSK_team_female = $_REQUEST['referral_by_RBSK_team_female'];
    $referral_by_RBSK_team_male = $_REQUEST['referral_by_RBSK_team_male'];
    $referral_by_other_male = $_REQUEST['referral_by_other_male'];
    $referral_by_other_female = $_REQUEST['referral_by_other_female'];
    $referral_by_other_total = $_REQUEST['referral_by_other_total'];
    $performance_indicator = '';


    $bed_occupancy_male = $_REQUEST['bed_occupancy_male'];
    $bed_occupancy_female = $_REQUEST['bed_occupancy_female'];
    $bed_occupancy_total = $_REQUEST['bed_occupancy_total'];

    $average_length_of_stay_male = $_REQUEST['average_length_of_stay_male'];
    $average_length_of_stay_female = $_REQUEST['average_length_of_stay_female'];
    $average_length_of_stay_total = $_REQUEST['average_length_of_stay_total'];

    $average_weight_gain_male = $_REQUEST['average_weight_gain_male'];
    $average_weight_gain_female = $_REQUEST['average_weight_gain_female'];
    $average_weight_gain_total = $_REQUEST['average_weight_gain_total'];


    $duration_of_stay = '';
    $duration_lessthan_7_day_total = $_REQUEST['duration_lessthan_7_day_male'];
    $duration_lessthan_7_day_female = $_REQUEST['duration_lessthan_7_day_female'];
    $duration_lessthan_7_day_male = $_REQUEST['duration_lessthan_7_day_total'];

    $duration_7_to_15_day_total = $_REQUEST['duration_7_to_15_day_total'];
    $duration_7_to_15_day_female = $_REQUEST['duration_7_to_15_day_female'];
    $duration_7_to_15_day_male = $_REQUEST['duration_7_to_15_day_male'];

    $duration_greaterthen_15_day_other = $_REQUEST['duration_greaterthen_15_day_other'];
    $duration_greaterthen_15_day_female = $_REQUEST['duration_greaterthen_15_day_female'];
    $duration_greaterthen_15_day_male = $_REQUEST['duration_greaterthen_15_day_male'];
    $monthly_output = '';

    $total_NRC_male = $_REQUEST['total_NRC_male'];
    $total_NRC_female = $_REQUEST['total_NRC_female'];
    $total_NRC_other = $_REQUEST['total_NRC_other'];

    $no_of_children_treatment_NRC_total = $_REQUEST['no_of_children_treatment_NRC_total'];
    $no_of_children_treatment_NRC_female = $_REQUEST['no_of_children_treatment_NRC_female'];
    $no_of_children_treatment_NRC_male = $_REQUEST['no_of_children_treatment_NRC_male'];

    $no_of_children_weight_gain_male = $_REQUEST['no_of_children_weight_gain_male'];
    $no_of_children_weight_gain_female = $_REQUEST['no_of_children_weight_gain_female'];
    $no_of_children_weight_gain_total = $_REQUEST['no_of_children_weight_gain_total'];


    $no_of_children_consecutive_total = $_REQUEST['no_of_children_consecutive_total'];
    $no_of_children_consecutive_female = $_REQUEST['no_of_children_consecutive_female'];
    $no_of_children_consecutive_male = $_REQUEST['no_of_children_consecutive_male'];



    $no_of_children_partial_total = $_REQUEST['no_of_children_partial_male'];
    $no_of_children_partial_female = $_REQUEST['no_of_children_partial_female'];
    $no_of_children_partial_total = $_REQUEST['no_of_children_partial_total'];


    $no_of_defaulter_total = $_REQUEST['no_of_defaulter_total'];
    $no_of_defaulter_female = $_REQUEST['no_of_defaulter_female'];
    $no_of_defaulter_male = $_REQUEST['no_of_defaulter_male'];

    $no_of_non_responders_male = $_REQUEST['no_of_non_responders_male'];
    $no_of_non_responders_female = $_REQUEST['no_of_non_responders_female'];
    $no_of_non_responders_total = $_REQUEST['no_of_non_responders_total'];


    $no_of_children_referred_male = $_REQUEST['no_of_children_referred_male'];
    $no_of_children_referred_female = $_REQUEST['no_of_children_referred_female'];
    $no_of_children_referred_total = $_REQUEST['no_of_children_referred_total'];

    $deaths_during_NRC_male = $_REQUEST['deaths_during_NRC_male'];
    $deaths_during_NRC_female = $_REQUEST['deaths_during_NRC_female'];
    $deaths_during_NRC_total = $_REQUEST['deaths_during_NRC_total'];

    $children_due_male = $_REQUEST['children_due_male'];
    $children_due_female = $_REQUEST['children_due_female'];
    $children_due_total = $_REQUEST['children_due_total'];


    $children_followed_up_total = $_REQUEST['children_followed_up_total'];
    $children_followed_up_female = $_REQUEST['children_followed_up_female'];
    $children_followed_up_male = $_REQUEST['children_followed_up_male'];


    $no_of_children_completed_other = $_REQUEST['no_of_children_completed_other'];
    $no_of_children_completed_female = $_REQUEST['no_of_children_completed_female'];
    $no_of_children_completed_male = $_REQUEST['no_of_children_completed_male'];

    $no_of_children_Z_score_total = $_REQUEST['no_of_children_Z_score_total'];
    $no_of_children_Z_score_male = $_REQUEST['no_of_children_Z_score_male'];
    $no_of_children_Z_score_female = $_REQUEST['no_of_children_Z_score_female'];


    $deaths_during_period_male = $_REQUEST['deaths_during_period_male'];
    $deaths_during_period_female = $_REQUEST['deaths_during_period_female'];
    $deaths_during_period_total = $_REQUEST['deaths_during_period_total'];


    $no_of_replace_children_male = $_REQUEST['no_of_replace_children_male'];
    $no_of_replace_children_female = $_REQUEST['no_of_replace_children_female'];
    $no_of_replace_children_total = $_REQUEST['no_of_replace_children_total'];
    $human_resource = "";

    $medical_officer_dedicated_total_trained = $_REQUEST['medical_officer_dedicated_total_trained'];
    $medical_officer_dedicated_total_posted = $_REQUEST['medical_officer_dedicated_total_posted'];

    $medical_officer_roster_total_trained = $_REQUEST['medical_officer_roster_total_trained'];
    $medical_officer_roster_total_posted = $_REQUEST['medical_officer_roster_total_posted'];


    $senior_nutrition_total_trained = $_REQUEST['senior_nutrition_total_trained'];
    $senior_nutrition_total_posted = $_REQUEST['senior_nutrition_total_posted'];



    $junior_nutrition_total_trained = $_REQUEST['junior_nutrition_total_trained'];
    $junior_nutrition_total_posted = $_REQUEST['junior_nutrition_total_posted'];


    $staff_nurse_dedicated_total_posted = $_REQUEST['staff_nurse_dedicated_total_posted'];
    $staff_nurse_dedicated_total_trained = $_REQUEST['staff_nurse_dedicated_total_trained'];

    $staff_nurse_roster_total_trained = $_REQUEST['staff_nurse_roster_total_trained'];
    $staff_nurse_roster_total_posted = $_REQUEST['staff_nurse_roster_total_posted'];


    $ANM_dedicated_total_trained = $_REQUEST['ANM_dedicated_total_trained'];
    $ANM_dedicated_total_posted = $_REQUEST['ANM_dedicated_total_posted'];

    $ANM_roster_total_posted = $_REQUEST['ANM_roster_total_posted'];
    $ANM_roster_total_trained = $_REQUEST['ANM_roster_total_trained'];

    $feeding_demonstrator_total_posted = $_REQUEST['feeding_demonstrator_total_posted'];
    $feeding_demonstrator_total_trained = $_REQUEST['feeding_demonstrator_total_trained'];

    $community_based_total_trained = $_REQUEST['community_based_total_trained'];
    $community_based_total_posted = $_REQUEST['community_based_total_posted'];


    $cook_cum_total_trained = $_REQUEST['cook_cum_total_trained'];
    $cook_cum_total_posted = $_REQUEST['cook_cum_total_posted'];


    $cook_cum_total_trained = $_REQUEST['cook_cum_total_trained'];
    $cook_cum_total_posted = $_REQUEST['cook_cum_total_posted'];


    $attendant_cum_total_trained = $_REQUEST['attendant_cum_total_trained'];
    $attendant_cum_total_posted = $_REQUEST['attendant_cum_total_posted'];


    $other_consultant_total_posted = $_REQUEST['other_consultant_total_posted'];
    $other_consultant_total_trained = $_REQUEST['other_consultant_total_trained'];
    $note = $_REQUEST['note'];

    $sql = "INSERT INTO ncr_monthly_reporting 
    (health_facility, district, mode_of_operationalizatio, month_year, number_of_beds_male, 
    number_of_beds_female, number_of_beds_total, total_admission_male, total_admission_female, 
    total_admission_total, caste_distribution_SC_male, caste_distribution_SC_female, 
    caste_distribution_SC_total, caste_distribution_ST_male, caste_distribution_ST_female, 
    caste_distribution_ST_total, caste_distribution_OBC_male, caste_distribution_OBC_female, 
    caste_distribution_OBC_total, caste_distribution_EBC_male, caste_distribution_EBC_female, 
    caste_distribution_EBC_total, caste_distribution_GEN_male, caste_distribution_GEN_female, 
    caste_distribution_GEN_total, economical_status_BPL_male, economical_status_BPL_female, 
    economical_status_BPL_total, economical_status_APL_male, economical_status_APL_female, 
    economical_status_APL_total, economical_status_other_male, economical_status_other_female, 
    economical_status_other_total, age_range_0_5_month_male, age_range_0_5_month_female, 
    age_range_0_5_month_total, age_range_6_23_month_male, age_range_6_23_month_female, 
    age_range_6_23_month_total, age_range_24_59_month_male, age_range_24_59_month_female, 
    age_range_24_59_month_total, admission_criteria_3SD_WFH_male, admission_criteria_3SD_WFH_female, 
    admission_criteria_3SD_WFH_total, admission_MUAC_male, admission_MUAC_female, admission_MUAC_other, 
    admission_bilateral_male, admission_bilateral_female, admission_bilateral_total, admission_criteria_medical, 
    admission_criteria_diarrhoea_male, admission_criteria_diarrhoea_female, admission_criteria_diarrhoea_total, 
    admission_criteria_ARI_pneumonia_male, admission_criteria_ARI_pneumonia_female, 
    admission_criteria_ARI_pneumonia_total, admission_criteria_TB_male, admission_criteria_TB_female, 
    admission_criteria_TB_total, admission_criteria_HIV_male, admission_criteria_HIV_female, 
    admission_criteria_HIV_total, admission_criteria_fever_male, admission_criteria_fever_female, 
    admission_criteria_fever_total, admission_criteria_nutrition_related_disorder_male, 
    admission_criteria_nutrition_related_disorder_female, admission_criteria_nutrition_related_disorder_total, 
    admission_criteria_other_male, admission_criteria_other_female, admission_criteria_other_total, referral_by, 
    referral_by_self_male, referral_by_self_female, referral_by_self_total, referral_by_frontline_worker_male, 
    referral_by_frontline_worker_female, referral_by_frontline_worker_total, referral_by_pediatric_ward_male, 
    referral_by_pediatric_ward_female, referral_by_pediatric_ward_total, referral_by_RBSK_team_male, 
    referral_by_RBSK_team_female, referral_by_RBSK_team_total, referral_by_other_male, referral_by_other_female, 
    referral_by_other_total, performance_indicator, bed_occupancy_male, bed_occupancy_female, bed_occupancy_total, 
    average_length_of_stay_male, average_length_of_stay_female, average_length_of_stay_total, 
    average_weight_gain_male, average_weight_gain_female, average_weight_gain_total, duration_of_stay, 
    duration_lessthan_7_day_male, duration_lessthan_7_day_female, duration_lessthan_7_day_total, 
    duration_7_to_15_day_male, duration_7_to_15_day_female, duration_7_to_15_day_total, 
    duration_greaterthen_15_day_male, duration_greaterthen_15_day_female, duration_greaterthen_15_day_other, 
    monthly_output, total_NRC_male, total_NRC_female, total_NRC_other, no_of_children_treatment_NRC_male, 
    no_of_children_treatment_NRC_female, no_of_children_treatment_NRC_total, no_of_children_weight_gain_male, 
    no_of_children_weight_gain_female, no_of_children_weight_gain_total, no_of_children_consecutive_male, 
    no_of_children_consecutive_female, no_of_children_consecutive_total, no_of_children_partial_male, 
    no_of_children_partial_female, no_of_children_partial_total, no_of_defaulter_male, no_of_defaulter_female, 
    no_of_defaulter_total, no_of_non_responders_male, no_of_non_responders_female, no_of_non_responders_total, 
    no_of_children_referred_male, no_of_children_referred_female, no_of_children_referred_total, 
    deaths_during_NRC_male, deaths_during_NRC_female, deaths_during_NRC_total, children_due_male, 
    children_due_female, children_due_total, children_followed_up_male, children_followed_up_female, 
    children_followed_up_total, no_of_children_completed_male, no_of_children_completed_female, 
    no_of_children_completed_other, no_of_children_Z_score_male, no_of_children_Z_score_female, 
    no_of_children_Z_score_total, deaths_during_period_male, deaths_during_period_female, 
    deaths_during_period_total, no_of_replace_children_male, no_of_replace_children_female, 
    no_of_replace_children_total, human_resource, medical_officer_dedicated_total_posted, 
    medical_officer_dedicated_total_trained, medical_officer_roster_total_posted, 
    medical_officer_roster_total_trained, senior_nutrition_total_posted, senior_nutrition_total_trained, 
    junior_nutrition_total_posted, junior_nutrition_total_trained, staff_nurse_dedicated_total_posted, 
    staff_nurse_dedicated_total_trained, staff_nurse_roster_total_posted, staff_nurse_roster_total_trained, 
    ANM_dedicated_total_posted, ANM_dedicated_total_trained, ANM_roster_total_posted, ANM_roster_total_trained, 
    feeding_demonstrator_total_posted, feeding_demonstrator_total_trained, community_based_total_posted, 
    community_based_total_trained, cook_cum_total_posted, cook_cum_total_trained, attendant_cum_total_posted, 
    attendant_cum_total_trained, other_consultant_total_posted, other_consultant_total_trained, note) 
    VALUES (
    '$health_facility', '$district', '$mode_of_operationalizatio', '$month_year', '$number_of_beds_male', 
    '$number_of_beds_female', '$number_of_beds_total', '$total_admission_male', '$total_admission_female', 
    '$total_admission_total', '$caste_distribution_SC_male', '$caste_distribution_SC_female', 
    '$caste_distribution_SC_total', '$caste_distribution_ST_male', '$caste_distribution_ST_female', 
    '$caste_distribution_ST_total', '$caste_distribution_OBC_male', '$caste_distribution_OBC_female', 
    '$caste_distribution_OBC_total', '$caste_distribution_EBC_male', '$caste_distribution_EBC_female', 
    '$caste_distribution_EBC_total', '$caste_distribution_GEN_male', '$caste_distribution_GEN_female', 
    '$caste_distribution_GEN_total', '$economical_status_BPL_male', '$economical_status_BPL_female', 
    '$economical_status_BPL_total', '$economical_status_APL_male', '$economical_status_APL_female', 
    '$economical_status_APL_total', '$economical_status_other_male', '$economical_status_other_female', 
    '$economical_status_other_total', '$age_range_0_5_month_male', '$age_range_0_5_month_female', 
    '$age_range_0_5_month_total', '$age_range_6_23_month_male', '$age_range_6_23_month_female', 
    '$age_range_6_23_month_total', '$age_range_24_59_month_male', '$age_range_24_59_month_female', 
    '$age_range_24_59_month_total', '$admission_criteria_3SD_WFH_male', '$admission_criteria_3SD_WFH_female', 
    '$admission_criteria_3SD_WFH_total', '$admission_MUAC_male', '$admission_MUAC_female', '$admission_MUAC_other', 
    '$admission_bilateral_male', '$admission_bilateral_female', '$admission_bilateral_total', 
    '$admission_criteria_medical', '$admission_criteria_diarrhoea_male', '$admission_criteria_diarrhoea_female', 
    '$admission_criteria_diarrhoea_total', '$admission_criteria_ARI_pneumonia_male', 
    '$admission_criteria_ARI_pneumonia_female', '$admission_criteria_ARI_pneumonia_total', '$admission_criteria_TB_male', 
    '$admission_criteria_TB_female', '$admission_criteria_TB_total', '$admission_criteria_HIV_male', 
    '$admission_criteria_HIV_female', '$admission_criteria_HIV_total', '$admission_criteria_fever_male', 
    '$admission_criteria_fever_female', '$admission_criteria_fever_total', '$admission_criteria_nutrition_related_disorder_male', 
    '$admission_criteria_nutrition_related_disorder_female', '$admission_criteria_nutrition_related_disorder_total', 
    '$admission_criteria_other_male', '$admission_criteria_other_female', '$admission_criteria_other_total', '$referral_by', 
    '$referral_by_self_male', '$referral_by_self_female', '$referral_by_self_total', '$referral_by_frontline_worker_male', 
    '$referral_by_frontline_worker_female', '$referral_by_frontline_worker_total', '$referral_by_pediatric_ward_male', 
    '$referral_by_pediatric_ward_female', '$referral_by_pediatric_ward_total', '$referral_by_RBSK_team_male', 
    '$referral_by_RBSK_team_female', '$referral_by_RBSK_team_total', '$referral_by_other_male', '$referral_by_other_female', 
    '$referral_by_other_total', '$performance_indicator', '$bed_occupancy_male', '$bed_occupancy_female', '$bed_occupancy_total', 
    '$average_length_of_stay_male', '$average_length_of_stay_female', '$average_length_of_stay_total', '$average_weight_gain_male', 
    '$average_weight_gain_female', '$average_weight_gain_total', '$duration_of_stay', '$duration_lessthan_7_day_male', 
    '$duration_lessthan_7_day_female', '$duration_lessthan_7_day_total', '$duration_7_to_15_day_male', 
    '$duration_7_to_15_day_female', '$duration_7_to_15_day_total', '$duration_greaterthen_15_day_male', 
    '$duration_greaterthen_15_day_female', '$duration_greaterthen_15_day_other', '$monthly_output', '$total_NRC_male', 
    '$total_NRC_female', '$total_NRC_other', '$no_of_children_treatment_NRC_male', '$no_of_children_treatment_NRC_female', 
    '$no_of_children_treatment_NRC_total', '$no_of_children_weight_gain_male', '$no_of_children_weight_gain_female', 
    '$no_of_children_weight_gain_total', '$no_of_children_consecutive_male', '$no_of_children_consecutive_female', 
    '$no_of_children_consecutive_total', '$no_of_children_partial_male', '$no_of_children_partial_female', 
    '$no_of_children_partial_total', '$no_of_defaulter_male', '$no_of_defaulter_female', '$no_of_defaulter_total', 
    '$no_of_non_responders_male', '$no_of_non_responders_female', '$no_of_non_responders_total', 
    '$no_of_children_referred_male', '$no_of_children_referred_female', '$no_of_children_referred_total', 
    '$deaths_during_NRC_male', '$deaths_during_NRC_female', '$deaths_during_NRC_total', '$children_due_male', 
    '$children_due_female', '$children_due_total', '$children_followed_up_male', '$children_followed_up_female', 
    '$children_followed_up_total', '$no_of_children_completed_male', '$no_of_children_completed_female', 
    '$no_of_children_completed_other', '$no_of_children_Z_score_male', '$no_of_children_Z_score_female', 
    '$no_of_children_Z_score_total', '$deaths_during_period_male', '$deaths_during_period_female', 
    '$deaths_during_period_total', '$no_of_replace_children_male', '$no_of_replace_children_female', 
    '$no_of_replace_children_total', '$human_resource', '$medical_officer_dedicated_total_posted', 
    '$medical_officer_dedicated_total_trained', '$medical_officer_roster_total_posted', '$medical_officer_roster_total_trained', 
    '$senior_nutrition_total_posted', '$senior_nutrition_total_trained', '$junior_nutrition_total_posted', 
    '$junior_nutrition_total_trained', '$staff_nurse_dedicated_total_posted', '$staff_nurse_dedicated_total_trained', 
    '$staff_nurse_roster_total_posted', '$staff_nurse_roster_total_trained', '$ANM_dedicated_total_posted', 
    '$ANM_dedicated_total_trained', '$ANM_roster_total_posted', '$ANM_roster_total_trained', '$feeding_demonstrator_total_posted', 
    '$feeding_demonstrator_total_trained', '$community_based_total_posted', '$community_based_total_trained', 
    '$cook_cum_total_posted', '$cook_cum_total_trained', '$attendant_cum_total_posted', '$attendant_cum_total_trained', 
    '$other_consultant_total_posted', '$other_consultant_total_trained', '$note')";



    $query = $conn->query($sql);
    if ($query) {
       // echo "<script>alert('Report submitted successfully')</script>";
        $success = true; 
    } else {
        $error_message = $conn->error; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Rehabilitation Center Reporting Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for form borders */
        table,
        th,
        td {
            border: 1px solid black;
            text-align: center;
        }

        table {
            width: 100%;
        }

        .header {
            position: fixed;
            left: 0;
            right: 0;
            z-index: 1002;
            background: #1cabe2 !important;
        }

        .navbar {
            background-color: #1cabe2 !important;
        }

        .py-5 {
            padding-top: 6rem !important;
            padding-bottom: 3rem !important;
        }

        .fw-bold {
            color: white;
        }

        table,
        th,
        td {
            border: 1px solid black;
            text-align: left;
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            /* border-color: inherit; */
            border-style: solid;
            border-width: 2px;
        }
    </style>
</head>

<body>

    <header class="header">
        <nav class="navbar navbar-expand-lg bg-nav navbar p-0 py-0" data-bs-theme="light" style="background-color:white">
            <div class="container-fluid py-3">


                <a class="navbar-brand m-0" href="javascript:void(0)">
                    <img src="images/logo-02.png" alt="" class="img-fluid second-logo">
                </a>
                <button class="navbar-toggler" id="navbar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPrimaryExample" aria-controls="navbarPrimaryExample" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="cala text-center">
                    <h3 class="fw-bold">
                        MONTHLY REPORTING FORMAT : NUTRITION REHABILITATION CENTER
                    </h3>
                </div>
                <div id="usericon">
                    <a class="navbar-brand1 m-0" onclick="logout()" href="javascript:void(0)">
                        <img src="images/avatar.png" alt="" class="img-fluid rounded-images second-logo">
                    </a>
                </div>
            </div>


        </nav>

    </header>

    <main>
        <div class="container-fluid py-5">

            <form action="" enctype="multipart/form-data" method="POST">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="4">Name of Health Facility:</th>
                        <td colspan="4">
                            <input required type="text" name="health_facility" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">Date of Operationalization:</th>
                        <td colspan="4">
                            <input required type="date" name="date_of_operationalization" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">District:</th>
                        <td colspan="4">
                            <input required type="text" name="district" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">Mode of Operationalization (PPP/ Govt Run):</th>
                        <td colspan="4">
                            <input required type="text" name="mode_of_operationalizatio" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">Month & Year:</th>
                        <td colspan="4">
                            <input required type="month" name="month_year" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">Number of beds:</th>
                        <td colspan="1"><input type="number" placeholder="Male" id="number_of_beds_male" oninput="calculateTotal('number_of_beds')" name="number_of_beds_male" class="form-control"></td>
                        <td colspan="1"><input type="number" placeholder="Female" id="number_of_beds_female" oninput="calculateTotal('number_of_beds')" name="number_of_beds_female" class="form-control"></td>
                        <td colspan="1"> <input type="number" placeholder="Total" id="number_of_beds_total" name="number_of_beds_total" class="form-control"></td>
                    </tr>
                    <tr>
                        <th colspan="4">Total admission</th>
                        <td colspan="1"><input type="number" placeholder="Male" oninput="calculateTotal('total_admission')" id="total_admission_male" name="total_admission_male" class="form-control"></td>
                        <td colspan="1"><input type="number" placeholder="Female" oninput="calculateTotal('total_admission')" id="total_admission_female" name="total_admission_female" class="form-control"></td>
                        <td colspan="1"> <input type="number" placeholder="Total" id="total_admission_total" name="total_admission_total" class="form-control"></td>
                    </tr>
                    <tr>
                        <th rowspan="5" colspan="2">A.1 Caste Distribution</th>
                        <td colspan="2">SC</td>
                        <td colspan="1"><input type="number" name="caste_distribution_SC_male" id="caste_distribution_SC_male" oninput="calculateTotal('caste_distribution_SC')" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" name="caste_distribution_SC_female" id="caste_distribution_SC_female" oninput="calculateTotal('caste_distribution_SC')" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="caste_distribution_SC_total" id="caste_distribution_SC_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">ST</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('caste_distribution_ST')" id="caste_distribution_ST_male" placeholder="Male" name="caste_distribution_ST_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('caste_distribution_ST')" id="caste_distribution_ST_female" name="caste_distribution_ST_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="caste_distribution_ST_total" id="caste_distribution_ST_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">OBC</td>
                        <td colspan="1"><input type="number" placeholder="Male" oninput="calculateTotal('caste_distribution_OBC')" id="caste_distribution_OBC_male" name="caste_distribution_OBC_male" class="form-control"></td>
                        <td colspan="1"><input type="number" name="caste_distribution_OBC_female" oninput="calculateTotal('caste_distribution_OBC')" id="caste_distribution_OBC_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="caste_distribution_OBC_total" id="caste_distribution_OBC_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">EBC</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('caste_distribution_EBC')" id="caste_distribution_EBC_male" placeholder="Male" name="caste_distribution_EBC_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('caste_distribution_EBC')" id="caste_distribution_EBC_female" name="caste_distribution_EBC_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="caste_distribution_EBC_total" id="caste_distribution_EBC_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">GEN</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('caste_distribution_GEN')" id="caste_distribution_GEN_male" placeholder="Male" name="caste_distribution_GEN_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('caste_distribution_GEN')" id="caste_distribution_GEN_female" name="caste_distribution_GEN_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="caste_distribution_GEN_total" id="caste_distribution_GEN_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <th rowspan="3" colspan="2">A.2 Economical Status</th>
                        <td colspan="2">BPL</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('economical_status_BPL')" id="economical_status_BPL_male" placeholder="Male" name="economical_status_BPL_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('economical_status_BPL')" id="economical_status_BPL_female" name="economical_status_BPL_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="economical_status_BPL_total" name="economical_status_BPL_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">APL</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('economical_status_APL')" id="economical_status_APL_male" placeholder="Male" name="economical_status_APL_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('economical_status_APL')" id="economical_status_APL_female" name="economical_status_APL_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="economical_status_APL_total" name="economical_status_APL_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Others/Not Available</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('economical_status_other')" id="economical_status_other_male" placeholder="Male" name="economical_status_other_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('economical_status_other')" id="economical_status_other_female" name="economical_status_other_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"> <input type="number" id="economical_status_other_total" name="economical_status_other_total" placeholder="Male" class="form-control"></td>
                    </tr>
                    <tr>
                        <th rowspan="4" colspan="2">A.3 Age Range</th>
                        <td colspan="2">0 Month - 5 Month</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('age_range_0_5_month')" id="age_range_0_5_month_male" placeholder="Male" name="age_range_0_5_month_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('age_range_0_5_month')" id="age_range_0_5_month_female" name="age_range_0_5_month_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="age_range_0_5_month_total" name="age_range_0_5_month_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">6 Month - 23 Month</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('age_range_6_23_month')" id="age_range_6_23_month_male" placeholder="Male" name="age_range_6_23_month_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('age_range_6_23_month')" id="age_range_6_23_month_female" name="age_range_6_23_month_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="age_range_6_23_month_total" name="age_range_6_23_month_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">24 Month - 59 Month</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('age_range_0_5_month')" id="age_range_24_59_month_male" placeholder="Male" name="age_range_24_59_month_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('age_range_0_5_month')" id="age_range_24_59_month_female" name="age_range_24_59_month_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="age_range_24_59_month_total" name="age_range_24_59_month_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <th rowspan="3" colspan="2">A.4.1 Admission criteria - Nutrition Status</th>
                        <td colspan="2">-3SD WFH</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_3SD_WFH')" placeholder="Male" id="admission_criteria_3SD_WFH_male" name="admission_criteria_3SD_WFH_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_3SD_WFH')" id="admission_criteria_3SD_WFH_female" name="admission_criteria_3SD_WFH_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="admission_criteria_3SD_WFH_total" id="admission_criteria_3SD_WFH_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">MUAC &lt;115mm</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_MUAC')" id="admission_MUAC_male" placeholder="Male" name="admission_MUAC_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_MUAC')" id="admission_MUAC_female" name="admission_MUAC_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_MUAC_other" name="admission_MUAC_other" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Bilateral pitting edema</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_bilateral')" id="admission_bilateral_male" placeholder="Male" name="admission_bilateral_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_bilateral')" id="admission_bilateral_female" name="admission_bilateral_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_bilateral_total" name="admission_bilateral_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <th rowspan="7" colspan="2">A.4.1 Admission criteria - Medical Complication (No. of Children with) </th>
                        <td colspan="2">Diarrhoea/ Dehydration</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_diarrhoea')" id="admission_criteria_diarrhoea_male" placeholder="Male" name="admission_criteria_diarrhoea_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_diarrhoea')" id="admission_criteria_diarrhoea_female" name="admission_criteria_diarrhoea_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_criteria_diarrhoea_total" name="admission_criteria_diarrhoea_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">ARI/ Pneumonia</td>
                        <td colspan="1"><input oninput="calculateTotal('admission_criteria_ARI_pneumonia')" id="admission_criteria_ARI_pneumonia_male" type="number" placeholder="Male" name="admission_criteria_ARI_pneumonia_male" class="form-control"></td>
                        <td colspan="1"><input oninput="calculateTotal('admission_criteria_ARI_pneumonia')" id="admission_criteria_ARI_pneumonia_female" type="number" name="admission_criteria_ARI_pneumonia_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_criteria_ARI_pneumonia_total" name="admission_criteria_ARI_pneumonia_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">T.B</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_TB')" id="admission_criteria_TB_male" placeholder="Male" name="admission_criteria_TB_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_TB')" id="admission_criteria_TB_female" name="admission_criteria_TB_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_criteria_TB_total" name="admission_criteria_TB_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">HIV</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_HIV')" id="admission_criteria_HIV_male" placeholder="Male" name="admission_criteria_HIV_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_HIV')" id="admission_criteria_HIV_female" name="admission_criteria_HIV_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_criteria_HIV_total" name="admission_criteria_HIV_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Fever</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_fever')" id="admission_criteria_fever_male" placeholder="Male" name="admission_criteria_fever_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_fever')" id="admission_criteria_fever_female" name="admission_criteria_fever_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_criteria_fever_total" name="admission_criteria_fever_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Nutrition related disorder</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_nutrition_related_disorder')" id="admission_criteria_nutrition_related_disorder_male" placeholder="Male" name="admission_criteria_nutrition_related_disorder_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_nutrition_related_disorder')" id="admission_criteria_nutrition_related_disorder_female" name="admission_criteria_nutrition_related_disorder_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_criteria_nutrition_related_disorder_total" name="admission_criteria_nutrition_related_disorder_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Others</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_other')" id="admission_criteria_other_male" placeholder="Male" name="admission_criteria_other_male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('admission_criteria_other')" id="admission_criteria_other_female" name="admission_criteria_other_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="admission_criteria_other_total" name="admission_criteria_other_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <th rowspan="5" colspan="2">A.5 Referral by </th>
                        <td colspan="2">Self</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_self')" id="referral_by_self_male" name="referral_by_self_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_self')" id="referral_by_self_female" name="referral_by_self_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="referral_by_self_total" name="referral_by_self_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Frontline worker</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_frontline_worker')" id="referral_by_frontline_worker_male" name="referral_by_frontline_worker_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_frontline_worker')" id="referral_by_frontline_worker_female" name="referral_by_frontline_worker_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="referral_by_frontline_worker_total" name="referral_by_frontline_worker_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Pediatric ward/emergency/OPD</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_pediatric_ward')" id="referral_by_pediatric_ward_male" name="referral_by_pediatric_ward_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_pediatric_ward')" id="referral_by_pediatric_ward_female" name="referral_by_pediatric_ward_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="referral_by_pediatric_ward_total" name="referral_by_pediatric_ward_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">RBSK Team</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_RBSK_team')" id="referral_by_RBSK_team_male" name="referral_by_RBSK_team_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_RBSK_team')" id="referral_by_RBSK_team_female" name="referral_by_RBSK_team_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="referral_by_RBSK_team_total" name="referral_by_RBSK_team_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Others</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_other')" id="referral_by_other_male" name="referral_by_other_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('referral_by_other')" id="referral_by_other_female" name="referral_by_other_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="referral_by_other_total" id="referral_by_other_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <th rowspan="3" colspan="2">B. Performance Indicator </th>
                        <td colspan="2">Bed Occupancy </td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('bed_occupancy')" id="bed_occupancy_male" name="bed_occupancy_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('bed_occupancy')" id="bed_occupancy_female" name="bed_occupancy_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="bed_occupancy_total" name="bed_occupancy_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Average length of stay (No. of Days)</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('average_length_of_stay')" id="average_length_of_stay_male" name="average_length_of_stay_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('average_length_of_stay')" id="average_length_of_stay_female" name="average_length_of_stay_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="average_length_of_stay_total" name="average_length_of_stay_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Average weight gain(gm/kg/day)</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('average_weight_gain')" id="average_weight_gain_male" name="average_weight_gain_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('average_weight_gain')" id="average_weight_gain_female" name="average_weight_gain_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="average_weight_gain_total" name="average_weight_gain_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <th rowspan="3" colspan="2">C.1. Duration of Stay </th>
                        <td colspan="2">
                            < 7 Days </td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('duration_lessthan_7_day')" id="duration_lessthan_7_day_male" name="duration_lessthan_7_day_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('duration_lessthan_7_day')" id="duration_lessthan_7_day_female" name="duration_lessthan_7_day_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="duration_lessthan_7_day_total" name="duration_lessthan_7_day_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">7 - 15 Days</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('duration_7_to_15_day')" id="duration_7_to_15_day_male" name="duration_7_to_15_day_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('duration_7_to_15_day')" id="duration_7_to_15_day_female" name="duration_7_to_15_day_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="duration_7_to_15_day_total" name="duration_7_to_15_day_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">> 15 Days</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('duration_greaterthen_15_day')" id="duration_greaterthen_15_day_male" name="duration_greaterthen_15_day_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('duration_greaterthen_15_day')" id="duration_greaterthen_15_day_female" name="duration_greaterthen_15_day_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="duration_greaterthen_15_day_total" name="duration_greaterthen_15_day_other" placeholder="Total" class="form-control"></td>
                    </tr>

                    <tr>
                        <th rowspan="15" colspan="2">C.1 Monthly Output </th>
                        <td colspan="2"> Total Exit from NRC </td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('total_NRC')" id="total_NRC_male" name="total_NRC_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('total_NRC')" id="total_NRC_female" name="total_NRC_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="total_NRC_total" name="total_NRC_other" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of children still under treatment in NRC</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_treatment_NRC')" id="no_of_children_treatment_NRC_male" name="no_of_children_treatment_NRC_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_treatment_NRC')" id="no_of_children_treatment_NRC_female" name="no_of_children_treatment_NRC_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="no_of_children_treatment_NRC_total" name="no_of_children_treatment_NRC_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Children discharged with atleast 15 % weight gain</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_weight_gain')" id="no_of_children_weight_gain_male" name="no_of_children_weight_gain_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_weight_gain')" id="no_of_children_weight_gain_female" name="no_of_children_weight_gain_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="no_of_children_weight_gain_total" name="no_of_children_weight_gain_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Children discharged with 5gm/kg/day for three consecutive days</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_consecutive')" id="no_of_children_consecutive_male" name="no_of_children_consecutive_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_consecutive')" id="no_of_children_consecutive_female" name="no_of_children_consecutive_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="no_of_children_consecutive_total" name="no_of_children_consecutive_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Children discharged with Partial Weight Gain</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_partial')" id="no_of_children_partial_male" name="no_of_children_partial_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_partial')" id="no_of_children_partial_female" name="no_of_children_partial_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="no_of_children_partial_total" name="no_of_children_partial_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Defaulter/LAMA</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_defaulter')" id="no_of_defaulter_male" name="no_of_defaulter_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_defaulter')" id="no_of_defaulter_female" name="no_of_defaulter_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="no_of_defaulter_total" name="no_of_defaulter_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Non responders</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_non_responders')" id="no_of_non_responders_male" name="no_of_non_responders_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_non_responders')" id="no_of_non_responders_female" name="no_of_non_responders_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="no_of_non_responders_total" name="no_of_non_responders_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Children Referred from NRC</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_referred')" id="no_of_children_referred_male" name="no_of_children_referred_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('no_of_children_referred')" id="no_of_children_referred_female" name="no_of_children_referred_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="no_of_children_referred_total" name="no_of_children_referred_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Deaths during stay in NRC</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('deaths_during_NRC')" id="deaths_during_NRC_male" name="deaths_during_NRC_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('deaths_during_NRC')" id="deaths_during_NRC_female" name="deaths_during_NRC_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="deaths_during_NRC_total" name="deaths_during_NRC_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Children due for follow-up (in month)</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('children_due')" id="children_due_male" name="children_due_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('children_due')" id="children_due_female" name="children_due_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="children_due_total" name="children_due_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Children followed-up during the month</td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('children_followed_up')" id="children_followed_up_male" name="children_followed_up_male" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" oninput="calculateTotal('children_followed_up')" id="children_followed_up_female" name="children_followed_up_female" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" id="children_followed_up_total" name="children_followed_up_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Children completed 4 follow up </td>
                        <td colspan="1"><input type="number" name="no_of_children_completed_male" id="no_of_children_completed_male" oninput="calculateTotal('no_of_children_completed')" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" name="no_of_children_completed_female" id="no_of_children_completed_female" oninput="calculateTotal('no_of_children_completed')" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="no_of_children_completed_other" id="no_of_children_completed_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Children who completed 4 follow up having Z socre -1SD or above</td>
                        <td colspan="1"><input type="number" name="no_of_children_Z_score_male" id="no_of_children_Z_score_male" oninput="calculateTotal('no_of_children_Z_score')" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" name="no_of_children_Z_score_female" id="no_of_children_Z_score_female" oninput="calculateTotal('no_of_children_Z_score')" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="no_of_children_Z_score_total" id="no_of_children_Z_score_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Deaths during follow up period (After discharge from NRC)</td>
                        <td colspan="1"><input type="number" name="deaths_during_period_male" id="deaths_during_period_male" oninput="calculateTotal('deaths_during_period')" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" name="deaths_during_period_female" id="deaths_during_period_female" oninput="calculateTotal('deaths_during_period')" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="deaths_during_period_total" id="deaths_during_period_total" placeholder="Total" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">No. of Relapse Children</td>
                        <td colspan="1"><input type="number" name="no_of_replace_children_male" id="no_of_replace_children_male" oninput="calculateTotal('no_of_replace_children')" placeholder="Male" class="form-control"></td>
                        <td colspan="1"><input type="number" name="no_of_replace_children_female" id="no_of_replace_children_female" oninput="calculateTotal('no_of_replace_children')" placeholder="Female" class="form-control"></td>
                        <td colspan="1"><input type="number" name="no_of_replace_children_total" id="no_of_replace_children_total" placeholder="Total" class="form-control"></td>
                    </tr>

                    <tr>
                        <th rowspan="14" colspan="2">B. Human Resource </th>
                        <th colspan="2"></th>
                        <th colspan="2">Total Posted</th>
                        <th colspan="2">Total Trained</th>
                    </tr>
                    <tr>
                        <td colspan="2">Medical Officer (Dedicated for NRC)</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="medical_officer_dedicated_total_posted" oninput="calculateTotal1('medical_officer_dedicated_total')" name="medical_officer_dedicated_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="medical_officer_dedicated_total_trained" name="medical_officer_dedicated_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Medical Officer on Roster Duty</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="medical_officer_roster_total_posted" oninput="calculateTotal1('medical_officer_roster_total')" name="medical_officer_roster_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="medical_officer_roster_total_trained" name="medical_officer_roster_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Senior Nutrition counsellor-UNICEF support</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="senior_nutrition_total_posted" oninput="calculateTotal1('senior_nutrition_total')" name="senior_nutrition_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="senior_nutrition_total_trained" name="senior_nutrition_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Junior Nutrition counsellor-UNICEF support</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="junior_nutrition_total_posted" oninput="calculateTotal1('junior_nutrition_total')" name="junior_nutrition_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="junior_nutrition_total_trained" name="junior_nutrition_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Staff Nurse dedicated for NRC</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="staff_nurse_dedicated_total_posted" oninput="calculateTotal1('staff_nurse_dedicated_total')" name="staff_nurse_dedicated_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="staff_nurse_dedicated_total_trained" name="staff_nurse_dedicated_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Staff Nurse on Roaster basis</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="staff_nurse_roster_total_posted" oninput="calculateTotal1('staff_nurse_roster_total')" name="staff_nurse_roster_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="staff_nurse_roster_total_trained" name="staff_nurse_roster_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">ANM dedicated for NRC </td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="ANM_dedicated_total_posted" oninput="calculateTotal1('ANM_dedicated_total')" name="ANM_dedicated_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="ANM_dedicated_total_trained" name="ANM_dedicated_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">ANM on roaster basis</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="ANM_roster_total_posted" oninput="calculateTotal1('ANM_roster_total')" name="ANM_roster_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="ANM_roster_total_trained" name="ANM_roster_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Feeding demonstrator</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="feeding_demonstrator_total_posted" oninput="calculateTotal1('feeding_demonstrator_total')" name="feeding_demonstrator_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="feeding_demonstrator_total_trained" name="feeding_demonstrator_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Community Based Care Extender</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="community_based_total_posted" oninput="calculateTotal1('community_based_total')" name="community_based_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="community_based_total_trained" name="community_based_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Cook cum Care taker</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="cook_cum_total_posted" oninput="calculateTotal1('cook_cum_total')" name="cook_cum_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="cook_cum_total_trained" name="cook_cum_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Attendant cum Cleaner</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="attendant_cum_total_posted" oninput="calculateTotal1('attendant_cum_total')" name="attendant_cum_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="attendant_cum_total_trained" name="attendant_cum_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Others- Consultant, IM-SAM (1) and Data management (1) from UNICEF support</td>
                        <td colspan="2"><input type="number" placeholder="Total Posted" id="other_consultant_total_posted" oninput="calculateTotal1('other_consultant_total')" name="other_consultant_total_posted" class="form-control"></td>
                        <td colspan="2"><input type="number" placeholder="Total Trained" id="other_consultant_total_trained" name="other_consultant_total_trained" class="form-control"></td>
                    </tr>
                    <tr>
                        <th colspan="2">Note:</th>
                        <td colspan="6">
                            <textarea name="note" id="" class="form-control" placeholder="Note:-" cols="100" rows="5"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8">
                            <div class="text-end">
                                <button type="submit" name="submit" class="btn btn-primary">Submit </button>
                            </div>
                        </td>
                    </tr>

                </table>

            </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+q2I5iB5z9myGyk5kF1FAdBR6+8cb" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRRhb5PO6Ml5kiqVZ4d3uXTx1ax4LLkaXq7g5hxBz" crossorigin="anonymous"></script>

</body>

<script>
    function calculateTotal(prefix) {
        var male = parseInt(document.getElementById(prefix + '_male').value) || 0;
        var female = parseInt(document.getElementById(prefix + '_female').value) || 0;
        document.getElementById(prefix + '_total').value = male + female;
    }
</script>
<!-- <script>
    function calculateTotal1(prefix) {
        var male = parseInt(document.getElementById(prefix + '_posted').value) || 0;
        var female = 0;
        document.getElementById(prefix + '_trained').value = male + female;
    }
</script> -->


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Flag from PHP (output it safely)
    var success = <?php echo json_encode($success); ?>;
    var errorMessage = <?php echo isset($error_message) ? json_encode($error_message) : 'null'; ?>;

    if (success) {
        // Show success alert
        Swal.fire({
            title: 'Success!',
            text: 'Data inserted successfully',
            icon: 'success',
            timer: 1500, // Close after 1.5 seconds
            showConfirmButton: false
        }).then(() => {
        
            window.location.href = 'nrcreportingFormat.php'; // Redirect to another page

        });
    } else if (errorMessage) {
        Swal.fire({
            title: 'Error!',
            text: 'Data insertion failed: ' + errorMessage,
            icon: 'error'
        });
    }
</script>


</html>