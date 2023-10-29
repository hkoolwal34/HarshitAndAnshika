<?php
/*
 * This is the child theme for WeddingFocus theme.
 *
 * (Please see https://developer.wordpress.org/themes/advanced-topics/child-themes/#how-to-create-a-child-theme)
 */

/**
 * Loads the child theme textdomain and update notifier.
 */
function weddingfocus_setup() {
    load_child_theme_textdomain( 'signify-dark', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'weddingfocus_setup', 11 );

/**
 * Enqueue Styles and scripts.
 */
function weddingfocus_enqueue_styles() {
    // Include parent theme CSS.
    wp_enqueue_style( 'photofocus-style', get_template_directory_uri() . '/style.css', null, date( 'Ymd-Gis', filemtime( get_template_directory() . '/style.css' ) ) );
    
    // Include child theme CSS.
    wp_enqueue_style( 'weddingfocus-style', get_stylesheet_directory_uri() . '/style.css', array( 'photofocus-style' ), date( 'Ymd-Gis', filemtime( get_stylesheet_directory() . '/style.css' ) ) );

	// Load the rtl.
	if ( is_rtl() ) {
		wp_enqueue_style( 'photofocus-rtl', get_template_directory_uri() . '/rtl.css', array( 'photofocus-style' ), $version );
	}

	// Enqueue child block styles after parent block style.
	wp_enqueue_style( 'weddingfocus-block-style', get_stylesheet_directory_uri() . '/assets/css/child-blocks.css', array( 'photofocus-block-style' ), date( 'Ymd-Gis', filemtime( get_stylesheet_directory() . '/assets/css/child-blocks.css' ) ) );
}
add_action( 'wp_enqueue_scripts', 'weddingfocus_enqueue_styles' );

/**
 * Add child theme editor styles
 */
function weddingfocus_editor_style() {
	add_editor_style( array(
			'assets/css/child-editor-style.css',
			photofocus_fonts_url(),
			get_theme_file_uri( 'assets/css/font-awesome/css/font-awesome.css' ),
		)
	);
}
add_action( 'after_setup_theme', 'weddingfocus_editor_style', 11 );

/**
 * Enqueue editor styles for Gutenberg
 */
function weddingfocus_block_editor_styles() {
	// Enqueue child block editor style after parent editor block css.
	wp_enqueue_style( 'weddingfocus-block-editor-style', get_stylesheet_directory_uri() . '/assets/css/child-editor-blocks.css', array( 'photofocus-block-editor-style' ), date( 'Ymd-Gis', filemtime( get_stylesheet_directory() . '/assets/css/child-editor-blocks.css' ) ) );
}
add_action( 'enqueue_block_editor_assets', 'weddingfocus_block_editor_styles', 11 );

/**
 * Register Google fonts Poppin for WeddingFociu
 *
 * @since WeddingFocus 1.0.0
 *
 * @return string Google fonts URL for the theme.
 */
function photofocus_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Poppins, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$poppins = _x( 'on', 'Poppins: on or off', 'weddingfocus' );

	/* Translators: If there are characters in your language that are not
	* supported by Playfair Display, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$playfair_display = _x( 'on', 'Playfair Display: on or off', 'weddingfocus' );

	if ( 'off' !== $poppins || 'off' !== $playfair_display ) {
		$font_families = array();

		if ( 'off' !== $poppins ) {
			$font_families[] = 'Poppins:200,300,400,500,600,700,400italic,700italic';
		}

		if ( 'off' !== $playfair_display ) {
			$font_families[] = 'Playfair Display:200,300,400,500,600,700,400italic,700italic';
		}
		
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}
	// Load google font locally.
	require_once get_theme_file_path( 'inc/wptt-webfont-loader.php' );

	return esc_url_raw( wptt_get_webfont_url( $fonts_url ) );
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function weddingfocus_body_classes( $classes ) {
	// Added color scheme to body class.
	$classes['color-scheme'] = 'color-scheme-wedding';

	return $classes;
}
add_filter( 'body_class', 'weddingfocus_body_classes', 100 );

/**
 * Change default header text color
 */
function weddingfocus_header_default_color( $args ) {
	$args['default-image'] = get_theme_file_uri( 'assets/images/header-image.jpg' );

	return $args;
}
add_filter( 'photofocus_custom_header_args', 'weddingfocus_header_default_color' );

/**
 * Override parent theme to add promotion headline section.
 */
function photofocus_sections( $selector = 'header' ) {
	get_template_part( 'template-parts/header/header', 'media' );
	get_template_part( 'template-parts/slider/display', 'slider' );
	get_template_part( 'template-parts/services/display', 'services' );
	get_template_part( 'template-parts/hero-content/content','hero' );
	get_template_part( 'template-parts/featured-content/display', 'featured' );
	get_template_part( 'template-parts/portfolio/display', 'portfolio' );
	get_template_part( 'template-parts/testimonial/display', 'testimonial' );
	get_template_part( 'template-parts/promotion-headline/post-type-promotion' );
}

/**
 * Override Parent function
 */
function photofocus_header_media_text() {

	if ( ! photofocus_has_header_media_text() ) {
		// Bail early if header media text is disabled on front page
		return get_header_image();
	}

	$content_alignment = get_theme_mod( 'photofocus_header_media_content_alignment', 'content-align-center' );
	$text_alignment = get_theme_mod( 'photofocus_header_media_text_alignment', 'text-align-center' );

	$header_media_logo = get_theme_mod( 'photofocus_header_media_logo' );

	$classes = array();
	if( is_front_page() ) {
		$classes[] = $content_alignment;
		$classes[] = $text_alignment;
	}

	?>
	<div class="custom-header-content sections header-media-section <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<div class="custom-header-content-wrapper">
			<?php
			$header_media_subtitle = get_theme_mod( 'photofocus_header_media_sub_title' );
			$enable_homepage_logo = get_theme_mod( 'photofocus_header_media_logo_option', 'homepage' );

			if( is_front_page() ) : ?>
				<div class="section-subtitle"> <?php echo esc_html( $header_media_subtitle ); ?> </div>
			<?php endif;

			if ( photofocus_check_section( $enable_homepage_logo ) && $header_media_logo ) {  ?>
				<div class="site-header-logo">
					<img src="<?php echo esc_url( $header_media_logo ); ?>" title="<?php echo esc_url( home_url( '/' ) ); ?>" />
				</div><!-- .site-header-logo -->
			<?php } ?>

			<?php
			$tag = 'h2';

			if ( is_singular() || is_404() ) {
				$tag = 'h1';
			}

			photofocus_header_title( '<div class="section-title-wrapper"><' . $tag . ' class="section-title entry-title">', '</' . $tag . '></div>' );
			?>

			<?php photofocus_header_description( '<div class="site-header-text">', '</div>' ); ?>

			<?php if ( is_front_page() ) :
				$header_media_url_text = get_theme_mod( 'photofocus_header_media_url_text' );
				
				if ( $header_media_url_text ) : 
					$header_media_url = get_theme_mod( 'photofocus_header_media_url', '#' );
					?>
					<span class="more-link">
						<a href="<?php echo esc_url( $header_media_url ); ?>" target="<?php echo esc_attr( get_theme_mod( 'photofocus_header_url_target' ) ? '_blank' : '_self' ); ?>" class="readmore"><?php echo esc_html( $header_media_url_text ); ?></a>
					</span>
				<?php endif;

				$header_media_secondary_url_text = get_theme_mod( 'photofocus_header_media_secondary_url_text' );
				
				if ( $header_media_secondary_url_text ) : 
					$header_media_secondary_url = get_theme_mod( 'photofocus_header_media_secondary_url', '#' );
					?>
					<span class="more-link">
						<a href="<?php echo esc_url( $header_media_secondary_url ); ?>" target="<?php echo esc_attr( get_theme_mod( 'photofocus_header_secondary_url_target' ) ? '_blank' : '_self' ); ?>" class="readmore solid-button"><?php echo esc_html( $header_media_secondary_url_text ); ?></a>
					</span>
				<?php endif; ?>
			<?php endif; ?>
		</div><!-- .custom-header-content-wrapper -->
	</div><!-- .custom-header-content -->
	<?php
} // photofocus_header_media_text.

/**
 * Adds custom overlay for Header Media
 */
function photofocus_header_media_image_overlay_css() {
	$overlay = get_theme_mod( 'photofocus_header_media_image_opacity' );

	$css = '';

	$overlay_bg = $overlay / 100;
	if ( $overlay ) {
	$css = '.custom-header-overlay {
			background-color: rgba(0, 0, 0, ' . esc_attr( $overlay_bg ) . ' );
	    } '; // Dividing by 100 as the option is shown as % for user
	}

	wp_add_inline_style( 'weddingfocus-style', $css );
}

/**
 * Load Customizer Options
 */
require trailingslashit( get_stylesheet_directory() ) . 'inc/customizer/promotion-headline.php';
