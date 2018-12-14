<div id="post-<?php the_ID(); ?>" <?php post_class('blog-lg-area-left'); ?>>
	<div class="media">						
	<?php //appointment_aside_meta_content(); ?>
		<div class="media-body">
			<?php // Check Image size for fullwidth template
				 appointment_post_thumbnail('','img-responsive');
				 //appointment_post_meta_content();
				 //NOTE: Role must be defined in option called 'care_roles_that_watch' to view a webinar
				 $ok = false;
				 if (is_user_logged_in()) {
					$currentUser = wp_get_current_user();					
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
				 $videoUrl = get_post_meta( $postid, Course::VIDEO_META_KEY, true ); 
				 error_log( __FILE__ . ": Postid='$postid'; Video Url='$videoUrl'");
				?>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				
				<video id="<?php the_ID(); ?>" data-name="<?php the_Title()?>" width="600"  
					poster="http://devel.care4nurses.org/wp-content/uploads/cropped-CARE_WordPress_Icon_512x512.png">
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
					<button id="makenormal">Normal</button>		-->		
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