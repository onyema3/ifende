/**
 * Ifende — no-flash theme detection.
 *
 * Sets the data-theme attribute on <html> BEFORE first paint so the page
 * never renders in the wrong color scheme. Loaded synchronously from the
 * <head> by inc/enqueue.php — do not defer or async this script.
 *
 * Source of truth for the user's choice is localStorage('ifende-theme').
 * If unset, fall back to the system color-scheme preference. The toggle
 * button wired up in main.js writes back to the same key.
 */
(function () {
  try {
    var t = localStorage.getItem('ifende-theme');
    if (!t) {
      t = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
    }
    document.documentElement.setAttribute('data-theme', t);
  } catch (e) {
    // localStorage / matchMedia unavailable — leave the document in its default state.
  }
})();
