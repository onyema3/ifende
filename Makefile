# Ifende Theme — Asset Build
#
# Targets:
#   make minify  — regenerate assets/css/main.min.css and assets/js/main.min.js.
#   make pot     — regenerate languages/ifende.pot from translatable strings.
#
# CSS: simple sed/tr pipeline strips comments + collapses whitespace.
# JS:  uses terser when node_modules has it (after `npm install`),
#      otherwise falls back to a verbatim copy. Either way `main.min.js`
#      is byte-for-byte safe — no string-truncating sed pipelines.
# POT: uses xgettext with the WordPress keyword set. Requires `xgettext`
#      from GNU gettext (apt: `gettext`, brew: `gettext`).

.PHONY: minify css js pot fonts

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


pot:
	@command -v xgettext >/dev/null || { echo "ERROR: xgettext not found. Install GNU gettext."; exit 1; }
	@find . \( -path ./node_modules -o -path ./.git -o -path ./languages \) -prune -o \
		\( -name '*.php' -o -name '*.js' \) -type f -print | LC_ALL=C sort > .pot-files.tmp
	@xgettext --files-from=.pot-files.tmp --from-code=UTF-8 \
		--keyword=__ --keyword=_e --keyword=_x:1,2c --keyword=_ex:1,2c \
		--keyword=_n:1,2 --keyword=_nx:1,2,4c \
		--keyword=esc_html__ --keyword=esc_html_e --keyword=esc_html_x:1,2c \
		--keyword=esc_attr__ --keyword=esc_attr_e --keyword=esc_attr_x:1,2c \
		--keyword=_n_noop:1,2 --keyword=_nx_noop:1,2,3c \
		--add-comments=translators: \
		--package-name=Ifende \
		--copyright-holder='Onyemechi Ifende' \
		--msgid-bugs-address=hello@ifende.com \
		--output=languages/ifende.pot
	@rm -f .pot-files.tmp
	@echo "  → languages/ifende.pot"


fonts:
	@command -v python3 >/dev/null || { echo "ERROR: python3 not found."; exit 1; }
	@python3 tools/fetch-fonts.py
	@echo "  → assets/fonts/*.woff2"
	@echo "  → assets/css/fonts.css"
