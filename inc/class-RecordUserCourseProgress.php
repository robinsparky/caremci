<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 
 * Data and functions for Recording Course Progress
 * @class  RecordUserCourseProgress
 * @package Care
 * @version 1.0.0
 * @since   0.1.0
*/
class RecordUserCourseProgress
{ 

    //Action hook used by the AJAX class.
    const ACTION     = 'recordCourseProgess';

    const NONCE      = 'recordCourseProgess';
    const META_KEY   = "course_status";

    //Course registration workflow
    const COMPLETED  = "Completed";
    const PENDING    = "Pending";
    const REGISTERED = "Registered";

    //Only emit on this page
    const HOOK = 'profile.php';

    const TABLE_CLASS = 'course-status';

    private $ajax_nonce = null;
    private $errobj = null;
    private $errcode = 0;

    private $statuses;
    private $roles;
    private $log;
    /**
     * Register the AJAX handler class with all the appropriate WordPress hooks.
     */
    public static function register() {
        $loc = __CLASS__ . '::' . __FUNCTION__;
        error_log("$loc");
        $handler = new self();
        add_action('admin_enqueue_scripts', array( $handler, 'registerAdminScript' ) );
        
        $handler->registerHandlers();
    }

	/*************** Instance Methods ****************/
	public function __construct( ) {
        $this->errobj = new WP_Error();			
        $rolesWatch = esc_attr( get_option('care_roles_that_watch') );
        $this->roles = explode( ",", $rolesWatch );
        $this->statuses = array( self::PENDING, self::COMPLETED );
        $this->log = new BaseLogger( false );
    }

    public function registerScript( $hook ) {
        $loc = __CLASS__ . '::' . __FUNCTION__;
        $this->log->error_log( "$loc" ); 

    }
    
    public function registerAdminScript( $hook ) {
        $loc = __CLASS__ . '::' . __FUNCTION__;
        $this->log->error_log( "$loc" ); 

        //Make sure we are rendering the "user-edit" page
        if( $hook === RecordUserCourseProgress::HOOK ) {
            wp_register_script( 'care-userprofile-courses'
                            , get_stylesheet_directory_uri() . '/js/care-record-user-course-status.js'
                            , array('jquery') );
    
            wp_localize_script( 'care-userprofile-courses', 'care_userprofile_obj', $this->get_data() );

            wp_enqueue_script( 'care-userprofile-courses' );
        }
    }

    public function registerHandlers() {
        $loc = __CLASS__ . '::' . __FUNCTION__;
        $this->log->error_log("$loc");

        //Show the data
        add_action( 'show_user_profile', array( $this, 'courseStatusUserProfileFields' ), 10, 1  );
        add_action( 'edit_user_profile', array( $this, 'courseStatusUserProfileFields' ), 10, 1  );
        add_action( 'user_new_form', array( $this, 'courseStatusUserProfileFields' ) ); // creating a new user
        
        //Save the entered data
        add_action( 'personal_options_update', array( $this, 'courseStatusUserProfileSave' ) );
        add_action( 'edit_user_profile_update', array( $this, 'courseStatusUserProfileSave' ) );
        add_action( 'user_register', array( $this, 'courseStatusUserProfileSave' ) );
    }

    public function courseStatusUserProfileFields( $profileuser ) {	
        $loc = __CLASS__ . '::' . __FUNCTION__;
        $this->log->error_log( $loc );


        $ok = false;
        if( current_user_can( 'manage_options' ) ) $ok = true;
        if( ! $ok ) return;

    ?>
    <h3>Course and Webinar Status</h3>
		<table class="form-table">
			<tr>
				<td>
					<?php echo $this->courseProgressEmitter( $profileuser );?>
				</td>
		</table>
    <?php
    }

