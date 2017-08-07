<?php
/**
 * Plugin Name: Ajax Security Lesson
 */

add_action( 'wp_ajax_show_anything', 'show_anything' );
add_action( 'wp_ajax_nopriv_show_anything', 'show_anything' );
function show_anything() {
	
	if ( empty( $_POST['nonce'] ) ) {
		wp_die( '0' );
	}
	// Первый вариант - простое сравнение ключей
	/*$nonce_outside = $_POST['nonce'];
	$nonce_inside  = wp_create_nonce( 'nonce-for-lesson' );
	
	$text = "nonce_outside: $nonce_outside, nonce_inside: $nonce_inside";
	
	if ( $nonce_outside === $nonce_inside ) {
		wp_die( "Ура! $text" );
	} else {
		wp_die( "Эх! $text", '', 403 );
	}*/
	
	// Второй вариант - wp_verify_nonce()
	/*if ( wp_verify_nonce( $_POST['nonce'], 'nonce-for-lesson' ) ) {
		wp_die( 'Ура!' );
	} else {
		wp_die( 'Эх!', '', 403 );
	}*/
	
	// Третий вариант - check_ajax_referer()
	/*if ( check_ajax_referer( 'nonce-for-lesson', 'nonce', false ) ) {
		wp_die( 'Ура!' );
	} else {
		wp_die( 'Эх!', '', 403 );
	}*/
	
	// Четвертый вариант - check_ajax_referer() + current_user_can()
	$check_ajax_referer = check_ajax_referer( 'nonce-for-lesson', 'nonce', false );
	$current_user_can   = current_user_can( 'edit_others_pages' );
	
	if ( $check_ajax_referer && $current_user_can ) {
		wp_die( 'Ура!' );
	} else {
		wp_die( 'Эх!', '', 403 );
	}
}


// Подключение JS и CSS
add_action( 'wp_enqueue_scripts', 'my_assets' );
function my_assets() {
	wp_enqueue_script( 'sweetalert', plugins_url( 'assets/sweetalert.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_style( 'sweetalert', plugins_url( 'assets/sweetalert.css', __FILE__ ) );
	
	wp_enqueue_script( 'custom', plugins_url( 'assets/custom.js', __FILE__ ), array( 'jquery', 'sweetalert' ) );
	
	wp_localize_script( 'custom', 'myPlugin', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'nonce-for-lesson' )
	) );
}
