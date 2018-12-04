<div id="post-<?php the_ID(); ?>" <?php post_class('blog-lg-area-left'); ?>>
	<div class="media">						
	<?php //appointment_aside_meta_content(); ?>
		<div class="media-body">
			<?php // Check Image size for fullwidth template
				 appointment_post_thumbnail('','img-responsive');
				 appointment_post_meta_content(); 
				 $price = get_post_meta( get_the_ID(), Course::PRICE_META_KEY, true );
				 $duration = get_post_meta( get_the_ID(), Course::DURATION_META_KEY, true );
				 $needsApproval = get_post_meta( get_the_ID(), Course::NEEDS_APPROVAL_META_KEY, true );
				 $instructions = $needsApproval === 'yes' ? 'Note: Requires case manager approval' : '';
				?>
				
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<h4><span>Price: $<?php echo $price ?></span>
					&nbsp;<span>Duration: <?php echo $duration ?> hours</span>
					&nbsp;<span><?php echo $instructions ?></span>
					</h4>
				<?php $courseId = get_the_ID(); $allSessions = CourseSession::getCourseSessions( $courseId );
					error_log(__FILE__ . " courseId=$courseId");
					error_log( print_r( $allSessions, true ) );
				?>
				<?php if(count( $allSessions ) > 0 ) { ?>
					<table class="course_sessions"> 
						<caption>Course Sessions</caption>
						<thead>
						<tr>
							<th>Location</th>
							<th>Start Date</th>
							<th>Start Time</th>
							<th>End Date</th>
							<th>End Time</th>
							<th>Registration</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach( $allSessions as $session ) { ?>
							<tr>
								<td><?php echo $session['location_name']?></td>
								<td><?php echo $session['event_start_date']?></td>
								<td><?php echo $session['event_start_time']?></td>
								<td><?php echo $session['event_end_date']?></td>
								<td><?php echo $session['event_end_time']?></td>
								<td>
								<?php
									$user = wp_get_current_user();
									if ( is_user_logged_in() 
									     && ( in_array('um_caremember', $user->roles) || in_array('administrator', $user->roles))
										) { 
								    ?>
									<button id="carecourse_register" type="button" 
										data-courseId="<?php echo get_the_ID(); ?>" 
										data-courseName="<?php echo get_the_title()?>" 
										data-startDate="<?php echo $session['event_start_date'] ?>"
										data-startTime="<?php echo $session['event_start_time']?>"
										data-endDate="<?php echo $session['event_end_date'] ?>"
										data-endTime="<?php echo $session['event_end_time']?>"
										data-sessionLocation = "<?php echo $session['location_name']?>"
										data-sessionSlug="<?php echo site_url('events') . '/'. $session['event_slug']?>">
										<?php echo __("Register", CARE_TEXTDOMAIN ) ?>
									</button>
									<?php }	else {
										echo __("CARE members only", CARE_TEXTDOMAIN )?>
									<?php } ?>
									<?php 
									if($needsApproval === 'yes') {
										echo '<input id="passwordrequired" type="hidden" name="coursepassword" value="passwordrequired" />';
									}
									?>
								</td>
							</tr>
					<?php } ?>
						</tbody>
					</table>
				<?php } ?>
				
				<?php		
				// call editor content of post/page	
				the_content( __('Read More', 'appointment' ) );
				wp_link_pages( );
			   ?>
		</div>
	 </div>
</div>