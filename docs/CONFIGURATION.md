# Configuration Reference

All configuration is done through **HTML data-attributes** on the root
`.zslcarousel` element and optional **CSS custom properties**.

---

## Data Attributes

| Attribute | Default | Description |
|---|---|---|
| `data-zslcarousel-dots` | `"true"` | Show dot navigation. Set to `"false"` to hide. |
| `data-zslcarousel-autoplay` | `0` | Autoplay interval in **milliseconds**. `0` or omit to disable. |
| `data-zslcarousel-speed` | `500` | Slide transition duration in **milliseconds**. |

### Examples

```html
<!-- Dots off, no autoplay, fast transition -->
<div class="zslcarousel"
     data-zslcarousel-dots="false"
     data-zslcarousel-speed="300">
  …
</div>

<!-- Autoplay every 3 seconds, dots on (default) -->
<div class="zslcarousel"
     data-zslcarousel-autoplay="3000"
     data-zslcarousel-speed="600">
  …
</div>
```

---

## CSS Custom Properties

Set these directly on the root `.zslcarousel` element via the `style` attribute
or via CSS targeting that element.

| Property | Default | Description |
|---|---|---|
| `--zslcarousel-speed` | Set by JS from `data-zslcarousel-speed` | Transition duration used by the CSS rule. Normally you do not set this manually; use `data-zslcarousel-speed` instead. |
| `--zslcarousel-height` | `auto` | Fixed height for all slides and their images. Useful for uniform-height carousels. |

### Fixed Height Example

```html
<div class="zslcarousel"
     style="--zslcarousel-height: 480px"
     data-zslcarousel-dots="true"
     data-zslcarousel-autoplay="4000">
  …
</div>
```

---

## Modifier Classes

| Class | Effect |
|---|---|
| `zslcarousel--fullwidth` | Breaks out of the content container and spans the full viewport width. Compatible with Astra's constrained content areas. |

### Full-Width Example

```html
<div class="zslcarousel zslcarousel--fullwidth"
     data-zslcarousel-dots="true"
     data-zslcarousel-autoplay="5000">
  …
</div>
```

---

## Slide HTML Structure

Each slide must follow this structure:

```html
<div class="zslcarousel__slide">
  <img
    src="https://example.com/image.jpg"
    alt="Descriptive alt text"
    width="1200"
    height="600"
    loading="eager"
    decoding="async"
  >
  <!-- Overlay container (keep even if empty – reserved for captions) -->
  <div class="zslcarousel__overlay"></div>
</div>
```

### Notes

- **`loading`**: The JavaScript automatically sets `loading="eager"` on the first
  real slide and `loading="lazy"` on all others. You may pre-set them in the HTML
  for the initial render before JS runs.
- **`width` / `height`**: Always provide these to prevent layout shift (CLS).
- **`alt`**: Required for SEO and accessibility. Describe the image content.
- **`srcset` / `sizes`**: Optionally add responsive image attributes; the carousel
  does not interfere with them.
- **`decoding="async"`**: Tells the browser to decode the image off the main thread.

---

## Full Carousel Boilerplate

```html
<div class="zslcarousel"
     data-zslcarousel-dots="true"
     data-zslcarousel-autoplay="4000"
     data-zslcarousel-speed="500">

  <div class="zslcarousel__track-container">
    <div class="zslcarousel__track">

      <div class="zslcarousel__slide">
        <img src="/images/slide1.jpg" alt="Slide 1" width="1200" height="600"
             loading="eager" decoding="async">
        <div class="zslcarousel__overlay"></div>
      </div>

      <div class="zslcarousel__slide">
        <img src="/images/slide2.jpg" alt="Slide 2" width="1200" height="600"
             loading="lazy" decoding="async">
        <div class="zslcarousel__overlay"></div>
      </div>

      <!-- Add more .zslcarousel__slide elements as needed (6–15 recommended) -->

    </div>
  </div>

  <button class="zslcarousel__btn zslcarousel__btn--prev"
          type="button" aria-label="Previous slide">&#8249;</button>
  <button class="zslcarousel__btn zslcarousel__btn--next"
          type="button" aria-label="Next slide">&#8250;</button>

  <div class="zslcarousel__dots" aria-label="Carousel navigation" role="group"></div>

</div>
```

---

## Autoplay Behaviour

- Autoplay **pauses** on:
  - Mouse hover over the carousel
  - Keyboard focus entering the carousel
  - Browser tab becoming hidden (`visibilitychange` event)
- Autoplay **resumes** when:
  - Mouse leaves the carousel
  - Focus leaves the carousel
  - Browser tab becomes visible again

This prevents the carousel from advancing in the background and desyncing when
the user returns to the tab.

---

## Reduced Motion

When the user has enabled `prefers-reduced-motion: reduce` in their OS settings:

- CSS transitions are disabled via a media query.
- The JavaScript skips the transition class and performs instant slide changes.
- The infinite-loop clone repositioning still works correctly (no visible jump).

---

## Multiple Carousels per Page

Any number of `.zslcarousel` elements can exist on the same page. Each instance
is initialised independently with its own state, timers, and event listeners.
There are no global variables shared between instances.
