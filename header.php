<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

$after_opening_head_code = get_theme_mod('_s_after_opening_head');
$before_closing_head_code = get_theme_mod('_s_before_closing_head');
$after_opening_body_code = get_theme_mod('_s_after_opening_body');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <?php if ($after_opening_head_code) {
        echo $after_opening_head_code;
    } ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<?php wp_head(); ?>
	<?php if ($before_closing_head_code) {
		echo $before_closing_head_code;
	} ?>
</head>

<body <?php body_class(); ?>>
<?php if ($after_opening_body_code) {
	echo $after_opening_body_code;
} ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', '_s' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding container">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$_s_description = get_bloginfo( 'description', 'display' );
			if ( $_s_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $_s_description; /* WPCS: xss ok. */ ?></p>
			<?php endif; ?>
		</div>
    </header>

        <div class="nav-wrap">
            <div class="container">
                <nav id="site-navigation" class="main-navigation navbar navbar-expand-lg navbar-light" role="navigation">
                    <span class="sr-only"><?php esc_html_e( 'Toggle navigation', '_s' ); ?></span>
                    <button class="navbar-toggler brand align-self-end" type="button" data-toggle="collapse" data-target="#navbar-collapse-primary" aria-controls="navbar-collapse-primary" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div id="navbar-collapse-primary" class="collapse navbar-collapse">
						<?php wp_nav_menu( array(
							'theme_location' => 'menu-1',
							'menu_id' => 'primary-menu',
							'container' => null,
							'menu_class' => 'navbar-nav mr-auto',
							'depth' => 2,
							'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
							'walker' => new wp_bootstrap_navwalker() ) );?>
                    </div>
                </nav>
            </div>
        </div>

        <?php if ( function_exists( 'yoast_breadcrumb' ) ) { ?>
            <div class="container">
                <?php yoast_breadcrumb( '<div id="breadcrumbs" class="breadcrumbs">', '</div>' ); ?>
            </div>
        <?php } ?>

	<div id="content" class="site-content">
