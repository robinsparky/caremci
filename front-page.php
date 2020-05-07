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
	if( $header_setting['upload_image_favicon'] != '' ) { ?>
	<link rel="shortcut icon" href="<?php  echo $header_setting['upload_image_favicon']; ?>" /> 
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<!-- <div class="graybox"></div> -->

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
<div class="page-builder landingpagebuilder">
	<div class="container">
		<div class="row">
		 <!-- MCI or PASS Area -->
		 <?php
		 	$passSite = str_replace( "care", "pass", home_url());
		 ?>
			<div class="<?php //appointment_post_layout_class(); ?> mainlanding" >
			<!-- New Layout -->

				<img class="mainlanding" src="<?php echo home_url(); ?>/wp-content/uploads/CARE_logo_PNG.png">
				<h3 id="intro" class="mainlanding intro">CARE Centre can help you achieve nursing registration and get started on your Canadian nursing career.
				</h3>
				<div class="centerlandingmenu">
					<?php
						wp_nav_menu( array(  
								'theme_location' => 'landing',
								'container'  => '',
								'menu_class' => 'nav navbar-nav navbar-right star-nav',
								'fallback_cb' => false, //'webriti_fallback_page_menu',
								'container_id' => 'landing-about-us',
								// 'before' => 'Before',
								// 'after' => 'After',
								// 'link_after' => '&amp;',
								// 'link_before' => '&amp;'
								// 'items_wrap'  => $social,
								'walker' => new webriti_nav_walker()
								) );
					?>
				</div>
				<!-- <button id="about-us">About Us</button> -->
				<hr class="mainlanding">

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
					<!-- dev: /wp-content/uploads/STARS-HOME-PAGE-2.gif
						stage: /wp-content/uploads/2019/08/STARS-HOME-PAGE.gif
						live: /wp-content/uploads/2019/09/STARS-HOME-PAGE.gif
						live: /wp-content/uploads/2020/05/STARS-HOME-PAGE-2.gif
					-->
						<img src="<?php echo home_url(); ?>/wp-content/uploads/STARS-HOME-PAGE-2.gif">
					</a>
				</article>
			</div>
		<!-- /MCI or PASS Area -->	
		</div>
	</div>
</div>
<div class="iframe-container">
	<iframe id="iframepage" src="" class="mainlanding care-overlay">
		<p>Your browser does not support iframes.</p>
	</iframe>
</div>
