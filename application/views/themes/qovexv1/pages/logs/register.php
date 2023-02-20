                <div class="col-md-8 col-lg-6 col-xl-5">
                	<div class="card overflow-hidden">
                		<div class="bg-login text-center">
                			<div class="bg-login-overlay"></div>
                			<div class="position-relative">
                				<h5 class="text-white font-size-20">Nelson Fitness</h5>
                				<p class="text-white-50 mb-0">Register Your Account</p>
                				<a href="<?= site_url(); ?>" class="logo logo-admin mt-4">
                					<img src="<?= base_url($theme_assets); ?>/images/logo-sm-dark.png" alt="" height="30">
                				</a>
                			</div>
                		</div>
                		<div class="card-body pt-5">
                			<div class="p-2">
                				<form class="form-horizontal" action="index.html">

                					<div class="form-group">
                						<label for="useremail">Email</label>
                						<input type="email" class="form-control" id="useremail" placeholder="Enter email">
                					</div>

                					<div class="form-group">
                						<label for="username">Username</label>
                						<input type="text" class="form-control" id="username" placeholder="Enter username">
                					</div>

                					<div class="form-group">
                						<label for="userpassword">Password</label>
                						<input type="password" class="form-control" id="userpassword" placeholder="Enter password">
                					</div>

                					<div class="mt-4">
                						<button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Register</button>
                					</div>

                					<div class="mt-4 text-center">
                						<p class="mb-0">By registering you agree to the Skote <a href="#" class="text-primary">Terms of Use</a></p>
                					</div>
                				</form>
                			</div>
                		</div>
                	</div>
                	<div class="mt-5 text-center">
                		<p>Already have an account ? <a href="<?= site_url('signin'); ?>" class="font-weight-medium text-primary"> Login</a> </p>
                		<p>&copy; <?= (date('Y') == 2022) ? '2023' : '2023 - ' . date('Y'); ?>. Fitness One <i class="mdi mdi-heart text-danger"></i> Powered by Vormia</p>
                	</div>
                </div>
