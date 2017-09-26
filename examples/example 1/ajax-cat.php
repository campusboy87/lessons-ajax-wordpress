<?php
/**
 * Plugin Name: Ajax Cat
 * Description: Ajax подгрузка постов из рубрик. Плагин создан в рамках изучения Ajax в Wordpress. Полный цикл уроков
 * смотрите на канале <a href="http://www.youtube.com/c/wpplus">wp-plus</a>.
 * Author: Campusboy
 * Author URI: https://wp-plus.ru/
 * Plugin URI: https://github.com/campusboy87/lessons-ajax-wordpress
 */

add_action( 'wp_ajax_get_cat', 'ajax_show_posts_in_cat' );
add_action( 'wp_ajax_nopriv_get_cat', 'ajax_show_posts_in_cat' );
function ajax_show_posts_in_cat() {
	
	$link = ! empty( $_POST['link'] ) ? esc_attr( $_POST['link'] ) : false;
	$slug = $link ? wp_basename( $link ) : false;
	$cat  = get_category_by_slug( $slug );
	
	if ( ! $cat ) {
		die( 'Рубрика не найдена' );
	}
	
	query_posts( array(
		'posts_per_page' => get_option( 'posts_per_page' ),
		'post_status'    => 'publish',
		'category_name'   => $cat->slug
	) );
	
	require plugin_dir_path( __FILE__ ) . 'tpl-cat.php';
	
	wp_die();
}


add_action( 'wp_enqueue_scripts', 'my_assets' );
function my_assets() {
	wp_enqueue_script( 'custom', plugins_url( 'custom.js', __FILE__ ), array( 'jquery' ) );
	
	wp_localize_script( 'custom', 'myPlugin', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	) );
}
