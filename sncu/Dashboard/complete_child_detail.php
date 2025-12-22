<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>

<!-- Search Code -->
<?php
$filter = '';
$filter1 = '';
$extraParams = [];
if (isset($_REQUEST['search'])) {

  $reg_idd = $_GET['reg_idd'] ?? '';
  $contect = $_GET['contect'] ?? '';
  $reg_idff = $_GET['reg_idff'] ?? '';
  $monitor = $_GET['monitor'] ?? '';

  if (!empty($reg_idd)) {
    $filter .= " AND rf.unique_id_of_body LIKE '%" . addslashes($reg_idd) . "%'";
    $extraParams['reg_idd'] = $reg_idd;
  }
  if (!empty($contect)) {
    $filter .= " AND rf.phone_number_of_mother_father_caregivers LIKE '%" . addslashes($contect) . "%'";
    $extraParams['contect'] = $contect;
  }

  if (!empty($reg_idff)) {
    $filter1 .= "AND r.unique_id_of_body LIKE '%" . addslashes($reg_idff) . "%'";
    $extraParams['reg_idff'] = $reg_idff;
  }
  if (!empty($monitor)) {
    $filter1 .= "AND r.monitor_name LIKE '%" . addslashes($monitor) . "%'";
    $extraParams['monitor'] = $monitor;
  }
  $extraParams['search'] = $_GET['search'];
}
?>

<!-- Pagination Code -->
<?php
$limit = 10;
// Get current pages
$reg_page = isset($_GET['rreg_page']) ? (int)$_GET['rreg_page'] : 1;
$fallo_page = isset($_GET['fallo_page']) ? (int)$_GET['fallo_page'] : 1;

$regOffset = ($reg_page - 1) * $limit;
// $falloOffset = ($fallo_page - 1) * $limit;

$regQuery = "SELECT * FROM registration_form LIMIT $limit OFFSET $regOffset";
$userResult = mysqli_query($conn, $regQuery);

// $falloQuery = "SELECT * FROM follow_up LIMIT $limit OFFSET $falloOffset";
// $productResult = mysqli_query($conn, $falloQuery);

$total_reg = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(rf.id) FROM registration_form rf  JOIN monitoring_data adm ON rf.id = adm.registration_id AND adm.type_of_monitoring = 'Date of Admission' JOIN monitoring_data dis ON rf.id = dis.registration_id AND dis.type_of_monitoring = 'Discharge Day' JOIN monitoring_data nb ON rf.id = nb.registration_id AND nb.type_of_monitoring = 'Mother and Newborn at MNCU' JOIN follow_up fal ON rf.id = fal.registration_id $filter"))[0];

// $total_fallo = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM follow_up as f JOIN registration_form as r ON r.id = f.registration_id JOIN district_master AS d ON r.district_id = d.district_id $filter1"))[0];

// $totalregPages = ceil($total_reg / $limit);
// $totalfalloPages = ceil($total_fallo / $limit);
?>

<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

<style>
  table {
    border-collapse: collapse;
    width: 50%;
    margin-bottom: 20px;
  }

  th,
  td {
    border: 1px solid #ccc;
    padding: 8px;
  }

  .pagination a {
    margin: 0 5px;
    text-decoration: none;
  }

  /* .srch{
    background-color: ;
    color: ;
  }
  .rst{
    background-color: ;
    color: ;
  } */
</style>

