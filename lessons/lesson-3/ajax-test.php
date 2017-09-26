<?php
/**
 * Plugin Name: Тест Ajax запросов
 */

add_action( 'wp_ajax_hello', 'say_hello' );
add_action( 'wp_ajax_nopriv_hello', 'say_hello' );
function say_hello() {
	
	if ( empty( $_GET['name'] ) ) {
		$name = 'пользователь';
	} else {
		$name = esc_attr( $_GET['name'] );
	}
	
	echo "Привет, $name!";
	wp_die();
}


add_action( 'wp_enqueue_scripts', 'my_assets' );
function my_assets() {
	wp_enqueue_script( 'custom', plugins_url( 'custom.js', __FILE__ ), array( 'jquery' ) );
	
	wp_localize_script( 'custom', 'myPlugin', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'name'    => wp_get_current_user()->display_name
	) );
}
