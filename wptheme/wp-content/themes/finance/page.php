<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package finance
 */

get_header(); ?>
<div class="col-md-12">

	<div id="primary" class="content-area <?php  echo esc_attr ( get_theme_mod( 'product_single_layout', 'fullwidth' ) ) ?>">
		
			<?php while ( have_posts() ) : the_post(); ?>
				<?php echo the_content(); ?>
				<?php //get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

	</div><!-- #primary -->

	<?php 
	if ( function_exists( 'is_woocommerce' ) && is_page( array( 'cart', 'checkout' ) ) ) {
        if ( get_theme_mod( 'product_single_layout', 'fullwidth' ) != 'fullwidth' ) :
			get_sidebar();		
		endif;
    } else {
    	get_sidebar();
    }
	?>

</div><!-- /.col-md-12 -->

<?php get_footer(); ?>
