<?php include_once('includes/config.php'); ?>
<?php define("title","View User | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('mycrypt.php'); ?>
<?php 
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
$client_id=$_SESSION['client_id'];
?>
<?php
// echo "hello";
$userid=(int)$_GET['id'];
if(isset($_REQUEST['id']) && $_REQUEST['id']){
	$getdata = mysqli_query($conn, "SELECT users.user_id,users.name,users.username FROM users where user_id='".$_REQUEST['id']."'");
	$data=mysqli_fetch_array($getdata);	
	
	$functionalsql="SELECT GROUP_CONCAT(DISTINCT(roles.name)) as role_name FROM `functional_role` INNER join roles on functional_role.role_id=roles.id where functional_role.user_id='".$_REQUEST['id']."'";
	$getfunUsers = mysqli_query($conn,$functionalsql);
	$fundata = mysqli_fetch_array($getfunUsers);
}
?>

<?php
    //pagination
    $per_page=10;
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page_url = "?";
	$page_url = isset($_GET['id'])? $page_url."id=".$_GET['id']:$page_url;
	
    $page=0;
    $current_page=1;
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
	$query="select survey_id,created_on,survey_name,survey_data_monitoring_id from survey_data_monitoring where user_id='$userid' $clnt order by survey_data_monitoring_id desc";
	$get_query=mysqli_query($conn,$query);
    $total_record=mysqli_num_rows($get_query);
    $total_pages=ceil($total_record/$per_page);
?>

<style>
  .cls{
    color:white;
  }
  .row {
		margin-top: 10px;
	}
.pagination1,.pagination2 {
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	padding-left: 0;
	list-style: none;
	border-radius: 0.25rem;
	margin-top: 10px;
}
.pagination1 > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
    z-index: 2;
    color: #ffffff;
    background-color: #8cc63f;
    border-color: #8cc63f;
    cursor: default;
}
.pagination2 > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
    z-index: 2;
    color: #ffffff;
    background-color: #8cc63f;
    border-color: #8cc63f;
    cursor: default;
}
.pagination1 > li > a, .pagination > li > span {
    position: relative;
    float: left;
    padding: 6px 12px;
    line-height: 1.428571429;
    text-decoration: none;
    background-color: #ffffff;
    border: 1px solid #dddddd;
    margin-left: -1px;
}
.pagination2 > li > a, .pagination > li > span {
    position: relative;
    float: left;
    padding: 6px 12px;
    line-height: 1.428571429;
    text-decoration: none;
    background-color: #ffffff;
    border: 1px solid #dddddd;
    margin-left: -1px;
}

  </style>
