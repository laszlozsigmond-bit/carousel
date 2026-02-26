# Troubleshooting

## The carousel does not appear / shows unstyled HTML

**Cause:** One or more snippets are not active.

**Fix:**
1. Open your snippets plugin and verify that all three snippets are **enabled**.
2. Check that Snippet 1 and Snippet 2 are set to run on the **frontend** (not
   admin-only).
3. Check that Snippet 3 is set to run **everywhere** (frontend + admin).

---

## The CSS/JS is not injected even though the carousel is on the page

**Cause:** The conditional check (`zslcarousel_has_markup()`) did not find the
carousel string in the post content.

This can happen when:
- The carousel was added via a **widget**, **sidebar**, or **template part** that
  is stored outside the post's `post_content` column.
- The page uses a **page builder** that stores content in post meta rather than
  `post_content`.

**Fix (Option A):** Remove the conditional check — edit Snippet 1 and Snippet 2,
delete the `if ( ! zslcarousel_has_markup() ) { return; }` line, and save. The
CSS/JS will now load on every frontend page.

**Fix (Option B):** Manually enqueue on specific page IDs. Add the following to
the snippet above the `add_action` call, replacing `42` with your page ID:

```php
function zslcarousel_has_markup() {
    return is_page( 42 ); // change to your page ID or use is_page( array(42, 57) )
}
```

---

## Infinite loop jumps / flashes at the wrap-around point

**Cause:** The `transitionend` event is not firing, or the slide widths are not
set correctly.

**Check:**
1. Open DevTools → Console — look for any JavaScript errors.
2. Inspect the `.zslcarousel__track` element; it should have an inline `width`
   and `transform` style set by the JS.
3. The `.zslcarousel__slide` elements should each have an inline `width` style.

**Common causes:**
- A CSS rule elsewhere is overriding `.zslcarousel__track { display: flex }` or
  the slide widths. Inspect the element in DevTools and look for conflicting rules.
- The carousel is inside a hidden element (`display: none`) at page load. JS
  cannot measure `clientWidth` of hidden elements. Initialise the carousel after
  it becomes visible, or use `ResizeObserver`.

---

## Dots are not updating correctly

**Cause:** Usually caused by a conflict between the dot click handler index and
the actual slide position.

**Check:** Open DevTools → Console. If there are no errors, verify that
`data-zslcarousel-dots` is not set to `"false"` on the carousel element.

---

## Autoplay does not stop on hover

**Cause:** A CSS `pointer-events: none` on the carousel or a wrapping element is
preventing mouse events from reaching the carousel.

**Fix:** Ensure the `.zslcarousel` element and its ancestors have
`pointer-events: auto` (or unset).

---

## Autoplay desyncs after switching tabs

This should not happen with the current implementation because autoplay is paused
via the `visibilitychange` event. If you observe desyncing:

1. Check for JS errors in the console.
2. Make sure Snippet 2 is the only script managing the carousel (no conflicting
   third-party carousel library loaded on the same page).

---

## Touch / swipe does not work

**Cause:** Another element (e.g. a lightbox or overlay) is consuming touch events.

**Check:**
1. Verify there is no element with `touch-action: none` covering the carousel.
2. Ensure no third-party script is calling `e.preventDefault()` on `touchstart`
   before the carousel's handler runs.

---

## Images show a layout shift (CLS) before the carousel initialises

**Cause:** Missing `width` and `height` attributes on `<img>` tags.

**Fix:** Always provide `width` and `height` attributes on every carousel image
(they should match the image's intrinsic dimensions). This lets the browser
reserve the correct space before the image loads.

---

## The block pattern does not appear in the editor

**Cause:** Snippet 3 is set to run on frontend only.

**Fix:** Edit Snippet 3 in your snippets plugin and change the execution context
to **"everywhere"** (or enable "Run in administration area"). The `init` hook
must fire in the admin context for the pattern to be registered in the block
editor.

---

## "Call to undefined function register_block_pattern()"

**Cause:** Your WordPress version is older than 5.5.

**Fix:** Update WordPress. Block patterns require WordPress 5.5+. The snippet
contains a version guard and will silently do nothing on older versions.

---

## Two carousels on the same page conflict with each other

This should not happen because each carousel instance is fully independent.
If you observe conflicts:

1. Make sure neither carousel has a manually set `data-zslcarousel-init`
   attribute in the HTML — that attribute is used by the JS to prevent
   double-initialisation.
2. Check that you are not running Snippet 2 more than once (duplicate snippet).

---

## Getting More Help

If the steps above do not resolve your issue:

1. Open DevTools → Console and copy any error messages.
2. Open DevTools → Elements, select the `.zslcarousel` element, and screenshot
   the **Styles** panel showing applied CSS.
3. Check the **Network** tab for any failed resource loads.

Include this information when reporting an issue.
