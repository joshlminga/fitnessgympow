<div class="col-sm-12 col-md-9 col-lg-8 col-xl-7">
	<div class="card overflow-hidden">
		<div class="bg-login text-center">
			<div class="bg-login-overlay"></div>
			<div class="position-relative">
				<h5 class="text-white font-size-20"><?= $brand_name; ?></h5>
				<p class="text-white-50 mb-0">Register Your Account</p>

				<!-- Banner -->
				<?php $this->load->view("themes/$theme_name/menus/log/banner"); ?>
			</div>
		</div>
		<div class="card-body pt-5">
			<div class="p-2">
				<form action="<?= site_url($form_register) ?>" class="form-horizontal" method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">

					<?= (!is_null($notify) && !empty($notify)) ? $notify : ''; ?>

					<!-- First & Last Name -->
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								<label for="firstname" class="sks-required">First Name</label>
								<input type="text" class="form-control" id="firstname" placeholder="Enter first name" name="first_name" value="<?= set_value('first_name'); ?>">
							</div>
							<span class="error"><?= form_error('first_name') ?></span>
						</div>

						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								<label for="lastname">Last Name</label>
								<input type="text" class="form-control" id="lastname" placeholder="Enter last name" name="last_name" value=" <?= set_value('last_name'); ?>">
							</div>
							<span class="error"><?= form_error('last_name') ?></span>
						</div>
					</div>

					<!-- Phone & Email -->
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								<label for="userphone" class="sks-required">Phone</label>
								<input type="text" class="form-control" id="userphone" placeholder="Enter phone" name="user_mobile" value="<?= set_value('user_mobile'); ?>">
							</div>
							<span class="error"><?= form_error('user_mobile') ?></span>
						</div>

						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								<label for="useremail">Email</label>
								<input type="email" class="form-control" id="useremail" placeholder="Enter email" name="user_email" value="<?= set_value('user_email'); ?>">
							</div>
							<span class="error"><?= form_error('user_email') ?></span>
						</div>
					</div>
					<!-- Password & Confirm Password -->
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								<label for="userpassword" class="sks-required">Password</label>
								<input type="password" class="form-control" require id="userpassword" placeholder="Enter password" name="user_password" value="<?= set_value('user_password'); ?>">
							</div>
							<span class="error"><?= form_error('user_password') ?></span>
						</div>

						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								<label for="userpassword" class="sks-required">Confirm Password</label>
								<input type="password" class="form-control" id="userpassword" placeholder="Enter confirm password" name="user_password_confirm" value="<?= set_value('user_password_confirm'); ?>">
							</div>
							<span class="error"><?= form_error('user_password_confirm') ?></span>
						</div>
					</div>

					<div class="mt-4">
						<button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Register</button>
					</div>

					<div class="mt-4 text-center">
						<p class="mb-0">By registering you agree to the <a href="#" class="text-primary">Terms &amp; Conditions</a></p>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="mt-5 text-center">
		<p>Already have an account ? <a href="<?= site_url('signin'); ?>" class="font-weight-medium text-primary"> Login</a> </p>
		<!-- Copyright -->
		<?php $this->load->view("themes/$theme_name/menus/log/copyright"); ?>
	</div>
</div>
