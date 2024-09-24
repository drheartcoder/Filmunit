<?php
/*
Template Name: Front Page
*/

get_header(); ?>
<div class="col-md-12">
	<div id="primary" class="content-area-front-page">

			<div class="entry-content">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			</div><!-- .entry-content -->

	</div><!-- #primary -->
</div><!-- /.col-md-12 -->
<?php get_footer(); ?>
