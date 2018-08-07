<?php
/**
 * _s Theme Customizer
 *
 * @package _s
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function _s_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	//Section for custom code
	$wp_customize->add_section('_s_custom_code', array(
		'title' => __( 'Custom Code', '_s' ),
		'description' => '',
		'priority' => 210,
	));
	//Fields for custom code
	$wp_customize->add_setting('_s_after_opening_head');
	$wp_customize->add_control( '_s_after_opening_head', array(
			'type' => 'textarea',
			'section' => '_s_custom_code', // Required, core or custom.
			'label' => __( 'After Opening <head> Tag', '_s' ),
		)
	);
	$wp_customize->add_setting('_s_before_closing_head');
	$wp_customize->add_control( '_s_before_closing_head', array(
			'type' => 'textarea',
			'section' => '_s_custom_code', // Required, core or custom.
			'label' => __( 'Before Closing </head> Tag', '_s' ),
		)
	);
	$wp_customize->add_setting('_s_after_opening_body');
	$wp_customize->add_control( '_s_after_opening_body', array(
			'type' => 'textarea',
			'section' => '_s_custom_code', // Required, core or custom.
			'label' => __( 'After Opening <body> Tag', '_s' ),
		)
	);
	$wp_customize->add_setting('_s_before_closing_body');
	$wp_customize->add_control( '_s_before_closing_body', array(
			'type' => 'textarea',
			'section' => '_s_custom_code', // Required, core or custom.
			'label' => __( 'Before Closing </body> Tag', '_s' ),
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => '_s_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => '_s_customize_partial_blogdescription',
		) );
	}
}
add_action( 'customize_register', '_s_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function _s_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function _s_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function _s_customize_preview_js() {
	wp_enqueue_script( '_s-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', '_s_customize_preview_js' );
