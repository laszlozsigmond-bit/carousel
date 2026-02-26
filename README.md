# zslcarousel – Seamless Infinite Image Carousel for WordPress

A lightweight, SEO-friendly infinite image carousel designed for WordPress sites
running **Astra Pro + Spectra Pro**. It is installed via a free snippets plugin as
three standalone PHP snippets — no theme-file edits, no custom plugin required.

## Features

| Feature | Detail |
|---|---|
| Infinite loop | Seamless (cloned-edge-slides + `transitionend` reposition) |
| SEO images | Real `<img>` tags, `alt`, `loading`, `decoding="async"` |
| Eager first image | First visible slide is `loading="eager"`; rest are `loading="lazy"` |
| Dot navigation | Optional, generated dynamically; toggled via `data-zslcarousel-dots` |
| Autoplay | Optional; pauses on hover, focus, and hidden tab |
| Touch/swipe | Native touch events, no library |
| Keyboard | Arrow-left / Arrow-right |
| Reduced motion | Honours `prefers-reduced-motion: reduce` |
| Multiple instances | Any number of carousels per page, each independent |
| Conditional loading | CSS/JS only injected on pages that contain carousel markup |
| Astra full-width | `zslcarousel--fullwidth` modifier breaks out of the content container |
| No dependencies | Pure PHP + vanilla JS, no jQuery |

## Quick Start

1. Install a free snippets plugin (e.g. **WPCode**, **Code Snippets**).
2. Add the three PHP snippets from the `snippets/` folder – see
   [docs/INSTALLATION.md](docs/INSTALLATION.md) for the exact steps.
3. In the block editor, open the **Patterns** tab and insert **"Image Carousel"**
   (under the *Carousel* category).
4. Replace the placeholder image `src` / `alt` values with your own images.
5. Publish – the carousel renders automatically.

## File Structure

```
snippets/
  snippet-1-zslcarousel-styles.php   # CSS output via wp_head
  snippet-2-zslcarousel-scripts.php  # JS output via wp_footer
  snippet-3-zslcarousel-pattern.php  # Gutenberg block pattern
docs/
  INSTALLATION.md
  CONFIGURATION.md
  TROUBLESHOOTING.md
README.md
```

## Documentation

- [Installation guide](docs/INSTALLATION.md)
- [Configuration reference](docs/CONFIGURATION.md)
- [Troubleshooting](docs/TROUBLESHOOTING.md)

## Browser Support

Modern evergreen browsers (Chrome, Firefox, Edge, Safari). ES5-compatible JS
with no transpilation required.

## License

MIT
