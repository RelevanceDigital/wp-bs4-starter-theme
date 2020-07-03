<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package _s
 */

get_header();
?>

<div id="primary" class="content-area container">
    <div class="row">
        <main tabindex="-1" id="main" class="site-main col-md-8">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				the_post_navigation();

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

        </main>

        <aside id="secondary" class="widget-area col-md-4" role="complementary">
			<?php get_sidebar(); ?>
        </aside>
    </div>
</div>
<?php get_footer(); ?>
