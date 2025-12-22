<?php
include('includes/config.php');
// Example: Connect to DB and export large dataset to CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="Consolidated-Report.csv"');

// Open PHP output stream
$output = fopen('php://output', 'w');

// Optional: Write CSV header
fputcsv($output, ['Reg_Id', 'Child ID', 'Registration Date', 'Monitor Name', 'SNCU Name', 'Baby Name', 'Father name', 'Mother name', 'Contact', 'Baby (DOB)', 'Gender', 'Delivery type', 'Birth weight (kg)', 'Was baby LBW', 'Gestational age for baby (weeks)', 'Term Status', 'Growth chart used', 'Immunization status', 'Means for verification immunization', 'Mother age', 'Mother Weight (kg)', 'Age at marriage (years)', 'Reason for admission', 'LBW', 'Preterm', 'Others', 'Mention other reason', 'Admission', 'Date of admission', 'Child Age in Days', 'Gestational Week', 'Admission weight (kg)', 'Admission length (cm)', 'Admission head circumference (cm)', 'Type of feed', 'Breastmilk', 'Animal Milk', 'Formula Milk', 'Parentrel', 'None', 'Mention other feed', 'Mode of Feeding', 'Growth chart used', 'WHO Weight for Age (Z-score)', 'WHO Length for Age (Z-score)', 'WHO Weight for Length (Z-score)', 'WHO Head-Circumferences (Z-score)', 'WHO Weight for Age (Percentile)', 'WHO Length for Age (Percentile)', 'WHO Weight for Length (Percentile)', 'WHO Head-Circumferences (Percentile)', 'Fenton Weight Z-score', 'Fenton Length Z-score', 'Fenton Head-Circumferences Z-score', 'Fenton Weight Percentile', 'Fenton Length Percentile', 'Fenton Head-Circumferences Percentile', 'Intergrowth Weight Z-score', 'Intergrowth Length Z-score', 'Intergrowth Head-Circumferences Z-score', 'Intergrowth Weight Percentile', 'Intergrowth Length Percentile', 'Intergrowth Head-Circumferences Percentile', 'Size at Birth-Inter Growth Clasifiaction', 'Size at Birth-Fenton Growth Classification', 'Size at Birth-WHO Growth Classification', 'SVN Category', 'SVN Status', 'Discharge', 'Date of discharge', 'Child Age in Days', 'Gestational Week at Discharge', 'Discharge Outcomes', 'Discharge Weight(kg)', 'Discharge Length(cm)', 'Discharge Head-Circumferences(cm)', 'Type of feed', 'Breastmilk', 'Animal Milk', 'Formula Milk', 'Parentrel', 'Other', 'Mention other feed', 'Mode of feeding', 'Growth chart used', 'WHO Weight for Age (Z-score)', 'WHO Length for Age (Z-score)', 'WHO Weight for Length (Z-score)', 'WHO Head-Circumferences (Z-score)', 'WHO Weight for Age (Percentile)', 'WHO Length for Age (Percentile)', 'WHO Weight for Length (Percentile)', 'WHO Head-Circumferences (Percentile)', 'Fenton Weight Z-score', 'Fenton Length Z-score', 'Fenton Head-Circumferences Z-score', 'Fenton Weight Percentile', 'Fenton Length Percentile', 'Fenton Head-Circumferences Percentile', 'Intergrowth Weight Z-score', 'Intergrowth Length Z-score', 'Intergrowth Head-Circumferences Z-score', 'Intergrowth Weight Percentile', 'Intergrowth Length Percentile', 'Intergrowth Head-Circumferences Percentile', 'Intergrowth Growth Classification', 'Fenton Growth Classification', 'WHO Growth Classification', 'Fallow-Up Type', 'Schedule Date', 'Date of Visit', 'Child Age in Days', 'Gestational Post Menstural Week', 'Baby Weight', 'Baby Length', 'Baby Head Circumferences', 'Immunization Status', 'Type of Feed', 'Breast Milk', 'Animal Milk', 'Formula Milk', 'Water', 'Others', 'Other Feed', 'Times Baby Breastfed', 'Mode of Feeding', 'Health Examination', 'Asha Check Following Infant', 'Weight', 'Tempreture', 'Respiratory rate', 'Pus on umbilicus', 'Eyes', 'Urine frequency', 'None', 'WHO Weight for Age (Z-score)', 'WHO Length for Age (Z-score)', 'WHO Weight for Length (Z-score)', 'WHO Head-Circumferences (Z-score)', 'WHO Weight for Age (Percentile)', 'WHO Length for Age (Percentile)', 'WHO Weight for Length (Percentile)', 'WHO Head-Circumferences (Percentile)', 'Fenton Weight Z-score', 'Fenton Length Z-score', 'Fenton Head-Circumferences Z-score', 'Fenton Weight Percentile', 'Fenton Length Percentile', 'Fenton Head-Circumferences Percentile', 'Intergrowth Weight Z-score', 'Intergrowth Length Z-score', 'Intergrowth Head-Circumferences Z-score', 'Intergrowth Weight Percentile', 'Intergrowth Length Percentile', 'Intergrowth Head-Circumferences Percentile', 'Intergrowth Growth Classification', 'Fenton Growth Classification', 'WHO Growth Classification', 'Fallow-Up Type', 'Schedule Date', 'Date of Visit', 'Child Age in Days', 'Gestational Post Menstural Week', 'Baby Weight', 'Baby Length', 'Baby Head Circumferences', 'Immunization Status', 'Type of Feed', 'Breast Milk', 'Animal Milk', 'Formula Milk', 'Water', 'Others', 'Other Feed', 'Times Baby Breastfed', 'Mode of Feeding', 'Health Examination', 'Asha Check Following Infant', 'Weight', 'Tempreture', 'Respiratory rate', 'Pus on umbilicus', 'Eyes', 'Urine frequency', 'None', 'WHO Weight for Age (Z-score)', 'WHO Length for Age (Z-score)', 'WHO Weight for Length (Z-score)', 'WHO Head-Circumferences (Z-score)', 'WHO Weight for Age (Percentile)', 'WHO Length for Age (Percentile)', 'WHO Weight for Length (Percentile)', 'WHO Head-Circumferences (Percentile)', 'Fenton Weight Z-score', 'Fenton Length Z-score', 'Fenton Head-Circumferences Z-score', 'Fenton Weight Percentile', 'Fenton Length Percentile', 'Fenton Head-Circumferences Percentile', 'Intergrowth Weight Z-score', 'Intergrowth Length Z-score', 'Intergrowth Head-Circumferences Z-score', 'Intergrowth Weight Percentile', 'Intergrowth Length Percentile', 'Intergrowth Head-Circumferences Percentile', 'Intergrowth Growth Classification', 'Fenton Growth Classification', 'WHO Growth Classification', 'Fallow-Up Type', 'Schedule Date', 'Date of Visit', 'Child Age in Days', 'Gestational Post Menstural Week', 'Baby Weight', 'Baby Length', 'Baby Head Circumferences', 'Immunization Status', 'Type of Feed', 'Breast Milk', 'Animal Milk', 'Formula Milk', 'Water', 'Others', 'Other Feed', 'Times Baby Breastfed', 'Mode of Feeding', 'Health Examination', 'Asha Check Following Infant', 'Weight', 'Tempreture', 'Respiratory rate', 'Pus on umbilicus', 'Eyes', 'Urine frequency', 'None', 'WHO Weight for Age (Z-score)', 'WHO Length for Age (Z-score)', 'WHO Weight for Length (Z-score)', 'WHO Head-Circumferences (Z-score)', 'WHO Weight for Age (Percentile)', 'WHO Length for Age (Percentile)', 'WHO Weight for Length (Percentile)', 'WHO Head-Circumferences (Percentile)', 'Fenton Weight Z-score', 'Fenton Length Z-score', 'Fenton Head-Circumferences Z-score', 'Fenton Weight Percentile', 'Fenton Length Percentile', 'Fenton Head-Circumferences Percentile', 'Intergrowth Weight Z-score', 'Intergrowth Length Z-score', 'Intergrowth Head-Circumferences Z-score', 'Intergrowth Weight Percentile', 'Intergrowth Length Percentile', 'Intergrowth Head-Circumferences Percentile', 'Intergrowth Growth Classification', 'Fenton Growth Classification', 'WHO Growth Classification', 'Fallow-Up Type', 'Schedule Date', 'Date of Visit', 'Child Age in Days', 'Gestational Post Menstural Week', 'Baby Weight', 'Baby Length', 'Baby Head Circumferences', 'Immunization Status', 'Type of Feed', 'Breast Milk', 'Animal Milk', 'Formula Milk', 'Water', 'Others', 'Other Feed', 'Times Baby Breastfed', 'Mode of Feeding', 'Health Examination', 'Asha Check Following Infant', 'Weight', 'Tempreture', 'Respiratory rate', 'Pus on umbilicus', 'Eyes', 'Urine frequency', 'None', 'Start Feeding at Home', 'Offered Food Items',    'Cereals', 'Legume and Nuts', 'Vitamin-A Fruits and Vegetables', 'Other Fruits and Vegitables', 'Milk and Milk Product', 'Egg', 'Meat or Poultry or Fish', 'Junk Items', 'WHO Weight for Age (Z-score)', 'WHO Length for Age (Z-score)', 'WHO Weight for Length (Z-score)', 'WHO Head-Circumferences (Z-score)', 'WHO Weight for Age (Percentile)', 'WHO Length for Age (Percentile)', 'WHO Weight for Length (Percentile)', 'WHO Head-Circumferences (Percentile)', 'Fenton Weight Z-score', 'Fenton Length Z-score', 'Fenton Head-Circumferences Z-score', 'Fenton Weight Percentile', 'Fenton Length Percentile', 'Fenton Head-Circumferences Percentile', 'Intergrowth Weight Z-score', 'Intergrowth Length Z-score', 'Intergrowth Head-Circumferences Z-score', 'Intergrowth Weight Percentile', 'Intergrowth Length Percentile', 'Intergrowth Head-Circumferences Percentile', 'Intergrowth Growth Classification', 'Fenton Growth Classification', 'WHO Growth Classification', 'Fallow-Up Type', 'Schedule Date', 'Date of Visit', 'Child Age in Days', 'Gestational Post Menstural Week', 'Baby Weight', 'Baby Length', 'Baby Head Circumferences', 'Immunization Status', 'Type of Feed', 'Breast Milk', 'Animal Milk', 'Formula Milk', 'Water', 'Others', 'Other Feed', 'Times Baby Breastfed', 'Mode of Feeding', 'Health Examination', 'Asha Check Following Infant', 'Weight', 'Tempreture', 'Respiratory rate', 'Pus on umbilicus', 'Eyes', 'Urine frequency', 'None', 'Start Feeding at Home', 'Offered Food Items',    'Cereals', 'Legume and Nuts', 'Vitamin-A Fruits and Vegetables', 'Other Fruits and Vegitables', 'Milk and Milk Product', 'Egg', 'Meat or Poultry or Fish', 'Junk Items', 'WHO Weight for Age (Z-score)', 'WHO Length for Age (Z-score)', 'WHO Weight for Length (Z-score)', 'WHO Head-Circumferences (Z-score)', 'WHO Weight for Age (Percentile)', 'WHO Length for Age (Percentile)', 'WHO Weight for Length (Percentile)', 'WHO Head-Circumferences (Percentile)', 'Fenton Weight Z-score', 'Fenton Length Z-score', 'Fenton Head-Circumferences Z-score', 'Fenton Weight Percentile', 'Fenton Length Percentile', 'Fenton Head-Circumferences Percentile', 'Intergrowth Weight Z-score', 'Intergrowth Length Z-score', 'Intergrowth Head-Circumferences Z-score', 'Intergrowth Weight Percentile', 'Intergrowth Length Percentile', 'Intergrowth Head-Circumferences Percentile', 'Intergrowth Growth Classification', 'Fenton Growth Classification', 'WHO Growth Classification']); // change as per your columns

