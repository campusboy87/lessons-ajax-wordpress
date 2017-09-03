<?php
/**
 * Plugin Name: Ajax WordPress Lessons
 * Description: Выводит на главной страницы админки виджет с заметками.
 * Plugin URI: https://github.com/campusboy87/lessons-ajax-wordpress
 * Author: Обучающий YouTube канал "WP-PLUS"
 * Author URI: https://www.youtube.com/c/wpplus
 */

// Регистрация виджета "Мои заметки"
add_action( 'wp_dashboard_setup', 'my_notes_dashboard_widget' );
function my_notes_dashboard_widget() {
	// Регистрируем виджет только для администраторов сайта
	if ( current_user_can( 'activate_plugins' ) ) {
		wp_add_dashboard_widget( 'my_notes', 'Мои заметки', 'my_notes_form' );
	}
}

// Отображение виджета "Мои заметки"
function my_notes_form() {
	?>

    <form>
        <textarea><?php echo esc_textarea( get_option( 'my_notes_content' ) ); ?></textarea>
        <button type="reset" class="clear button button-secondary">Очистить</button>
		<?php submit_button( null, 'primary', null, false ); ?>
    </form>
	
	<?php
}

// Сохранение текста заметки с помощью Ajax
add_action( 'wp_ajax_my_notes', 'my_notes_ajax_save' );
function my_notes_ajax_save() {
	check_ajax_referer( 'my_notes_nonce', 'security' );
	
	if ( ! isset( $_POST['my_notes_content'] ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	
	// Получаем и чистим данные
	$notes_content = sanitize_textarea_field( wp_unslash( $_POST['my_notes_content'] ) );
	
	// Обновляем данные
	$status = update_option( 'my_notes_content', $notes_content, false );
	
	if ( $status ) {
		wp_send_json_success( [
			'message' => 'Заметка сохранена',
		] );
	} else {
		wp_send_json_error( [
			'message' => 'Заметка не изменилась',
		] );
	}
	
}

// Индивидуальные стили и JS скрипт для ajax сохранения из виджета "Мои заметки"
add_action( 'admin_print_scripts', 'my_notes_scripts', 999 );
function my_notes_scripts() {
	global $screen;
	
	// Если это не главная страница админки - прекращаем выполнение функции
	if ( 'dashboard' != $screen->base ) {
		return;
	}
	?>
    <style>
        #my_notes textarea {
            width: 100%;
            min-height: 100px;
            margin-bottom: 5px;
        }
    </style>

    <script>
        jQuery(document).ready(function ($) {
            var $boxNotes = $('#my_notes');
            var boxTitle = $('h2 span', $boxNotes).text();

            // Очистка поля с заметками и изменение заголовка виджета на дефолтный
            $('.clear', $boxNotes).click(function () {
                $('textarea', $boxNotes).text('');
                $('h2 span', $boxNotes)
                    .text('Поле очищено. Не забудьте сохранить результат!')
                    .css('color', 'orangered');
            });

            // Отправка формы
            $('form', $boxNotes).submit(function (e) {
                e.preventDefault();

                // Анимация
                $boxNotes.animate({opacity: 0.5}, 300);

                // Ajax запрос
                var request = $.post(
                    ajaxurl,
                    {
                        action: 'my_notes',
                        my_notes_content: $('textarea', $boxNotes).val(),
                        security: '<?php echo wp_create_nonce( "my_notes_nonce" ); ?>'
                    }
                );

                // Обработка успешного запроса
                request.done(function (response) {
                    var $title = $('h2 span', $boxNotes).text(response.data.message);
                    if (response.success) {
                        $title.css('color', 'green');
                    } else {
                        $title.css('color', 'orangered');
                    }
                });

                // Обработка запроса с ошибкой
                request.fail(function () {
                    $('h2 span', $boxNotes)
                        .text('Непредвиденная ошибка!')
                        .css('color', 'red');
                });

                // Обработка запроса при обоих случаях
                request.always(function () {
                    $boxNotes.animate(
                        {opacity: 1},
                        300,
                        '',
                        function () {
                            setTimeout(function () {
                                $('h2 span', $boxNotes)
                                    .text(boxTitle)
                                    .attr('style', '');
                            }, 2000);
                        });
                });

            });
        });
    </script>
	<?php
}
