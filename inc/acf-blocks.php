<?php
/**
 * Adds Gutenberg content blocks through Advance Custom Fields Plugin
 *
 * See the full list of parameters at: https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package _s
 */

function _s_register_acf_block_types() {

	// Text widths
	/*
	acf_register_block_type( array(
		'name'            => 'text_custom_width',
		'title'           => __( 'Text Custom Width', '_s' ),
		'description'     => __( 'Adjustable Width Text.', '_s' ),
		'render_template' => 'template-parts/blocks/text-custom-width.php',
		'category'        => 'common',
		'icon'            => 'editor-paragraph',
		'keywords'        => array( 'text' ),
	) );
	*/

}

// Check if function exists and hook into setup.
if ( function_exists( '_s_acf_register_block_type' ) ) {
	add_action( 'acf/init', '_s_register_acf_block_types' );
}