<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _s
 */

get_header();
?>

    <div id="primary" class="content-area container">
        <div class="row">
            <main tabindex="-1" id="main" class="site-main col-md-8">

				<?php if ( have_posts() ) : ?>

                    <header class="page-header">
						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
						?>
                    </header>

					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;

					_s_pagination_links();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>

            </main>
            <aside id="secondary" class="widget-area col-md-4" role="complementary">
				<?php get_sidebar(); ?>
            </aside>
        </div>
    </div>

<?php get_footer();
