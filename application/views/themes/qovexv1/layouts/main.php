<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Title -->
	<title><?= $site_title; ?></title>

	<!-- SEO -->
	<?php if ($site_global == 'any') : ?>
		<meta name="description" content="<?= $description; ?>">
		<meta name="keywords" content="<?= $keywords; ?>">
		<meta name="author" content="<?= $site_title; ?>">
		<meta name="robots" content="<?= $site_robots; ?>">
		<?= stripcslashes($seo_data); ?>
	<?php endif ?>


	<!-- Main Header -->
	<?php $this->load->view("themes/$theme_name/includes/head"); ?>
	<!-- End Main Header -->

	<!-- Custom Css-->
	<link href="<?= base_url($customcss); ?>/style.min.css?v=<?= time(); ?>" id="customcss-style" rel="stylesheet" type="text/css" />

	<!-- Include Head -->
	<?php $this->load->view("$theme_dir/functions/incl_head"); ?>
	<!-- End Include Head -->
</head>

<body data-layout="detached" data-topbar="colored">
	<div class="container-fluid">
		<!-- Begin page -->
		<div id="layout-wrapper">
			<header id="page-topbar">
				<div class="navbar-header">
					<div class="container-fluid">
						<?php $this->load->view("themes/$theme_name/menus/nav/page-topbar"); ?>
					</div>
				</div>
			</header>

			<!-- ========== Left Sidebar Start ========== -->
			<div class="vertical-menu">
				<div class="h-100">
					<?php $this->load->view("themes/$theme_name/menus/nav/vertical-menu"); ?>
				</div>
			</div>
			<!-- Left Sidebar End -->

			<!-- ============================================================== -->
			<!-- Start right Content here -->
			<!-- ============================================================== -->
			<div class="main-content">
				<div class="page-content">
					<!-- start page title -->
					<div class="row">
						<div class="col-12">
							<?php $this->load->view("themes/$theme_name/menus/page-title"); ?>
						</div>
					</div>
					<!-- end page title -->

					<!-- Main Page -->
					<?php $this->load->view("themes/$theme_name/pages/$site_page"); ?>
					<!-- End Main Page -->
				</div>
				<!-- End Page-content -->

				<!-- Footer -->
				<footer class="footer">
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-6">
								<script>
									document.write(new Date().getFullYear());
								</script>
								&copy; <?= $product_name; ?>.
							</div>
							<div class="col-sm-6">
								<div class="text-sm-right d-none d-sm-block">
									Developed by <?= $coder_name; ?> <<?= $dev_name; ?>>
								</div>
							</div>
						</div>
					</div>
				</footer>
			</div>
			<!-- end main content-->
		</div>
		<!-- END layout-wrapper -->
	</div>
	<!-- end container-fluid -->

	<!-- Right bar overlay-->
	<div class="rightbar-overlay"></div>

	<!-- Main Footer -->
	<?php $this->load->view("themes/$theme_name/includes/footer"); ?>
	<!-- End Main Footer -->

	<!-- Include Footer -->
	<?php $this->load->view("$theme_dir/functions/incl_footer"); ?>
	<!-- End Include Footer -->
</body>

</html>
