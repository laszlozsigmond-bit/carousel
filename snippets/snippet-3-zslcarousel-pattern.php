<?php
/**
 * zslcarousel – Snippet 3: Gutenberg Block Pattern
 *
 * Paste this snippet into your snippets plugin and set it to run
 * EVERYWHERE (frontend + admin) so the pattern appears in the
 * block editor. Hook: init.
 *
 * The pattern inserts a ready-to-use 6-slide carousel via a
 * Custom HTML block. Replace the placeholder image URLs and alt
 * text with images from your Media Library.
 */

if ( ! function_exists( 'zslcarousel_register_pattern' ) ) {
	function zslcarousel_register_pattern() {
		if ( ! function_exists( 'register_block_pattern' ) ) {
			/* Block patterns require WordPress 5.5+ */
			return;
		}

		/* Register a dedicated pattern category */
		if ( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category(
				'zslcarousel',
				array( 'label' => __( 'Carousel', 'zslcarousel' ) )
			);
		}

		/*
		 * The carousel HTML.
		 * Six sample slides – replace src / alt values with your own images.
		 * Use data-zslcarousel-autoplay="0" to disable autoplay.
		 * Use data-zslcarousel-dots="false" to hide dot navigation.
		 */
		$slides = array(
			array(
				'src'    => 'https://placehold.co/1200x600/3498db/ffffff?text=Slide+1',
				'alt'    => 'Slide 1 – replace with your image',
				'width'  => '1200',
				'height' => '600',
			),
			array(
				'src'    => 'https://placehold.co/1200x600/e74c3c/ffffff?text=Slide+2',
				'alt'    => 'Slide 2 – replace with your image',
				'width'  => '1200',
				'height' => '600',
			),
			array(
				'src'    => 'https://placehold.co/1200x600/2ecc71/ffffff?text=Slide+3',
				'alt'    => 'Slide 3 – replace with your image',
				'width'  => '1200',
				'height' => '600',
			),
			array(
				'src'    => 'https://placehold.co/1200x600/f39c12/ffffff?text=Slide+4',
				'alt'    => 'Slide 4 – replace with your image',
				'width'  => '1200',
				'height' => '600',
			),
			array(
				'src'    => 'https://placehold.co/1200x600/9b59b6/ffffff?text=Slide+5',
				'alt'    => 'Slide 5 – replace with your image',
				'width'  => '1200',
				'height' => '600',
			),
			array(
				'src'    => 'https://placehold.co/1200x600/1abc9c/ffffff?text=Slide+6',
				'alt'    => 'Slide 6 – replace with your image',
				'width'  => '1200',
				'height' => '600',
			),
		);

		$slides_html = '';
		foreach ( $slides as $index => $slide ) {
			$loading      = ( 0 === $index ) ? 'eager' : 'lazy';
			$src          = esc_url( $slide['src'] );
			$alt          = esc_attr( $slide['alt'] );
			$width        = absint( $slide['width'] );
			$height       = absint( $slide['height'] );
			$slides_html .= sprintf(
				'<div class="zslcarousel__slide">' .
					'<img src="%s" alt="%s" width="%d" height="%d" loading="%s" decoding="async">' .
					'<div class="zslcarousel__overlay"></div>' .
				'</div>',
				$src,
				$alt,
				$width,
				$height,
				$loading
			);
		}

		$carousel_html = sprintf(
			'<div class="zslcarousel" ' .
				'data-zslcarousel-dots="true" ' .
				'data-zslcarousel-autoplay="4000" ' .
				'data-zslcarousel-speed="500">' .
				'<div class="zslcarousel__track-container">' .
					'<div class="zslcarousel__track">' .
						'%s' .
					'</div>' .
				'</div>' .
				'<button class="zslcarousel__btn zslcarousel__btn--prev" type="button" aria-label="Previous slide">&#8249;</button>' .
				'<button class="zslcarousel__btn zslcarousel__btn--next" type="button" aria-label="Next slide">&#8250;</button>' .
				'<div class="zslcarousel__dots" aria-label="Carousel navigation" role="group"></div>' .
			'</div>',
			$slides_html
		);

		/* Wrap in a Custom HTML block so it renders as raw HTML in the editor */
		$pattern_content = "<!-- wp:html -->\n" . $carousel_html . "\n<!-- /wp:html -->";

		register_block_pattern(
			'zslcarousel/image-carousel',
			array(
				'title'       => __( 'Image Carousel', 'zslcarousel' ),
				'description' => __( 'A seamless, SEO-friendly infinite image carousel. Replace placeholder images with your own.', 'zslcarousel' ),
				'categories'  => array( 'zslcarousel', 'gallery' ),
				'keywords'    => array( 'carousel', 'slider', 'gallery', 'images' ),
				'content'     => $pattern_content,
			)
		);
	}
	add_action( 'init', 'zslcarousel_register_pattern' );
}
