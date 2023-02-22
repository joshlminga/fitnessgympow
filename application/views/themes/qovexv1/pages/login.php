<div class="col-md-8 col-lg-6 col-xl-5">
	<div class="card overflow-hidden">
		<div class="bg-login text-center">
			<div class="bg-login-overlay"></div>
			<div class="position-relative">
				<h5 class="text-white font-size-20"><?= $brand_name; ?></h5>
				<p class="text-white-50 mb-0">Sign In</p>
				<!-- Banner -->
				<?php $this->load->view("themes/$theme_name/menus/log/banner"); ?>
			</div>
		</div>
		<div class="card-body pt-5">
			<div class="p-2">
				<form action="<?= site_url($form_login) ?>" class="form-horizontal" method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">

					<!-- Notification -->
					<?= (!is_null($notify) && !empty($notify)) ? $notify : ''; ?>


					<div class="form-group">
						<label for="lognmlognamee" class="sks-required">Email / Phone No.</label>
						<input type="text" class="form-control" id="logname" name="logname" value="<?= set_value('logname'); ?>" placeholder="Enter Email/Phone Number">
						<span class="error"><?= form_error('logname') ?></span>
					</div>

					<div class="form-group">
						<label for="userpassword" class="sks-required">Password</label>
						<input type="password" class="form-control" id="userpassword" name="password" value="<?= set_value('password'); ?>" placeholder="Enter password">
						<span class="error"><?= form_error('password') ?></span>
					</div>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="remeber" name="remember" value="yes" <?= set_checkbox('remember', 'yes'); ?>>
						<label class="custom-control-label" for="remeber">Remember me</label>
						<div class="col-12">
							<span class="error"><?= form_error('remember') ?></span>
						</div>
					</div>

					<div class="mt-3">
						<button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
					</div>

					<div class="mt-4 text-center">
						<a href="#" class="text-muted"><i class="mdi mdi-lock mr-1"></i> Forgot your password?</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="mt-5 text-center">
		<p>Don't have an account ? <a href="<?= site_url('signup'); ?>" class="font-weight-medium text-primary"> Signup now </a> </p>
		<!-- Copyright -->
		<?php $this->load->view("themes/$theme_name/menus/log/copyright"); ?>
	</div>
</div>
