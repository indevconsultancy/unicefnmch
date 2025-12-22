 <?php include('includes/config.php'); ?>
 <?php include('includes/functions.php'); ?>
 <?php include('includes/headers.php'); ?>

 <?php
    $child_id = $_GET['c_id'];
    ?>

 <?php
    // Admission And Discharge Update  
    if (isset($_POST['adm_dis_upd'])) {
        $child_id = $_POST['child_id'];
        $type = $_POST['type'];

        $doa = mysqli_real_escape_string($conn, $_POST['doa']);
        $adm_wt = mysqli_real_escape_string($conn, $_POST['adm_wt']);
        $adm_lt = mysqli_real_escape_string($conn, $_POST['adm_lt']);
        $hed_cir = mysqli_real_escape_string($conn, $_POST['hed_cir']);
        $md_of_feed = mysqli_real_escape_string($conn, $_POST['md_of_feed']);
        $g_c_usd = mysqli_real_escape_string($conn, $_POST['g_c_usd']);

        $type_of_feed = $_POST['type_of_feed'];
        $feed_string = implode(',', $type_of_feed);
        $oth_fd =  (in_array('Other', $type_of_feed)) ? $_POST['oth_fd'] : '';

        if ($type == 'Discharge Day' && isset($_POST['poc'])) {
            $poc = mysqli_real_escape_string($conn, $_POST['poc']);
            if ($poc == 'Death') {
                $upd_qry = "UPDATE monitoring_data SET date_of_admission = '$doa', admission_weight = '', admission_length = '', admission_head_circumference = '', mode_of_feeding = '', growth_chart_used = '', type_of_feed = '',other_feed='',progress_of_child='$poc',growth_status = 0,growth_status_fenton = 0,growth_status_intergrowth = 0 WHERE registration_id = '$child_id' AND type_of_monitoring = '$type'";
            } else {
                $upd_qry = "UPDATE monitoring_data SET date_of_admission = '$doa', admission_weight = '$adm_wt', admission_length = '$adm_lt', admission_head_circumference = '$hed_cir', mode_of_feeding = '$md_of_feed', growth_chart_used = '$g_c_usd', type_of_feed = '$feed_string',other_feed='$oth_fd',progress_of_child='$poc',growth_status = 0,growth_status_fenton = 0,growth_status_intergrowth = 0 WHERE registration_id = '$child_id' AND type_of_monitoring = '$type'";
            }
        } else {
            $upd_qry = "UPDATE monitoring_data SET date_of_admission = '$doa', admission_weight = '$adm_wt', admission_length = '$adm_lt', admission_head_circumference = '$hed_cir', mode_of_feeding = '$md_of_feed', growth_chart_used = '$g_c_usd', type_of_feed = '$feed_string',other_feed='$oth_fd',growth_status = 0,growth_status_fenton = 0,growth_status_intergrowth = 0 WHERE registration_id = '$child_id' AND type_of_monitoring = '$type'";
        }
        $res_upd = mysqli_query($conn, $upd_qry);
        if ($res_upd) {
            echo "<script>
                    alert('Record Updated Sucessfully!');
                    window.location.href = 'child_profile.php?c_id=$child_id';
                  </script>
                  ";
        } else {
            echo "<script>
                    alert('Something went wrong!');
                    window.location.href = 'child_profile.php?c_id=$child_id';
                  </script>
                  ";
        }
    }
    // Admission And Discharge Insert  
    if (isset($_POST['adm_dis_ins'])) {
        $type = $_POST['type'];
        $user_id = $_POST['user_id'];
        $reg_id = $_POST['reg_id'];

        $doa = mysqli_real_escape_string($conn, $_POST['doa']);
        $adm_wt = mysqli_real_escape_string($conn, $_POST['adm_wt']);
        $adm_lt = mysqli_real_escape_string($conn, $_POST['adm_lt']);
        $hed_cir = mysqli_real_escape_string($conn, $_POST['hed_cir']);
        $md_of_feed = mysqli_real_escape_string($conn, $_POST['md_of_feed']);
        $g_c_usd = mysqli_real_escape_string($conn, $_POST['g_c_usd']);

        $type_of_feed = $_POST['type_of_feed'];
        $feed_string = implode(',', $type_of_feed);
        $oth = mysqli_real_escape_string($conn, $_POST['oth_fd']);

        if ($type == 'Discharge Day' && isset($_POST['pocadd'])) {
            $pocadd = mysqli_real_escape_string($conn, $_POST['pocadd']);
            if ($pocadd == 'Death') {
                $ins_qry = "INSERT INTO monitoring_data(type_of_monitoring,user_id,registration_id,date_of_admission,progress_of_child) values('$type','$user_id','$reg_id','$doa','$pocadd')";
            } else {
                $ins_qry = "INSERT INTO monitoring_data(type_of_monitoring,user_id,registration_id,date_of_admission,admission_weight,admission_length,admission_head_circumference,type_of_feed,other_feed,mode_of_feeding,growth_chart_used,progress_of_child) values('$type','$user_id','$reg_id','$doa','$adm_wt','$adm_lt','$hed_cir','$feed_string','$oth','$md_of_feed','$g_c_usd','$pocadd')";
                $ins_reg = "UPDATE registration_form SET monitoring_status = '$type' WHERE id = $reg_id";
            }
        } else {
            $ins_qry = "INSERT INTO monitoring_data(type_of_monitoring,user_id,registration_id,date_of_admission,admission_weight,admission_length,admission_head_circumference,type_of_feed,other_feed,mode_of_feeding,growth_chart_used) values('$type','$user_id','$reg_id','$doa','$adm_wt','$adm_lt','$hed_cir','$feed_string','$oth','$md_of_feed','$g_c_usd')";
            $ins_reg = "UPDATE registration_form SET monitoring_status = '$type' WHERE id = $reg_id";
        }

        $res_ins = mysqli_query($conn, $ins_qry);
        if ($res_ins) {
            mysqli_query($conn, $ins_reg);
            echo "<script>
                    alert('Record Inserted Sucessfully!');
                    window.location.href = 'child_profile.php?c_id=$child_id';
                  </script>";
        } else {
            echo "<script>
                    alert('Something went wrong!');
                    window.location.href = 'child_profile.php?c_id=$child_id';
                  </script>";
        }
    }
    // Follow-Up Update
    if (isset($_POST['upd_follow_up'])) {
        $reg_id = $_POST['child_id'];
        $type_of_time = $_POST['type_of_time'];
        $foll_id = $_POST['foll_id'];

        $sch_date = mysqli_real_escape_string($conn, $_POST['sch_date']);
        $vsi_date = mysqli_real_escape_string($conn, $_POST['vsi_date']);
        $b_weight = mysqli_real_escape_string($conn, $_POST['b_weight']);
        $b_length = mysqli_real_escape_string($conn, $_POST['b_length']);
        $h_circum = mysqli_real_escape_string($conn, $_POST['h_circum']);
        $im_status = mysqli_real_escape_string($conn, $_POST['im_status']);
        $times_b_feed = mysqli_real_escape_string($conn, $_POST['times_b_feed']);
        $feed_bot = mysqli_real_escape_string($conn, $_POST['feed_bot']);
        $prm_feed = mysqli_real_escape_string($conn, $_POST['prm_feed']);
        $hlt_exp = mysqli_real_escape_string($conn, $_POST['hlt_exp']);
        $m_h_issue = ($hlt_exp == 'Has health issue') ? $_POST['m_h_issue'] : '';
        $dis_sncu = mysqli_real_escape_string($conn, $_POST['dis_sncu']);
        $asa_vsi_time = ($dis_sncu == 'Yes') ? $_POST['asa_vsi_time'] : '';
        $aww_visit = mysqli_real_escape_string($conn, $_POST['aww_visit']);
        $aww_time = ($aww_visit == 'Yes') ? $_POST['aww_time'] : '';

        $up_start_feed = '';
        $up_off_food = '';
        if ($type_of_time == '6 Month' || $type_of_time == '1 Year') {
            $up_start_feed = mysqli_real_escape_string($conn, $_POST['add_start_feed']);
            if ($up_start_feed == 'Yes') {
                $upof_fd = $_POST['add_offered_food'];
                $cearls  = (in_array('Cereals/Tuber', $upof_fd)) ? 'Yes' : 'No';
                $leg_nut = (in_array('Legume & Nuts', $upof_fd)) ? 'Yes' : 'No';
                $vit_frt = (in_array('Vitamin-A rich fruits and vegetables ( Red/ Yellow/Orange)', $upof_fd)) ? 'Yes' : 'No';
                $oth_frt = (in_array('Other fruits & Vegetables', $upof_fd)) ? 'Yes' : 'No';
                $milk    = (in_array('Milk & Milk products - Dahi or Lassi or Paneer)', $upof_fd)) ? 'Yes' : 'No';
                $egg     = (in_array('Egg', $upof_fd)) ? 'Yes' : 'No';
                $meat    = (in_array('Meat/ Poultry/ Fish', $upof_fd)) ? 'Yes' : 'No';
                $junk    = (in_array('Junk items - Chips or Biscuits or chocolate etc', $upof_fd)) ? 'Yes' : 'No';
                $up_off_food = implode(',', $upof_fd);
            }
        }

        $type_of_feed = $_POST['addtype_of_feed'];
        $feed_string = implode(',', $type_of_feed);
        $oth_feed = (in_array('Others', $type_of_feed)) ? $_POST['oth_res'] : '';

        $asha_check_infant = $_POST['addinfant'];
        $infant_string = implode(',', $asha_check_infant);

        $counnsel = $_POST['addcounnsel'];
        $counnsel_string = implode(',', $counnsel);

        $fol_qry = "UPDATE follow_up SET type_of_time='$type_of_time',schedule_follow_up_date='$sch_date',date_of_visit='$vsi_date',baby_weight='$b_weight',baby_length='$b_length',baby_head_circumference='$h_circum',immunization_status='$im_status',type_of_feed='$feed_string',other_feed='$oth_feed',times_baby_breastfed='$times_b_feed',mode_of_feeding='$feed_bot',anyone_counselled='$prm_feed',check_health_examination='$hlt_exp',mention_identified_health='$m_h_issue',asha_visit_baby_from_sncu='$dis_sncu',how_many_times_asha_visited='$asa_vsi_time',aww_visit_baby_from_sncu='$aww_visit',how_many_times_aww_visited='$aww_time',asha_check_following_infant='$infant_string',asha_counsel_mother_family='$counnsel_string',started_feeding_at_home = '$up_start_feed',offered_food_items='$up_off_food',cereals='$cearls',legume_and_nuts='$leg_nut',vitamin_a_fruits_and_vegetables='$vit_frt',other_fruits_and_vegitables='$oth_frt',milk_and_milk_product='$milk',egg='$egg',meat_or_poultry_or_fish='$meat',junk_items='$junk',growth_status=0,growth_status_fenton=0,growth_status_intergrowth=0 WHERE id = $foll_id AND status='1'";
        $res_upd = mysqli_query($conn, $fol_qry);
        if ($res_upd) {
            echo "<script>
                    alert('Record Updated Sucessfully!');
                    window.location.href = 'child_profile.php?c_id=$reg_id';
                  </script>";
        } else {
            echo "<script>
                    alert('Something went wrong!');
                    window.location.href = 'child_profile.php?c_id=$reg_id';
                  </script>";
        }
    }
    // Follow-Up Add
    if (isset($_POST['ins_follow_up'])) {
        $reg_id = $_POST['addchild_id'];
        $adduser_id = $_POST['adduser_id'];
        $type_of_time = $_POST['addtype_of_time'];

        $sch_date = mysqli_real_escape_string($conn, $_POST['addsch_date']);
        $vsi_date = mysqli_real_escape_string($conn, $_POST['addvsi_date']);
        $b_weight = mysqli_real_escape_string($conn, $_POST['addb_weight']);
        $b_length = mysqli_real_escape_string($conn, $_POST['addb_length']);
        $h_circum = mysqli_real_escape_string($conn, $_POST['addh_circum']);
        $im_status = mysqli_real_escape_string($conn, $_POST['addim_status']);
        $times_b_feed = mysqli_real_escape_string($conn, $_POST['addtimes_b_feed']);
        $feed_bot = mysqli_real_escape_string($conn, $_POST['addfeed_bot']);
        $prm_feed = mysqli_real_escape_string($conn, $_POST['addprm_feed']);
        $hlt_exp = mysqli_real_escape_string($conn, $_POST['addhlt_exp']);
        $m_h_issue = ($hlt_exp == 'Has health issue') ? $_POST['addm_h_issue'] : '';
        $dis_sncu = mysqli_real_escape_string($conn, $_POST['adddis_sncu']);
        $asa_vsi_time = ($dis_sncu == 'Yes') ? $_POST['addasa_vsi_time'] : '';
        $aww_visit = mysqli_real_escape_string($conn, $_POST['addaww_visit']);
        $aww_time = ($aww_visit == 'Yes') ? $_POST['addaww_time'] : '';

        $start_feed = '';
        $off_food = '';
        if ($type_of_time == '6 Month' || $type_of_time == '1 Year') {
            $start_feed = mysqli_real_escape_string($conn, $_POST['add_start_feed']);
            if ($start_feed == 'Yes') {
                $of_fd = $_POST['add_offered_food'];
                $cearls  = (in_array('Cereals/Tuber', $of_fd)) ? 'Yes' : 'No';
                $leg_nut = (in_array('Legume & Nuts', $of_fd)) ? 'Yes' : 'No';
                $vit_frt = (in_array('Vitamin-A rich fruits and vegetables ( Red/ Yellow/Orange)', $of_fd)) ? 'Yes' : 'No';
                $oth_frt = (in_array('Other fruits & Vegetables', $of_fd)) ? 'Yes' : 'No';
                $milk    = (in_array('Milk & Milk products - Dahi or Lassi or Paneer)', $of_fd)) ? 'Yes' : 'No';
                $egg     = (in_array('Egg', $of_fd)) ? 'Yes' : 'No';
                $meat    = (in_array('Meat/ Poultry/ Fish', $of_fd)) ? 'Yes' : 'No';
                $junk    = (in_array('Junk items - Chips or Biscuits or chocolate etc', $of_fd)) ? 'Yes' : 'No';
                $off_food = implode(',', $of_fd);
            }
        }

        $type_of_feed = $_POST['addtype_of_feed'];
        $feed_string = implode(',', $type_of_feed);
        $oth_feed = (in_array('Others', $type_of_feed)) ? $_POST['addoth_res'] : '';

        $asha_check_infant = $_POST['addinfant'];
        $infant_string = implode(',', $asha_check_infant);

        $counnsel = $_POST['addcounnsel'];
        $counnsel_string = implode(',', $counnsel);


        $addfol_qry = "INSERT INTO follow_up (type_of_time,user_id,registration_id, schedule_follow_up_date, date_of_visit, baby_weight, baby_length, baby_head_circumference, immunization_status, type_of_feed, other_feed, times_baby_breastfed, mode_of_feeding, anyone_counselled, check_health_examination, mention_identified_health, asha_visit_baby_from_sncu, how_many_times_asha_visited, aww_visit_baby_from_sncu, how_many_times_aww_visited, asha_check_following_infant, asha_counsel_mother_family,started_feeding_at_home,offered_food_items,cereals,legume_and_nuts,vitamin_a_fruits_and_vegetables,other_fruits_and_vegitables,milk_and_milk_product,egg,meat_or_poultry_or_fish,junk_items) VALUES ('$type_of_time', '$adduser_id', '$reg_id', '$sch_date', '$vsi_date', '$b_weight', '$b_length', '$h_circum', '$im_status', '$feed_string', '$oth_feed', '$times_b_feed', '$feed_bot', '$prm_feed', '$hlt_exp', '$m_h_issue', '$dis_sncu', '$asa_vsi_time', '$aww_visit', '$aww_time', '$infant_string', '$counnsel_string','$start_feed','$off_food','$cearls','$leg_nut','$vit_frt','$oth_frt','$milk','$egg','$meat','$junk')";
        $fol_add = mysqli_query($conn, $addfol_qry);

        if ($fol_add) {
            echo "<script>
                    alert('Record Inserted Sucessfully!');
                    window.location.href = 'child_profile.php?c_id=$reg_id';
                  </script>";
        } else {
            echo "<script>
                    alert('Something went wrong!');
                    window.location.href = 'child_profile.php?c_id=$reg_id';
                  </script>";
        }
    }
    // Update MNCU
    if (isset($_POST['upd_mncu1'])) {
        $reg_id = $_POST['childidm'];
        $adduser_id = $_POST['adduser_id'];

        $mncudoa = mysqli_real_escape_string($conn, $_POST['mncudoa']);
        $brn_admt = mysqli_real_escape_string($conn, $_POST['brn_admt']);
        $ikmc = mysqli_real_escape_string($conn, $_POST['ikmc']);
        $mncu_hrs = ($ikmc == 'Yes') ? $_POST['mncu_hrs'] : '';
        $fmly_care = mysqli_real_escape_string($conn, $_POST['fmly_care']);
        $dpmt_sprt = mysqli_real_escape_string($conn, $_POST['dpmt_sprt']);
        $cpac = mysqli_real_escape_string($conn, $_POST['cpac']);
        $kmc_bind = mysqli_real_escape_string($conn, $_POST['kmc_bind']);

        $mncu_upd = "UPDATE monitoring_data SET date_of_admission = '$mncudoa',new_born_admitted='$brn_admt',is_ikmc_provided='$ikmc',number_of_hours_MNCU='$mncu_hrs',family_participatory_care='$fmly_care',developmental_supportive='$dpmt_sprt',continuous_positive_airway_CPAC='$cpac',kmc_binders='$kmc_bind',growth_status=0,growth_status_fenton=0,growth_status_intergrowth=0 WHERE type_of_monitoring = 'Mother and Newborn at MNCU' AND registration_id = '$reg_id' AND status = '1'";

        $mncu_res = mysqli_query($conn, $mncu_upd);

        if ($mncu_res) {
            echo "<script>
                    alert('Record Updated Sucessfully!');
                    window.location.href = 'child_profile.php?c_id=$reg_id';
                  </script>";
        } else {
            echo "<script>
                    alert('Something went wrong!');
                    window.location.href = 'child_profile.php?c_id=$reg_id';
                  </script>";
        }
    }
    // Add MNCU
    if (isset($_POST['ins_mncu1'])) {
        $reg_id = $_POST['addchildidm'];
        $adduser_id = $_POST['addmncuuser_id'];

        $mncudoa = mysqli_real_escape_string($conn, $_POST['addmncudoa']);
        $brn_admt = mysqli_real_escape_string($conn, $_POST['addbrn_admt']);
        $ikmc = mysqli_real_escape_string($conn, $_POST['addikmc']);
        $mncu_hrs = ($ikmc == 'Yes') ? $_POST['addmncu_hrs'] : '';
        $fmly_care = mysqli_real_escape_string($conn, $_POST['addfmly_care']);
        $dpmt_sprt = mysqli_real_escape_string($conn, $_POST['adddpmt_sprt']);
        $cpac = mysqli_real_escape_string($conn, $_POST['addcpac']);
        $kmc_bind = mysqli_real_escape_string($conn, $_POST['addkmc_bind']);

        $mncu_ins = "INSERT INTO monitoring_data (type_of_monitoring, user_id, registration_id, date_of_admission, new_born_admitted, is_ikmc_provided, number_of_hours_MNCU, family_participatory_care, developmental_supportive, continuous_positive_airway_CPAC, kmc_binders) VALUES ('Mother and Newborn at MNCU', '$adduser_id', '$reg_id', '$mncudoa', '$brn_admt', '$ikmc', '$mncu_hrs', '$fmly_care', '$dpmt_sprt', '$cpac', '$kmc_bind')";
        $ins_reg = "UPDATE registration_form SET monitoring_status = 'Mother and Newborn at MNCU' WHERE id = $reg_id";

        $mncu_res = mysqli_query($conn, $mncu_ins);

        if ($mncu_res) {
            mysqli_query($conn, $ins_reg);
            echo "<script>
                    alert('Record Inserted Sucessfully!');
                    window.location.href = 'child_profile.php?c_id=$reg_id';
                  </script>
                 ";
        } else {
            echo "<script>
                    alert('Something went wrong!');
                    window.location.href = 'child_profile.php?c_id=$reg_id';
                  </script>
                 ";
        }
    }
    ?>

 <!doctype html>
 <html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

 <head>
     <meta charset="utf-8" />
     <title>Profile | Velzon - Admin & Dashboard Template</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
     <meta content="Themesbrand" name="author" />

     <link rel="shortcut icon" href="assets/images/favicon.ico">
     <link rel="stylesheet" href="assets/libs/swiper/swiper-bundle.min.css">
     <script src="assets/js/layout.js"></script>
     <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
     <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

 </head>

 <body>
     <div id="layout-wrapper">
         <div class="main-content px-3">
             <div class="page-content">
                 <div class="container-fluid">
                     <div class="profile-foreground position-relative mx-n4 mt-n4">
                         <div class="profile-wid-bg">
                             <img src="assets/images/profile-bg.jpg" alt="" class="profile-wid-img" />
                         </div>
                     </div>
                     <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
                         <div class="row g-4">
                             <div class="col-auto">
                                 <div class="avatar-lg">
                                     <img src="assets/newborn.png" alt="user-img" class="img-thumbnail rounded-circle" />
                                 </div>
                             </div><?= $child_id ?>
                             <?php $sqlfacilitator = mysqli_query($conn, "select boby_name_optional,boby_of_mothers_name,unique_id_of_body,monitor_name,monitor_institution,boby_name_optional,baby_date_of_birth,birth_weight_kg,sex,sncu_id,delivery_type from registration_form where id = '$child_id'");
                                $datafacilitator = mysqli_fetch_object($sqlfacilitator)
                                ?>
                             <!-- Details Data -->
                             <div class="col d-flex gap-5">
                                 <div class="p-2">
                                     <h3 class="text-white mb-1"><?= $datafacilitator->unique_id_of_body ?></h3>
                                     <p class="text-white">Baby Name : <span><?= $datafacilitator->boby_name_optional ?></span></p>
                                     <p class="text-white">Monitor Name : <span><?= $datafacilitator->monitor_name ?></span></p>
                                     <p class="text-white">Mother Name : <span><?= $datafacilitator->boby_of_mothers_name ?></span></p>
                                 </div>
                                 <div class="p-2 gap-5">
                                     <br><br>
                                     <p class="text-white">SNCU Name : <span>&nbsp;<?= ($datafacilitator->sncu_id == 1) ? 'SNCU Gaya' : 'SNCU Purnea' ?></span></p>
                                     <p class="text-white">Monitor Institution : <span><?= $datafacilitator->monitor_institution ?></span></p>
                                     <p class="text-white">Delivery Type : <span><?= $datafacilitator->delivery_type ?></span></p>
                                 </div>
                                 <div class="p-2 gap-5">
                                     <br><br>
                                     <p class="text-white">Birth Waight : <span><?= $datafacilitator->birth_weight_kg ?></span></p>
                                     <p class="text-white">Baby DOB : <span>&nbsp;<?= $datafacilitator->baby_date_of_birth ?></span></p>
                                     <p class="text-white">Gender : <span><?= $datafacilitator->sex ?></span></p>
                                 </div>
                             </div>

                             <div class="col-12 col-lg-auto order-last order-lg-0">
                                 <!-- <div class="flex-shrink-0">
                                    <a href="pages-profile-settings.html" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                                </div> -->
                             </div>

                         </div>

                     </div>

                     <div class="row">
                         <div class="col-lg-12">
                             <div>
                                 <div class="d-flex profile-wrapper">
                                     <!-- <h2 class="nav-item text-white">Child_Name Details</h2> -->
                                 </div>
                                 <div class="tab-content pt-4 text-muted">
                                     <!-- Monitoring Page -->
                                     <div class="tab-pane d-flex pe-3" id="activities" role="tabpanel">
                                         <div class="card col-4 ms-0">
                                             <?php
                                                $admission = mysqli_query($conn, "SELECT m.date_of_admission, m.admission_weight, m.admission_length, m.admission_head_circumference, m.type_of_feed,m.other_feed, m.mode_of_feeding, m.growth_chart_used FROM monitoring_data AS m JOIN registration_form AS r ON m.registration_id=r.id where r.id = $child_id and m.type_of_monitoring = 'Date of Admission' AND m.status = '1'");
                                                $admission_data = mysqli_fetch_object($admission)
                                                ?>
                                             <div class="card-body">
                                                 <div style="display: flex; justify-content: space-between; align-items: center;">
                                                     <h5 class="card-title mb-3">Admission</h5>
                                                     <?php
                                                        if (!empty($admission_data->date_of_admission)) { ?>
                                                         <button class="btn btn-primary btn-sm  mb-3" data-bs-toggle="modal" data-bs-target="#followup" onclick="addmission(<?= $child_id ?>)">
                                                             <i class="bi bi-pencil"></i> Edit
                                                         </button>
                                                     <?php } else { ?>
                                                         <button class="btn btn-primary btn-sm  mb-3" data-bs-toggle="modal" data-bs-target="#followup" onclick="new_addmission(<?= $child_id ?>)">
                                                             <i class="bi bi-plus"></i> Add
                                                         </button>
                                                     <?php } ?>
                                                 </div>
                                                 <table style="line-height: 2.2;">
                                                     <tr>
                                                         <td><b>Date of Admission</b></td>
                                                         <td><b>:</b></td>
                                                         <td style="text-indent: 20px;"><?= $admission_data->date_of_admission ?></td>
                                                     </tr>
                                                     <tr>
                                                         <td><b>Admission Weight</b></td>
                                                         <td><b>:</b></td>
                                                         <td style="text-indent: 20px;"><?= $admission_data->admission_weight ?></td>
                                                     </tr>
                                                     <tr>
                                                         <td><b>Admission Length</b></td>
                                                         <td><b>:</b></td>
                                                         <td style="text-indent: 20px;"><?= $admission_data->admission_length ?></td>
                                                     </tr>
                                                     <tr>
                                                         <td><b>Head Circumference</b></td>
                                                         <td><b>:</b></td>
                                                         <td style="text-indent: 20px;"><?= $admission_data->admission_head_circumference ?></td>
                                                     </tr>
                                                     <tr>
                                                         <td><b>Type of Feed</b></td>
                                                         <td><b>:</b></td>
                                                         <td style="text-indent: 20px;"><?= $admission_data->type_of_feed ?></td>
                                                     </tr>
                                                     <tr>
                                                         <td><b>Other Feed</b></td>
                                                         <td><b>:</b></td>
                                                         <td style="text-indent: 20px;"><?= $admission_data->other_feed ?></td>
                                                     </tr>
                                                     <tr>
                                                         <td><b>Mode of Feeding</b></td>
                                                         <td><b>:</b></td>
                                                         <td style="text-indent: 20px;"><?= $admission_data->mode_of_feeding ?></td>
                                                     </tr>
                                                     <tr>
                                                         <td><b>Growth Chart Used</b></td>
                                                         <td><b>:</b></td>
                                                         <td style="text-indent: 20px;"><?= $admission_data->growth_chart_used ?></td>
                                                     </tr>
                                                 </table>
                                             </div>
                                         </div>
                                         <div class="card col-4 ms-2">
                                             <?php
                                                $discharge = mysqli_query($conn, "SELECT m.date_of_admission, m.admission_weight, m.admission_length,m.admission_head_circumference, m.type_of_feed,m.other_feed, m.mode_of_feeding, m.growth_chart_used,m.progress_of_child FROM monitoring_data AS m JOIN registration_form AS r ON m.registration_id=r.id where r.id = $child_id and m.type_of_monitoring = 'Discharge Day' AND m.status = '1'");
                                                $discharge_data = mysqli_fetch_object($discharge)
                                                ?>
                                             <div class="card-body">
                                                 <div style="display: flex; justify-content: space-between; align-items: center;">
                                                     <h5 class="card-title mb-3">Discharge</h5>
                                                     <?php
                                                        if (!empty($discharge_data->date_of_admission)) { ?>
                                                         <button class="btn btn-primary btn-sm  mb-3" data-bs-toggle="modal" data-bs-target="#followup" onclick="discharge(<?= $child_id ?>)">
                                                             <i class="bi bi-pencil"></i> Edit
                                                         </button>
                                                     <?php } else { ?>
                                                         <button class="btn btn-primary btn-sm  mb-3" data-bs-toggle="modal" data-bs-target="#followup" onclick="new_discharge(<?= $child_id ?>)">
                                                             <i class="bi bi-plus"></i> Add
                                                         </button>
                                                     <?php } ?>
                                                 </div>
                                                 <?php
                                                    if ($discharge_data->progress_of_child == 'Death') { ?>
                                                     <p><span><b>Progress of Child :</b></span><span>&nbsp;&nbsp;<?= $discharge_data->progress_of_child ?></span></p>
                                                     <p><span><b>Date of Death :</b></span><span>&nbsp;&nbsp;<?= $discharge_data->date_of_admission ?></span></p>
                                                 <?php } else { ?>
                                                     <table style="line-height: 2.2;">
                                                         <tr>
                                                             <td><b>Progress of Child</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->progress_of_child ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Date of Discharge</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->date_of_admission ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Discharge Weight</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->admission_weight ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Discharge Length</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->admission_length ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Head Circumference</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->admission_head_circumference ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Type of Feed</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->type_of_feed ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Other Feed</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->other_feed ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Mode of Feeding</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->mode_of_feeding ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Growth Chart Used</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $discharge_data->growth_chart_used ?></td>
                                                         </tr>
                                                     </table>
                                                 <?php } ?>
                                             </div>
                                         </div>
                                         <div class="card col-4 ms-2">
                                             <!-- Monitoring Page -->
                                             <div class="tab-pane d-flex gap-2" id="activities" role="tabpanel">
                                                 <div class="card-body">
                                                     <?php
                                                        $mncu = mysqli_query($conn, "SELECT m.date_of_admission,m.new_born_admitted, m.is_ikmc_provided, m.number_of_hours_MNCU,m.family_participatory_care, m.developmental_supportive, m.continuous_positive_airway_CPAC, m.kmc_binders FROM monitoring_data AS m JOIN registration_form AS r ON m.registration_id=r.id where r.id = $child_id and m.type_of_monitoring = 'Mother and Newborn at MNCU' AND m.status = '1'");
                                                        $mncu_data = mysqli_fetch_object($mncu)
                                                        ?>
                                                     <div style="display: flex; justify-content: space-between; align-items: center;">
                                                         <h5 class="card-title mb-3">Mother and Newborn at MNCU</h5>
                                                         <?php
                                                            if (!empty($mncu_data->date_of_admission)) { ?>
                                                             <button class="btn btn-primary btn-sm  mb-3" data-bs-toggle="modal" data-bs-target="#followup" onclick="edit_mncu(<?= $child_id ?>)">
                                                                 <i class="bi bi-pencil"></i> Edit
                                                             </button>
                                                         <?php } else { ?>
                                                             <button class="btn btn-primary btn-sm  mb-3" data-bs-toggle="modal" data-bs-target="#followup" onclick="add_mncu(<?= $child_id ?>)">
                                                                 <i class="bi bi-plus"></i> Add
                                                             </button>
                                                         <?php } ?>
                                                     </div>
                                                     <table style="line-height: 2.2;">
                                                         <tr>
                                                             <td><b>New Born Admitted</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $mncu_data->new_born_admitted ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Is IKMC Provided</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $mncu_data->is_ikmc_provided ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Hours in MNCU</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $mncu_data->number_of_hours_MNCU ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Family Participatory Care</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $mncu_data->family_participatory_care ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Developmental Supportive Care</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $mncu_data->developmental_supportive ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>Positive Airway CPAC</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $mncu_data->continuous_positive_airway_CPAC ?></td>
                                                         </tr>
                                                         <tr>
                                                             <td><b>KMC Binders</b></td>
                                                             <td><b>:</b></td>
                                                             <td style="text-indent: 20px;"><?= $mncu_data->kmc_binders ?></td>
                                                         </tr>
                                                     </table>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="card col-12 ms-2">
                             <div class="card-body">
                                 <div class="d-flex justify-content-between align-items-center mb-3">
                                     <h5 class="card-title mb-3">Post Discharge Follow-Up</h5>
                                     <?php
                                        $chk_qry = "SELECT type_of_monitoring,progress_of_child FROM monitoring_data WHERE registration_id = '$child_id' AND type_of_monitoring = 'Discharge Day' AND status = '1'";
                                        $chk_res = mysqli_query($conn, $chk_qry);
                                        if (mysqli_num_rows($chk_res) > 0) {
                                            $chk_data = mysqli_fetch_assoc($chk_res);
                                            if ($chk_data['progress_of_child'] != 'Death') { ?>
                                             <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#followup" onclick="add_follow(<?= $child_id ?>)">
                                                 Add New Follow-Up
                                             </button>
                                     <?php }
                                        }

                                        ?>

                                 </div>
                                 <div class='table-responsive'>
                                     <table class="table table-nowrap ">
                                         <thead>
                                             <tr>
                                                 <th scope="col">Sr.No</th>
                                                 <th scope="col">Follow-Up Time</th>
                                                 <th scope="col">Schedule Date</th>
                                                 <th scope="col">Date of Visit</th>
                                                 <th scope="col">Immunization Status</th>
                                                 <th scope="col">Baby Weight</th>
                                                 <th scope="col">Baby Length</th>
                                                 <th scope="col">Status</th>
                                                 <th scope="col">Action</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             <?php
                                                $discharge = mysqli_query($conn, "SELECT type_of_time FROM follow_up where registration_id = $child_id");
                                                $fallow_up = [];
                                                while ($row = $discharge->fetch_assoc()) {
                                                    $fallow_up[] = $row['type_of_time'];
                                                }
                                                ?>
                                             <tr style="background-color: <?= in_array('8 Days', $fallow_up) ? 'lightgreen' : 'lightblue'; ?>">
                                                 <td>1</td>
                                                 <td>8 days</td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'schedule_follow_up_date', 'registration_id', $child_id, '8 Days') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'date_of_visit', 'registration_id', $child_id, '8 Days') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'immunization_status', 'registration_id', $child_id, '8 Days') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_weight', 'registration_id', $child_id, '8 Days') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_length', 'registration_id', $child_id, '8 Days') ?></td>
                                                 <td><?= in_array('8 Days', $fallow_up) ? 'Complete' : 'Pending'; ?></td>
                                                 <td>
                                                     <?php if (in_array('8 Days', $fallow_up)): ?>
                                                         <button type="button" class="btn btn-sm btn-dark remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="edit_follow(<?= $child_id ?>,'8 Days')">
                                                             <i class="bi bi-pencil"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-sm btn-info remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="follow_up1(<?= $child_id ?>,'8 Days')">
                                                             <i class="bi bi-eye"></i>
                                                         </button>
                                                     <?php endif; ?>
                                                 </td>
                                             </tr>
                                             <tr style="background-color: <?= in_array('1 Month', $fallow_up) ? 'lightgreen' : 'lightblue'; ?>">
                                                 <td>2</td>
                                                 <td>1 month</td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'schedule_follow_up_date', 'registration_id', $child_id, '1 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'date_of_visit', 'registration_id', $child_id, '1 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'immunization_status', 'registration_id', $child_id, '1 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_weight', 'registration_id', $child_id, '1 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_length', 'registration_id', $child_id, '1 Month') ?></td>
                                                 <td><?= in_array('1 Month', $fallow_up) ? 'Complete' : 'Pending'; ?></td>
                                                 <td>
                                                     <?php if (in_array('1 Month', $fallow_up)): ?>
                                                         <button type="button" class="btn btn-sm btn-dark remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="edit_follow(<?= $child_id ?>,'1 Month')">
                                                             <i class="bi bi-pencil"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-sm btn-info remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="follow_up1(<?= $child_id ?>,'1 Month')">
                                                             <i class="bi bi-eye"></i>
                                                         </button>
                                                     <?php endif; ?>
                                                 </td>
                                             </tr>
                                             <tr style="background-color: <?= in_array('3 Month', $fallow_up) ? 'lightgreen' : 'lightblue'; ?>">
                                                 <td>3</td>
                                                 <td>3 month</td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'schedule_follow_up_date', 'registration_id', $child_id, '3 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'date_of_visit', 'registration_id', $child_id, '3 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'immunization_status', 'registration_id', $child_id, '3 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_weight', 'registration_id', $child_id, '3 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_length', 'registration_id', $child_id, '3 Month') ?></td>
                                                 <td><?= in_array('3 Month', $fallow_up) ? 'Complete' : 'Pending'; ?></td>
                                                 <td>
                                                     <?php if (in_array('3 Month', $fallow_up)): ?>
                                                         <button type="button" class="btn btn-sm btn-dark remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="edit_follow(<?= $child_id ?>,'3 Month')">
                                                             <i class="bi bi-pencil"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-sm btn-info remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="follow_up1(<?= $child_id ?>,'3 Month')">
                                                             <i class="bi bi-eye"></i>
                                                         </button>
                                                     <?php endif; ?>
                                                 </td>
                                             </tr>
                                             <tr style="background-color: <?= in_array('6 Month', $fallow_up) ? 'lightgreen' : 'lightblue'; ?>">
                                                 <td>4</td>
                                                 <td>6 month</td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'schedule_follow_up_date', 'registration_id', $child_id, '6 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'date_of_visit', 'registration_id', $child_id, '6 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'immunization_status', 'registration_id', $child_id, '6 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_weight', 'registration_id', $child_id, '6 Month') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_length', 'registration_id', $child_id, '6 Month') ?></td>
                                                 <td><?= in_array('6 Month', $fallow_up) ? 'Complete' : 'Pending'; ?></td>
                                                 <td>
                                                     <?php if (in_array('6 Month', $fallow_up)): ?>
                                                         <button type="button" class="btn btn-sm btn-dark remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="edit_follow(<?= $child_id ?>,'6 Month')">
                                                             <i class="bi bi-pencil"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-sm btn-info remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="follow_up1(<?= $child_id ?>,'6 Month')">
                                                             <i class="bi bi-eye"></i>
                                                         </button>
                                                     <?php endif; ?>
                                                 </td>
                                             </tr>
                                             <tr style="background-color: <?= in_array('1 Year', $fallow_up) ? 'lightgreen' : 'lightblue'; ?>">
                                                 <td>5</td>
                                                 <td>1 year</td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'schedule_follow_up_date', 'registration_id', $child_id, '1 Year') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'date_of_visit', 'registration_id', $child_id, '1 Year') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'immunization_status', 'registration_id', $child_id, '1 Year') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_weight', 'registration_id', $child_id, '1 Year') ?></td>
                                                 <td><?= getdatatwocondition($conn, 'follow_up', 'baby_length', 'registration_id', $child_id, '1 Year') ?></td>
                                                 <td><?= in_array('1 Year', $fallow_up) ? 'Complete' : 'Pending'; ?></td>
                                                 <td>
                                                     <?php if (in_array('1 Year', $fallow_up)): ?>
                                                         <button type="button" class="btn btn-sm btn-dark remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="edit_follow(<?= $child_id ?>,'1 Year')">
                                                             <i class="bi bi-pencil"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-sm btn-info remove-item-btn" data-bs-toggle="modal" data-bs-target="#followup" onclick="follow_up1(<?= $child_id ?>,'1 Year')">
                                                             <i class="bi bi-eye"></i>
                                                         </button>
                                                     <?php endif; ?>
                                                 </td>
                                             </tr>
                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             <footer class="footer">
                 <div class="container-fluid">
                     <div class="row">
                         <div class="col-sm-6">
                             <script>
                                 document.write(new Date().getFullYear())
                             </script> © Velzon.
                         </div>
                         <div class="col-sm-6">
                             <div class="text-sm-end d-none d-sm-block">
                                 Design & Develop by Themesbrand
                             </div>
                         </div>
                     </div>
                 </div>
             </footer>
         </div>
     </div>

     <!--start back-to-top-->
     <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
         <i class="ri-arrow-up-line"></i>
     </button>
     <!--end back-to-top-->

     <!-- follow Up Modal Start -->
     <div class="modal fade" id="followup" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title" id="viewModalLabel"></h4>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <form method="post" enctype="multipart/form-data">
                     <div class="modal-body" id="modalContent">

                     </div>
                     <div class="d-flex justify-content-end gap-2 mb-2 me-3">
                         <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                         <button type="submit" class="btn btn-success d-none" id="upd_btn" name="adm_dis_upd" onclick="return validateaddForm();">Update</button>
                         <button type="submit" class="btn btn-success d-none" id="ins_btn" name="adm_dis_ins" onclick="return validateaddForm();">Add</button>
                         <button type="submit" class="btn btn-success d-none" id="upd_fol" name="upd_follow_up" onclick="return validatefallowForm();">upd_fol_up</button>
                         <button type="submit" class="btn btn-success d-none" id="ins_fol" name="ins_follow_up" onclick="return validatefallowForm();">add_fol_up</button>
                         <button type="submit" class="btn btn-success d-none" id="upd_mncu1" name="upd_mncu1" onclick="return validatemncuForm();">upd_mncu</button>
                         <button type="submit" class="btn btn-success d-none" id="ins_mncu1" name="ins_mncu1" onclick="return validatemncuForm();">add_mncu</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
     <!-- Follow Up modal end -->


     <!-- JAVASCRIPT -->
     <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
     <script src="assets/libs/simplebar/simplebar.min.js"></script>
     <script src="assets/libs/node-waves/waves.min.js"></script>
     <script src="assets/libs/feather-icons/feather.min.js"></script>
     <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
     <script src="assets/js/plugins.js"></script>

     <!-- swiper js -->
     <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
     <!-- profile init js -->
     <script src="assets/js/pages/profile.init.js"></script>
     <!-- App js -->
     <script src="assets/js/app.js"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

     <!-- View Script -->
     <script>
         ///////////////////////////////////////////// AJAX SCRIPT ///////////////////////////////////////////
         // Add Monitoring Addmission
         function new_addmission(new_reg) {
             $('#viewModalLabel').text('Add Addmission Record');
             $('#ins_btn').removeClass('d-none');
             $('#upd_btn').addClass('d-none');
             $('#upd_fol').addClass('d-none');
             $('#ins_fol').addClass('d-none');
             $('#upd_mncu1').addClass('d-none');
             $('#ins_mncu1').addClass('d-none');
             document.querySelector('#followup .modal-dialog').classList.remove('modal-xl');
             document.querySelector("#followup .modal-dialog").classList.add("modal-lg");
             $.ajax({
                 url: 'ajax/dishcharge_ajax.php',
                 type: 'POST',
                 data: {
                     new_adm_reg: new_reg,
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }
         // Add Monitoring Discharge
         function new_discharge(new_dis) {
             $('#viewModalLabel').text('Add Discharge Record');
             $('#ins_btn').removeClass('d-none');
             $('#upd_btn').addClass('d-none');
             $('#upd_fol').addClass('d-none');
             $('#ins_fol').addClass('d-none');
             $('#upd_mncu1').addClass('d-none');
             $('#ins_mncu1').addClass('d-none');
             document.querySelector('#followup .modal-dialog').classList.remove('modal-xl');
             document.querySelector("#followup .modal-dialog").classList.add("modal-lg");
             $.ajax({
                 url: 'ajax/dishcharge_ajax.php',
                 type: 'POST',
                 data: {
                     new_dis_reg: new_dis,
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }
         // Edit Monitoring Addmission
         function addmission(reg) {
             $('#viewModalLabel').text('Update Addmission Record');
             $('#upd_btn').removeClass('d-none');
             $('#ins_btn').addClass('d-none');
             $('#upd_fol').addClass('d-none');
             $('#ins_fol').addClass('d-none');
             $('#upd_mncu1').addClass('d-none');
             $('#ins_mncu1').addClass('d-none');
             document.querySelector('#followup .modal-dialog').classList.remove('modal-xl');
             document.querySelector("#followup .modal-dialog").classList.add("modal-lg");
             $.ajax({
                 url: 'ajax/dishcharge_ajax.php',
                 type: 'POST',
                 data: {
                     adm_regg_id: reg,
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }
         // Edit Monitoring Discharge
         function discharge(dis) {
             $('#viewModalLabel').text('Update Discharge Record');
             $('#upd_btn').removeClass('d-none');
             $('#ins_btn').addClass('d-none');
             $('#upd_fol').addClass('d-none');
             $('#ins_fol').addClass('d-none');
             $('#upd_mncu1').addClass('d-none');
             $('#ins_mncu1').addClass('d-none');
             document.querySelector('#followup .modal-dialog').classList.remove('modal-xl');
             document.querySelector("#followup .modal-dialog").classList.add("modal-lg");
             $.ajax({
                 url: 'ajax/dishcharge_ajax.php',
                 type: 'POST',
                 data: {
                     dis_regg_id: dis,
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }
         // View Post Discharge Follow-Up
         function follow_up1(asc, field) {
             $('#viewModalLabel').text('Post Discharge Follow-Up');
             document.querySelector('#followup .modal-dialog').classList.remove('modal-xl');
             document.querySelector('#followup .modal-dialog').classList.add('modal-lg');
             $.ajax({
                 url: 'ajax/get_followup_data.php',
                 type: 'POST',
                 data: {
                     id: asc,
                     field: field
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }
         // Edit Follow-Up
         function edit_follow(fol_id, type) {
             $('#viewModalLabel').text('Update Follow-Up');
             $('#upd_fol').removeClass('d-none');
             $('#upd_btn').addClass('d-none');
             $('#ins_btn').addClass('d-none');
             $('#ins_fol').addClass('d-none');
             $('#upd_mncu').addClass('d-none');
             $('#ins_mncu1').addClass('d-none');
             document.querySelector("#followup .modal-dialog").classList.remove("modal-lg");
             document.querySelector('#followup .modal-dialog').classList.add('modal-xl');
             $.ajax({
                 url: 'ajax/get_followup_data.php',
                 type: 'POST',
                 data: {
                     fol_id: fol_id,
                     type: type
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }
         // Add Follow-Up
         function add_follow(fol_iddd) {
             $('#viewModalLabel').text('Add Follow-Up');
             $('#ins_fol').removeClass('d-none');
             $('#upd_btn').addClass('d-none');
             $('#ins_btn').addClass('d-none');
             $('#upd_fol').addClass('d-none');
             $('#upd_mncu1').addClass('d-none');
             $('#ins_mncu1').addClass('d-none');
             document.querySelector("#followup .modal-dialog").classList.remove("modal-lg");
             document.querySelector('#followup .modal-dialog').classList.add('modal-xl');
             $.ajax({
                 url: 'ajax/get_followup_data.php',
                 type: 'POST',
                 data: {
                     addfol_id: fol_iddd,
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }
         // Edit Newborn at MNCU
         function edit_mncu(mncu_child_id) {
             $('#viewModalLabel').text('Update Mother and Newborn at MNCU');
             $('#upd_mncu1').removeClass('d-none');
             $('#ins_fol').addClass('d-none');
             $('#upd_btn').addClass('d-none');
             $('#ins_btn').addClass('d-none');
             $('#upd_fol').addClass('d-none');
             $('#ins_mncu1').addClass('d-none');
             document.querySelector('#followup .modal-dialog').classList.remove('modal-xl');
             document.querySelector('#followup .modal-dialog').classList.add('modal-lg');
             $.ajax({
                 url: 'ajax/get_followup_data.php',
                 type: 'POST',
                 data: {
                     mncu_child_id: mncu_child_id
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }
         // Add Newborn at MNCU
         function add_mncu(madd_child_id) {
             $('#viewModalLabel').text('Add Mother and Newborn at MNCU');
             $('#ins_mncu1').removeClass('d-none');
             $('#upd_mncu').addClass('d-none');
             $('#ins_fol').addClass('d-none');
             $('#upd_btn').addClass('d-none');
             $('#ins_btn').addClass('d-none');
             $('#upd_fol').addClass('d-none');
             document.querySelector('#followup .modal-dialog').classList.remove('modal-xl');
             document.querySelector('#followup .modal-dialog').classList.add('modal-xl');
             $.ajax({
                 url: 'ajax/get_followup_data.php',
                 type: 'POST',
                 data: {
                     madd_child_id: madd_child_id
                 },
                 success: function(response) {
                     $('#modalContent').html(response);
                 },
                 error: function(xhr, status, error) {
                     $('#modalContent').html('Error loading data.');
                 }
             });
         }

         /////////////////////////////////////////// FORM SCRIPT //////////////////////////////////////////
         //////////////// Add Admission ///////////////////
         function oth_field(status) {
             var otherBox = document.getElementById('other_field');
             if (status.checked) {
                 otherFeedInput.style.display = "block";
                 console.log('checked');
             } else {
                 otherFeedInput.style.display = "none";
                 console.log('unchecked');
             }
         };
         ////////////// Add Discharge //////////////////
         function oth_fielddd(status) {
             if (status.checked) {
                 document.getElementById('otherfl').classList.remove('d-none');
             } else {
                 document.getElementById('otherfl').classList.add('d-none');
             }
         }

         function toggleFields(value) {
             let ids = ['hide1', 'hide2', 'hide3', 'hide4', 'hide5', 'hide6'];
             if (value === 'Discharge') {
                 ids.forEach(function(id) {
                     document.getElementById(id).classList.remove('d-none');
                 });
                 document.getElementById('label1').innerText = 'Date of Discharge';
                 document.getElementById('label2').innerText = 'Discharge Weight(kg)';
                 document.getElementById('label3').innerText = 'Discharge Length(cm)';
                 document.getElementById('label4').innerText = 'Discharge Head Circumference(cm)';
             } else if (value === 'LAMA') {
                 ids.forEach(function(id) {
                     document.getElementById(id).classList.remove('d-none');
                 });
                 document.getElementById('label1').innerText = 'Last Date at SNCU';
                 document.getElementById('label2').innerText = 'Last Measured Weight(kg)';
                 document.getElementById('label3').innerText = 'Last Measured Length(cm)';
                 document.getElementById('label4').innerText = 'Last Measured Head Circumference(cm)';
             } else if (value === 'Referred') {
                 ids.forEach(function(id) {
                     document.getElementById(id).classList.remove('d-none');
                 });
                 document.getElementById('label1').innerText = 'Date of Referred';
                 document.getElementById('label2').innerText = 'Referred Measured Weight(kg)';
                 document.getElementById('label3').innerText = 'Referred Measured Length(cm)';
                 document.getElementById('label4').innerText = 'Referred Measured Head Circumference(cm)';
             } else {
                 document.getElementById('label1').innerText = 'Date of Death';
                 ids.forEach(function(id) {
                     document.getElementById(id).classList.add('d-none');
                 });
             }
         }
         ///////////// update admission /////////////
         function other_fld(othval) {
             var othcheck = document.getElementById("otherfl");
             if (othval.checked) {
                 othcheck.classList.remove('d-none');
             } else {
                 othcheck.classList.add('d-none');
             }
         }
         //////////// update discharge //////////////
         function other_fld(othval) {
             var othcheck = document.getElementById("otherfl");
             if (othval.checked) {
                 othcheck.classList.remove('d-none');
             } else {
                 othcheck.classList.add('d-none');
             }
         }

         function toggleFields(value) {
             let ids = ['hide1', 'hide2', 'hide3', 'hide4', 'hide5', 'hide6'];
             if (value === 'Discharge') {
                 ids.forEach(function(id) {
                     document.getElementById(id).classList.remove('d-none');
                 });
                 document.getElementById('label1').innerText = 'Date of Discharge';
                 document.getElementById('label2').innerText = 'Discharge Weight(kg)';
                 document.getElementById('label3').innerText = 'Discharge Length(cm)';
                 document.getElementById('label4').innerText = 'Head Circumference(cm)';
             } else if (value === 'LAMA') {
                 ids.forEach(function(id) {
                     document.getElementById(id).classList.remove('d-none');
                 });
                 document.getElementById('label1').innerText = 'Last Date at SNCU';
                 document.getElementById('label2').innerText = 'Last Measured Weight(kg)';
                 document.getElementById('label3').innerText = 'Last Measured Length(cm)';
                 document.getElementById('label4').innerText = 'Last Measured Head Circumference(cm)';
             } else if (value === 'Referred') {
                 ids.forEach(function(id) {
                     document.getElementById(id).classList.remove('d-none');
                 });
                 document.getElementById('label1').innerText = 'Date of Referred';
                 document.getElementById('label2').innerText = 'Referred Measured Weight(kg)';
                 document.getElementById('label3').innerText = 'Referred Measured Length(cm)';
                 document.getElementById('label4').innerText = 'Referred Measured Head Circumference(cm)';
             } else {
                 document.getElementById('label1').innerText = 'Date of Death';
                 ids.forEach(function(id) {
                     document.getElementById(id).classList.add('d-none');
                 });
             }
         }
         //////////// Add fallow-up /////////////////
         function dis_sncuppp(dis_sn) {
             var asha_sncu = document.getElementById('dissncu');
             if (dis_sn == 'Yes') {
                 asha_sncu.classList.remove('d-none');
             } else {
                 asha_sncu.classList.add('d-none');
             }
         }

         function oth_feed(othh) {
             var feed_oth = document.getElementById('feed_oth');
             if (othh.checked) {
                 feed_oth.classList.remove('d-none');
             } else {
                 feed_oth.classList.add('d-none');
             }
         }

         function hel_field(status) {
             var iden_hlt = document.getElementById('iden_hlt');
             if (status === 'Has health issue') {
                 iden_hlt.classList.remove('d-none');
             }
             if (status === 'No health issue') {
                 iden_hlt.classList.add('d-none');
             }
         }

         function aww_visit_fun(aww_val) {
             var aww_visit1 = document.getElementById('aww_visit1');
             if (aww_val === 'Yes') {
                 aww_visit1.classList.remove('d-none');
             } else {
                 aww_visit1.classList.add('d-none');
             }
         }

         function uncheck_field2(checkk) {
             if (checkk.checked) {
                 Object.assign(document.getElementById('check1'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check2'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check3'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check4'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check5'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check6'), {
                     checked: false,
                     disabled: true
                 });
             } else {
                 document.getElementById('check1').disabled = false;
                 document.getElementById('check2').disabled = false;
                 document.getElementById('check3').disabled = false;
                 document.getElementById('check4').disabled = false;
                 document.getElementById('check5').disabled = false;
                 document.getElementById('check6').disabled = false;
             }
         }

         function uncheck_field(check_val) {
             if (check_val.checked) {
                 Object.assign(document.getElementById('cunl1'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('cunl2'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('cunl3'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('cunl4'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('cunl5'), {
                     checked: false,
                     disabled: true
                 });
             } else {
                 document.getElementById('cunl1').disabled = false;
                 document.getElementById('cunl2').disabled = false;
                 document.getElementById('cunl3').disabled = false;
                 document.getElementById('cunl4').disabled = false;
                 document.getElementById('cunl5').disabled = false;
             }
         }

         function gen_sch_date(fol_type, datee) {
             const parts = datee.split("-");
             const dd = parts[2];
             const mm = parts[1];
             const yy = parts[0];
             const formatted = `${yy}-${mm}-${dd}`;
             if (fol_type == '8 Days') {
                 // start feed code
                 document.getElementById('st_fd_hm').classList.add('d-none');
                 document.getElementById('of_fd_hm').classList.add('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 8);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else if (fol_type == '1 Month') {
                 // start feed code
                 document.getElementById('st_fd_hm').classList.add('d-none');
                 document.getElementById('of_fd_hm').classList.add('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 30);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else if (fol_type == '3 Month') {
                 // start feed code
                 document.getElementById('st_fd_hm').classList.add('d-none');
                 document.getElementById('of_fd_hm').classList.add('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 90);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else if (fol_type == '6 Month') {
                 // start feed code
                 document.getElementById('st_fd_hm').classList.remove('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 180);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else if (fol_type == '1 Year') {
                 // start feed code
                 document.getElementById('st_fd_hm').classList.remove('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 365);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else {
                 document.getElementById("sch_date").value = '';
             }
         }

         function start_feeding(st_feed) {
             let s_feed = document.getElementById('of_fd_hm');
             if (st_feed == 'Yes') {
                 s_feed.classList.remove('d-none');
             }
             if (st_feed == 'No') {
                 s_feed.classList.add('d-none');
             }
         }
         ///////////// Update Fallow-up ////////////////
         function dis_sncuppp(dis_sn) {
             var asha_sncu = document.getElementById('dissncu');
             if (dis_sn == 'Yes') {
                 asha_sncu.classList.remove('d-none');
             } else {
                 asha_sncu.classList.add('d-none');
             }
         }

         function oth_feed(othh) {
             var feed_oth = document.getElementById('feed_oth');
             if (othh.checked) {
                 feed_oth.classList.remove('d-none');
             } else {
                 feed_oth.classList.add('d-none');
             }
         }

         function hel_field(status) {
             var iden_hlt = document.getElementById('iden_hlt');
             if (status === 'Has health issue') {
                 iden_hlt.classList.remove('d-none');
             }
             if (status === 'No health issue') {
                 iden_hlt.classList.add('d-none');
             }
         }

         function aww_visit_fun(aww_val) {
             var aww_visit1 = document.getElementById('aww_visit1');
             if (aww_val === 'Yes') {
                 aww_visit1.classList.remove('d-none');
             } else {
                 aww_visit1.classList.add('d-none');
             }
         }

         function uncheck_field2(checkk) {
             if (checkk.checked) {
                 Object.assign(document.getElementById('check1'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check2'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check3'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check4'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check5'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('check6'), {
                     checked: false,
                     disabled: true
                 });
             } else {
                 document.getElementById('check1').disabled = false;
                 document.getElementById('check2').disabled = false;
                 document.getElementById('check3').disabled = false;
                 document.getElementById('check4').disabled = false;
                 document.getElementById('check5').disabled = false;
                 document.getElementById('check6').disabled = false;
             }
         }

         function uncheck_field(check_val) {
             if (check_val.checked) {
                 Object.assign(document.getElementById('cunl1'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('cunl2'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('cunl3'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('cunl4'), {
                     checked: false,
                     disabled: true
                 });
                 Object.assign(document.getElementById('cunl5'), {
                     checked: false,
                     disabled: true
                 });
             } else {
                 document.getElementById('cunl1').disabled = false;
                 document.getElementById('cunl2').disabled = false;
                 document.getElementById('cunl3').disabled = false;
                 document.getElementById('cunl4').disabled = false;
                 document.getElementById('cunl5').disabled = false;
             }
         }

         function upsch_date(up_fol_type, upsch_date) {
             const parts = upsch_date.split("-");
             const dd = parts[2];
             const mm = parts[1];
             const yy = parts[0];
             const formatted = `${yy}-${mm}-${dd}`;
             if (up_fol_type == '8 Days') {
                 // start feed code
                 document.getElementById('up_st_fd').classList.add('d-none');
                 document.getElementById('up_of_fd').classList.add('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 8);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else if (up_fol_type == '1 Month') {
                 // start feed code
                 document.getElementById('up_st_fd').classList.add('d-none');
                 document.getElementById('up_of_fd').classList.add('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 30);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else if (up_fol_type == '3 Month') {
                 // start feed code
                 document.getElementById('up_st_fd').classList.add('d-none');
                 document.getElementById('up_of_fd').classList.add('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 90);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else if (up_fol_type == '6 Month') {
                 // start feed code
                 document.getElementById('up_st_fd').classList.remove('d-none');
                 document.getElementById('up_of_fd').classList.remove('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 180);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else if (up_fol_type == '1 Year') {
                 // start feed code
                 document.getElementById('up_st_fd').classList.remove('d-none');
                 document.getElementById('up_of_fd').classList.remove('d-none');
                 // start feed code
                 const newDate = new Date(formatted.split("-").join("-"));
                 newDate.setDate(newDate.getDate() + 365);
                 const yyyy = newDate.getFullYear();
                 const mm = String(newDate.getMonth() + 1).padStart(2, '0');
                 const dd = String(newDate.getDate()).padStart(2, '0');
                 const result = `${yyyy}-${mm}-${dd}`;
                 document.getElementById("sch_date").value = result;
             } else {
                 document.getElementById("sch_date").value = '';
             }
         }
         /////////// Add MNCU ///////////////
         function ikmvhide(kmcc) {
             var ikmc_fld = document.getElementById('hrs_fld');
             if (kmcc == 'Yes') {
                 ikmc_fld.classList.remove('d-none');
             } else {
                 ikmc_fld.classList.add('d-none');
             }
         }

         /////////////////////////////////////////////// VALIDATION SCRIPT ///////////////////////////////////////////
         // validate admission and discharge form
         function validateaddForm() {
             const pocaddElem = document.getElementById('pocadd');
             const pocadd = pocaddElem ? pocaddElem.value.trim() : null;
             if (pocadd == 'Death') {
                 const doa = document.getElementById('doa').value.trim();
                 if (doa === '') {
                     alert("Date of Admission is required.");
                     $("#doa").focus();
                     return false;
                 }
                 return true;
             } else {
                 const doa = document.getElementById('doa').value.trim();
                 const adm_wt = document.getElementById('adm_wt').value.trim();
                 const adm_lt = document.getElementById('adm_lt').value.trim();
                 const hed_cir = document.getElementById('hed_cir').value.trim();
                 const md_of_feed = document.getElementById('md_of_feed').value.trim();
                 const g_c_usd = document.getElementById('g_c_usd').value.trim();
                 const checkboxes = document.querySelectorAll('input[name="type_of_feed[]"]');
                 const oth_fd = document.getElementById('oth_fd').value.trim();
                 let isChecked = false;
                 checkboxes.forEach((checkbox) => {
                     if (checkbox.checked) {
                         isChecked = true;
                     }
                 });

                 //  $("#adm_wt").focus().css('border-color', 'red');
                 if (doa === '') {
                     alert("Date of Admission is required.");
                     $("#doa").focus();
                     return false;
                 }
                 if (adm_wt === '') {
                     alert("Admission Weight is required.");
                     $("#adm_wt").focus();
                     return false;
                 }
                 if (adm_lt === '') {
                     alert("Admission Length is required.");
                     $("#adm_lt").focus();
                     return false;
                 }
                 if (hed_cir === '') {
                     alert("Head Circumfrence is required.");
                     $("#hed_cir").focus();
                     return false;
                 }
                 if (md_of_feed === '') {
                     alert("Mode of Feeding is required.");
                     $("#md_of_feed").focus();
                     return false;
                 }
                 if (g_c_usd === '') {
                     alert("Growth Chart is required.");
                     $("#g_c_usd").focus();
                     return false;
                 }

                 if (!isChecked) {
                     alert("Please select at least one Type of Feed.");
                     return false;
                 }

                 const otherCheckbox = document.getElementById('othercheckbox');
                 const otherText = document.getElementById('oth_fd');

                 if (otherCheckbox.checked && otherText.value.trim() === '') {
                     alert("Please specify 'Other' feed type.");
                     otherText.focus();
                     return false;
                 }
                 return true;
             }
         }
         // validate Newborn at MNCU Form
         function validatemncuForm() {
             const mncudoa = document.getElementById('mncudoa').value.trim();
             const brn_admt = document.getElementById('brn_admt').value.trim();
             const ikmc = document.getElementById('ikmc').value.trim();
             const mncu_hrs = document.getElementById('mncu_hrs').value.trim();
             const fmly_care = document.getElementById('fmly_care').value.trim();
             const dpmt_sprt = document.getElementById('dpmt_sprt').value.trim();
             const cpac = document.getElementById('cpac').value.trim();
             const kmc_bind = document.getElementById('kmc_bind').value.trim();

             //  $("#adm_wt").focus().css('border-color', 'red');
             if (mncudoa === '') {
                 alert("MNCU Admission date is required.");
                 $("#mncudoa").focus();
                 return false;
             }
             if (brn_admt === '') {
                 alert("New Born Admitted is required.");
                 $("#brn_admt").focus();
                 return false;
             }
             if (ikmc === '') {
                 alert("Is IKMC Provided is required.");
                 $("#ikmc").focus();
                 return false;
             }
             if (ikmc === 'Yes') {
                 if (mncu_hrs === '') {
                     alert("Hours in MNCU is required.");
                     $("#mncu_hrs").focus();
                     return false;
                 }
             }
             if (fmly_care === '') {
                 alert("Family Participatory Care is required.");
                 $("#fmly_care").focus();
                 return false;
             }
             if (dpmt_sprt === '') {
                 alert("Developmental Supportive is required.");
                 $("#dpmt_sprt").focus();
                 return false;
             }
             if (cpac === '') {
                 alert("Positive Airway CPAC is required.");
                 $("#cpac").focus();
                 return false;
             }
             if (kmc_bind === '') {
                 alert("KMC Binders is required.");
                 $("#kmc_bind").focus();
                 return false;
             }

             return true;
         }
         // validate follow-up form
         function validatefallowForm() {
             const type_of_time = document.getElementById('type_of_time').value.trim();
             const sch_date = document.getElementById('sch_date').value.trim();
             const vsi_date = document.getElementById('vsi_date').value.trim();
             const b_weight = document.getElementById('b_weight').value.trim();
             const b_length = document.getElementById('b_length').value.trim();
             const h_circum = document.getElementById('h_circum').value.trim();
             const im_status = document.getElementById('im_status').value.trim();
             const times_b_feed = document.getElementById('times_b_feed').value.trim();
             const feed_bot = document.getElementById('feed_bot').value.trim();
             const prm_feed = document.getElementById('prm_feed').value.trim();
             const hlt_exp = document.getElementById('hlt_exp').value.trim();
             const m_h_issue = document.getElementById('m_h_issue').value.trim();
             const dis_sncu = document.getElementById('dis_sncu').value.trim();
             const asa_vsi_time = document.getElementById('asa_vsi_time').value.trim();
             const aww_visit = document.getElementById('aww_visit').value.trim();
             const aww_time = document.getElementById('aww_time').value.trim();
             const start_feed = document.getElementById('start_feed').value.trim();

             if (type_of_time === '') {
                 alert("Type of Time is required.");
                 $("#type_of_time").focus();
                 return false;
             }
             if (sch_date === '') {
                 alert("Schedule Date is required.");
                 $("#sch_date").focus();
                 return false;
             }
             if (vsi_date === '') {
                 alert("Visit Date is required.");
                 $("#vsi_date").focus();
                 return false;
             }
             if (b_weight === '') {
                 alert("Baby Weight is required.");
                 $("#b_weight").focus();
                 return false;
             }
             if (b_length === '') {
                 alert("Baby Length is required.");
                 $("#b_length").focus();
                 return false;
             }
             if (h_circum === '') {
                 alert("Head Circumference is required.");
                 $("#h_circum").focus();
                 return false;
             }
             if (im_status === '') {
                 alert("Immunization Status is required.");
                 $("#im_status").focus();
                 return false;
             }
             if (times_b_feed === '') {
                 alert("Times Baby Breastfed is required.");
                 $("#times_b_feed").focus();
                 return false;
             }
             if (feed_bot === '') {
                 alert("Child Feeding Bottle is required.");
                 $("#feed_bot").focus();
                 return false;
             }
             if (prm_feed === '') {
                 alert("Promoting Breastfeeding is required.");
                 $("#prm_feed").focus();
                 return false;
             }
             if (hlt_exp === '') {
                 alert("Health Examination is required.");
                 $("#hlt_exp").focus();
                 return false;
             }
             if (hlt_exp === 'Has health issue') {
                 if (m_h_issue === '') {
                     alert("Identified Health Issue is required.");
                     $("#m_h_issue").focus();
                     return false;
                 }
             }
             if (dis_sncu === '') {
                 alert("ASHA Visit is required.");
                 $("#dis_sncu").focus();
                 return false;
             }
             if (dis_sncu === 'Yes') {
                 if (asa_vsi_time === '') {
                     alert("Times of ASHA Visited is required.");
                     $("#asa_vsi_time").focus();
                     return false;
                 }
             }

             // type of feed
             const checkboxes = document.querySelectorAll('input[name="addtype_of_feed[]"]');
             let isChecked = false;
             checkboxes.forEach((checkbox) => {
                 if (checkbox.checked) {
                     isChecked = true;
                 }
             });
             if (!isChecked) {
                 alert("Please select at least one Type of Feed.");
                 return false;
             }

             const otherCheckbox = document.getElementById('ty_feed_oth_fol');
             const otherText = document.getElementById('oth_res');
             if (otherCheckbox.checked && otherText.value.trim() === '') {
                 alert("Please specify 'Other' feed.");
                 otherText.focus();
                 return false;
             }
             // aww
             if (aww_visit === '') {
                 alert("AWW Visit on Discharge is required.");
                 $("#aww_visit").focus();
                 return false;
             }
             if (aww_visit === 'Yes') {
                 if (aww_time === '') {
                     alert("Times of AWW Visited is required.");
                     $("#aww_time").focus();
                     return false;
                 }
             }
             // start feed at home
             const folltype = document.getElementById('type_of_time');
             if (folltype.value === '6 Month' || folltype.value === '1 Year') {
                 if (start_feed === '') {
                     alert("Select Started Feeding is required.");
                     $("#start_feed").focus();
                     return false;
                 }
                 const stfeddbox = document.querySelectorAll('input[name="add_offered_food[]"]');
                 if (start_feed === 'Yes') {
                     let sfhChecked = false;
                     stfeddbox.forEach((checkbox) => {
                         if (checkbox.checked) {
                             sfhChecked = true;
                         }
                     });
                     if (!sfhChecked) {
                         alert("Please select at least offerad food.");
                         return false;
                     }
                 }
             }
             // infent
             const infantbox = document.querySelectorAll('input[name="addinfant[]"]');
             let infChecked = false;
             infantbox.forEach((checkbox) => {
                 if (checkbox.checked) {
                     infChecked = true;
                 }
             });
             if (!infChecked) {
                 alert("Please select at least one infent.");
                 return false;
             }
             // counsel
             const counnselbox = document.querySelectorAll('input[name="addcounnsel[]"]');
             let cunChecked = false;
             counnselbox.forEach((checkbox) => {
                 if (checkbox.checked) {
                     cunChecked = true;
                 }
             });
             if (!cunChecked) {
                 alert("Please select at least one counsel.");
                 return false;
             }

             return true;
         }
     </script>

 </body>

 </html>