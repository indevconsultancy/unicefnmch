<?php session_start(); ?>
<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>

<!-- Search Code -->
<?php
$filter = "";
$extraParams = [];
if (isset($_REQUEST['search'])) {

  $reg_idd = $_GET['reg_idd'] ?? '';
  $contect = $_GET['contect'] ?? '';
  $child_id = $_GET['child_id'] ?? '';

  if (!empty($reg_idd)) {
    $filter .= "AND rf.unique_id_of_body LIKE '%" . addslashes($reg_idd) . "%'";
    $extraParams['reg_idd'] = $reg_idd;
  }
  if (!empty($contect)) {
    $filter .= " AND rf.phone_number_of_mother_father_caregivers LIKE '%" . addslashes($contect) . "%'";
    $extraParams['contect'] = $contect;
  }
  if (!empty($child_id)) {
    $filter .= " AND rf.id LIKE '%" . addslashes($child_id) . "%'";
    $extraParams['child_id'] = $child_id;
  }
  $extraParams['search'] = $_GET['search'];
}
?>

<!-- Pagination Code -->
<?php
$limit = 10;
// Get current pages
$reg_page = isset($_GET['rreg_page']) ? (int)$_GET['rreg_page'] : 1;

$regOffset = ($reg_page - 1) * $limit;

$regQuery = "SELECT * FROM registration_form LIMIT $limit OFFSET $regOffset";
$userResult = mysqli_query($conn, $regQuery);

$total_reg = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) AS total_count FROM (SELECT rf.id FROM registration_form rf LEFT JOIN monitoring_data adm  ON rf.id = adm.registration_id AND adm.type_of_monitoring = 'Date of Admission' LEFT JOIN monitoring_data dis ON rf.id = dis.registration_id AND dis.type_of_monitoring = 'Discharge Day' LEFT JOIN follow_up fal ON rf.id = fal.registration_id WHERE 1=1 $filter GROUP BY rf.id) AS count_table"))[0];

