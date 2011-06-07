<?php 
/**
 * Content of Dashboard-Widget
 */
function my_wp_dashboard_test() {
	echo 'Test Add Dashboard-Widget';
}
 
/**
 * add Dashboard Widget via function wp_add_dashboard_widget()
 */
function my_wp_dashboard_setup() {
	wp_add_dashboard_widget( 'my_wp_dashboard_test', __( 'Test My Dashboard' ), 'my_wp_dashboard_test' );
}
 
/**
 * use hook, to integrate new widget
 */
add_action('wp_dashboard_setup', 'my_wp_dashboard_setup');
?>