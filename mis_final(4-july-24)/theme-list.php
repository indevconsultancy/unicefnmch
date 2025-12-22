<?php include_once('includes/config.php'); ?>
<?php define("title","List Theme | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>


<?php
$qry = '';
if (isset($_REQUEST['search'])) {
  if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '') {
    $qry = " and categories.category_id = '" . $_REQUEST['category_id'] . "' ";
  }
  if (isset($_REQUEST['theme_name']) && $_REQUEST['theme_name'] != '') {
    $qry = " and theme.theme_name like '%" . $_REQUEST['theme_name'] . "%' ";
  }
}
?>

<?php

//pagination
$per_page = 10;
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['theme_id']) ? $page_url . "theme_id=" . $_GET['theme_id'] : $page_url;
$page_url = isset($_GET['category_id']) ? $page_url . "&category_id=" . $_GET['category_id'] : $page_url;
$page_url = isset($_GET['theme_name']) ? $page_url . "&theme_name=" . $_GET['theme_name'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
  $current_page = intval($_GET['page']);
  $page = ($current_page - 1) * $per_page;
}
$query = "SELECT theme.theme_name,categories.category_name FROM `theme` INNER JOIN categories ON theme.category_id=categories.category_id where theme.status='0' $qry ";
$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
?>
<style>
  .panel-heading {
    background: #394a59;
    color: white;
    font-weight: unset;
  }

  .btn:not(:disabled):not(.disabled) {
    cursor: pointer;
  }

  .add-button-bg a {
    position: fixed;
    bottom: 54px;
    right: 50px;
    background: rgb(57, 74, 89);
    z-index: 99999;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    color: #fff;
    line-height: 46px;
    font-size: 22px;
    transition: all .3s ease-in-out;
  }

  .add-button-bg a:hover {
    background: rgb(4 39 60);
    color: #ffffff;
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);
    box-shadow: 1px 1px 1px 17px rgb(255 192 192 / 28%);

  }
