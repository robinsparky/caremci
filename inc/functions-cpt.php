<?php
//Register custom post types
require get_stylesheet_directory() . '/inc/class-Webinar.php';
require get_stylesheet_directory() . '/inc/class-Course.php';
require get_stylesheet_directory() . '/inc/class-CourseSession.php';
//require get_stylesheet_directory() . '/inc/class-CourseRegisterByEmail.php';
require get_stylesheet_directory() . '/inc/class-CourseRegisterByEvent.php';

/**
 * Register ajax-based classes
 * */
//ReportCourseProgress::register();
//RecordUserCourseProgress::register();
WatchWebinarProgress::register();
RecordUserWebinarProgress::register();
ManagementReports::register();


/**
 * Customize Event Query using Post Meta
 * 
 * @author Bill Erickson
 * @link https://www.billerickson.net/customize-the-wordpress-query/
 * @param object $query data
 *
 */
function be_event_query( $query ) {
	
	if( $query->is_main_query() && !$query->is_feed() && !is_admin() && $query->is_post_type_archive( '////' ) ) {
		$meta_query = array(
			array(
				'key' => 'be_events_manager_end_date',
				'value' => time(),
				'compare' => '>'
			)
		);
		$query->set( 'meta_query', $meta_query );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'meta_key', 'be_events_manager_start_date' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', '4' );
	}
}
//add_action( 'pre_get_posts', 'be_event_query' );

/**
 * Customize the Query for Care Course Archives
 * @param object $query data
 *
 */
function archive_carecourse_query( $query ) {
    $loc = __FILE__ . '/' . __FUNCTION__;
	error_log("$loc");
	// $strQuery = print_r( $query, true );
	// error_log( $strQuery );
	
	if( $query->is_main_query() && !$query->is_feed() && !is_admin() 
	&& $query->is_post_type_archive( 'carecourse' ) ) {
		$courses_per_page = get_option('care_courses_page_size', 10 );
		error_log("$loc --> courses per page=$courses_per_page");

		$tax_query = array( 'relation' => 'AND'
							, array( 'taxonomy' => 'coursecategory'
								, 'field' => 'slug'
								, 'terms' => array('workshop')
								, 'operator' => 'NOT IN'
							)
					);

		$query->set( 'tax_query', $tax_query );
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', $courses_per_page );
	}
}
add_action( 'pre_get_posts', 'archive_carecourse_query' );

/**
 * Customize the Query for Care Course Taxonomy
 * @param object $query data
 *
 */
function taxonomy_carecourse_query( $query ) {
    $loc = __FILE__ . '/' . __FUNCTION__;
    error_log("$loc");

	if( $query->is_main_query() && !$query->is_feed() && !is_admin() 
	&& is_tax( 'coursecategory', 'Workshop' ) ) {
		$workshops_per_page = get_option('care_courses_page_size', 10 );
		error_log("$loc --> workshops per page=$workshops_per_page");
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', $workshops_per_page );
	}
}
add_action( 'pre_get_posts', 'taxonomy_carecourse_query' );

/**
 * Customize the Query for Care Course Archives
 * @param object $query data
 *
 */
function archive_carewebinartax_query( $query ) {
    $loc = __FILE__ . '/' . __FUNCTION__;
	error_log("$loc");
	// $strQuery = print_r( $query, true );
	// error_log( $strQuery );
	
	if( $query->is_main_query() && !$query->is_feed() && !is_admin() 
	&& $query->is_post_type_archive( 'carewebinartax' ) ) {
		$courses_per_page = get_option('care_courses_page_size', 10 );
		error_log("$loc --> courses per page=$courses_per_page");

		$tax_query = array( 'relation' => 'AND'
							, array( 'taxonomy' => 'coursecategory'
								, 'field' => 'slug'
								, 'terms' => array('workshop')
								, 'operator' => 'NOT IN'
							)
					);

		//$query->set( 'tax_query', $tax_query );
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', $courses_per_page );
	}
}
add_action( 'pre_get_posts', 'archive_carewebinartax_query' );

function carecourse_archive_modify_term_link( $termlink, $term, $taxonomy ) {
    $loc = __FILE__ . '/' . __FUNCTION__;
	error_log("$loc");
	error_log("termlink=$termlink; taxonomy=$taxonomy" );
	// $term_ser = print_r($term, true );
	// error_log( $term_ser );
	$parsedUrl = parse_url($termlink);
	$path  = isset($parsedUrl['path']) ? $parsedUrl['path'] : ''; 
	error_log("Path=$path");
	
    if ( is_tax( 'cat_projet' ) ) {
        if ( get_queried_object_id() === $term->term_id ) {
            $termlink = get_post_type_archive_link( 'post_type_name' );
        }
    }

    return $termlink;
}
//add_filter( 'term_link', 'carecourse_archive_modify_term_link', 10, 3 );

/* 
   ===========================================
	Function to render taxonomy/tag links
   ===========================================
*/
function care_mci_get_term_links( $postID, $termname ) {
	$term_list = wp_get_post_terms( $postID, $termname ); 
	$i = 0;
	$len = count( $term_list );
	foreach( $term_list as $term ) {
		if( $i++ >= 0 && $i < $len) {
			$sep = ',';
		}
		else if( $i >= $len ) {
			$sep = '';
		}
		$lnk = get_term_link( $term );
		if( is_wp_error( $lnk ) ) {
			$mess = $lnk->get_error_message();
			echo "<span>$mess</span>$sep";
		}
		else {
			echo "<a href='$lnk'>$term->name</a>$sep";
		}
	}
}