	public function courseProgressEmitter( $user )
    {
        $loc = __CLASS__ . '::' . __FUNCTION__;
        $this->log->error_log( $loc );

		$user_id = $user->ID;
        $this->log->error_log( sprintf("%s: User id=%d and email=%s",$loc, $user_id, $user->user_email ));
    
        //Setup the course selection options
        $select_title = __('---Select A Course or Webinar To Register---', CARE_TEXTDOMAIN );
        $tmpl = '<option value="%s">%s</option>';
        $selection = '<select id="course-select">';
        $selection .= '<option value="0" selected="selected">' . $select_title . '</option>';
        $args = array( 'post_type' => 'carecourse', 'posts_per_page' => -1 ); 
        $loop = new WP_Query( $args );
        if ($loop->have_posts()) {
            while ( $loop->have_posts() ) {
                $loop->the_post();
                $selection .= sprintf( $tmpl, get_the_ID(), get_the_title() );
            }
        }
        else {
            $selection .= sprintf( $tmpl, 0, "No Courses found" );
        }    
        $selection .= "</select>";
        // Reset Post Data 
        wp_reset_postdata();
        wp_reset_query();

        $register_title = __('Register', CARE_TEXTDOMAIN );
        $toggle_title   = __('Toggle Status', CARE_TEXTDOMAIN );
        $remove_title   = __('Remove', CARE_TEXTDOMAIN );
        $save_title     = __('Save', CARE_TEXTDOMAIN );
        //$overall_title  = __('Course and Webinar Registration Report', CARE_TEXTDOMAIN );

        $hidden = "";
        $out  = $selection;
        
        //Now provide means to record progress or register re other courses
        $register = "<button id='register-for-course' name='register-for-course' type='button'>$register_title</button>";
        $toggle = "<button id='toggle-course-status' name='record-course-completed' type='button'>$toggle_title</button>";
        $remove   = "<button id='remove-course' name='remove-course' type='button'>$remove_title</button>";
        //$done     = "<button id='done-course-work' name='done-course-work' type='button'>$save_title</button>";
		$caption = __('Please click "Update User" when done.', CARE_TEXTDOMAIN );
        //Currently registered course statuses
        $out .= '<table class="course-status">';
        $out .= '<caption style="caption-side:bottom; align:right;">' . $caption . '</caption>';
        $out .= '<thead><th>Course</th><th>Location</th><th>Start Date</th><th>End Date</th><th>Status</th><th>Operation</th></thead>';
        $out .= '<tbody>';
        //$out .= sprintf("<tr id=\"add\"><td id=\"selection\" colspan=\"2\">%s</td><td>%s %s</td></tr>", $selection, $complete, $remove );

        $classes = self::TABLE_CLASS;
        //Note: recorded_courses is a 2-d array
        //delete_user_meta( $user_id, RecordUserCourseProgress::META_KEY);
        $recorded_courses = get_user_meta( $user_id, self::META_KEY, false );
        $this->log->error_log("$loc Entire record from user meta:");
        $this->log->error_log( $recorded_courses );

        $templ = <<<EOT
            <tr id="%d">
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td id="operation">%s %s</td>
            </tr>
EOT;
        $ctr = 0;
        foreach( $recorded_courses as $arrcourse ) {
            $this->log->error_log("$loc --> Course Reports from user meta...");
            foreach( $arrcourse as $course) {
                $this->log->error_log( $course );
                $row = sprintf( $templ
                              , $course['id']
                              , $course['name']
                              , $course["location"]
                              , $course["startDate"]
                              , $course["endDate"]
                              , $course['status']
                              , $remove, $toggle );
                $out .= $row;
                $val = sprintf("%d|%s|%s|%s|%s|%s"
                              , $course['id']
                              , $course['name']
                              , $course["location"]
                              , $course["startDate"]
                              , $course["endDate"]
                              , $course['status'] );
                $hidden .= sprintf("<input type=\"hidden\" name=\"statusreports[]\" value=\"%s\"> ", $val );
            }
        }
        $out .= '</tbody></table>';  
        $out .= $hidden; 

        $out .= "<div id='care-resultmessage'></div>";
        $out .= "</div>";

        return $out;
    }

    public function courseStatusUserProfileSave( $userId ) 
    {
        $loc = __CLASS__ . '::' . __FUNCTION__;
        $this->log->error_log("$loc: _POST:");
        $this->log->error_log( $_POST );
    
        if ( !current_user_can('edit_user', $userId)) {
            return;
        }

        //Get the registered courses
        if ( !empty( $_POST['statusreports'] )) {
            $statusreports = $_POST['statusreports'];
        }
        elseif ( !empty( $_GET['statusreports'] )) {
            $statusreports = $_GET['statusreports'];
        }
        else {
            $statusreports = array();
        }

        if( is_string( $statusreports ) ) {
            $s = $statusreports;
            $statusreports = array();
            array_push( $statusreports, $s );
        }

        $this->log->error_log("Length of status reports=" . count( $statusreports ) );
        $this->log->error_log( $statusreports );

        $this->storeCourseProgress( $userId, $webinarreports );

        return;
    }
    
