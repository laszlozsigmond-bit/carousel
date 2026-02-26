<?php
/**
 * zslcarousel – Snippet 2: Scripts (wp_footer)
 *
 * Paste this snippet into your snippets plugin and set it to run on the
 * FRONTEND ONLY (not in admin). Hook: wp_footer.
 *
 * The snippet outputs an inline <script> block only on pages whose post
 * content contains the string "zslcarousel".
 *
 * Requires: snippet-1-zslcarousel-styles.php to be active.
 * Dependencies: none – vanilla JS, no jQuery.
 */

if ( ! function_exists( 'zslcarousel_has_markup' ) ) {
	/**
	 * Return true when the current page renders at least one post whose
	 * content includes the carousel identifier string.
	 * (Defined here as a fallback in case snippet-1 is not active.)
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

		$post = get_post();
		if ( $post instanceof WP_Post ) {
			return false !== strpos( $post->post_content, 'zslcarousel' );
		}

		return false;
	}
}

if ( ! function_exists( 'zslcarousel_output_scripts' ) ) {
	function zslcarousel_output_scripts() {
		if ( ! zslcarousel_has_markup() ) {
			return;
		}
		?>
<script id="zslcarousel-scripts">
/* jshint esversion: 6 */
(function () {
	'use strict';

	/* ── Data-attribute names ── */
	var ATTR_DOTS     = 'data-zslcarousel-dots';
	var ATTR_AUTOPLAY = 'data-zslcarousel-autoplay';
	var ATTR_SPEED    = 'data-zslcarousel-speed';
	var DEFAULT_SPEED = 500;

	/* ── Initialise all carousels found on the page ── */
	function zslcarouselInitAll() {
		var carousels = document.querySelectorAll('.zslcarousel');
		for (var i = 0; i < carousels.length; i++) {
			zslcarouselInitInstance(carousels[i]);
		}
	}

	/* ── Initialise a single carousel instance ── */
	function zslcarouselInitInstance(el) {
		/* Guard against double-initialisation */
		if (el.getAttribute('data-zslcarousel-init')) { return; }
		el.setAttribute('data-zslcarousel-init', '1');

		var track = el.querySelector('.zslcarousel__track');
		if (!track) { return; }

		/* Collect the real (author-authored) slides */
		var originalSlides = Array.prototype.slice.call(
			track.querySelectorAll('.zslcarousel__slide')
		);
		var slideCount = originalSlides.length;
		if (slideCount < 1) { return; }

		/* ── Configuration from data-attributes ── */
		var speed        = parseInt(el.getAttribute(ATTR_SPEED), 10)    || DEFAULT_SPEED;
		var dotsEnabled  = el.getAttribute(ATTR_DOTS) !== 'false';
		var autoplayMs   = parseInt(el.getAttribute(ATTR_AUTOPLAY), 10) || 0;
		var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		/* Expose the speed as a CSS custom property for the transition */
		el.style.setProperty('--zslcarousel-speed', speed + 'ms');

		/* ── Loading attributes on original slides ── */
		originalSlides.forEach(function (slide, i) {
			var img = slide.querySelector('img');
			if (!img) { return; }
			if (i === 0) {
				img.setAttribute('loading', 'eager');
			} else if (!img.getAttribute('loading')) {
				img.setAttribute('loading', 'lazy');
			}
		});

		/* ── Clone first & last slides for seamless infinite loop ──
		 *
		 *  allSlides layout after cloning:
		 *  [ cloneLast | slide0 | slide1 | … | slideN-1 | cloneFirst ]
		 *   index:  0        1       2           slideCount  slideCount+1
		 *
		 *  We start at index 1 (the real first slide).
		 */
		var cloneFirst = originalSlides[0].cloneNode(true);
		var cloneLast  = originalSlides[slideCount - 1].cloneNode(true);

		/* Clones are decorative; hide from assistive tech */
		cloneFirst.setAttribute('aria-hidden', 'true');
		cloneLast.setAttribute('aria-hidden', 'true');

		/* Clones are lazy – they are never the first visible image */
		[cloneFirst, cloneLast].forEach(function (clone) {
			var img = clone.querySelector('img');
			if (img) { img.setAttribute('loading', 'lazy'); }
		});

		track.appendChild(cloneFirst);
		track.insertBefore(cloneLast, originalSlides[0]);

		var allSlides  = Array.prototype.slice.call(track.querySelectorAll('.zslcarousel__slide'));
		var totalSlides = allSlides.length; /* slideCount + 2 */

		/* ── State ── */
		var currentIndex  = 1;   /* 1-based; 1 = real first slide */
		var isTransitioning = false;

		/* ── Layout helpers ── */
		var container = el.querySelector('.zslcarousel__track-container');

		function getSlideWidth() {
			return container.clientWidth;
		}

		function applyTransformInstant() {
			track.classList.remove('zslcarousel__track--transitioning');
			track.style.transform = 'translateX(-' + (currentIndex * getSlideWidth()) + 'px)';
		}

		function applyTransformAnimated() {
			if (reducedMotion) {
				/* Skip animation; handle clone jumps synchronously */
				applyTransformInstant();
				handleCloneJump();
				return;
			}
			track.classList.add('zslcarousel__track--transitioning');
			track.style.transform = 'translateX(-' + (currentIndex * getSlideWidth()) + 'px)';
			isTransitioning = true;
		}

		function setLayout() {
			var w = getSlideWidth();
			allSlides.forEach(function (slide) {
				slide.style.width = w + 'px';
			});
			track.style.width = (totalSlides * w) + 'px';
			applyTransformInstant();
		}

		/* ── Infinite-loop clone jump (called after transition ends) ── */
		function handleCloneJump() {
			if (currentIndex === totalSlides - 1) {
				/* We landed on cloneFirst → jump to real first */
				currentIndex = 1;
				applyTransformInstant();
			} else if (currentIndex === 0) {
				/* We landed on cloneLast → jump to real last */
				currentIndex = slideCount;
				applyTransformInstant();
			}
			updateDots();
		}

		/* ── transitionend handler ── */
		track.addEventListener('transitionend', function (e) {
			if (e.target !== track || e.propertyName !== 'transform') { return; }
			isTransitioning = false;
			handleCloneJump();
		});

		/* ── Navigation ── */
		function goTo(index, animate) {
			if (isTransitioning && animate) { return; }
			currentIndex = index;
			if (animate) {
				applyTransformAnimated();
			} else {
				applyTransformInstant();
			}
			updateDots();
		}

		function next() { goTo(currentIndex + 1, true); }
		function prev() { goTo(currentIndex - 1, true); }

		/* ── Dot navigation ── */
		var dotsEl = el.querySelector('.zslcarousel__dots');
		var dots   = [];

		function updateDots() {
			if (!dotsEnabled || dots.length === 0) { return; }
			/* Compute 0-based real index, normalising clone indices */
			var realIdx = currentIndex - 1;
			if (realIdx < 0)           { realIdx = slideCount - 1; }
			if (realIdx >= slideCount) { realIdx = 0; }
			dots.forEach(function (dot, i) {
				dot.classList.toggle('zslcarousel__dot--active', i === realIdx);
			});
		}

		if (dotsEnabled && dotsEl) {
			for (var d = 0; d < slideCount; d++) {
				(function (idx) {
					var dot = document.createElement('button');
					dot.className = 'zslcarousel__dot';
					dot.setAttribute('type', 'button');
					dot.setAttribute('aria-label', 'Go to slide ' + (idx + 1));
					dot.addEventListener('click', function () { goTo(idx + 1, true); });
					dotsEl.appendChild(dot);
					dots.push(dot);
				}(d));
			}
		} else if (dotsEl) {
			dotsEl.style.display = 'none';
		}

		/* ── Prev / Next button wiring ── */
		var btnPrev = el.querySelector('.zslcarousel__btn--prev');
		var btnNext = el.querySelector('.zslcarousel__btn--next');
		if (btnPrev) { btnPrev.addEventListener('click', prev); }
		if (btnNext) { btnNext.addEventListener('click', next); }

		/* ── Keyboard navigation ── */
		el.setAttribute('tabindex', '0');
		el.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft')  { prev(); e.preventDefault(); }
			if (e.key === 'ArrowRight') { next(); e.preventDefault(); }
		});

		/* ── Touch / swipe support ── */
		var touchStartX = 0;
		var touchStartY = 0;
		var touchMoved  = false;

		el.addEventListener('touchstart', function (e) {
			touchStartX = e.touches[0].clientX;
			touchStartY = e.touches[0].clientY;
			touchMoved  = false;
		}, { passive: true });

		el.addEventListener('touchmove', function () {
			touchMoved = true;
		}, { passive: true });

		el.addEventListener('touchend', function (e) {
			if (!touchMoved) { return; }
			var dx = e.changedTouches[0].clientX - touchStartX;
			var dy = e.changedTouches[0].clientY - touchStartY;
			/* Only trigger horizontal swipe if horizontal movement dominates */
			if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 30) {
				if (dx < 0) { next(); } else { prev(); }
			}
		});

		/* ── Autoplay ── */
		var autoplayTimer = null;

		function startAutoplay() {
			if (!autoplayMs || autoplayTimer) { return; }
			autoplayTimer = setInterval(function () {
				if (!document.hidden) { next(); }
			}, autoplayMs);
		}

		function stopAutoplay() {
			if (autoplayTimer) {
				clearInterval(autoplayTimer);
				autoplayTimer = null;
			}
		}

		if (autoplayMs) {
			el.addEventListener('mouseenter', stopAutoplay);
			el.addEventListener('mouseleave', startAutoplay);
			el.addEventListener('focusin',    stopAutoplay);
			el.addEventListener('focusout',   startAutoplay);
			document.addEventListener('visibilitychange', function () {
				if (document.hidden) { stopAutoplay(); } else { startAutoplay(); }
			});
			startAutoplay();
		}

		/* ── Responsive resize ── */
		var resizeTimer = null;
		window.addEventListener('resize', function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				isTransitioning = false; /* reset in case resize interrupts a transition */
				setLayout();
			}, 150);
		});

		/* ── Initial render ── */
		setLayout();
		updateDots();
	}

	/* ── Bootstrap ── */
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', zslcarouselInitAll);
	} else {
		zslcarouselInitAll();
	}
}());
</script>
		<?php
	}
	add_action( 'wp_footer', 'zslcarousel_output_scripts', 20 );
}
