<aside>
	<div id="sidebar" class="nav-collapse">
		<!-- sidebar menu start-->
		<ul class="sidebar-menu">
			<?php
			$user_id = $_SESSION['user_id'];

			$sqlMainMenuQry = mysqli_query($conn, "select name,icon,page,class_name,menu_id,parent_id from menu_master where parent_id=0 and status=0 and menu_id in(select distinct(menu_id) from menu_role where role_id='" . $_SESSION['role_id'] . "' and user_id='" . $user_id . "' and status='0' ) ORDER BY sequence ASC");
			while ($dataMainMenuQry = mysqli_fetch_object($sqlMainMenuQry)) {
				//echo "select name,page,menu_id,class_name,parent_id from menu_master where status=0 and parent_id='".$dataMainMenuQry->menu_id."' and menu_id in(select distinct(menu_id) from menu_role where role_id='".$_SESSION['role_id']."'  and user_id='".$user_id."'  and status='0')";
				// echo"select name,page,menu_id,class_name,parent_id from menu_master where status=0 and parent_id='" . $dataMainMenuQry->menu_id . "' and menu_id in(select distinct(menu_id) from menu_role where role_id='" . $_SESSION['role_id'] . "'  and user_id='" . $user_id . "'  and status='0')";
				$sqlSubMenuQry = mysqli_query($conn, "select name,page,menu_id,class_name,parent_id from menu_master where status=0 and parent_id='" . $dataMainMenuQry->menu_id . "' and menu_id in(select distinct(menu_id) from menu_role where role_id='" . $_SESSION['role_id'] . "'  and user_id='" . $user_id . "'  and status='0')");
				$submenucount = mysqli_num_rows($sqlSubMenuQry);
				if ($submenucount > 0) { ?>

					<li class="sub-menu">
						<a href="javascript:" class="">
							<i class="<?= $dataMainMenuQry->icon ?>"></i>
							<span><?= $dataMainMenuQry->name ?></span>
							<span class="menu-arrow arrow_carrot-right"></span>
						</a>
						<ul class="sub">
							<?php while ($dataSubMenuQry = mysqli_fetch_object($sqlSubMenuQry)) { ?>
								<li><a class="<?= $dataSubMenuQry->class_name ?>" href="<?= base_url(); ?><?= $dataSubMenuQry->page ?>"><?= $dataSubMenuQry->name ?></a></li>
							<?php }
							echo "</ul></li>";
						} else { ?>

							<li class="">
								<a href="<?= base_url(); ?><?= $dataMainMenuQry->page ?>" class="">
									<i class="<?= $dataMainMenuQry->icon ?>"></i>
									<span><?= $dataMainMenuQry->name ?></span>
								</a>
							</li>
							<!--<li class="">
				<a class="" href="dashboard.php">
				<i class="icon_house_alt"></i>
				<span>Dashboard</span>
				</a>
			</li>-->
					<?php }
					}
					?>
						</ul>
	</div>
</aside>