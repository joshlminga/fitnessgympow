		<div class="user-wid text-center py-4">
			<div class="user-img">
				<img src="<?= base_url($theme_assets); ?>/images/users/avatar-2.jpg" alt="" class="avatar-md mx-auto rounded-circle" />
			</div>

			<div class="mt-3">
				<a href="#" class="text-dark font-weight-medium font-size-16">Patrick Becker</a>
				<p class="text-body mt-1 mb-0 font-size-13">UI/UX Designer</p>
			</div>
		</div>

		<!--- Sidemenu -->
		<div id="sidebar-menu">
			<!-- Left Menu Start -->
			<ul class="metismenu list-unstyled" id="side-menu">
				<li class="menu-title">Menu</li>

				<!-- Menus -->
				<?php $this->load->view("themes/$theme_name/menus/sidebar-menu"); ?>
				<!-- End Menus -->
			</ul>
		</div>
		<!-- Sidebar -->
