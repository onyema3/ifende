# Ifende Theme — Asset Build
# Run `make minify` to regenerate minified CSS/JS.
#
# CSS: simple sed/tr pipeline strips comments + collapses whitespace.
# JS:  uses terser when node_modules has it (after `npm install`),
#      otherwise falls back to a verbatim copy. Either way `main.min.js`
#      is byte-for-byte safe — no string-truncating sed pipelines.

.PHONY: minify css js

minify: css js
	@echo "✓ Minified assets generated"

css:
	@cat assets/css/main.css | sed 's/\/\*[^*]*\*\///g' | tr -s ' \t' ' ' | sed 's/ *{ */{/g; s/ *} */}/g; s/ *: */:/g; s/ *; */;/g; s/;}/}/g' | tr -d '\n' | sed 's/  */ /g' > assets/css/main.min.css
	@echo "  → assets/css/main.min.css"

js:
	@if [ -x ./node_modules/.bin/terser ]; then \
		./node_modules/.bin/terser assets/js/main.js --compress --mangle --output assets/js/main.min.js; \
		echo "  → assets/js/main.min.js (terser-minified)"; \
	else \
		cp assets/js/main.js assets/js/main.min.js; \
		echo "  → assets/js/main.min.js (verbatim copy — run \`npm install\` to enable terser)"; \
	fi
