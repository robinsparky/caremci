<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://use.typekit.net/otd8wni.css">
	<?php 
	$appointment_options=theme_setup_data(); 
	$header_setting = wp_parse_args(  get_option( 'appointment_options', array() ), $appointment_options);
	if( $header_setting['upload_image_favicon'] != '' ) { ?>
	<link rel="shortcut icon" href="<?php  echo $header_setting['upload_image_favicon']; ?>" /> 
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<div class="graybox"></div>

<?php if ( get_header_image() != '') {?>
<div class="header-img">
	<div class="header-content">
		<?php if($header_setting['header_one_name'] != '') { ?>
		<h1><?php echo $header_setting['header_one_name'];?></h1>
		<?php }  if($header_setting['header_one_text'] != '') { ?>
		<h3><?php echo $header_setting['header_one_text'];?></h3>
		<?php } ?>
	</div>
	<img class="img-responsive" src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
</div>
<?php } ?>
<!-- Blog Section with Sidebar -->
<div class="page-builder">
	<div class="container">
		<div class="row">
		 <!-- MCI or PASS Area -->
		 <?php
		 	$passSite = str_replace( "care", "pass", home_url());
		 ?>
			<div class="<?php //appointment_post_layout_class(); ?> mainlanding" >
			<!-- New Layout -->

				<img class="mainlanding" src="<?php echo home_url(); ?>/wp-content/uploads/CARE_logo_PNG.png">
				<h2>CARE Centre can help you achieve nursing registration and get started on your Canadian nursing career.
				<hr class="mainlanding">
				</h2>
				
				<article class="passintro">
				<p>Are you an Internationally Educated Nurse (IEN) accepted for immigration to Canada? Please visit our 
					<span>Pre-Arrival Supports and Services (PASS)</span> program.</p>
					<a href="<?php echo $passSite;?>">
						<img src="<?php echo home_url(); ?>/wp-content/uploads/PASS_Intro.png">
					</a>
				</article>
				<article class="starsintro"><p>Are you an IEN who has arrived in Ontario? Please visit the website for our 
					<span>Supports, Training &amp; Access to Regulated-employment Services (STARS)</span> program.</p>
					<a  href="<?php echo home_url();?>/welcome">
						<img src="<?php echo home_url(); ?>/wp-content/uploads/STARS_Intro.png">
					</a>
				</article>
			</div>
		<!-- /MCI or PASS Area -->	
		</div>
	</div>
</div>
