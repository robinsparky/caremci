<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php 
	$appointment_options=theme_setup_data(); 
	$header_setting = wp_parse_args(  get_option( 'appointment_options', array() ), $appointment_options);
	if($header_setting['upload_image_favicon']!=''){ ?>
	<link rel="shortcut icon" href="<?php  echo $header_setting['upload_image_favicon']; ?>" /> 
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?> >

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
			<div class="<?php appointment_post_layout_class(); ?>" >

				<img src="<?php echo home_url(); ?>/wp-content/uploads/CARE-Landing-Page.png" usemap="#program">

			</div>
		 <map name="program">
		 <?php
		 	$passSite = str_replace( "care", "pass", home_url());
		 ?>
			<area shape="rect" coords="259,523,605,916" alt="PASS" href="<?php echo $passSite;?>">
			<area shape="rect" coords="673,523,1020,916" alt="MCI" href="<?php echo home_url();?>/welcome">
		 </map>
		<!-- /MCI or PASS Area -->	
		</div>
	</div>
</div>