<!--main content start-->
    <section id="main-content">
        <section class="wrapper">
			<div class="row">
				<div class="col-lg-12">
					<ol class="breadcrumb">
                        <li><i class="fa fa-list"></i>List Users</li> <!-- <a href="user-list.php"></a> -->
                        <li><i class="fa fa-eye"></i></i>View User</li>
                    </ol>
                </div>
			</div>
		<!-- page start-->
		<div class="row">
		<div class="card" style="height:;">
		<div class="col-sm-3">
			<section class="panel" style="padding:15px;">
			<?php $clnt='';
				if($_SESSION['role_id']=="3"){
					$clnt = " and client_id='".$_SESSION['client_id']."' ";
				}
				$consql=mysqli_query($conn,"select COUNT(survey_data_monitoring.survey_name_id) as total_survey from survey_data_monitoring where user_id='$userid' $clnt ");
				$count_data=mysqli_fetch_array($consql);
			?>
			
			<!-- <h4 style="font-size:30px;"><b>Total Survey Monitored : <strong class="text-primary" style="margin-left:70px;"><?=$count_data['total_survey'];?></strong></b></h4>-->
               <h4><span style="font-weight: bold; font-size:22px;">Total Collected Data: <?=$count_data['total_survey'];?></span></h4>
			   <hr>            
				<div class="profile-usermenu">
					<ul class="nav">
						<li><p style="color:#394A59;"><strong>Name: </strong><?=$data['name']?></p></li><hr>
						<li><p style="color:#394A59;"><strong>Username: </strong><?=$data['username']?></p></li><hr>
						<li><p style="color:#394A59;"><strong>Functional Type: </strong><?=$fundata['role_name']?></p></li><hr>
						
					</ul>
				</div>
			</section>
		  </div>
		  </div>
		  <!--start-->
		  
		<div class="col-md-9">
            <div class="profiles-data">  
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home">Collected Form</a></li>
					<li><a data-toggle="tab" href="#menu1" >Assigned Form</a></li>
					
				</ul>
			<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<div class="table-responsive mb-0 mt-2" id="jar">
							<section class="panel" style="padding:15px;">
								<table class="table table-striped">
									<thead>
									<tr style="background: #394a59;">
										<th class="cls">S.No</th>
										<th class="cls">Form ID</th>
										<th class="cls">Date</th>
										<th class="cls">Form Name</th>
										<th class="cls">Status</th>
										<th class="cls">Action </th>
									</tr>
									</thead>
									<?php
									//echo "select survey_id,created_on,survey_name,survey_data_monitoring_id from survey_data_monitoring where user_id='$userid' $clnt order by survey_data_monitoring_id DESC ";
									$getuser=mysqli_query($conn,"select survey_id,created_on,full_json,survey_name,survey_data_monitoring_id,survey_data_monitoring.survey_status from survey_data_monitoring where user_id='$userid' $clnt order by survey_data_monitoring_id DESC ");
									$sn=1+$page;
									if(mysqli_num_rows($getuser)>0){
										while($user=mysqli_fetch_array($getuser)){ 
										$DecryptedJson = $mcrypt->decrypt($user['full_json']);
										$full_json = json_decode($DecryptedJson, true);
										$sequence_unique_id=$full_json[sequence_unique_id];
										?>
											<tbody>
												<tr class="content">
													<td><?= $sn++; ?></td>
													<td><?php if($sequence_unique_id!=''){
															echo $sequence_unique_id;
															}else{
																echo $user['survey_id'];
															}
													?></td>
													<td><?=date('d-M-Y,h:i:s A',strtotime($user['created_on']));?></td>
													<td><?= $user['survey_name'] ?></td>
													<td>
														<?php
															if ($user['survey_status']==1){
														?>
															<span class="label label-success">Submitted</span>
														<?php
															}elseif($user['survey_status']==3){
														?>
															<span class="label label-danger">Terminated</span>
														<?php
															}elseif($user['survey_status']==5){
														?>
															<span class="label label-success">Approved</span>
														<?php
															}elseif($user['survey_status']==6){
														?>
															<span class="label label-warning">Re-submitted</span>
														<?php
															}elseif($user['survey_status']==4){
														?>
															<span class="label label-primary">Send for review</span>
														<?php
															}elseif($user['survey_status']==7){
														?>
															<span class="label label-danger">Rejected</span>
														<?php
															}
														?>
													</td>
													<td>
													<!--<a href="view_survey.php?id=<?php //echo $user['survey_data_monitoring_id'];?>" class="btn btn-success btn-sm">View Form</a>-->
													<a href="view_survey.php?id=<?=$user['survey_data_monitoring_id'];?>" class="btn btn-success btn-sm">View Form</a>
									  
													</td>
												</tr>
											</tbody>	
										<?php 
										}
									} else{ ?>
										<tr>
									   <td colspan="12" align="center" >No record Found !! You haven't collected any data</td>
									</tr>
									<?php } ?>
								</table>
								<nav>
									<ul class="pagination1 justify-content-end pagination-sm mt-2">
									</ul>
                                </nav>
							</section>
							<!--<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
								<div class="col-md-10">
								  <div class="d-flex align-items-center justify-content-between" id="pagination">
									 <?=paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>                               
								 </div>
								 </div>
							</div>-->
						</div>             
				    </div>
					<div id="menu1" class="tab-pane fade">
						<div class="table-responsive mb-0 mt-2" id="jar2">
							<section class="panel" style="padding:15px;">
							<!--<h4><strong>Total Record (s): <?php// echo $total_record?></strong></h4>-->
								<table class="table table-striped">
									<thead>
									<tr style="background: #394a59;">
										<th class="cls">S.No</th>
										<th class="cls">Form Name</th>
										<th class="cls"># of Interviews</th>
										<th class="cls">Action </th>
									</tr>
									</thead>
									<?php
									
									if(isset($_REQUEST['id'])){
									//echo "select DISTINCT(assign_survey.survey_id) as survey_id,assign_survey.id, survey.survey_name from assign_survey inner join survey on assign_survey.survey_id=survey.id where assign_survey.user_id='".$_REQUEST['id']."' AND assign_survey.status='0' order by assign_survey.id desc";	
									 $sqldata=mysqli_query($conn,"select DISTINCT(assign_survey.survey_id) as survey_id,assign_survey.id, survey.survey_name from assign_survey LEFT join survey on assign_survey.survey_id=survey.id where assign_survey.user_id='".$_REQUEST['id']."' AND assign_survey.status='0' order by assign_survey.id desc");
									}
									$sn=1+$page;
									if(mysqli_num_rows($sqldata)>0){
									while($data=mysqli_fetch_array($sqldata)){ ?>
										
									<tbody>
										<tr class="content2" id="sid-<?=$data['id'];?>">
											<td><?= $sn++; ?></td>
											<td><?= $data['survey_name'] ?></td>
											<td><?php echo getcount($conn, 'survey_data_monitoring', 'survey_data_monitoring_id', 'survey_name_id', $data['survey_id'], 'user_id', $_REQUEST['id']) ?></td>
											<td>
											<a href="javascript:void(0);" data-id="<?=$data['id'];?>" class="btn btn-danger btn-sm delForm"><i class="fa fa-trash" alt="delete" title="Delete Survey"></i></a>
											</td>
										</tr>
									<?php 
										}
									} else{ ?>
										<tr>
									   <td colspan="12" align="center" >No record Found !! You do not have assigned forms</td>
									</tr>
									<?php } ?>
									</tbody>
								</table>
								<nav>
									<ul class="pagination2 justify-content-end pagination-sm mt-2">
									</ul>
                                </nav>
							</section>
							
						</div>             
					</div>
					
				</div>
           </div>
        </div>
		</div>
		<!-- page end-->
		</section>
	</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>

