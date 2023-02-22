<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>FITNESS-ONE | NELSON FITNNESS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?= $description; ?>">
	<meta name="keywords" content="">
	<meta name="author" content="<?= $site_title; ?>">
	<meta name="robots" content="no index, no follow">
	<!-- App favicon -->
	<link rel="shortcut icon" href="<?= base_url($theme_assets); ?>/custom/img/favicon.ico" type="image/x-icon">

	<!-- Bootstrap Css -->
	<link href="<?= base_url($theme_assets); ?>/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<!-- Icons Css -->
	<link href="<?= base_url($theme_assets); ?>/css/icons.min.css" rel="stylesheet" type="text/css" />
	<!-- App Css-->
	<link href="<?= base_url($theme_assets); ?>/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<!-- Custom Css-->
	<link href="<?= base_url($customcss); ?>/style.min.css?v=<?= time(); ?>" id="customcss-style" rel="stylesheet" type="text/css" />

</head>

<body style="background-color: #aac3dc !important;">
	<div class="home-btn d-none d-sm-block">
		<a href="<?= site_url(''); ?>" class="text-dark"><i class="fas fa-home h2 sks-color-red"></i></a>
	</div>
	<div class="account-pages my-5 pt-sm-5">
		<div class="container">
			<div class="row justify-content-center">
				<!-- Main Page -->
				<?php $this->load->view("themes/$theme_name/pages/$site_page"); ?>
				<!-- End Main Page -->
			</div>
		</div>
	</div>

	<!-- JAVASCRIPT -->
	<script src="<?= base_url($theme_assets); ?>/libs/jquery/jquery.min.js"></script>
	<script src="<?= base_url($theme_assets); ?>/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url($theme_assets); ?>/libs/metismenu/metisMenu.min.js"></script>
	<script src="<?= base_url($theme_assets); ?>/libs/simplebar/simplebar.min.js"></script>
	<script src="<?= base_url($theme_assets); ?>/libs/node-waves/waves.min.js"></script>
	<script src="<?= base_url($theme_assets); ?>/js/app.js"></script>

</body>

</html>