</style>
<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="add-button-bg">
      <a href="" class="btn btn-fixed-circle" title="Add Category" data-toggle="modal" data-target="#exampleModal" data-backdrop="static" data-keyboard="false" data-whatever="@fat" style="border-radius: 40px;"><i class="fa fa-plus"></i></a>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <!--  <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li> -->
          <li><i class="fa fa-cog" aria-hidden="true"></i>Setting</li>
          <li><i class="fa fa-list"></i>List Theme</li>
        </ol>
      </div>
    </div>
    <!-- page start-->
    <div class="row">
      <div class="col-sm-12">
        <div class="container-fluid">
          <form class="form-inline" method="get" role="form">
            <div class="row filter_css clearfix">
				<div class="col-lg-5" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
				  <select class="form-control select2" name="category_id" id="category_id">
					<option value="">Select Information area</option>
					<?php 
						$categorysql=mysqli_query($conn,"SELECT category_id,category_name FROM categories where status='0' order by category_id ASC ");
						while($rowCategory=mysqli_fetch_array($categorysql)){ ?>
							<option value="<?=$rowCategory['category_id']?>"<?php if($rowCategory['category_id']==@$_REQUEST['category_id']){ echo "selected";}?>><?=$rowCategory['category_name']?></option>
						<?php } ?>
						
					</select>
				</div>
              <div class="form-group col-lg-5" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
                <input type="text" class="form-control" name="theme_name" value="<?= @$_REQUEST['theme_name'] ?>" placeholder="Theme Name">
              </div>
              <div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
                <button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
              </div>
            </div>
          </form>
        </div>
        <section class="panel">
          <header class="panel-heading">Total Theme: <?= $total_record ?>
          </header>
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="">S.No</th>
                <th class="">Information Area</th>
                <th class="">Theme</th>
                <th class="">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $_SESSION['query'] = "SELECT theme.theme_name,categories.category_name FROM `theme` INNER JOIN categories ON theme.category_id=categories.category_id where theme.status='0' ";

               $sql = "SELECT theme.theme_id,theme.category_id,theme.theme_name,categories.category_name FROM `theme` INNER JOIN categories ON theme.category_id=categories.category_id where theme.status='0' $qry limit $page,$per_page";
              $getsql = mysqli_query($conn, $sql);
              $sn = 1 + $page;
              $cat = 1;
              $a = 1;
              $b = 1;
              $c = 1;
              $d = 1;
              while ($user = mysqli_fetch_array($getsql)) {
                $theme_id = $user['theme_id'];
                $category_id = $user['category_id'];  ?>
                <tr>
                  <td><?= $sn++; ?></td>
                  <td><?= ucfirst($user['category_name']) ?> <input type="hidden" value="<?= $user['category_name'] ?>" class="cat<?= $cat++; ?>" /> <input type="hidden" value="<?= $user['category_id'] ?>" class="cata<?= $c++; ?>" /> </td>
                  <td><?= ucfirst($user['theme_name']) ?> <input type="hidden" value="<?= $user['theme_name'] ?>" class="the<?= $a++; ?>" /> <input type="hidden" value="<?= $user['theme_id'] ?>" class="thea<?= $d++; ?>" /> </td>
                  <td><a href="" class="btn-sm btn-primary" title="Add Category" onclick="return editTheme(<?= $b++; ?>)" data-toggle="modal" data-target="#exampleModalEdit" data-backdrop="static" data-keyboard="false" data-whatever="@fat"><i class="fa fa-pencil-square-o"></i></a> </td>

                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </section>
      </div>
    </div>
    <div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
      <div class="col-md-10">
        <div class="d-flex align-items-center justify-content-between" id="pagination">
          <?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
        </div>
      </div>
      <?php
      $_SESSION['header_column'] = "Information Area,Theme";
      $_SESSION['db_column'] = "category_name,theme_name";
      ?>
      <div class=" col-md-2 " style="margin-bottom: 0rem!important; padding-top: 10px">
        <a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
          <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
      </div>

    </div>


    <!-- page end-->
  </section>
</section>
<!--main content end-->
<?php
$mqcategory = mysqli_query($conn, "SELECT category_id,category_name FROM `categories`");
?>
<?php
if (isset($_POST['addcategory'])) {
  $theme = $_POST['theme'];
  $category = $_POST['category'];
  $add_theme = mysqli_query($conn, "INSERT INTO theme SET theme_name='" . $theme . "',category_id='" . $category . "'");
  if ($add_theme) {
    $_SESSION['status'] = "add-category";
    echo "<script>window.location.href='theme_list.php';</script>";
  }
}

if (isset($_POST['Edittheme'])) {
  $themeid = $_POST['themeid'];
  $category_id = $_POST['category_name'];
  $theme = $_POST['theme'];
  // echo "Update theme SET theme_name='".$theme."',category_id='".$category_id."' WHERE theme_id='" . $themeid . "' ";die();
  $add_theme = mysqli_query($conn, "Update theme SET theme_name='" . $theme . "',category_id='" . $category_id . "' WHERE theme_id='" . $themeid . "' ");
  if ($add_theme) {
    $_SESSION['statuss'] = "Edit-theme";
    echo "<script>window.location.href='theme_list.php';</script>";
  }
}


?>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="exampleModalLabel" style="color:#394A59;">Theme</h1>
		
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <select name="category" class="form-control">
            <option value="">Select Category</option>
              <?php foreach ($mqcategory as $mqcategorys) { ?>
                <option value="<?= $mqcategorys['category_id'] ?>"><?= $mqcategorys['category_name'] ?></option>
              <?php } ?>
            </select><br>
            <input type="text" name="theme" placeholder="Theme" class="form-control" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="addcategory" value="submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="exampleModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="exampleModalLabel" style="color:#394A59;">Edit Theme</h1>
		
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="category_idss" class="ecateid form-control" />
            <input type="hidden" name="category_id" class="ecateidaa form-control" />
            <input type="hidden" name="category_name" placeholder="Category Name" class="ecate form-control" disabled /><br>
            <select name="category_name" class="ecate form-control">
            <option value="">Select Category</option>
              <?php foreach ($mqcategory as $mqcategorys) { ?>
                <option value="<?= $mqcategorys['category_id'] ?>"><?= $mqcategorys['category_name'] ?></option>
              <?php } ?>
            </select><br>
            <input type="hidden" name="themeid" class="them111 form-control" />
            <input type="hidden" name="themeidsss" class="theid form-control" />
            <input type="text" name="theme" placeholder="Theme" class="the form-control" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="Edittheme" value="submit">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include_once('includes/footer.php'); ?>
<script>
  function editTheme(val) {
    // alert(val);
    var cata = $(".cata" + val).val();
    var thea = $(".thea" + val).val();
    $(".ecateidaa").attr("value", cata);
    $(".them111").attr("value", thea);
    // alert(cata);
    var cat = $(".cat" + val).val();
    var the = $(".the" + val).val();
    // alert(cat);
    $(".ecateid").val(val);
    $(".ecate").val(cata);

    $(".theid").attr("value", val);
    $(".the").attr("value", the);
  }
</script>
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<?php
if ($_SESSION['status'] == 'add-theme' && $_SESSION['status'] != '') { ?>
  <script>
    toastr.success('Category Added Successfully..!', 'Success Alert', {
      timeOut: 5000
    });
  </script>
<?php $_SESSION['status'] = '';
}
?>

<?php
if ($_SESSION['statuss'] == 'Edit-theme' && $_SESSION['statuss'] != '') { ?>
  <script>
    toastr.success('Theme Updated Successfully..!', 'Success Alert', {
      timeOut: 5000
    });
  </script>
<?php $_SESSION['statuss'] = '';
}
?>