<?php
/**
 * Шаблон вывода названия рубрики и постов из неё темы Twenty Sixteen
 */
if ( have_posts() ) : ?>

    <header class="page-header">
		<?php
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="taxonomy-description">', '</div>' );
		?>
    </header><!-- .page-header -->
	
	
	<?php
	while ( have_posts() ) : the_post();
		
		get_template_part( 'template-parts/content', get_post_format() );
	
	endwhile;
	
	$pagination = get_the_posts_pagination( array(
		'prev_text'          => __( 'Previous page', 'twentysixteen' ),
		'next_text'          => __( 'Next page', 'twentysixteen' ),
		'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
	) );
	
	echo str_replace( admin_url( 'admin-ajax.php/' ), get_category_link( $cat->term_id ), $pagination );

else :
	get_template_part( 'template-parts/content', 'none' );

endif;