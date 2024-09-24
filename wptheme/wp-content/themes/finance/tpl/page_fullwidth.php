<?php

/*
Template Name: Full width Page
*/
get_header(); ?>
<div class="col-md-12">

	<div id="primary" class="content-area-full-width">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; // end of the loop. ?>

	</div><!-- #primary -->
</div>
<?php get_footer(); ?>

