<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

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
		background: rgb(57,74,89);
		z-index: 99999;
		border-radius: 50%;
		width: 60px;
		height: 60px;
		color: #fff;
		line-height: 46px;
		font-size: 22px;
		transition: all .3s ease-in-out;
	}
	.add-button-bg a:hover{
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
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                       <!-- <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li> -->
                        <li><i class="icon_documents_alt"></i>Device Management</li>
                       <li><i class="fa fa-list"></i>List Device</li>
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
                                    <select class="form-control" name="id" id="id">
                                        <option value="">Select Device Type</option>
                                        
                                    </select>
                                </div>
									<div class="form-group col-md-5"style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
                                    <input type="text" class="form-control" name="name" placeholder="Device Id"></input>
                                </div>
								<div class="form-group col-md-2"style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
									<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
								</div>	
								</div>
                            </form>
                        </div>
						<section class="panel">
						<header class="panel-heading">Total Device(s): 1
						</header>
                        <table class="table table-striped">
                            <thead>
							<tr>
                                <th class="">Sl.No</th>
                                <th class="">Device Id</th>
                                <th class="">Device name</th>
                                <th class="">Version</th>
								<th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>D1236</td>
									<td>Redmi</td>
                                    <td>12 SP1A</td>
									 <td>
									  <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
									 </td>
                                </tr>
                            </tbody>
                        </table>
						</section>
                </div>
            </div>
			<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
				<div class="col-md-10">
				  <div class="d-flex align-items-center justify-content-between" id="pagination">
					                          
				 </div>
				</div>
				
			</div>
			
			
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

<?php include_once('includes/footer.php'); ?>