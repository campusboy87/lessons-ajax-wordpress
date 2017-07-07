<?php
/**
 * Plugin Name: Тест Ajax запросов
 */

add_action( 'wp_ajax_hello', 'say_hello' );
//add_action( 'wp_ajax_nopriv_hello', 'say_hello' );

function say_hello() {
	
	if ( empty( $_GET['name'] ) ) {
		$name = 'пользователь';
	} else {
		$name = esc_attr( $_GET['name'] );
	}
	
	echo "Привет, $name!";
	wp_die();
}


add_action( 'admin_print_footer_scripts', 'my_action_javascript', 99 );
function my_action_javascript() {
	?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var data = {
                action: 'hello',
                name: 'Дмитрий'
            };

            // с версии 2.8 'ajaxurl' всегда определен в админке
            jQuery.get(ajaxurl, data, function (response) {
                alert('Получено с сервера: ' + response);
            });
        });
    </script>
	<?php
}