<div class="main-content">

  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Registration List</h4>
            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                <li class="breadcrumb-item active">Registration</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- end page title -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4 class="card-title mb-0"><span style="font-weight: 600; font-size: 16px; color: black;">Registration List</span></h4>
              <div class="col-md-3 d-flex align-items-end">
                 <button class="btn btn-success width-md waves-effect waves-light form-control" id="child_detail"><i class="fas fa-file-excel"></i> Export to excel</button>
              </div>
            </div><!-- end card header -->

            <div class="card-body">
              <div class="listjs-table" id="customerList">
                <div class="row g-4 mb-3">
                  <div class="col-sm-auto">
                    <div>
                      <span style="font-weight: 600; font-size: 16px;">
                        Total Records: <span><?php echo $total_reg ?></span>
                      </span>
                    </div>
                  </div>
                  <div class="col-sm">
                    <form method="GET">
                      <div class="d-flex justify-content-sm-end">
                        <div class="ms-2">
                          <input type="text" name="reg_idd" value="<?= htmlspecialchars($_GET['reg_idd'] ?? '') ?>" class="form-control" placeholder="Registration_id...">
                        </div>
                        <div class="ms-2">
                          <input type="text" name="contect" value="<?= htmlspecialchars($_GET['contect'] ?? '') ?>" class="form-control " placeholder="Contect...">
                        </div>
                        <div class="ms-2">
                          <button type="submit" name="search" class="form-control btn btn-secondary">Search</button>
                        </div>
                        <div class="ms-2">
                          <button type="reset" name="reset" onclick="resetSearch()" class="form-control btn btn-danger">Reset</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="table-responsive table-card mt-3 mb-1">
                  <table class="table align-middle table-nowrap" id="full_child_detail">
                    <thead class="table-light">
                      <tr>
                        <td colspan="21" data-f-bold='true' data-fill-color='FFFDAF' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #fffdaf; font-size: 20px; text-align: center; font-weight: bold;">Registration</td>
                        <td colspan="12" data-f-bold='true' data-fill-color='EAE8E0' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #eae8e0; font-size: 20px; text-align: center; font-weight: bold;">Admission</td>
                        <td colspan="13" data-f-bold='true' data-fill-color='FFEFEF' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #ffefef; font-size: 20px; text-align: center; font-weight: bold;">Discharge</td>
                        <td colspan="8"  data-f-bold='true' data-fill-color='FDFA72' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #fdfa72; font-size: 20px; text-align: center; font-weight: bold;">Mother & New Born</td>
                        <td colspan="16" data-f-bold='true' data-fill-color='20E4EB' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #20e4eb; font-size: 20px; text-align: center; font-weight: bold;">Post-discharge First Follow-Up (8 days)</td>
                        <td colspan="16" data-f-bold='true' data-fill-color='86E94D' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #86e94d; font-size: 20px; text-align: center; font-weight: bold;">Post-discharge Second Follow-Up (1 Month)</td>
                        <td colspan="16" data-f-bold='true' data-fill-color='EBC51E' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #ebc51e; font-size: 20px; text-align: center; font-weight: bold;">Post-discharge Third Follow-Up (3 Month)</td>
                        <td colspan="16" data-f-bold='true' data-fill-color='D63DCF' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #d63dcf; font-size: 20px; text-align: center; font-weight: bold;">Post-discharge Fourth Follow-Up (6 Month)</td>
                        <td colspan="16" data-f-bold='true' data-fill-color='B4B9AF' data-b-a-s='thin' data-b-a-c='000000' style="background-color: #b4b9af; font-size: 20px; text-align: center; font-weight: bold;">Post-discharge Fifth Follow-Up (1 Year)</td>
                      </tr>
                      <tr>
                        <!-- registration -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Reg_Id</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Monitor Name</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>SNCU Name</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Name</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Father's name</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mother's name</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Contact</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby's (DOB)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gender</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Delivery type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Birth weight (kg)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Was baby LBW</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gestational age for baby (weeks)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Growth chart used</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Means for verification immunization</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mother's age</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mother's Weight (kg)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Age at marriage (years)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Reason for admission</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mention other reason</th>
                        <!-- admission -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of admission</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Admission weight (kg)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Admission length (cm)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Admission head circumference (cm)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mention other feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Growth chart used</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Head-Circumferences (Z-score)</th>
                        <!-- discharge -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of discharge</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Discharge Outcomes</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Discharge Weight(kg)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Discharge Length(cm)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mention other feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Progress of Child</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Growth chart used</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Head-Circumferences (Z-score)</th>
                        <!-- newborn -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Is newborn admitted at MNCU</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Is i-KMC provided to the newborn at MNCU</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>No. of hours that i-KMC is ensured to newborn at MNCU</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Family Participatory Care (FPC) counseling done or not</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Developmental supportive care practiced</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Continuous Positive Airway Pressure (CPAP) used anytime during the stay</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mention reason for not using CPAP</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>KMC binders are in use or not</th>
                        <!-- Fallow up 8 Days -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circufrence</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Head-Circumferences (Z-score)</th>
                        <!-- Fallow up 1 Month -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circufrence</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Head-Circumferences (Z-score)</th>
                        <!-- Fallow up 3 Month -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circufrence</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Head-Circumferences (Z-score)</th>
                        <!-- Fallow up 6 Month -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circufrence</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Head-Circumferences (Z-score)</th>
                        <!-- Fallow up 1 Year -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circufrence</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Head-Circumferences (Z-score)</th>
                      </tr>
                    </thead>
                    <tbody class="list form-check-all">
                      <?php $postdis = mysqli_query($conn, "SELECT rf.id,rf.unique_id_of_body,rf.monitor_name,rf.sncu_id,rf.boby_name_optional,rf.fathers_name,rf.boby_of_mothers_name,rf.baby_date_of_birth,rf.sex,rf.delivery_type,rf.birth_weight_kg,rf.was_baby_lbw_at_time_of_birth_kg,rf.phone_number_of_mother_father_caregivers,rf.growth_chart_used,rf.immunization_status,rf.means_for_verification_immunization,rf.mothers_age_years,rf.mother_weight_kg,rf.age_at_marrage_years,rf.reason_for_admission,rf.mention_other_reason,rf.gestational_age_LBW,   adm.date_of_admission,adm.admission_weight,adm.admission_length,adm.admission_head_circumference,adm.type_of_feed AS adm_type_of_feed,adm.other_feed AS adm_other_feed,adm.mode_of_feeding AS adm_mode_of_feeding,adm.growth_chart_used,adm.who_wtage AS adm_who_wtage,adm.who_lenage AS adm_who_lenage,adm.who_wtlen AS adm_who_wtlen,adm.who_head_circum AS adm_who_head_circum,dis.date_of_admission AS discharge_date,dis.admission_weight AS discharge_weight, dis.admission_length AS discharge_length,dis.type_of_feed AS dis_type_of_feed,dis.other_feed AS dis_other_feed,dis.mode_of_feeding AS dis_mode_of_feeding,dis.growth_chart_used AS dis_grouth_chart,dis.progress_of_child,dis.who_wtage,dis.who_lenage,dis.who_wtlen,dis.who_head_circum,nb.new_born_admitted,nb.number_of_hours_MNCU,nb.family_participatory_care,nb.developmental_supportive,nb.continuous_positive_airway_CPAC,nb.mention_reason_not_using_CPAC,nb.kmc_binders,fal.type_of_time,fal.schedule_follow_up_date,fal.date_of_visit,fal.baby_weight,fal.baby_length,fal.baby_head_circumference,fal.immunization_status,fal.type_of_feed AS fal_type_of_feed,fal.other_feed AS fal_other_feed,fal.times_baby_breastfed,fal.mode_of_feeding,fal.check_health_examination,fal.who_wtage AS fal_who_wtage,fal.who_lenage AS fal_who_lenage,fal.who_wtlen AS fal_who_wtlen,fal.who_head_circum AS fal_who_head_circum FROM registration_form rf  JOIN monitoring_data adm ON rf.id = adm.registration_id AND adm.type_of_monitoring = 'Date of Admission' JOIN monitoring_data dis ON rf.id = dis.registration_id AND dis.type_of_monitoring = 'Discharge Day' JOIN monitoring_data nb ON rf.id = nb.registration_id AND nb.type_of_monitoring = 'Mother and Newborn at MNCU' JOIN follow_up fal ON rf.id = fal.registration_id $filter ORDER BY rf.id DESC");
                      // limit $limit OFFSET $regOffset
                      while ($postdisdata = mysqli_fetch_object($postdis)) {
                      ?>
                        <tr>
                          <td class="customer_name"><?= !empty($postdisdata->unique_id_of_body) ? $postdisdata->unique_id_of_body : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->monitor_name) ? $postdisdata->monitor_name : 'NA' ?></td>
                          <td class="customer_name"><?= ($postdisdata->sncu_id == 1) ? 'SNCU Gaya' : 'SNCU Purnea' ?></td>
                          <td class="customer_name"><?= ($postdisdata->boby_name_optional == '') ? 'NA' : $postdisdata->boby_name_optional ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->fathers_name) ? $postdisdata->fathers_name : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->boby_of_mothers_name) ? $postdisdata->boby_of_mothers_name : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->phone_number_of_mother_father_caregivers) ? $postdisdata->phone_number_of_mother_father_caregivers : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->baby_date_of_birth) ? $postdisdata->baby_date_of_birth : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->sex) ? $postdisdata->sex : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->delivery_type) ? $postdisdata->delivery_type : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->birth_weight_kg) ? $postdisdata->birth_weight_kg : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->was_baby_lbw_at_time_of_birth_kg) ? $postdisdata->was_baby_lbw_at_time_of_birth_kg : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->gestational_age_LBW) ? $postdisdata->gestational_age_LBW : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->growth_chart_used) ? $postdisdata->growth_chart_used : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->immunization_status) ? $postdisdata->immunization_status : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->means_for_verification_immunization) ? $postdisdata->means_for_verification_immunization : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->mothers_age_years) ? $postdisdata->mothers_age_years : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->mother_weight_kg) ? $postdisdata->mother_weight_kg : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->age_at_marrage_years) ? $postdisdata->age_at_marrage_years : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->reason_for_admission) ? $postdisdata->reason_for_admission : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->mention_other_reason) ? $postdisdata->mention_other_reason : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->date_of_admission) ? $postdisdata->date_of_admission : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->admission_weight) ? $postdisdata->admission_weight : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->admission_length) ? $postdisdata->admission_length : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->admission_head_circumference) ? $postdisdata->admission_head_circumference : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_type_of_feed) ? $postdisdata->adm_type_of_feed : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_other_feed) ? $postdisdata->adm_other_feed : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_mode_of_feeding) ? $postdisdata->adm_mode_of_feeding : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->growth_chart_used) ? $postdisdata->growth_chart_used : 'NA' ?></td>
                          <td><?= !empty($postdisdata->adm_who_wtage) ? $postdisdata->adm_who_wtage : 'NA' ?></td>
                          <td><?= !empty($postdisdata->adm_who_lenage) ? $postdisdata->adm_who_lenage : 'NA' ?></td>
                          <td><?= !empty($postdisdata->adm_who_wtlen) ? $postdisdata->adm_who_wtlen : 'NA' ?></td>
                          <td><?= !empty($postdisdata->adm_who_head_circum) ? $postdisdata->adm_who_head_circum : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->discharge_date) ? $postdisdata->discharge_date : 'NA' ?></td>
                          <td class="customer_name">NA</td>
                          <td class="customer_name"><?= !empty($postdisdata->discharge_weight) ? $postdisdata->discharge_weight : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->discharge_length) ? $postdisdata->discharge_length : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->dis_type_of_feed) ? $postdisdata->dis_type_of_feed : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->dis_other_feed) ? $postdisdata->dis_other_feed : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->dis_mode_of_feeding) ? $postdisdata->dis_mode_of_feeding : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->progress_of_child) ? $postdisdata->progress_of_child : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->dis_grouth_chart) ? $postdisdata->dis_grouth_chart : 'NA' ?></td>
                          <td><?= !empty($postdisdata->who_wtage) ? $postdisdata->who_wtage : 'NA' ?></td>
                          <td><?= !empty($postdisdata->who_lenage) ? $postdisdata->who_lenage : 'NA' ?></td>
                          <td><?= !empty($postdisdata->who_wtlen) ? $postdisdata->who_wtlen : 'NA' ?></td>
                          <td><?= !empty($postdisdata->who_head_circum) ? $postdisdata->who_head_circum : 'NA' ?></td>
                          <!-- <td class="customer_name">NA</td> -->
                          <td class="customer_name"><?= !empty($postdisdata->new_born_admitted) ? $postdisdata->new_born_admitted : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->is_ikmc_provided) ? $postdisdata->is_ikmc_provided : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->number_of_hours_MNCU) ? $postdisdata->number_of_hours_MNCU : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->family_participatory_care) ? $postdisdata->family_participatory_care : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->developmental_supportive) ? $postdisdata->developmental_supportive : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->continuous_positive_airway_CPAC) ? $postdisdata->continuous_positive_airway_CPAC : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->mention_reason_not_using_CPAC) ? $postdisdata->mention_reason_not_using_CPAC : 'NA' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->kmc_binders) ? $postdisdata->kmc_binders : 'NA' ?></td>
                          <!-- Fallow Up 8 days -->
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_time','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'schedule_follow_up_date','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'date_of_visit','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_weight','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_length','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_head_circumference','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'immunization_status','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_feed','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'other_feed','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'times_baby_breastfed','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'mode_of_feeding','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'check_health_examination','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtage','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_lenage','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtlen','8 Days') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_head_circum','8 Days') ?></td>
                           <!-- Fallow Up 1 Month -->
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_time','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'schedule_follow_up_date','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'date_of_visit','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_weight','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_length','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_head_circumference','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'immunization_status','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_feed','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'other_feed','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'times_baby_breastfed','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'mode_of_feeding','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'check_health_examination','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtage','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_lenage','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtlen','1 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_head_circum','1 Month') ?></td>
                           <!-- Fallow Up 3 Month -->
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_time','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'schedule_follow_up_date','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'date_of_visit','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_weight','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_length','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_head_circumference','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'immunization_status','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_feed','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'other_feed','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'times_baby_breastfed','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'mode_of_feeding','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'check_health_examination','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtage','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_lenage','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtlen','3 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_head_circum','3 Month') ?></td>
                           <!-- Fallow Up 6 Month -->
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_time','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'schedule_follow_up_date','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'date_of_visit','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_weight','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_length','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_head_circumference','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'immunization_status','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_feed','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'other_feed','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'times_baby_breastfed','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'mode_of_feeding','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'check_health_examination','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtage','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_lenage','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtlen','6 Month') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_head_circum','6 Month') ?></td>
                           <!-- Fallow Up 1 Year -->
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_time','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'schedule_follow_up_date','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'date_of_visit','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_weight','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_length','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'baby_head_circumference','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'immunization_status','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'type_of_feed','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'other_feed','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'times_baby_breastfed','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'mode_of_feeding','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'check_health_examination','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtage','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_lenage','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_wtlen','1 Year') ?></td>
                           <td><?= getfallowup($conn,'follow_up',$postdisdata->id,'who_head_circum','1 Year') ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <div class="noresult" style="display: none">
                    <div class="text-center">
                      <h5 class="mt-2">Sorry! No Result Found</h5>
                    </div>
                  </div>
                </div>


                <!-- <#?php echo pegination2($reg_page, $totalregPages, $extraParams, 'rreg_page'); ?> -->


              </div>
            </div><!-- end card -->
          </div>
          <!-- end col -->
        </div>
        <!-- end col -->
      </div>

    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
  <?php include('includes/footers.php'); ?>

  <!-- prismjs plugin -->
  <script src="assets/libs/prismjs/prism.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- listjs init -->
  <script src="assets/js/pages/listjs.init.js"></script>
  <!-- Sweet Alerts js -->
  <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- data export code -->
  <script>
    let button = document.querySelector("#child_detail");
    button.addEventListener("click", (e) => {
        let table = document.querySelector("#full_child_detail");
        TableToExcel.convert(table, {
            name: "Complete Child Details.xlsx", 
            sheet: {
                name: "Child Detail" 
            }
        });
    });
  </script>
  <!-- reset page -->
  <script>
    function resetSearch() {
      window.location.href = window.location.pathname; 
    }
  </script>