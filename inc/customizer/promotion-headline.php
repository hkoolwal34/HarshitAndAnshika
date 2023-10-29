<?php
/**
 * Promotion Headline Options
 *
 * @package WeddingFocus
 */
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function weddingfocus_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'photofocus_header_media_content_alignment' )->default = 'content-align-center';
	$wp_customize->get_setting( 'photofocus_header_media_text_alignment' )->default    = 'text-align-center';
	$wp_customize->get_setting( 'photofocus_header_media_image_opacity' )->default    = 50;
}
add_action( 'customize_register', 'weddingfocus_customize_register', 999 );

/**
 * Add promotion headline options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function weddingfocus_promo_head_options( $wp_customize ) {
	$wp_customize->add_section( 'photofocus_promotion_headline', array(
			'title' => esc_html__( 'Promotion Headline', 'weddingfocus' ),
			'panel' => 'photofocus_theme_options',
		)
	);

	photofocus_register_option( $wp_customize, array(
			'name'              => 'photofocus_promo_head_visibility',
			'default'           => 'disabled',
			'sanitize_callback' => 'photofocus_sanitize_select',
			'choices'           => photofocus_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'weddingfocus' ),
			'section'           => 'photofocus_promotion_headline',
			'type'              => 'select',
		)
	);

	/* Promotion Headline Image */
	photofocus_register_option( $wp_customize, array(
			'name'              => 'photofocus_promo_head_logo_image',
			'sanitize_callback' => 'photofocus_sanitize_image',
			'custom_control'    => 'WP_Customize_Image_Control',
			'active_callback'   => 'photofocus_is_promotion_headline_active',
			'label'             => esc_html__( 'Promotion Headline Image', 'weddingfocus' ),
			'section'           => 'photofocus_promotion_headline',
		)
	);

	photofocus_register_option( $wp_customize, array(
			'name'              => 'photofocus_promotion_headline',
			'default'           => '0',
			'sanitize_callback' => 'photofocus_sanitize_post',
			'active_callback'   => 'photofocus_is_promotion_headline_active',
			'label'             => esc_html__( 'Page', 'weddingfocus' ),
			'section'           => 'photofocus_promotion_headline',
			'type'              => 'dropdown-pages',
		)
	);

	photofocus_register_option( $wp_customize, array(
			'name'              => 'photofocus_promo_head_sub_title',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'photofocus_is_promotion_headline_active',
			'label'             => esc_html__( 'Sub Title', 'weddingfocus' ),
			'section'           => 'photofocus_promotion_headline',
			'type'              => 'textarea',
		)
	);

	$wp_customize->get_setting( 'photofocus_header_media_content_alignment' )->default = 'content-align-left';
	
}
add_action( 'customize_register', 'weddingfocus_promo_head_options', 100 );

/** Active Callback Functions **/
if ( ! function_exists( 'photofocus_is_promotion_headline_active' ) ) :
	/**
	* Return true if promotion headline is active
	*
	* @since WeddingFocus 1.0
	*/
	function photofocus_is_promotion_headline_active( $control ) {
		$enable = $control->manager->get_setting( 'photofocus_promo_head_visibility' )->value();

		return photofocus_check_section( $enable );
	}
endif;
