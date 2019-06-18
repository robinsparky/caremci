<div id="post-<?php the_ID(); ?>" <?php post_class('blog-lg-area-left'); ?>>
	<div class="media">						
	<?php //appointment_aside_meta_content(); ?>
		<div class="media-body">
			<?php // Check Image size for fullwidth template
				 appointment_post_thumbnail('','img-responsive');
				 //appointment_post_meta_content();
				 //NOTE: Must be a member of the site to view a webinar
				 $ok = false;
				 if (is_user_logged_in()) {

					$currentUser = wp_get_current_user();

					/*** DEBUGGING *********************************************** */
					// $blog_id = get_current_blog_id();
					// $user_login = $currentUser->user_login;
					// error_log(__FILE__ . " User Login: $user_login; Blog id=$blog_id" );
					// //error_log(wp_debug_backtrace_summary());
					
					// if( ! empty( $currentUser->roles) ) {
					// 	foreach( $currentUser->roles as $r ) {
					// 		error_log("******************************Has role: $r");
					// 	}
					// }
					// else {
					// 	error_log("*******************************Has No Roles!");
					// }
				
					// $user_query = new WP_User_Query( array( 'blog_id' => $blog_id, 'role' => 'Administrator' ) );
					// // User Loop
					// error_log("Admin Users in blog id 1");
					// if ( ! empty( $user_query->get_results() ) ) {
					// 	foreach ( $user_query->get_results() as $user ) {
					// 		error_log($user->user_login);
					// 	}
					// } else {
					// 	error_log("No admin users found for given blog id: $blog_id.");
					// }
					
					// $wp_roles = wp_roles();
					// //error_log( print_r($wp_roles, true));
					// $user_roles = get_option( 'wpsc_user_roles', array() );
					// error_log("User Roles:");
					// error_log( print_r($user_roles, true) );
					// $cap_key = $currentUser->cap_key;
					// error_log("Current Users cap_key='$cap_key'");
					// $meta = get_user_meta($currentUser->ID, $currentUser->cap_key, true);
					// error_log( print_r($meta, true) );
					//error_log(print_r($currentUser, true));

					/*****************END DEBUGGING ************************************** */
					
					$rolesWatch = esc_attr( get_option('care_roles_that_watch') );
					$rolesWatchArr = explode( ",", $rolesWatch );
					foreach( $rolesWatchArr as $roleName ) {
						if( in_array( $roleName, $currentUser->roles ) ) {
							$ok = true;
							break;
						}
					}
				}
				 $postid = get_the_ID();
				 $videoUrl = get_post_meta( $postid, Webinar::VIDEO_META_KEY, true ); 
				 error_log( __FILE__ . ": Postid='$postid'; Video Url='$videoUrl'");
				?>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				
				<video id="<?php the_ID(); ?>" data-name="<?php the_Title()?>" width="600"  
					poster="<?php get_home_url(); ?>/wp-content/uploads/cropped-CARE_WordPress_Icon_512x512.png">
					<source src="<?php echo $videoUrl ?>" type="video/mp4">
					Your browser does not support HTML5 video.
				</video>
				<?php 
					if( $ok ) {
				?>
				<div class="webinar-buttons">
					<button id="play">Play</button>
					<!-- <button id="restart">Restart</button>
					<button id="makebig">Big</button>
					<button id="makesmall">Small</button>
					<button id="makenormal">Normal</button> -->					
					<button type="button" id="full-screen">Full-Screen</button>
				</div>

				<ul class="webinar-controls">
					<li><label for="progress">Progress</label><progress class="webinar-progress" id="progress" value="0"></progress></li>
					<li><label for="seek-bar">Seek</label><input type="range" id="seek-bar" value="0"></li>
					<li><label for="volume-bar">Volume</label><input type="range" id="volume-bar" min="0.0"  max="1.0" step="0.1"></li>
				</ul>

				<?php
				}
				// call editor content of post/page	
				the_content( __('Read More', 'appointment' ) );
				wp_link_pages( );
			   ?>
		</div>
	 </div>
	 <div id='care-resultmessage'></div>
</div>