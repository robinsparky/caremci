<?php
/*
This snippet stores PayPal API keys to the wp_options database table.
Useful if site is not running SSL but you can upload via encrypted FTP or SFTP.
 
For installation instructions, see here (use the mu-plugins method):
 http://wp-events-plugin.com/tutorials/how-to-safely-add-php-code-to-wordpress/
 */
function my_em_paypal_api_keys_save() {
    update_option('em_paypal_api', array (
        'username' => 'cleblanc-facilitator_api1.care4nurses.org',
        'password' => '4B6CJF8ZMCJZZU79',
        'signature' => 'ANAhfQvjC-U8ClXWFXJwsmR-MeJKAt4KxHelJ2thsRwByfTtfv71ROML',
    ));
}

my_em_paypal_api_keys_save();