// Connect to DB

// Use unbuffered query for large data
$query = "SELECT
    rf.unique_id_of_body, 
    rf.id,
    rf.created_at AS reg_date,
    rf.monitor_name,
    sm.sncu_name,
    rf.boby_name_optional,
    rf.fathers_name,
    rf.boby_of_mothers_name,
	rf.phone_number_of_mother_father_caregivers,
    rf.baby_date_of_birth,
    rf.sex,
    rf.delivery_type,
    rf.birth_weight_kg,
    rf.was_baby_lbw_at_time_of_birth_kg,
    rf.gestational_age_LBW,
	CASE
        WHEN rf.gestational_age_LBW < 33 THEN 'Very pre-term'
        WHEN rf.gestational_age_LBW >= 33 AND rf.gestational_age_LBW < 37 THEN 'Pre-term'
        WHEN rf.gestational_age_LBW >= 37 AND rf.gestational_age_LBW < 39 THEN 'Early-term'
        WHEN rf.gestational_age_LBW >= 39 AND rf.gestational_age_LBW < 41 THEN 'Full-term'
        WHEN rf.gestational_age_LBW >= 41 THEN 'Late-term'
        ELSE ''
    END AS term_classification,
	REPLACE(rf.growth_chart_used, \"'\", \"\") AS growth_chart_used,
    rf.immunization_status,
    rf.means_for_verification_immunization,
    rf.mothers_age_years,
    rf.mother_weight_kg,
    rf.age_at_marrage_years,
    rf.reason_for_admission,
    rf.reason_LBW,
    rf.reason_Preterm,
    rf.reason_Others,
    rf.mention_other_reason,

    'Admission' AS admission_status,
    adm.date_of_admission,
	DATEDIFF(adm.date_of_admission,rf.baby_date_of_birth) AS adm_age_in_days, 
	(rf.gestational_age_LBW+0) as adm_gestestional_week,
    adm.admission_weight,
    adm.admission_length,
    adm.admission_head_circumference,
    adm.type_of_feed AS adm_type_of_feed,
	adm.type_of_feed__Breastmilk AS adm_type_of_feed__Breastmilk,
    adm.type_of_feed__Animal_Milk AS adm_type_of_feed__Animal_Milk,
    adm.type_of_feed__Formula_Milk AS adm_type_of_feed__Formula_Milk,
    adm.type_of_feed__Parenteral AS adm_type_of_feed__Parenteral,
    adm.type_of_feed__Other AS adm_type_of_feed__Other,
    adm.other_feed AS adm_other_feed,
    adm.mode_of_feeding AS adm_mode_of_feeding,
	REPLACE(adm.growth_chart_used, \"'\", \"\") AS adm_growth_chart_used,
    adm.who_wtage AS adm_who_wtage,
    adm.who_lenage AS adm_who_lenage,
    adm.who_wtlen AS adm_who_wtlen,
    adm.who_head_circum AS adm_who_head_circum,
    adm.who_per_wtage AS adm_who_per_wtage,
    adm.who_per_lenage AS adm_who_per_lenage,
    adm.who_per_wtlen AS adm_who_per_wtlen,
    adm.who_head_circum_per AS adm_who_per_head_circum,
    adm.fenton_wtage AS adm_fenton_wtage,
    adm.fenton_lenage AS adm_fenton_lenage,
    adm.fenton_head_circum AS adm_fenton_head_circum,
    adm.fenton_per_wtage AS adm_fenton_per_wtage,
    adm.fenton_per_lenage AS adm_fenton_per_lenage,
    adm.fenton_head_circum_per AS adm_fenton_per_head_circum,
    adm.intergrowth_wtage AS adm_intergrowth_wtage,
    adm.intergrowth_lenage AS adm_intergrowth_lenage,
    adm.intergrowth_head_circum AS adm_intergrowth_head_circum,
    adm.intergrowth_per_wtage AS adm_intergrowth_per_wtage,
    adm.intergrowth_per_lenage AS adm_intergrowth_per_lenage,
    adm.intergrowth_head_circum_per AS adm_intergrowth_per_head_circum,
    adm.intergrowth_classification AS adm_intergrowth_classification,
    adm.fenton_growth_classification AS adm_fenton_growth_classification,
	adm.who_classification AS adm_who_classification,
    CASE
        WHEN rf.gestational_age_LBW >= 37 THEN 'Term'         
        WHEN rf.gestational_age_LBW >= 33 AND rf.gestational_age_LBW < 37 THEN 'Pre-term'
        WHEN rf.gestational_age_LBW < 33 THEN 'Very pre-term'
        ELSE ''                                      
    END AS svn_category,
    CASE
        WHEN rf.gestational_age_LBW >= 37 AND adm.who_classification = 'SGA' THEN 'SVN'
        WHEN rf.gestational_age_LBW >= 37 AND (adm.who_classification = 'AGA' OR adm.who_classification = 'LGA') THEN 'Non SVN'

        WHEN rf.gestational_age_LBW >= 33 AND rf.gestational_age_LBW < 37 AND (adm.intergrowth_classification = 'SGA' OR adm.intergrowth_classification = 'AGA' OR adm. intergrowth_classification = 'LGA') THEN 'SVN'
        WHEN rf.gestational_age_LBW < 33 AND (adm.fenton_growth_classification = 'SGA' OR adm.fenton_growth_classification = 'LGA' OR adm.fenton_growth_classification = 'AGA') THEN 'SVN'

        ELSE ''
    END AS svn_status,
    'Discharge' AS discharge_status,
    dis.date_of_admission AS discharge_date,
	DATEDIFF(dis.date_of_admission,rf.baby_date_of_birth) AS dis_age_in_days,
	(rf.gestational_age_LBW+FLOOR(DATEDIFF(dis.date_of_admission, rf.baby_date_of_birth) / 7)) as dis_gestestional_week,
    dis.progress_of_child,
    dis.admission_weight AS discharge_weight,
    dis.admission_length AS discharge_length,
    dis.admission_head_circumference AS discharge_head_circumference,
    dis.type_of_feed AS dis_type_of_feed,
	dis.type_of_feed__Breastmilk AS dis_type_of_feed__Breastmilk,
    dis.type_of_feed__Animal_Milk AS dis_type_of_feed__Animal_Milk,
    dis.type_of_feed__Formula_Milk AS dis_type_of_feed__Formula_Milk,
    dis.type_of_feed__Parenteral AS dis_type_of_feed__Parenteral,
    dis.type_of_feed__Other AS dis_type_of_feed__Other,
    dis.other_feed AS dis_other_feed,
    dis.mode_of_feeding AS dis_mode_of_feeding,
	REPLACE(dis.growth_chart_used, \"'\", \"\") AS dis_growth_chart,
    dis.who_wtage,
    dis.who_lenage,
    dis.who_wtlen,
    dis.who_head_circum,
    dis.who_per_wtage AS dis_who_per_wtage,
    dis.who_per_lenage AS dis_who_per_lenage,
    dis.who_per_wtlen AS dis_who_per_wtlen,
    dis.who_head_circum_per AS dis_who_per_head_circum,
    dis.fenton_wtage AS dis_fenton_wtage,
    dis.fenton_lenage AS dis_fenton_lenage,
    dis.fenton_head_circum AS dis_fenton_head_circum,
    dis.fenton_per_wtage AS dis_fenton_per_wtage,
    dis.fenton_per_lenage AS dis_fenton_per_lenage,
    dis.fenton_head_circum_per AS dis_fenton_per_head_circum,
    dis.intergrowth_wtage AS dis_intergrowth_wtage,
    dis.intergrowth_lenage AS dis_intergrowth_lenage,
    dis.intergrowth_head_circum AS dis_intergrowth_head_circum,
    dis.intergrowth_per_wtage AS dis_intergrowth_per_wtage,
    dis.intergrowth_per_lenage AS dis_intergrowth_per_lenage,
    dis.intergrowth_head_circum_per AS dis_intergrowth_per_head_circum,
    dis.fenton_growth_classification AS dis_fenton_growth_classification,
    dis.intergrowth_classification AS dis_intergrowth_classification,
    dis.who_classification AS dis_who_classification,

    fu8.type_of_time AS fu8_type,
    fu8.schedule_follow_up_date AS fu8_schedule,
    fu8.date_of_visit AS fu8_visit,
    DATEDIFF(fu8.date_of_visit, rf.baby_date_of_birth) AS fu8_age_in_days,
    (rf.gestational_age_LBW + FLOOR(DATEDIFF(fu8.date_of_visit, rf.baby_date_of_birth) / 7)) AS fu8_total_weeks,
    fu8.baby_weight AS fu8_weight,
    fu8.baby_length AS fu8_length,
    fu8.baby_head_circumference AS fu8_head_circ,
    fu8.immunization_status AS fu8_immunization_status,
    fu8.type_of_feed AS fu8_feed_type,
    fu8.tof_Breast_milk AS fu8_breast_milk,
    fu8.tof_Animal_milk AS fu8_animal_milk,
    fu8.tof_Formula_milk AS fu8_formula_milk,
    fu8.tof_Water AS fu8_water,
    fu8.tof_Others AS fu8_others,
    fu8.other_feed AS fu8_other_feed,
    fu8.times_baby_breastfed AS fu8_bf_times,
    fu8.mode_of_feeding AS fu8_mode_of_feeding,
    fu8.check_health_examination AS fu8_health_check,
    fu8.asha_check_following_infant AS fu8_asha_check,
    fu8.check_Weight as fu8_check_weight, fu8.check_Temparature as fu8_check_Temparature, fu8.check_Respiratory_rate as fu8_check_Respiratory_rate,
    fu8.check_Pus_on_umbilicus as fu8_check_Pus_on_umbilicus, fu8.check_Eyes as fu8_check_Eyes, fu8.check_Urine_frequency as fu8_check_Urine_frequency, fu8.check_none as fu8_check_none,
    fu8.who_wtage AS fu8_wtage, fu8.who_lenage AS fu8_lenage, fu8.who_wtlen AS fu8_wtlen,
    fu8.who_head_circum AS fu8_who_head_circum,
    fu8.who_per_wtage AS fu8_per_wtage, fu8.who_per_lenage AS fu8_per_lenage,
    fu8.who_per_wtlen AS fu8_per_wtlen, fu8.who_head_circum_per AS fu8_per_head_circum,
    fu8.fenton_wtage AS fu8_fenton_wtage, fu8.fenton_lenage AS fu8_fenton_lenage,
    fu8.fenton_head_circum AS fu8_fenton_head_circum,
    fu8.fenton_per_wtage AS fu8_fenton_per_wtage, fu8.fenton_per_lenage AS fu8_fenton_per_lenage,
    fu8.fenton_head_circum_per AS fu8_fenton_per_head_circum,
    fu8.intergrowth_wtage AS fu8_intergrowth_wtage, fu8.intergrowth_lenage AS fu8_intergrowth_lenage,
    fu8.intergrowth_head_circum AS fu8_intergrowth_head_circum,
    fu8.intergrowth_per_wtage AS fu8_intergrowth_per_wtage,
    fu8.intergrowth_per_lenage AS fu8_intergrowth_per_lenage,
    fu8.intergrowth_head_circum_per AS fu8_intergrowth_per_head_circum,
    fu8.intergrowth_classification AS fu8_intergrowth_classification,
    fu8.fenton_growth_classification AS fu8_fenton_growth_classification,
    fu8.who_classification AS fu8_who_classification,

    
    fu1.type_of_time AS fu1_type,
    fu1.schedule_follow_up_date AS fu1_schedule,
    fu1.date_of_visit AS fu1_visit,
    DATEDIFF(fu1.date_of_visit, rf.baby_date_of_birth) AS fu1_age_in_days,
    (rf.gestational_age_LBW + FLOOR(DATEDIFF(fu1.date_of_visit, rf.baby_date_of_birth) / 7)) AS fu1_total_weeks,
    fu1.baby_weight AS fu1_weight,
    fu1.baby_length AS fu1_length,
    fu1.baby_head_circumference AS fu1_head_circ,
    fu1.immunization_status AS fu1_immunization_status,
    fu1.type_of_feed AS fu1_feed_type,
    fu1.tof_Breast_milk AS fu1_breast_milk,
    fu1.tof_Animal_milk AS fu1_animal_milk,
    fu1.tof_Formula_milk AS fu1_formula_milk,
    fu1.tof_Water AS fu1_water,
    fu1.tof_Others AS fu1_others,
    fu1.other_feed AS fu1_other_feed,
    fu1.times_baby_breastfed AS fu1_bf_times,
    fu1.mode_of_feeding AS fu1_mode_of_feeding,
    fu1.check_health_examination AS fu1_health_check,
    fu1.asha_check_following_infant AS fu1_asha_check,
    fu1.check_Weight as fu1_check_weight, fu1.check_Temparature as fu1_check_Temparature, fu1.check_Respiratory_rate as fu1_check_Respiratory_rate,
    fu1.check_Pus_on_umbilicus as fu1_check_Pus_on_umbilicus, fu1.check_Eyes as fu1_check_Eyes, fu1.check_Urine_frequency as fu1_check_Urine_frequency, fu1.check_none as fu1_check_none,
    fu1.who_wtage AS fu1_wtage, fu1.who_lenage AS fu1_lenage, fu1.who_wtlen AS fu1_wtlen,
    fu1.who_head_circum AS fu1_who_head_circum,
    fu1.who_per_wtage AS fu1_per_wtage, fu1.who_per_lenage AS fu1_per_lenage,
    fu1.who_per_wtlen AS fu1_per_wtlen, fu1.who_head_circum_per AS fu1_per_head_circum,
    fu1.fenton_wtage AS fu1_fenton_wtage, fu1.fenton_lenage AS fu1_fenton_lenage,
    fu1.fenton_head_circum AS fu1_fenton_head_circum,
    fu1.fenton_per_wtage AS fu1_fenton_per_wtage, fu1.fenton_per_lenage AS fu1_fenton_per_lenage,
    fu1.fenton_head_circum_per AS fu1_fenton_per_head_circum,
    fu1.intergrowth_wtage AS fu1_intergrowth_wtage, fu1.intergrowth_lenage AS fu1_intergrowth_lenage,
    fu1.intergrowth_head_circum AS fu1_intergrowth_head_circum,
    fu1.intergrowth_per_wtage AS fu1_intergrowth_per_wtage,
    fu1.intergrowth_per_lenage AS fu1_intergrowth_per_lenage,
    fu1.intergrowth_head_circum_per AS fu1_intergrowth_per_head_circum,
    fu1.intergrowth_classification AS fu1_intergrowth_classification,
    fu1.fenton_growth_classification AS fu1_fenton_growth_classification,
    fu1.who_classification AS fu1_who_classification,

    fu3.type_of_time AS fu3_type,
    fu3.schedule_follow_up_date AS fu3_schedule,
    fu3.date_of_visit AS fu3_visit,
    DATEDIFF(fu3.date_of_visit, rf.baby_date_of_birth) AS fu3_age_in_days,
    (rf.gestational_age_LBW + FLOOR(DATEDIFF(fu3.date_of_visit, rf.baby_date_of_birth) / 7)) AS fu3_total_weeks,
    fu3.baby_weight AS fu3_weight,
    fu3.baby_length AS fu3_length,
    fu3.baby_head_circumference AS fu3_head_circ,
    fu3.immunization_status AS fu3_immunization_status,
    fu3.type_of_feed AS fu3_feed_type,
    fu3.tof_Breast_milk AS fu3_breast_milk,
    fu3.tof_Animal_milk AS fu3_animal_milk,
    fu3.tof_Formula_milk AS fu3_formula_milk,
    fu3.tof_Water AS fu3_water,
    fu3.tof_Others AS fu3_others,
    fu3.other_feed AS fu3_other_feed,
    fu3.times_baby_breastfed AS fu3_bf_times,
    fu3.mode_of_feeding AS fu3_mode_of_feeding,
    fu3.check_health_examination AS fu3_health_check,
    fu3.asha_check_following_infant AS fu3_asha_check,
    fu3.check_Weight as fu3_check_weight, fu3.check_Temparature as fu3_check_Temparature, fu3.check_Respiratory_rate as fu3_check_Respiratory_rate,
    fu3.check_Pus_on_umbilicus as fu3_check_Pus_on_umbilicus, fu3.check_Eyes as fu3_check_Eyes, fu3.check_Urine_frequency as fu3_check_Urine_frequency, fu3.check_none as fu3_check_none,
    fu3.who_wtage AS fu3_wtage, fu3.who_lenage AS fu3_lenage, fu3.who_wtlen AS fu3_wtlen,
    fu3.who_head_circum AS fu3_who_head_circum,
    fu3.who_per_wtage AS fu3_per_wtage, fu3.who_per_lenage AS fu3_per_lenage,
    fu3.who_per_wtlen AS fu3_per_wtlen, fu3.who_head_circum_per AS fu3_per_head_circum,
    fu3.fenton_wtage AS fu3_fenton_wtage, fu3.fenton_lenage AS fu3_fenton_lenage,
    fu3.fenton_head_circum AS fu3_fenton_head_circum,
    fu3.fenton_per_wtage AS fu3_fenton_per_wtage, fu3.fenton_per_lenage AS fu3_fenton_per_lenage,
    fu3.fenton_head_circum_per AS fu3_fenton_per_head_circum,
    fu3.intergrowth_wtage AS fu3_intergrowth_wtage, fu3.intergrowth_lenage AS fu3_intergrowth_lenage,
    fu3.intergrowth_head_circum AS fu3_intergrowth_head_circum,
    fu3.intergrowth_per_wtage AS fu3_intergrowth_per_wtage,
    fu3.intergrowth_per_lenage AS fu3_intergrowth_per_lenage,
    fu3.intergrowth_head_circum_per AS fu3_intergrowth_per_head_circum,
    fu3.intergrowth_classification AS fu3_intergrowth_classification,
    fu3.fenton_growth_classification AS fu3_fenton_growth_classification,
    fu3.who_classification AS fu3_who_classification,

    fu6.type_of_time AS fu6_type,
    fu6.schedule_follow_up_date AS fu6_schedule,
    fu6.date_of_visit AS fu6_visit,
    DATEDIFF(fu6.date_of_visit, rf.baby_date_of_birth) AS fu6_age_in_days,
    (rf.gestational_age_LBW + FLOOR(DATEDIFF(fu6.date_of_visit, rf.baby_date_of_birth) / 7)) AS fu6_total_weeks,
    fu6.baby_weight AS fu6_weight,
    fu6.baby_length AS fu6_length,
    fu6.baby_head_circumference AS fu6_head_circ,
    fu6.immunization_status AS fu6_immunization_status,
    fu6.type_of_feed AS fu6_feed_type,
    fu6.tof_Breast_milk AS fu6_breast_milk,
    fu6.tof_Animal_milk AS fu6_animal_milk,
    fu6.tof_Formula_milk AS fu6_formula_milk,
    fu6.tof_Water AS fu6_water,
    fu6.tof_Others AS fu6_others,
    fu6.other_feed AS fu6_other_feed,
    fu6.times_baby_breastfed AS fu6_bf_times,
    fu6.mode_of_feeding AS fu6_mode_of_feeding,
    fu6.check_health_examination AS fu6_health_check,
    fu6.asha_check_following_infant AS fu6_asha_check,
    fu6.check_Weight as fu6_check_weight, fu6.check_Temparature as fu6_check_Temparature, fu6.check_Respiratory_rate as fu6_check_Respiratory_rate,
    fu6.check_Pus_on_umbilicus as fu6_check_Pus_on_umbilicus, fu6.check_Eyes as fu6_check_Eyes, fu6.check_Urine_frequency as fu6_check_Urine_frequency, fu6.check_none as fu6_check_none,fu6.started_feeding_at_home AS fu6_started_feeding_at_home,fu6.offered_food_items AS fu6_offered_food_items,fu6.cereals AS fu6_cereals,fu6.legume_and_nuts AS fu6_legume_and_nuts,fu6.vitamin_a_fruits_and_vegetables AS fu6_vitamin_a_fruits_and_vegetables,fu6.other_fruits_and_vegitables AS fu6_other_fruits_and_vegitables,fu6.milk_and_milk_product AS fu6_milk_and_milk_product,fu6.egg AS fu6_egg,fu6.meat_or_poultry_or_fish AS fu6_meat_or_poultry_or_fish,fu6.junk_items AS fu6_junk_items,
    fu6.who_wtage AS fu6_wtage, fu6.who_lenage AS fu6_lenage, fu6.who_wtlen AS fu6_wtlen,
    fu6.who_head_circum AS fu6_who_head_circum,
    fu6.who_per_wtage AS fu6_per_wtage, fu6.who_per_lenage AS fu6_per_lenage,
    fu6.who_per_wtlen AS fu6_per_wtlen, fu6.who_head_circum_per AS fu6_per_head_circum,
    fu6.fenton_wtage AS fu6_fenton_wtage, fu6.fenton_lenage AS fu6_fenton_lenage,
    fu6.fenton_head_circum AS fu6_fenton_head_circum,
    fu6.fenton_per_wtage AS fu6_fenton_per_wtage, fu6.fenton_per_lenage AS fu6_fenton_per_lenage,
    fu6.fenton_head_circum_per AS fu6_fenton_per_head_circum,
    fu6.intergrowth_wtage AS fu6_intergrowth_wtage, fu6.intergrowth_lenage AS fu6_intergrowth_lenage,
    fu6.intergrowth_head_circum AS fu6_intergrowth_head_circum,
    fu6.intergrowth_per_wtage AS fu6_intergrowth_per_wtage,
    fu6.intergrowth_per_lenage AS fu6_intergrowth_per_lenage,
    fu6.intergrowth_head_circum_per AS fu6_intergrowth_per_head_circum,
    fu6.intergrowth_classification AS fu6_intergrowth_classification,
    fu6.fenton_growth_classification AS fu6_fenton_growth_classification,
    fu6.who_classification AS fu6_who_classification,

    fu12.type_of_time AS fu12_type,
    fu12.schedule_follow_up_date AS fu12_schedule,
    fu12.date_of_visit AS fu12_visit,
    DATEDIFF(fu12.date_of_visit, rf.baby_date_of_birth) AS fu12_age_in_days,
    (rf.gestational_age_LBW + FLOOR(DATEDIFF(fu12.date_of_visit, rf.baby_date_of_birth) / 7)) AS fu12_total_weeks,
    fu12.baby_weight AS fu12_weight,
    fu12.baby_length AS fu12_length,
    fu12.baby_head_circumference AS fu12_head_circ,
    fu12.immunization_status AS fu12_immunization_status,
    fu12.type_of_feed AS fu12_feed_type,
    fu12.tof_Breast_milk AS fu12_breast_milk,
    fu12.tof_Animal_milk AS fu12_animal_milk,
    fu12.tof_Formula_milk AS fu12_formula_milk,
    fu12.tof_Water AS fu12_water,
    fu12.tof_Others AS fu12_others,
    fu12.other_feed AS fu12_other_feed,
    fu12.times_baby_breastfed AS fu12_bf_times,
    fu12.mode_of_feeding AS fu12_mode_of_feeding,
    fu12.check_health_examination AS fu12_health_check,
    fu12.asha_check_following_infant AS fu12_asha_check,
    fu12.check_Weight as fu12_check_weight, fu12.check_Temparature as fu12_check_Temparature, fu12.check_Respiratory_rate as fu12_check_Respiratory_rate,
    fu12.check_Pus_on_umbilicus as fu12_check_Pus_on_umbilicus, fu12.check_Eyes as fu12_check_Eyes, fu12.check_Urine_frequency as fu12_check_Urine_frequency, fu12.check_none as fu12_check_none,fu12.started_feeding_at_home AS fu12_started_feeding_at_home,fu12.offered_food_items AS fu12_offered_food_items,fu12.cereals AS fu12_cereals,fu12.legume_and_nuts AS fu12_legume_and_nuts,fu12.vitamin_a_fruits_and_vegetables AS fu12_vitamin_a_fruits_and_vegetables,fu12.other_fruits_and_vegitables AS fu12_other_fruits_and_vegitables,fu12.milk_and_milk_product AS fu12_milk_and_milk_product,fu12.egg AS fu12_egg,fu12.meat_or_poultry_or_fish AS fu12_meat_or_poultry_or_fish,fu12.junk_items AS fu12_junk_items,
    fu12.who_wtage AS fu12_wtage, fu12.who_lenage AS fu12_lenage, fu12.who_wtlen AS fu12_wtlen,
    fu12.who_head_circum AS fu12_who_head_circum,
    fu12.who_per_wtage AS fu12_per_wtage, fu12.who_per_lenage AS fu12_per_lenage,
    fu12.who_per_wtlen AS fu12_per_wtlen, fu12.who_head_circum_per AS fu12_per_head_circum,
    fu12.fenton_wtage AS fu12_fenton_wtage, fu12.fenton_lenage AS fu12_fenton_lenage,
    fu12.fenton_head_circum AS fu12_fenton_head_circum,
    fu12.fenton_per_wtage AS fu12_fenton_per_wtage, fu12.fenton_per_lenage AS fu12_fenton_per_lenage,
    fu12.fenton_head_circum_per AS fu12_fenton_per_head_circum,
    fu12.intergrowth_wtage AS fu12_intergrowth_wtage, fu12.intergrowth_lenage AS fu12_intergrowth_lenage,
    fu12.intergrowth_head_circum AS fu12_intergrowth_head_circum,
    fu12.intergrowth_per_wtage AS fu12_intergrowth_per_wtage,
    fu12.intergrowth_per_lenage AS fu12_intergrowth_per_lenage,
    fu12.intergrowth_head_circum_per AS fu12_intergrowth_per_head_circum,
    fu12.intergrowth_classification AS fu12_intergrowth_classification,
    fu12.fenton_growth_classification AS fu12_fenton_growth_classification,
    fu12.who_classification AS fu12_who_classification

FROM registration_form rf

LEFT JOIN monitoring_data adm ON rf.id = adm.registration_id AND adm.type_of_monitoring = 'Date of Admission'
LEFT JOIN monitoring_data dis ON rf.id = dis.registration_id AND dis.type_of_monitoring = 'Discharge Day'
LEFT JOIN sncu_master sm ON rf.sncu_id = sm.id 
LEFT JOIN follow_up fu8 ON rf.id = fu8.registration_id AND fu8.type_of_time = '8 Days'
LEFT JOIN follow_up fu1 ON rf.id = fu1.registration_id AND fu1.type_of_time = '1 Month'
LEFT JOIN follow_up fu3 ON rf.id = fu3.registration_id AND fu3.type_of_time = '3 Month'
LEFT JOIN follow_up fu6 ON rf.id = fu6.registration_id AND fu6.type_of_time = '6 Month'
LEFT JOIN follow_up fu12 ON rf.id = fu12.registration_id AND fu12.type_of_time = '1 Year'

WHERE 1=1
GROUP BY rf.id  
ORDER BY rf.id DESC"; // Adjust table and columns


$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query Error: ' . mysqli_error($conn));
}

// Stream CSV rows
while ($row = mysqli_fetch_assoc($result)) {
    $row['fu8_type'] = '8 Days';
    $row['fu1_type'] = '1 Month';
    $row['fu3_type'] = '3 Month';
    $row['fu6_type'] = '6 Month';
    $row['fu12_type'] = '1 Year';

    $sanitizedRow = array_map(function ($val) {
        $val = str_replace(["'", "\"", "\r", "\n", "\t"], ["", "", " ", " ", " "], $val);
        return trim($val);
    }, $row);
    fputcsv($output, $sanitizedRow);
}

mysqli_free_result($result);
fclose($output);
exit;