$totalregPages = ceil($total_reg / $limit);
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
                          <input type="text" name="child_id" value="<?= htmlspecialchars($_GET['child_id'] ?? '') ?>" class="form-control " placeholder="child_id...">
                        </div>
                        <div class="ms-2">
                          <input type="text" name="contect" value="<?= htmlspecialchars($_GET['contect'] ?? '') ?>" class="form-control" placeholder="Contect...">
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
                        <!-- registration -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Reg_Id</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Child ID</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Registration Date</th>
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
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Term Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Growth chart used</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Means for verification immunization</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mother's age</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mother's Weight (kg)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Age at marriage (years)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Reason for admission</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>LBW</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Preterm</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Others</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mention other reason</th>
                        <!-- admission -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Admission</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of admission</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Child Age in Days</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gestational Week</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Admission weight (kg)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Admission length (cm)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Admission head circumference (cm)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Breastmilk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Animal Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Formula Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Parentrel</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>None</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mention other feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Growth chart used</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Size at Birth-Inter Growth Clasifiaction</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Size at Birth-Fenton Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Size at Birth-WHO Growth Classification</th>

                        <!-- discharge -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Discharge</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of discharge</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Child Age in Days</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gestational Week at Discharge</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Discharge Outcomes</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Discharge Weight(kg)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Discharge Length(cm)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Discharge Head-Circumferences(cm)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Breastmilk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Animal Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Formula Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Parentrel</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mention other feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of feeding</th>

                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Growth chart used</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton <br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO <br />Growth Classification</th>

                        <!-- Fallow up 8 Days -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Child Age in Days</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gestational Post Menstural Week</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circumferences</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Breast Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Animal Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Formula Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Water</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Others</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Asha Check Following Infant</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Tempreture</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Respiratory rate</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Pus on umbilicus</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Eyes</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Urine frequency</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>None</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO <br />Growth Classification</th>

                        <!-- Fallow up 1 Month -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Child Age in Days</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gestational Post Menstural Week</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circumferences</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Breast Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Animal Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Formula Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Water</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Others</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Asha Check Following Infant</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Tempreture</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Respiratory rate</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Pus on umbilicus</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Eyes</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Urine frequency</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>None</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton <br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO <br />Growth Classification</th>

                        <!-- Fallow up 3 Month -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Child Age in Days</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gestational Post Menstural Week</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circumferences</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Breast Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Animal Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Formula Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Water</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Others</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Asha Check Following Infant</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Tempreture</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Respiratory rate</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Pus on umbilicus</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Eyes</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Urine frequency</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>None</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton <br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO <br />Growth Classification</th>

                        <!-- Fallow up 6 Month -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Child Age in Days</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gestational Post Menstural Week</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circumferences</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Breast Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Animal Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Formula Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Water</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Others</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Asha Check Following Infant</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Tempreture</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Respiratory rate</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Pus on umbilicus</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Eyes</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Urine frequency</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>None</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Start Feeding at Home</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Offered Food Items</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Cereals</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Legume and Nuts</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Vitamin-A Fruits and Vegetables</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Fruits and Vegitables</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Milk and Milk Product</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Egg</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Meat or Poultry or Fish</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Junk Items</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton <br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO <br />Growth Classification</th>

                        <!-- Fallow up 1 Year -->
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fallow-Up Type</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Schedule Date</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Date of Visit</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Child Age in Days</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Gestational Post Menstural Week</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Length</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Baby Head Circumferences</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Immunization Status</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Type of Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Breast Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Animal Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Formula Milk</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Water</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Others</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Feed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Times Baby Breastfed</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Mode of Feeding</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Health Examination</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Asha Check Following Infant</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Weight</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Tempreture</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Respiratory rate</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Pus on umbilicus</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Eyes</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Urine frequency</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>None</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Start Feeding at Home</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Offered Food Items</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Cereals</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Legume and Nuts</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Vitamin-A Fruits and Vegetables</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Other Fruits and Vegitables</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Milk and Milk Product</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Egg</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Meat or Poultry or Fish</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Junk Items</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Z-score)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Length for Age (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Weight for Length (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO<br />Head-Circumferences (Percentile)</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Z-score</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Weight Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Length Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Head-Circumferences Percentile</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Intergrowth<br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>Fenton<br />Growth Classification</th>
                        <th data-sort="customer_name" data-f-bold='true' data-b-a-s='thin' data-b-a-c='FFF9C4'>WHO <br />Growth Classification</th>
                      </tr>
                    </thead>
                    <tbody class="list form-check-all">
                      <?php
                      $_SESSION['hheader_column'] = "Reg_Id, Child ID, Registration Date, Monitor Name, SNCU Name, Baby Name, Fathers name, Mothers name, Contact, Babys (DOB), Gender, Delivery type, Birth weight (kg), Was baby LBW, Gestational age for baby (weeks), Term Status, Growth chart used, Immunization status, Means for verification immunization, Mothers age, Mothers Weight (kg), Age at marriage (years), Reason for admission, LBW, Preterm, Others, Mention other reason, Admission, Date of admission, Child Age in Days, Gestational Week, Admission weight (kg), Admission length (cm), Admission head circumference (cm), Type of feed, Breastmilk, Animal Milk, Formula Milk, Parentrel, None, Mention other feed, Mode of Feeding, Growth chart used, WHO Weight for Age (Z-score), WHO Length for Age (Z-score), WHO Weight for Length (Z-score), WHO Head-Circumferences (Z-score), WHO Weight for Age (Percentile), WHO Length for Age (Percentile), WHO Weight for Length (Percentile), WHO Head-Circumferences (Percentile), Fenton Weight Z-score, Fenton Length Z-score, Fenton Head-Circumferences Z-score, Fenton Weight Percentile, Fenton Length Percentile, Fenton Head-Circumferences Percentile, Intergrowth Weight Z-score, Intergrowth Length Z-score, Intergrowth Head-Circumferences Z-score, Intergrowth Weight Percentile, Intergrowth Length Percentile, Intergrowth Head-Circumferences Percentile, Size at Birth-Inter Growth Clasifiaction, Size at Birth-Fenton Growth Classification, Size at Birth-WHO Growth Classification, Discharge, Date of discharge, Child Age in Days, Gestational Week at Discharge, Discharge Outcomes, Discharge Weight(kg), Discharge Length(cm), Discharge Head-Circumferences(cm), Type of feed, Breastmilk, Animal Milk, Formula Milk, Parentrel, Other, Mention other feed, Mode of feeding, Growth chart used, WHO Weight for Age (Z-score), WHO Length for Age (Z-score), WHO Weight for Length (Z-score), WHO Head-Circumferences (Z-score), WHO Weight for Age (Percentile), WHO Length for Age (Percentile), WHO Weight for Length (Percentile), WHO Head-Circumferences (Percentile), Fenton Weight Z-score, Fenton Length Z-score, Fenton Head-Circumferences Z-score, Fenton Weight Percentile, Fenton Length Percentile, Fenton Head-Circumferences Percentile, Intergrowth Weight Z-score, Intergrowth Length Z-score, Intergrowth Head-Circumferences Z-score, Intergrowth Weight Percentile, Intergrowth Length Percentile, Intergrowth Head-Circumferences Percentile, Intergrowth Growth Classification, Fenton Growth Classification, WHO Growth Classification,Fallow-Up Type,Schedule Date,Date of Visit,Child Age in Days,Gestational Post Menstural Week,Baby Weight,Baby Length,Baby Head Circumferences,Immunization Status,Type of Feed,Breast Milk,Animal Milk,Formula Milk,Water,Others,Other Feed,Times Baby Breastfed,Mode of Feeding,Health Examination,Asha Check Following Infant,Weight,Tempreture,Respiratory rate,Pus on umbilicus,Eyes,Urine frequency,None,WHO Weight for Age (Z-score),WHO Length for Age (Z-score),WHO Weight for Length (Z-score),WHO Head-Circumferences (Z-score),WHO Weight for Age (Percentile),WHO Length for Age (Percentile),WHO Weight for Length (Percentile),WHO Head-Circumferences (Percentile),Fenton Weight Z-score,Fenton Length Z-score,Fenton Head-Circumferences Z-score,Fenton Weight Percentile,Fenton Length Percentile,Fenton Head-Circumferences Percentile,Intergrowth Weight Z-score,Intergrowth Length Z-score,Intergrowth Head-Circumferences Z-score,Intergrowth Weight Percentile,Intergrowth Length Percentile,Intergrowth Head-Circumferences Percentile,Intergrowth Growth Classification,Fenton Growth Classification,WHO Growth Classification";
                      $_SESSION['dbb_column'] = "id, reg_date, unique_id_of_body, monitor_name, sncu_id, boby_name_optional, fathers_name, boby_of_mothers_name, baby_date_of_birth, sex, delivery_type, birth_weight_kg, was_baby_lbw_at_time_of_birth_kg, phone_number_of_mother_father_caregivers, growth_chart_used, immunization_status, means_for_verification_immunization, mothers_age_years, mother_weight_kg, age_at_marrage_years, reason_for_admission, mention_other_reason, gestational_age_LBW, reason_LBW, reason_Preterm, reason_Others, date_of_admission, admission_weight, admission_length, admission_head_circumference, adm_type_of_feed, adm_other_feed, adm_mode_of_feeding, growth_chart_used, adm_who_wtage, adm_who_lenage, adm_who_wtlen, adm_who_head_circum, adm_type_of_feed__Breastmilk, adm_type_of_feed__Animal_Milk, adm_type_of_feed__Formula_Milk, adm_type_of_feed__Parenteral, adm_type_of_feed__Other, adm_who_per_wtage, adm_who_per_lenage, adm_who_per_wtlen, adm_who_per_head_circum, adm_fenton_wtage, adm_fenton_lenage, adm_fenton_wtlen, adm_fenton_head_circum, adm_fenton_per_wtage, adm_fenton_per_lenage, adm_fenton_per_wtlen, adm_fenton_per_head_circum, adm_intergrowth_wtage, adm_intergrowth_lenage, adm_intergrowth_head_circum, adm_intergrowth_per_wtage, adm_intergrowth_per_lenage, adm_intergrowth_per_head_circum, adm_intergrowth_classification, adm_who_classification, adm_fenton_growth_classification, discharge_date, discharge_weight, discharge_length, discharge_head_circumference, dis_type_of_feed, dis_other_feed, dis_mode_of_feeding, dis_grouth_chart, progress_of_child, who_wtage, who_lenage, who_wtlen, who_head_circum, dis_who_per_wtage, dis_who_per_lenage, dis_who_per_wtlen, dis_who_per_head_circum, dis_fenton_wtage, dis_fenton_lenage, dis_fenton_wtlen, dis_fenton_head_circum, dis_fenton_per_wtage, dis_fenton_per_lenage, dis_fenton_per_wtlen, dis_fenton_per_head_circum, dis_intergrowth_wtage, dis_intergrowth_lenage, dis_intergrowth_head_circum, dis_intergrowth_per_wtage, dis_intergrowth_per_lenage, dis_intergrowth_per_head_circum, dis_fenton_growth_classification, dis_type_of_feed__Breastmilk, dis_type_of_feed__Animal_Milk, dis_type_of_feed__Formula_Milk, dis_type_of_feed__Parenteral, dis_type_of_feed__Other, dis_intergrowth_classification, dis_who_classification, type_of_time, schedule_follow_up_date, date_of_visit, baby_weight, baby_length, baby_head_circumference, immunization_status";
                      $_SESSION['dbb_query'] = "SELECT rf.id,rf.created_at AS reg_date,rf.unique_id_of_body,rf.monitor_name,rf.sncu_id,rf.boby_name_optional,rf.fathers_name,rf.boby_of_mothers_name,rf.baby_date_of_birth,rf.sex,rf.delivery_type,rf.birth_weight_kg,rf.was_baby_lbw_at_time_of_birth_kg,rf.phone_number_of_mother_father_caregivers,rf.growth_chart_used,rf.immunization_status,rf.means_for_verification_immunization,rf.mothers_age_years,rf.mother_weight_kg,rf.age_at_marrage_years,rf.reason_for_admission,rf.mention_other_reason,rf.gestational_age_LBW,rf.reason_LBW,rf.reason_Preterm,rf.reason_Others,adm.date_of_admission,adm.admission_weight,adm.admission_length,adm.admission_head_circumference,adm.type_of_feed AS adm_type_of_feed,adm.other_feed AS adm_other_feed,adm.mode_of_feeding AS adm_mode_of_feeding,adm.growth_chart_used,adm.who_wtage AS adm_who_wtage,adm.who_lenage AS adm_who_lenage,adm.who_wtlen AS adm_who_wtlen,adm.who_head_circum AS adm_who_head_circum,adm.type_of_feed__Breastmilk AS adm_type_of_feed__Breastmilk,adm.type_of_feed__Animal_Milk AS adm_type_of_feed__Animal_Milk,adm.type_of_feed__Formula_Milk AS adm_type_of_feed__Formula_Milk,adm.type_of_feed__Parenteral AS adm_type_of_feed__Parenteral,adm.type_of_feed__Other AS adm_type_of_feed__Other,adm.who_per_wtage AS adm_who_per_wtage,adm.who_per_lenage AS adm_who_per_lenage,adm.who_per_wtlen AS adm_who_per_wtlen,adm.who_head_circum_per AS adm_who_per_head_circum,adm.fenton_wtage AS adm_fenton_wtage,adm.fenton_lenage AS adm_fenton_lenage,adm.fenton_wtlen AS adm_fenton_wtlen,adm.fenton_head_circum AS adm_fenton_head_circum,adm.fenton_per_wtage AS adm_fenton_per_wtage,adm.fenton_per_lenage AS adm_fenton_per_lenage,adm.fenton_per_wtlen AS adm_fenton_per_wtlen,adm.fenton_head_circum_per AS adm_fenton_per_head_circum,adm.intergrowth_wtage AS adm_intergrowth_wtage,adm.intergrowth_lenage AS adm_intergrowth_lenage,adm.intergrowth_head_circum AS adm_intergrowth_head_circum,adm.intergrowth_per_wtage AS adm_intergrowth_per_wtage,adm.intergrowth_per_lenage AS adm_intergrowth_per_lenage,adm.intergrowth_head_circum_per AS adm_intergrowth_per_head_circum,adm.intergrowth_classification AS adm_intergrowth_classification,adm.who_classification AS adm_who_classification,adm.fenton_growth_classification AS adm_fenton_growth_classification,dis.date_of_admission AS discharge_date,dis.admission_weight AS discharge_weight, dis.admission_length AS discharge_length,dis.admission_head_circumference AS discharge_head_circumference,dis.type_of_feed AS dis_type_of_feed,dis.other_feed AS dis_other_feed,dis.mode_of_feeding AS dis_mode_of_feeding,dis.growth_chart_used AS dis_grouth_chart,dis.progress_of_child,dis.who_wtage,dis.who_lenage,dis.who_wtlen,dis.who_head_circum,dis.who_per_wtage AS dis_who_per_wtage,dis.who_per_lenage AS dis_who_per_lenage,dis.who_per_wtlen AS dis_who_per_wtlen,dis.who_head_circum_per AS dis_who_per_head_circum,dis.fenton_wtage AS dis_fenton_wtage,dis.fenton_lenage AS dis_fenton_lenage,dis.fenton_wtlen AS dis_fenton_wtlen,dis.fenton_head_circum AS dis_fenton_head_circum,dis.fenton_per_wtage AS dis_fenton_per_wtage,dis.fenton_per_lenage AS dis_fenton_per_lenage,dis.fenton_per_wtlen AS dis_fenton_per_wtlen,dis.fenton_head_circum_per AS dis_fenton_per_head_circum,dis.intergrowth_wtage AS dis_intergrowth_wtage,dis.intergrowth_lenage AS dis_intergrowth_lenage,dis.intergrowth_head_circum AS dis_intergrowth_head_circum,dis.intergrowth_per_wtage AS dis_intergrowth_per_wtage,dis.intergrowth_per_lenage AS dis_intergrowth_per_lenage,dis.intergrowth_head_circum_per AS dis_intergrowth_per_head_circum,dis.fenton_growth_classification AS dis_fenton_growth_classification,dis.type_of_feed__Breastmilk AS dis_type_of_feed__Breastmilk,dis.type_of_feed__Animal_Milk AS dis_type_of_feed__Animal_Milk,dis.type_of_feed__Formula_Milk AS dis_type_of_feed__Formula_Milk,dis.type_of_feed__Parenteral AS dis_type_of_feed__Parenteral,dis.type_of_feed__Other AS dis_type_of_feed__Other,dis.intergrowth_classification AS dis_intergrowth_classification,dis.who_classification AS dis_who_classification FROM registration_form rf  LEFT JOIN monitoring_data adm ON rf.id = adm.registration_id AND adm.type_of_monitoring = 'Date of Admission' LEFT JOIN monitoring_data dis ON rf.id = dis.registration_id AND dis.type_of_monitoring = 'Discharge Day' WHERE 1=1";


                      $postdis = mysqli_query($conn, "SELECT rf.id,rf.created_at AS reg_date,rf.unique_id_of_body,rf.monitor_name,rf.sncu_id,rf.boby_name_optional,rf.fathers_name,rf.boby_of_mothers_name,rf.baby_date_of_birth,rf.sex,rf.delivery_type,rf.birth_weight_kg,rf.was_baby_lbw_at_time_of_birth_kg,rf.phone_number_of_mother_father_caregivers,rf.growth_chart_used,rf.immunization_status,rf.means_for_verification_immunization,rf.mothers_age_years,rf.mother_weight_kg,rf.age_at_marrage_years,rf.reason_for_admission,rf.mention_other_reason,rf.gestational_age_LBW,rf.reason_LBW,rf.reason_Preterm,rf.reason_Others,adm.date_of_admission,adm.admission_weight,adm.admission_length,adm.admission_head_circumference,adm.type_of_feed AS adm_type_of_feed,adm.other_feed AS adm_other_feed,adm.mode_of_feeding AS adm_mode_of_feeding,adm.growth_chart_used,adm.who_wtage AS adm_who_wtage,adm.who_lenage AS adm_who_lenage,adm.who_wtlen AS adm_who_wtlen,adm.who_head_circum AS adm_who_head_circum,adm.type_of_feed__Breastmilk AS adm_type_of_feed__Breastmilk,adm.type_of_feed__Animal_Milk AS adm_type_of_feed__Animal_Milk,adm.type_of_feed__Formula_Milk AS adm_type_of_feed__Formula_Milk,adm.type_of_feed__Parenteral AS adm_type_of_feed__Parenteral,adm.type_of_feed__Other AS adm_type_of_feed__Other,adm.who_per_wtage AS adm_who_per_wtage,adm.who_per_lenage AS adm_who_per_lenage,adm.who_per_wtlen AS adm_who_per_wtlen,adm.who_head_circum_per AS adm_who_per_head_circum,adm.fenton_wtage AS adm_fenton_wtage,adm.fenton_lenage AS adm_fenton_lenage,adm.fenton_wtlen AS adm_fenton_wtlen,adm.fenton_head_circum AS adm_fenton_head_circum,adm.fenton_per_wtage AS adm_fenton_per_wtage,adm.fenton_per_lenage AS adm_fenton_per_lenage,adm.fenton_per_wtlen AS adm_fenton_per_wtlen,adm.fenton_head_circum_per AS adm_fenton_per_head_circum,adm.intergrowth_wtage AS adm_intergrowth_wtage,adm.intergrowth_lenage AS adm_intergrowth_lenage,adm.intergrowth_head_circum AS adm_intergrowth_head_circum,adm.intergrowth_per_wtage AS adm_intergrowth_per_wtage,adm.intergrowth_per_lenage AS adm_intergrowth_per_lenage,adm.intergrowth_head_circum_per AS adm_intergrowth_per_head_circum,adm.intergrowth_classification AS adm_intergrowth_classification,adm.who_classification AS adm_who_classification,adm.fenton_growth_classification AS adm_fenton_growth_classification,dis.date_of_admission AS discharge_date,dis.admission_weight AS discharge_weight, dis.admission_length AS discharge_length,dis.admission_head_circumference AS discharge_head_circumference,dis.type_of_feed AS dis_type_of_feed,dis.other_feed AS dis_other_feed,dis.mode_of_feeding AS dis_mode_of_feeding,dis.growth_chart_used AS dis_grouth_chart,dis.progress_of_child,dis.who_wtage,dis.who_lenage,dis.who_wtlen,dis.who_head_circum,dis.who_per_wtage AS dis_who_per_wtage,dis.who_per_lenage AS dis_who_per_lenage,dis.who_per_wtlen AS dis_who_per_wtlen,dis.who_head_circum_per AS dis_who_per_head_circum,dis.fenton_wtage AS dis_fenton_wtage,dis.fenton_lenage AS dis_fenton_lenage,dis.fenton_wtlen AS dis_fenton_wtlen,dis.fenton_head_circum AS dis_fenton_head_circum,dis.fenton_per_wtage AS dis_fenton_per_wtage,dis.fenton_per_lenage AS dis_fenton_per_lenage,dis.fenton_per_wtlen AS dis_fenton_per_wtlen,dis.fenton_head_circum_per AS dis_fenton_per_head_circum,dis.intergrowth_wtage AS dis_intergrowth_wtage,dis.intergrowth_lenage AS dis_intergrowth_lenage,dis.intergrowth_head_circum AS dis_intergrowth_head_circum,dis.intergrowth_per_wtage AS dis_intergrowth_per_wtage,dis.intergrowth_per_lenage AS dis_intergrowth_per_lenage,dis.intergrowth_head_circum_per AS dis_intergrowth_per_head_circum,dis.fenton_growth_classification AS dis_fenton_growth_classification,dis.type_of_feed__Breastmilk AS dis_type_of_feed__Breastmilk,dis.type_of_feed__Animal_Milk AS dis_type_of_feed__Animal_Milk,dis.type_of_feed__Formula_Milk AS dis_type_of_feed__Formula_Milk,dis.type_of_feed__Parenteral AS dis_type_of_feed__Parenteral,dis.type_of_feed__Other AS dis_type_of_feed__Other,dis.intergrowth_classification AS dis_intergrowth_classification,dis.who_classification AS dis_who_classification,fal.type_of_time,fal.schedule_follow_up_date,fal.date_of_visit,fal.baby_weight,fal.baby_length,fal.baby_head_circumference,fal.immunization_status,fal.type_of_feed AS fal_type_of_feed,fal.other_feed AS fal_other_feed,fal.times_baby_breastfed,fal.mode_of_feeding,fal.check_health_examination,fal.who_wtage AS fal_who_wtage,fal.who_lenage AS fal_who_lenage,fal.who_wtlen AS fal_who_wtlen,fal.who_head_circum AS fal_who_head_circum FROM registration_form rf  LEFT JOIN monitoring_data adm ON rf.id = adm.registration_id AND adm.type_of_monitoring = 'Date of Admission' LEFT JOIN monitoring_data dis ON rf.id = dis.registration_id AND dis.type_of_monitoring = 'Discharge Day' LEFT JOIN follow_up fal ON rf.id = fal.registration_id WHERE 1=1 $filter group by rf.id ORDER BY rf.id DESC limit $limit OFFSET $regOffset");
                      // limit $limit OFFSET $regOffset
                      while ($postdisdata = mysqli_fetch_object($postdis)) {
                        $regdate = date('Y-m-d', strtotime($postdisdata->reg_date));
                      ?>
                        <tr>
                          <td class="customer_name"><?= !empty($postdisdata->unique_id_of_body) ? $postdisdata->unique_id_of_body : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->id) ? $postdisdata->id : '' ?></td>
                          <td class="customer_name"><?= $regdate ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->monitor_name) ? $postdisdata->monitor_name : '' ?></td>
                          <td class="customer_name"><?= ($postdisdata->sncu_id == 1) ? 'SNCU Gaya' : 'SNCU Purnea' ?></td>
                          <td class="customer_name"><?= ($postdisdata->boby_name_optional == '') ? '' : $postdisdata->boby_name_optional ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->fathers_name) ? $postdisdata->fathers_name : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->boby_of_mothers_name) ? $postdisdata->boby_of_mothers_name : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->phone_number_of_mother_father_caregivers) ? $postdisdata->phone_number_of_mother_father_caregivers : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->baby_date_of_birth) ? $postdisdata->baby_date_of_birth : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->sex) ? $postdisdata->sex : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->delivery_type) ? $postdisdata->delivery_type : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->birth_weight_kg) ? $postdisdata->birth_weight_kg : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->was_baby_lbw_at_time_of_birth_kg) ? $postdisdata->was_baby_lbw_at_time_of_birth_kg : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->gestational_age_LBW) ? $postdisdata->gestational_age_LBW : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->gestational_age_LBW) ? get_term($postdisdata->gestational_age_LBW) : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->growth_chart_used) ? $postdisdata->growth_chart_used : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->immunization_status) ? $postdisdata->immunization_status : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->means_for_verification_immunization) ? $postdisdata->means_for_verification_immunization : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->mothers_age_years) ? $postdisdata->mothers_age_years : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->mother_weight_kg) ? $postdisdata->mother_weight_kg : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->age_at_marrage_years) ? $postdisdata->age_at_marrage_years : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->reason_for_admission) ? $postdisdata->reason_for_admission : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->reason_LBW) ? $postdisdata->reason_LBW : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->reason_Preterm) ? $postdisdata->reason_Preterm : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->reason_Others) ? $postdisdata->reason_Others : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->mention_other_reason) ? $postdisdata->mention_other_reason : '' ?></td>

                          <!--------------Adminssion-------------------------------->
                          <td class="customer_name"><?= !empty($postdisdata->date_of_admission) ? 'Admission' : '' ?>
                          </td>
                          <td class="customer_name"><?= !empty($postdisdata->date_of_admission) ? $postdisdata->date_of_admission : '' ?></td>
                          <td class="customer_name"><?php
                                                    if (!empty($postdisdata->date_of_admission) && !empty($postdisdata->baby_date_of_birth)) {
                                                      echo ((strtotime($postdisdata->date_of_admission) - strtotime($postdisdata->baby_date_of_birth)) / 86400);
                                                    }
                                                    ?>
                          </td>
                          <td class="customer_name">
                            <?php
                            if (!empty($postdisdata->gestational_age_LBW) && !empty($postdisdata->date_of_admission) && !empty($postdisdata->baby_date_of_birth)) {
                              $ga_at_birth = $postdisdata->gestational_age_LBW;
                              $days_diff = ((strtotime($postdisdata->date_of_admission) - strtotime($postdisdata->baby_date_of_birth)) / 86400);
                              $total_ga = floor($ga_at_birth + ($days_diff / 7));
                              echo $total_ga;
                            }
                            ?>
                          </td>
                          <td class="customer_name"><?= !empty($postdisdata->admission_weight) ? $postdisdata->admission_weight : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->admission_length) ? $postdisdata->admission_length : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->admission_head_circumference) ? $postdisdata->admission_head_circumference : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_type_of_feed) ? $postdisdata->adm_type_of_feed : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_type_of_feed__Breastmilk) ? $postdisdata->adm_type_of_feed__Breastmilk : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_type_of_feed__Animal_Milk) ? $postdisdata->adm_type_of_feed__Animal_Milk : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_type_of_feed__Formula_Milk) ? $postdisdata->adm_type_of_feed__Formula_Milk : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_type_of_feed__Parenteral) ? $postdisdata->adm_type_of_feed__Parenteral : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_type_of_feed__Other) ? $postdisdata->adm_type_of_feed__Other : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_other_feed) ? $postdisdata->adm_other_feed : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->adm_mode_of_feeding) ? $postdisdata->adm_mode_of_feeding : '' ?></td>
                          <td class="customer_name"><?= !empty($postdisdata->growth_chart_used) ? $postdisdata->growth_chart_used : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_wtage) ? $postdisdata->adm_who_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_lenage) ? $postdisdata->adm_who_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_wtlen) ? $postdisdata->adm_who_wtlen : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_head_circum) ? $postdisdata->adm_who_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_per_wtage) ? $postdisdata->adm_who_per_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_per_lenage) ? $postdisdata->adm_who_per_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_per_wtlen) ? $postdisdata->adm_who_per_wtlen : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_per_head_circum) ? $postdisdata->adm_who_per_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->adm_fenton_wtage) ? $postdisdata->adm_fenton_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_fenton_lenage) ? $postdisdata->adm_fenton_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_fenton_head_circum) ? $postdisdata->adm_fenton_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->adm_fenton_per_wtage) ? $postdisdata->adm_fenton_per_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_fenton_per_lenage) ? $postdisdata->adm_fenton_per_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_fenton_per_head_circum) ? $postdisdata->adm_fenton_per_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->adm_intergrowth_wtage) ? $postdisdata->adm_intergrowth_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_intergrowth_lenage) ? $postdisdata->adm_intergrowth_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_intergrowth_head_circum) ? $postdisdata->adm_intergrowth_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->adm_intergrowth_per_wtage) ? $postdisdata->adm_intergrowth_per_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_intergrowth_per_lenage) ? $postdisdata->adm_intergrowth_per_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->adm_intergrowth_per_head_circum) ? $postdisdata->adm_intergrowth_per_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->adm_intergrowth_classification) ? $postdisdata->adm_intergrowth_classification : '' ?></td>
                          <td><?= !empty($postdisdata->adm_fenton_growth_classification) ? $postdisdata->adm_fenton_growth_classification : '' ?></td>
                          <td><?= !empty($postdisdata->adm_who_classification) ? $postdisdata->adm_who_classification : '' ?></td>
                          <!--------------Discharge -------------------->
                          <td class="customer_name"><?= !empty($postdisdata->discharge_date) ? 'Discharge' : '' ?></td>
                          <td><?= !empty($postdisdata->discharge_date) ? $postdisdata->discharge_date : '' ?></td>
                          <td class="customer_name">
                            <?php if (!empty($postdisdata->discharge_date) && !empty($postdisdata->baby_date_of_birth)) {
                              echo ((strtotime($postdisdata->discharge_date) - strtotime($postdisdata->baby_date_of_birth)) / 86400);
                            }
                            ?>
                          </td>
                          <td class="customer_name">
                            <?php
                            if (!empty($postdisdata->gestational_age_LBW) && !empty($postdisdata->discharge_date) && !empty($postdisdata->baby_date_of_birth)) {
                              $ga_at_birth = $postdisdata->gestational_age_LBW;
                              $days_diff = ((strtotime($postdisdata->discharge_date) - strtotime($postdisdata->baby_date_of_birth)) / 86400);
                              $total_ga = floor($ga_at_birth + ($days_diff / 7));
                              echo $total_ga;
                            }
                            ?>
                          </td>
                          <td><?= !empty($postdisdata->progress_of_child) ? $postdisdata->progress_of_child : '' ?></td>
                          <td><?= !empty($postdisdata->discharge_weight) ? $postdisdata->discharge_weight : '' ?></td>
                          <td><?= !empty($postdisdata->discharge_length) ? $postdisdata->discharge_length : '' ?></td>
                          <td><?= !empty($postdisdata->discharge_head_circumference) ? $postdisdata->discharge_head_circumference : '' ?></td>
                          <td><?= !empty($postdisdata->dis_type_of_feed) ? $postdisdata->dis_type_of_feed : '' ?></td>
                          <td><?= !empty($postdisdata->dis_type_of_feed__Breastmilk) ? $postdisdata->dis_type_of_feed__Breastmilk : '' ?></td>
                          <td><?= !empty($postdisdata->dis_type_of_feed__Animal_Milk) ? $postdisdata->dis_type_of_feed__Animal_Milk : '' ?></td>
                          <td><?= !empty($postdisdata->dis_type_of_feed__Formula_Milk) ? $postdisdata->dis_type_of_feed__Formula_Milk : '' ?></td>
                          <td><?= !empty($postdisdata->dis_type_of_feed__Parenteral) ? $postdisdata->dis_type_of_feed__Parenteral : '' ?></td>
                          <td><?= !empty($postdisdata->dis_type_of_feed__Other) ? $postdisdata->dis_type_of_feed__Other : '' ?></td>
                          <td><?= !empty($postdisdata->dis_other_feed) ? $postdisdata->dis_other_feed : '' ?></td>
                          <td><?= !empty($postdisdata->dis_mode_of_feeding) ? $postdisdata->dis_mode_of_feeding : '' ?></td>

                          <td><?= !empty($postdisdata->dis_grouth_chart) ? $postdisdata->dis_grouth_chart : '' ?></td>
                          <td><?= !empty($postdisdata->who_wtage) ? $postdisdata->who_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->who_lenage) ? $postdisdata->who_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->who_wtlen) ? $postdisdata->who_wtlen : '' ?></td>
                          <td><?= !empty($postdisdata->who_head_circum) ? $postdisdata->who_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->dis_who_per_wtage) ? $postdisdata->dis_who_per_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_who_per_lenage) ? $postdisdata->dis_who_per_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_who_per_wtlen) ? $postdisdata->dis_who_per_wtlen : '' ?></td>
                          <td><?= !empty($postdisdata->dis_who_per_head_circum) ? $postdisdata->dis_who_per_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->dis_fenton_wtage) ? $postdisdata->dis_fenton_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_fenton_lenage) ? $postdisdata->dis_fenton_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_fenton_head_circum) ? $postdisdata->dis_fenton_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->dis_fenton_per_wtage) ? $postdisdata->dis_fenton_per_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_fenton_per_lenage) ? $postdisdata->dis_fenton_per_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_fenton_per_head_circum) ? $postdisdata->dis_fenton_per_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->dis_intergrowth_wtage) ? $postdisdata->dis_intergrowth_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_intergrowth_lenage) ? $postdisdata->dis_intergrowth_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_intergrowth_head_circum) ? $postdisdata->dis_intergrowth_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->dis_intergrowth_per_wtage) ? $postdisdata->dis_intergrowth_per_wtage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_intergrowth_per_lenage) ? $postdisdata->dis_intergrowth_per_lenage : '' ?></td>
                          <td><?= !empty($postdisdata->dis_intergrowth_per_head_circum) ? $postdisdata->dis_intergrowth_per_head_circum : '' ?></td>
                          <td><?= !empty($postdisdata->dis_intergrowth_classification) ? $postdisdata->dis_intergrowth_classification : '' ?></td>
                          <td><?= !empty($postdisdata->dis_fenton_growth_classification) ? $postdisdata->dis_fenton_growth_classification : '' ?></td>
                          <td><?= !empty($postdisdata->dis_who_classification) ? $postdisdata->dis_who_classification : '' ?></td>
                          <!-- <td class="customer_name">NA</td> -->
                          <!-- <td class="customer_name"><#?= !empty($postdisdata->new_born_admitted) ? $postdisdata->new_born_admitted : '' ?></td>
                          <td class="customer_name"><#?= !empty($postdisdata->is_ikmc_provided) ? $postdisdata->is_ikmc_provided : '' ?></td>
                          <td class="customer_name"><#?= !empty($postdisdata->number_of_hours_MNCU) ? $postdisdata->number_of_hours_MNCU : '' ?></td>
                          <td class="customer_name"><#?= !empty($postdisdata->family_participatory_care) ? $postdisdata->family_participatory_care : '' ?></td>
                          <td class="customer_name"><#?= !empty($postdisdata->developmental_supportive) ? $postdisdata->developmental_supportive : '' ?></td>
                          <td class="customer_name"><#?= !empty($postdisdata->continuous_positive_airway_CPAC) ? $postdisdata->continuous_positive_airway_CPAC : '' ?></td>
                          <td class="customer_name"><#?= !empty($postdisdata->mention_reason_not_using_CPAC) ? $postdisdata->mention_reason_not_using_CPAC : '' ?></td>
                          <td class="customer_name"><#?= !empty($postdisdata->kmc_binders) ? $postdisdata->kmc_binders : '' ?></td> -->
                          <!-- Fallow Up 8 days -->
                          <?php if ($postdisdata->gestational_age_LBW >= 0) {
                            $gestLBW = $postdisdata->gestational_age_LBW;
                          } else {
                            $gestLBW = 0;
                          } ?>
                          <?= getfallowup($conn, 'follow_up', $postdisdata->id, $postdisdata->baby_date_of_birth, $gestLBW, '8 Days') ?>
                          <?= getfallowup($conn, 'follow_up', $postdisdata->id, $postdisdata->baby_date_of_birth, $gestLBW, '1 Month') ?>
                          <?= getfallowup($conn, 'follow_up', $postdisdata->id, $postdisdata->baby_date_of_birth, $gestLBW, '3 Month') ?>
                          <?= getfallowup($conn, 'follow_up', $postdisdata->id, $postdisdata->baby_date_of_birth, $gestLBW, '6 Month') ?>
                          <?= getfallowup($conn, 'follow_up', $postdisdata->id, $postdisdata->baby_date_of_birth, $gestLBW, '1 Year') ?>
                          <!-- Fallow Up 1 Month -->

                          <!-- Fallow Up 3 Month -->

                          <!-- Fallow Up 6 Month -->

                          <!-- Fallow Up 1 Year -->

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


                <?php echo pagination1($reg_page, $totalregPages, 'rreg_page', $extraParams); ?>


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
  // <script>
    //   let button = document.querySelector("#child_detail");
    //   button.addEventListener("click", (e) => {
    //     let table = document.querySelector("#full_child_detail");
    //     TableToExcel.convert(table, {
    //       name: "Complete Child Details.xlsx",
    //       sheet: {
    //         name: "Child Detail"
    //       }
    //     });
    //   });
    // 
  </script>
  <!-- reset page -->
  <script>
    function resetSearch() {
      window.location.href = window.location.pathname;
    }
  </script>