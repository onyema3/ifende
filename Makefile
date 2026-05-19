# Ifende Theme — Asset Build
# Run `make minify` to regenerate minified CSS/JS.
#
# CSS: simple sed/tr pipeline strips comments + collapses whitespace.
# JS:  pass-through copy. The previous sed-based JS minifier corrupted
#      string literals containing `//` (e.g. `'https://...'`) and deleted
#      whole blocks of code wrapped in `/* ... */`. Until a real minifier
#      (terser/esbuild) is wired up, we ship `main.js` verbatim as
#      `main.min.js`. Gzip handles the redundancy in transport, and the
#      file is small enough that the practical size hit is negligible.

.PHONY: minify css js

minify: css js
	@echo "✓ Minified assets generated"

css:
	@cat assets/css/main.css | sed 's/\/\*[^*]*\*\///g' | tr -s ' \t' ' ' | sed 's/ *{ */{/g; s/ *} */}/g; s/ *: */:/g; s/ *; */;/g; s/;}/}/g' | tr -d '\n' | sed 's/  */ /g' > assets/css/main.min.css
	@echo "  → assets/css/main.min.css"

js:
	@cp assets/js/main.js assets/js/main.min.js
	@echo "  → assets/js/main.min.js (copy of main.js — see Makefile note)"