<script>

$(".delForm").on("click", function(e){
	let surveyid = $(this).data("id");
	e.preventDefault();
	Swal.fire({
	  title: 'Are you sure to Delete this Form?',
	   icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#449A97',
	  cancelButtonColor: '#449A97',
	  confirmButtonText: 'Delete'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url:"ajax/get_ajax.php",
				type:"post",
				data:{surveyIdview:surveyid},
				success:function(res){
					var ress = JSON.parse(res);
					if(ress.status=="1"){
						$("#sid-"+surveyid).hide();
						Swal.fire({
						  title: 'Form deleted successfully',
						  icon: 'success',
						  confirmButtonColor: '#449A97',
						  confirmButtonText: 'Ok'
						}) 
						//window.location.reload();
					} 
				}
			})
		}
	});
})
</script>
<script>
// Returns an array of maxLength (or less) page numbers
// where a 0 in the returned array denotes a gap in the series.
// Parameters:
//   totalPages:     total number of pages
//   page:           current page
//   maxLength:      maximum size of returned array
function getPageList(totalPages, page, maxLength) {
	if (maxLength < 5) throw "maxLength must be at least 5";

	function range(start, end) {
		return Array.from(Array(end - start + 1), (_, i) => i + start);
	}

	var sideWidth = maxLength < 9 ? 1 : 2;
	var leftWidth = (maxLength - sideWidth * 2 - 3) >> 1;
	var rightWidth = (maxLength - sideWidth * 2 - 2) >> 1;
	if (totalPages <= maxLength) {
		// no breaks in list
		return range(1, totalPages);
	}
	if (page <= maxLength - sideWidth - 1 - rightWidth) {
		// no break on left of page
		return range(1, maxLength - sideWidth - 1)
			.concat([0])
			.concat(range(totalPages - sideWidth + 1, totalPages));
	}
	if (page >= totalPages - sideWidth - 1 - rightWidth) {
		// no break on right of page
		return range(1, sideWidth)
			.concat([0])
			.concat(
				range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages)
			);
	}
	// Breaks on both sides
	return range(1, sideWidth)
		.concat([0])
		.concat(range(page - leftWidth, page + rightWidth))
		.concat([0])
		.concat(range(totalPages - sideWidth + 1, totalPages));
}

$(function() {
	// Number of items and limits the number of items per page
	var numberOfItems = $("#jar .content").length;
	var limitPerPage = 10;
	// Total pages rounded upwards
	var totalPages = Math.ceil(numberOfItems / limitPerPage);
	// Number of buttons at the top, not counting prev/next,
	// but including the dotted buttons.
	// Must be at least 5:
	var paginationSize = 7;
	var currentPage;

	function showPage1(whichPage) {
		if (whichPage < 1 || whichPage > totalPages) return false;
		currentPage = whichPage;
		$("#jar .content")
			.hide()
			.slice((currentPage - 1) * limitPerPage, currentPage * limitPerPage)
			.show();
		// Replace the navigation items (not prev/next):
		$(".pagination1 li").slice(1, -1).remove();
		getPageList(totalPages, currentPage, paginationSize).forEach(item => {
			$("<li>")
				.addClass(
					"page-item " +
					(item ? "current-page " : "") +
					(item === currentPage ? "active " : "")
				)
				.append(
					$("<a>")
					.addClass("page-link")
					.attr({
						href: "javascript:void(0)"
					})
					.text(item || "...")
				)
				.insertBefore("#next-page");
		});
		return true;
	}

	// Include the prev/next buttons:
	$(".pagination1").append(
		$("<li>").addClass("page-item").attr({
			id: "previous-page"
		}).append(
			$("<a>")
			.addClass("page-link")
			.attr({
				href: "javascript:void(0)"
			})
			.text("Prev")
		),
		$("<li>").addClass("page-item").attr({
			id: "next-page"
		}).append(
			$("<a>")
			.addClass("page-link")
			.attr({
				href: "javascript:void(0)"
			})
			.text("Next")
		)
	);
	// Show the page links
	$("#jar").show();
	showPage1(1);

	// Use event delegation, as these items are recreated later
	$(
		document
	).on("click", ".pagination1 li.current-page:not(.active)", function() {
		return showPage1(+$(this).text());
	});
	$("#next-page").on("click", function() {
		return showPage1(currentPage + 1);
	});

	$("#previous-page").on("click", function() {
		return showPage1(currentPage - 1);
	});
	$(".pagination1").on("click", function() {
		$("html,body").animate({
			scrollTop: 0
		}, 0);
	});
});
</script>
     <!-- //2nd -->
