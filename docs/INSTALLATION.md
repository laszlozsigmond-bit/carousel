# Installation Guide

## Prerequisites

- WordPress 5.5 or later (block patterns API required)
- A free PHP snippets plugin. Tested with:
  - [WPCode Lite](https://wordpress.org/plugins/insert-headers-and-footers/)
  - [Code Snippets](https://wordpress.org/plugins/code-snippets/)

---

## Step 1 – Install a Snippets Plugin

Install and activate **WPCode Lite** or **Code Snippets** from the WordPress
plugin directory. Both are free and require no configuration beyond activation.

---

## Step 2 – Add Snippet 1 (Styles)

| Setting | Value |
|---|---|
| **Name** | `zslcarousel – Styles` |
| **Code type** | PHP Snippet |
| **Run on** | **Frontend only** (not admin) |
| **Hook / location** | `wp_head` (auto-handled by the snippet itself) |

**WPCode Lite:**
1. Go to **Code Snippets → + Add Snippet**.
2. Choose **PHP Snippet**.
3. Paste the entire contents of `snippets/snippet-1-zslcarousel-styles.php`.
4. Under *"Where Should I Insert This?"* choose **Site Wide Header** (or any
   location) — the snippet uses `add_action` with `wp_head` internally, so
   the location setting doesn't affect functionality, but selecting
   **Site Wide Header** keeps things organised.
5. Make sure the toggle is **Active** and click **Save**.

**Code Snippets:**
1. Go to **Snippets → Add New**.
2. Paste the code, set *"Only run in administration area"* to **off**.
3. Click **Save Changes and Activate**.

---

## Step 3 – Add Snippet 2 (Scripts)

| Setting | Value |
|---|---|
| **Name** | `zslcarousel – Scripts` |
| **Code type** | PHP Snippet |
| **Run on** | **Frontend only** (not admin) |
| **Hook / location** | `wp_footer` (auto-handled by the snippet itself) |

Follow the same steps as Snippet 1, but paste the contents of
`snippets/snippet-2-zslcarousel-scripts.php`.

For **WPCode Lite** you may select **Site Wide Footer** as the location for
clarity, though it makes no functional difference since the hook is embedded.

---

## Step 4 – Add Snippet 3 (Block Pattern)

| Setting | Value |
|---|---|
| **Name** | `zslcarousel – Block Pattern` |
| **Code type** | PHP Snippet |
| **Run on** | **Everywhere** (frontend + admin) – required so the pattern appears in the block editor |
| **Hook / location** | `init` (auto-handled by the snippet) |

Follow the same steps as Snippet 1, but paste the contents of
`snippets/snippet-3-zslcarousel-pattern.php`, and make sure *"Run in admin"* is
**enabled** (or leave it at "everywhere").

---

## Step 5 – Insert a Carousel

1. Open any post or page in the block editor.
2. Click **"+"** to add a block → switch to the **Patterns** tab.
3. Find the **Carousel** category and click **"Image Carousel"**.
4. The pattern inserts a 6-slide carousel with placeholder images.
5. Click inside the **Custom HTML** block and replace each placeholder `src` and
   `alt` value with real image URLs from your Media Library.
6. Publish/update the page.

> **Tip:** To get the URL of a Media Library image, go to
> **Media → Library**, click the image, and copy the *File URL* from the
> attachment details panel.

---

## Verifying the Installation

After publishing a page with the carousel:

1. Visit the page in a browser.
2. Open DevTools → **Console** — there should be no errors.
3. Open DevTools → **Network** — filter by `zslcarousel`; you should see no
   external requests (everything is inline).
4. Inspect the page source; you should see `<style id="zslcarousel-styles">` in
   `<head>` and `<script id="zslcarousel-scripts">` near the closing `</body>`.
5. Open a page that does **not** contain a carousel — confirm that neither the
   `<style>` nor the `<script>` tag is present.
