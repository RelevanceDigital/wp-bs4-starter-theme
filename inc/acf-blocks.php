<?php
/**
 * Adds Gutenberg content blocks through Advance Custom Fields Plugin
 *
 * See the full list of parameters at: https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package _s
 */

function register_acf_block_types() {

	// Text widths
	/*
	acf_register_block_type( array(
		'name'            => 'text_custom_width',
		'title'           => __( 'Text Custom Width' ),
		'description'     => __( 'Adjustable Width Text.' ),
		'render_template' => 'template-parts/blocks/text-custom-width.php',
		'category'        => 'common',
		'icon'            => 'editor-paragraph',
		'keywords'        => array( 'text' ),
	) );
	*/

}

// Check if function exists and hook into setup.
if ( function_exists( 'acf_register_block_type' ) ) {
	add_action( 'acf/init', 'register_acf_block_types' );
}