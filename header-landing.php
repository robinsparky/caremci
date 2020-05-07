<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-157271171-1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-157271171-1');
	</script>
	
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://use.typekit.net/otd8wni.css">

	<?php 
	$appointment_options=theme_setup_data(); 
	$header_setting = wp_parse_args(  get_option( 'appointment_options', array() ), $appointment_options);
	if($header_setting['upload_image_favicon']!=''){ ?>
	<link rel="shortcut icon" href="<?php  echo $header_setting['upload_image_favicon']; ?>" /> 
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!--Get needed scripts for the iframe -->
	<?php wp_enqueue_script('care-landing-page-iframe', get_stylesheet_directory_uri()."/js/care-landingpage.js", ['jquery','jquery-ui-draggable','jquery-ui-droppable', 'jquery-ui-sortable']); ?>

	<style>
		h2.landingheader, h3.landingheader, h4.landingheader, h5.landingheader {
			color: #7ac142 !important;
		}
		strong {
			color: #7ac142 !important;
		}
	</style> 
	
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
<div class="clearfix"></div>