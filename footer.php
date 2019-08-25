<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

$before_closing_body_code = get_theme_mod('_s_before_closing_body');
$copyright = get_theme_mod('_s_copyright');
?>

	</div><?php // #content ?>

	<footer id="colophon" class="site-footer">
		<div class="site-info container">
			<?php if ( $copyright ) : ?>
                <p class="mb-md-0"><?php echo str_replace( '{year}', date( 'Y' ), $copyright ); ?></p>
			<?php endif; ?>
		</div>
	</footer>
</div><?php // #page ?>

<?php wp_footer(); ?>

<?php if ($before_closing_body_code) {
	echo $before_closing_body_code;
} ?>
</body>
</html>
