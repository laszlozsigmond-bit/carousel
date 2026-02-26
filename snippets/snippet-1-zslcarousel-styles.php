<?php
/**
 * zslcarousel – Snippet 1: Styles (wp_head)
 *
 * Paste this snippet into your snippets plugin and set it to run on the
 * FRONTEND ONLY (not in admin). Hook: wp_head.
 *
 * The snippet outputs an inline <style> block only on pages whose post
 * content contains the string "zslcarousel", keeping pages without a
 * carousel completely unaffected.
 */

if ( ! function_exists( 'zslcarousel_has_markup' ) ) {
	/**
	 * Return true when the current page renders at least one post whose
	 * content includes the carousel identifier string.
	 */
	function zslcarousel_has_markup() {
		global $wp_query;

		if ( ! empty( $wp_query->posts ) && is_array( $wp_query->posts ) ) {
			foreach ( $wp_query->posts as $p ) {
				if ( $p instanceof WP_Post && false !== strpos( $p->post_content, 'zslcarousel' ) ) {
					return true;
				}
			}
			return false;
		}

		// Fallback for edge cases where $wp_query->posts is not yet populated.
		$post = get_post();
		if ( $post instanceof WP_Post ) {
			return false !== strpos( $post->post_content, 'zslcarousel' );
		}

		return false;
	}
}

if ( ! function_exists( 'zslcarousel_output_styles' ) ) {
	function zslcarousel_output_styles() {
		if ( ! zslcarousel_has_markup() ) {
			return;
		}
		?>
<style id="zslcarousel-styles">
/* ============================================================
   zslcarousel – Seamless Infinite Image Carousel
   All identifiers prefixed with "zslcarousel".
   ============================================================ */

/* Root wrapper */
.zslcarousel {
	position: relative;
	width: 100%;
	max-width: 100%;
	box-sizing: border-box;
	overflow: hidden; /* clip buttons that overflow when no slides */
}

/* Full-width breakout (Astra full-width layout compatible) */
.zslcarousel--fullwidth {
	width: 100vw;
	max-width: 100vw;
	margin-left: calc(50% - 50vw);
	margin-right: calc(50% - 50vw);
}

/* Slide viewport – clips the track */
.zslcarousel__track-container {
	position: relative;
	width: 100%;
	overflow: hidden;
}

/* Flex track – width & transform are set by JS */
.zslcarousel__track {
	display: flex;
	will-change: transform;
}

/* Animated state added/removed by JS */
.zslcarousel__track--transitioning {
	transition: transform var(--zslcarousel-speed, 500ms) ease;
}

/* Individual slide */
.zslcarousel__slide {
	flex: 0 0 auto;
	position: relative;
	box-sizing: border-box;
	overflow: hidden;
}

/* Responsive image */
.zslcarousel__slide img {
	display: block;
	width: 100%;
	height: auto;
	vertical-align: middle;
	/* Optional fixed-height mode: set --zslcarousel-height on the root element */
	height: var(--zslcarousel-height, auto);
	object-fit: cover;
}

/* Overlay layer (empty by default – reserved for captions / gradients) */
.zslcarousel__overlay {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	pointer-events: none;
}

/* ── Navigation buttons ── */
.zslcarousel__btn {
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	z-index: 10;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 44px;
	height: 44px;
	padding: 0;
	background: rgba(0, 0, 0, 0.45);
	color: #fff;
	border: 2px solid transparent;
	border-radius: 4px;
	font-size: 28px;
	line-height: 1;
	cursor: pointer;
	transition: background 0.2s ease, border-color 0.2s ease;
	-webkit-user-select: none;
	user-select: none;
}

.zslcarousel__btn:hover {
	background: rgba(0, 0, 0, 0.75);
}

.zslcarousel__btn:focus-visible {
	outline: none;
	border-color: #fff;
	background: rgba(0, 0, 0, 0.75);
}

.zslcarousel__btn--prev {
	left: 12px;
}

.zslcarousel__btn--next {
	right: 12px;
}

/* ── Dot navigation ── */
.zslcarousel__dots {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 8px;
	padding: 10px 0 4px;
	list-style: none;
	margin: 0;
}

.zslcarousel__dot {
	display: inline-block;
	width: 10px;
	height: 10px;
	padding: 0;
	border: none;
	border-radius: 50%;
	background: #bbb;
	cursor: pointer;
	transition: background 0.2s ease, transform 0.2s ease;
	-webkit-user-select: none;
	user-select: none;
}

.zslcarousel__dot:hover,
.zslcarousel__dot:focus-visible {
	background: #888;
	outline: 2px solid currentColor;
	outline-offset: 2px;
}

.zslcarousel__dot--active {
	background: #333;
	transform: scale(1.3);
}

/* ── Respect prefers-reduced-motion ── */
@media (prefers-reduced-motion: reduce) {
	.zslcarousel__track--transitioning {
		transition: none !important;
	}
	.zslcarousel__btn,
	.zslcarousel__dot {
		transition: none !important;
	}
}
</style>
		<?php
	}
	add_action( 'wp_head', 'zslcarousel_output_styles', 20 );
}
