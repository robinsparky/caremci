<?php

/* WP Admin Menu positions
2 - Dashboard
4 - Separator
5 - Posts
10 - Media
20 - Pages
25 - Comments
59 - Separator
60 - Appearance
65 - Plugins
70 - Users
75 - Tools
80 - Settings
99 - Separator
*/

/* ================================
    Admin Menus
   ================================   
*/
function care_admin_page() {
    //Generate MCI admin page
    add_menu_page( 'Care Admin Options' //title
                 , 'Care Settings' //menu name
                 , 'manage_options' //capabilities required of user
                 , 'caremci' //slug
                 , 'care_theme_create_page' // function to create page
                 , 'dashicons-admin-generic' //icon
                 , 5 //menu position
    );

    //Generate admin sub pages
    add_submenu_page( 'caremci' //parent slug
                    , 'Care Admin Options' //page title
                    , 'Settings' // menu title
                    , 'manage_options' //capabilities
                    , 'caremci' // menu slug
                    , 'care_theme_create_page' //callback
    );
    
    //Generate admin sub pages -- first one must mirror the main menu page
    // add_submenu_page( 'caremci' //parent slug
    //                 , 'Care Dashboard Options' //page title
    //                 , 'Dashboard' // menu title
    //                 , 'manage_options' //capabilities
    //                 , 'caremci_dashboard' // menu slug
    //                 , 'care_theme_dashboard_page' //callback
    // );

    add_action( 'admin_init', 'care_custom_settings' );
}

//Activate custom settings
add_action( 'admin_menu', 'care_admin_page' );

function care_theme_create_page() {
    //generation of our admin page
    require_once get_stylesheet_directory() . '/inc/templates/mci-settings.php';
}

// function care_theme_dashboard_page() {
//     //generation of our admin sub page
//     require_once get_stylesheet_directory() . '/inc/templates/mci-dashboard.php';
// }

function care_custom_settings() {
    register_setting( 'care-settings-group' //Options group
                    , 'care_webinar_pct_complete' //Option name used in get_option
                    //, '' //sanitize call back
                    );
    register_setting( 'care-settings-group' //Options group
                    , 'care_roles_that_watch' //Option name used in get_option
                    , 'sanitize_roles_csv' //sanitize call back
                    );
    register_setting( 'care-settings-group' //Options group
                    , 'care_courses_page_size' //Option name used in get_option
                    , 'sanitize_page_size' //sanitize call back
                    );

    add_settings_section( 'care-course-options' //id
                        , 'Webinar Options' //title
                        , 'care_webinar_options' //callback to generate html
                        , 'caremci' //page
                    );
    
    add_settings_field( 'webinar-percent-complete' // id
                    , 'Webinar Completion Percentage' // title
                    , 'webinarPercentComplete' // callback
                    , 'caremci' // page
                    , 'care-course-options' // section
                    //,  array of args
                    );
                    
    add_settings_field( 'care-roles-that-watch' // id
                    , 'Roles Allowed to Watch Webinars' // title
                    , 'webinarWatchRoles' // callback
                    , 'caremci' // page
                    , 'care-course-options' // section
                    //,  array of args
                    );
                
    add_settings_section( 'care-display-options' //id
                        , 'Display Options' //title
                        , 'care_courses_page_size_option' //callback to generate html
                        , 'caremci' //page
                    );
    add_settings_field( 'courses-per-page' // id
                      , 'Courses & Webinars Per Page' // title
                      , 'courses_per_page' // callback
                      , 'caremci' // page
                      , 'care-display-options' // section
                      //,  array of args
                );
}

function care_webinar_options() {
    echo "This section is for administrative settings";
}
function care_courses_page_size_option() {
    echo "This section is for display related settings";
}

function webinarPercentComplete() {
    $pctCompleteFactor = esc_attr( get_option('care_webinar_pct_complete', 85) );
    echo '<input type="number" min="10.0" max="100.0" step="1" name="care_webinar_pct_complete" value="' . $pctCompleteFactor . '" /><p>Max 100 and at least 10. Default is 85</p>';
}

function webinarWatchRoles() {
    $roleNames = array_keys( wp_roles()->roles );
    $rolesWatch = esc_attr( get_option('care_roles_that_watch','um_member') );
    echo '<input type="text" size="60" name="care_roles_that_watch" value="' . $rolesWatch . '" />';
    echo '<p>Available roles:</p>';
    echo '<ul>';
    foreach( $roleNames as $role ) {
        echo '<li>' . $role . '</li>';
    }
    echo '</ul>';
}

function courses_per_page() {
    $pagesize = esc_attr( get_option('care_courses_page_size') );
    echo '<input type="text" size="10" name="care_courses_page_size" value="' . $pagesize . '" /><p>Max 1000 and not negative</p>';
}

function sanitize_page_size( $input ) {
    $output = 1000;
    if( is_numeric( $input ) ) {
        if( $input < 0 || $input > 1000) $output = 1000;
        else $output = $input;
    }
    return $output;
}

function sanitize_roles_csv( $input ) {
    $checkstr = sanitize_text_field( $input );
    $checkarr = str_getcsv( $checkstr, "," );
    if( strpos( $checkstr, "," ) === false ) {
        $checkarr = array( trim( $checkstr ) );
    }
    
    $output = array();
    $acceptableRoles = wp_roles()->roles;
    $keys = array_keys( $acceptableRoles );
    foreach( $checkarr as $roleName ) {
        $roleName = trim( $roleName );
        if( in_array( $roleName, $keys ) ) {
            array_push( $output, $roleName );
        }
    }
    return implode( ",", $output );
}

