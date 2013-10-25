<?php 
/* 	
	Plugin Name: WP Donate
	Plugin URI: http://wordpress.org/extend/plugins/wp-donate/
	Description: Integration of the payment system donate using to AuthorizeNet.
	Author: Ketan Ajani
	Version: 1.3
	Author URI: http://www.webconfines.com
*/
session_start();

@define ( 'WP_DONATE_VERSION', '1.3' );
@define ( 'WP_DONATE_PATH',  WP_PLUGIN_URL . '/' . end( explode( DIRECTORY_SEPARATOR, dirname( __FILE__ ) ) ) );
include_once('includes/donate-function.php');
include_once('includes/donate-display.php');
include_once('includes/donate-options.php');

add_action('wp_print_styles', 'load_wp_donate_css');
add_action('wp_print_scripts', 'load_wp_donate_js');
add_action('admin_print_styles', 'load_wp_donate_admin_css');
add_action('admin_print_scripts', 'load_wp_donate_admin_js');

function load_wp_donate_js() 
{
    wp_enqueue_script( 'wp-donate-js', WP_DONATE_PATH . '/js/paymentmethods.js', array('jquery') );
}

function load_wp_donate_admin_js() 
{
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
}

function load_wp_donate_css() 
{
    $options = get_option('wp_donate_options');
    if ( $options['donate_css_switch'] ) {
        if ( $options['donate_css_switch'] == 'Yes') {
            wp_enqueue_style('donate-payment-css', WP_DONATE_PATH . '/css/wp-donate-display.css');
        }
    }
    wp_enqueue_style('donate-widget-css', WP_DONATE_PATH . '/css/wp-donate-widget.css');
}

function load_wp_donate_admin_css() {
    wp_enqueue_style('donate-css', WP_DONATE_PATH . '/css/wp-donate-admin.css');
}

function my_add_menu_items()
{
    add_menu_page( 'WP Donate', 'WP Donate', 'activate_plugins', 'wp_donate', 'my_render_list_page' );
	add_options_page( 'WP Donate', 'WP Donate', 'manage_options', 'wp_donate', 'wp_donate_options_page' );
}
add_action( 'admin_menu', 'my_add_menu_items' );

function my_render_list_page()
{
	
}
add_shortcode('Display Donate', 'wp_donate_form');
if(isset($_REQUEST['setting']))
{
	if($_REQUEST['setting']==1)
	{
		$wpdb->query("INSERT INTO `".$wpdb->prefix."donate_setting` (`id`, `mod`, `api_login`, `key`) VALUES ('1', '', '', '')");
		$wpdb->query("UPDATE `".$wpdb->prefix."donate_setting` SET `mod` = '".$_REQUEST['authnet_mode']."',`api_login` = '".$_REQUEST['x_login']."',`key` = '".$_REQUEST['x_tran_key']."' WHERE `id` =1");
	}
}

register_activation_hook( __FILE__, 'donate_install' );

global $donate_db_version;
$donate_db_version = "1.0";

function donate_install() {
   global $wpdb;
   global $donate_db_version;

   $table_name = $wpdb->prefix . "donate";
   $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `organization` varchar(255) CHARACTER SET utf8 NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 NOT NULL,
  `country` varchar(255) CHARACTER SET utf8 NOT NULL,
  `state` varchar(255) CHARACTER SET utf8 NOT NULL,
  `zip` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `donation_type` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$donate_setting = $wpdb->prefix . "donate_setting";
$donate_setting_sql = "CREATE TABLE IF NOT EXISTS `$donate_setting` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`mod` varchar(255) NOT NULL,
`api_login` varchar(255) NOT NULL,
`key` varchar(255) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";
  
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
   dbDelta( $donate_setting_sql );
 
   add_option( "donate_db_version", $donate_db_version );
}

function donate_install_data() {
   global $wpdb;
   $welcome_name = "Mr. WordPress";
   $welcome_text = "Congratulations, you just completed the installation!";
   $rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );
}
?>