<script>
// Returns an array of maxLength (or less) page numbers
// where a 0 in the returned array denotes a gap in the series.
// Parameters:
//   totalPages:     total number of pages
//   page:           current page
//   maxLength:      maximum size of returned array
function getPageList1(totalPages, page, maxLength) {
	if (maxLength < 5) throw "maxLength must be at least 5";

	function range(start, end) {
		return Array.from(Array(end - start + 1), (_, i) => i + start);
	}

	var sideWidth = maxLength < 9 ? 1 : 2;
	var leftWidth = (maxLength - sideWidth * 2 - 3) >> 1;
	var rightWidth = (maxLength - sideWidth * 2 - 2) >> 1;
	if (totalPages <= maxLength) {
		// no breaks in list
		return range(1, totalPages);
	}
	if (page <= maxLength - sideWidth - 1 - rightWidth) {
		// no break on left of page
		return range(1, maxLength - sideWidth - 1)
			.concat([0])
			.concat(range(totalPages - sideWidth + 1, totalPages));
	}
	if (page >= totalPages - sideWidth - 1 - rightWidth) {
		// no break on right of page
		return range(1, sideWidth)
			.concat([0])
			.concat(
				range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages)
			);
	}
	// Breaks on both sides
	return range(1, sideWidth)
		.concat([0])
		.concat(range(page - leftWidth, page + rightWidth))
		.concat([0])
		.concat(range(totalPages - sideWidth + 1, totalPages));
}

$(function() {
	// Number of items and limits the number of items per page
	var numberOfItems = $("#jar2 .content2").length;
	var limitPerPage = 10;
	// Total pages rounded upwards
	var totalPages = Math.ceil(numberOfItems / limitPerPage);
	// Number of buttons at the top, not counting prev/next,
	// but including the dotted buttons.
	// Must be at least 5:
	var paginationSize = 7;
	var currentPage;

	function showPage(whichPage) {
		if (whichPage < 1 || whichPage > totalPages) return false;
		currentPage = whichPage;
		$("#jar2 .content2")
			.hide()
			.slice((currentPage - 1) * limitPerPage, currentPage * limitPerPage)
			.show();
		// Replace the navigation items (not prev/next):
		$(".pagination2 li").slice(1, -1).remove();
		getPageList1(totalPages, currentPage, paginationSize).forEach(item => {
			$("<li>")
				.addClass(
					"page-item " +
					(item ? "current-page " : "") +
					(item === currentPage ? "active " : "")
				)
				.append(
					$("<a>")
					.addClass("page-link1")
					.attr({
						href: "javascript:void(0)"
					})
					.text(item || "...")
				)
				.insertBefore("#next-page1");
		});
		return true;
	}

	// Include the prev/next buttons:
	$(".pagination2").append(
		$("<li>").addClass("page-item1").attr({
			id: "previous-page1"
		}).append(
			$("<a>")
			.addClass("page-link1")
			.attr({
				href: "javascript:void(0)"
			})
			.text("Prev")
		),
		$("<li>").addClass("page-item1").attr({
			id: "next-page1"
		}).append(
			$("<a>")
			.addClass("page-link1")
			.attr({
				href: "javascript:void(0)"
			})
			.text("Next")
		)
	);
	// Show the page links
	$("#jar2").show();
	showPage(1);

	// Use event delegation, as these items are recreated later
	$(
		document
	).on("click", ".pagination2 li.current-page:not(.active)", function() {
		return showPage(+$(this).text());
	});
	$("#next-page1").on("click", function() {
		return showPage(currentPage + 1);
	});

	$("#previous-page1").on("click", function() {
		return showPage(currentPage - 1);
	});
	$(".pagination2").on("click", function() {
		$("html,body").animate({
			scrollTop: 0
		}, 0);
	});
});
</script>