    /**
     * Save the posted array of webinar statuses (histories) in the db
     * @param $userId The id of the user whose webinars are being recorded
     * @param $statusreports Is an array of 6 values: id, webinar name, startdate, enddate, status, watchedPct, location
     */
    private function storeCourseProgress( $userId, $statusreports ) {
        $loc = __CLASS__ . '::' . __FUNCTION__;
        $this->log->error_log( $loc );
        //Below is an example of the element giving the "statusreports" array
        //<input name="webinarreports[]" 
        //      value="7211|Information Session|2018-10-28|2018-10-28|In Progress|0|unknown" 
        //      type="hidden">
        $courses = array();
        $tracker = array();
        $format = 'Y-m-d';
        foreach( $statusreports as $report ) {
            $arr = explode( "|", $report );
            if( !in_array( $arr[0], $tracker ) ) {
                $this->log->error_log( $report );
                array_push( $tracker, $arr[0] );
                $course = array();
                $course['id']        = $arr[0];
                $course['name']      = $arr[1];
                $sdate = DateTime::createFromFormat($format, $arr[2]);
                if( false === $sdate ) {
                    $this->log->error_log( DateTime::getLastErrors(), "Error processing start date" );
                    $sdate = DateTime::createFromFormat($format, '1970-01-01');
                }
                $this->log->error_log( $sdate->format( $format ), "Start Date: ");
                $course["startDate"] = $sdate->format( $format );
                
                $edate = DateTime::createFromFormat($format, $arr[3]);
                if( false === $edate ) {
                    $this->log->error_log( DateTime::getLastErrors(), "Error processing end date" );
                    $edate = DateTime::createFromFormat($format, '1970-01-01');
                }
                $this->log->error_log( $edate->format( $format ), "End Date: ");
                $course["endDate"] = $edate->format( $format );

                $course['status']    = $arr[4];
                $course['watchedPct'] = (float)$arr[5];
                $course['location'] = $arr[6];
                array_push( $courses, $course );
            }
        }
        
        $this->log->error_log( $courses, "Courses..." );

        //TODO: Need to make sure that the webinars still exists in carewebinar's.
        $len = count( $courses );
        if( 0 === $len ) {
            if( delete_user_meta( $userId, self::META_KEY ) ) {
                $mess = "Removed all course progress reports.";
            }
            else {
                $mess = "No course progress reports exist so none were removed.";
            }
        }
        else {
            //array is returned because $single is false
            $prev_webinars = get_user_meta($userId, self::META_KEY, false );
            if( count( $prev_webinars ) < 1 ) {
                $meta_id = add_user_meta( $userId, self::META_KEY, $webinars, true );
                $mess = sprintf("Added %d course %s. (Meta id=%d)", $len, $len === 1 ? 'progress report' : 'progress reports', $meta_id );
            }
            else {
                $meta_id = update_user_meta( $userId, self::META_KEY, $webinars );
                if( true === $meta_id ) {
                    $mess = sprintf("Updated %d course %s.", $len, $len === 1 ? 'progress report' : 'progress reports' );
                }
                elseif( is_numeric( $meta_id ) ) {
                    $mess = sprintf("Added %d course %s. (Meta id=%d)", $len, $len === 1 ? 'progress report' : 'progress reports', $meta_id );
                }
            }
            if( false === $meta_id ) {
                $mess = "Course progress reports were not added/updated.";
            }
        }

        return $mess;

    }
 
    /**
     * Get the AJAX data that WordPress needs to output.
     *
     * @return array
     */
    private function get_data()
    {
        $loc = __CLASS__ . '::' . __FUNCTION__;
        $this->log->error_log( $loc );

        $mess = "";
        return array( 'tableclass' => self::TABLE_CLASS
                    , 'message' => $mess
                    , 'statusvalues' => $this->statuses
                   );
    }

    private function handleErrors( string $mess ) {
        wp_die( $mess );
    }
    
} //end of class