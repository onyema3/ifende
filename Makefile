# Ifende Theme — Asset Build
# Run `make minify` to regenerate minified CSS/JS

.PHONY: minify css js

minify: css js
	@echo "✓ Minified assets generated"

css:
	@cat assets/css/main.css | sed 's/\/\*[^*]*\*\///g' | tr -s ' \t' ' ' | sed 's/ *{ */{/g; s/ *} */}/g; s/ *: */:/g; s/ *; */;/g; s/;}/}/g' | tr -d '\n' | sed 's/  */ /g' > assets/css/main.min.css
	@echo "  → assets/css/main.min.css"

js:
	@cat assets/js/main.js | sed '/^\/\*/,/\*\//d' | sed 's|//.*$$||' | tr -s ' \t' ' ' | tr -d '\n' | sed 's/  */ /g' > assets/js/main.min.js
	@echo "  → assets/js/main.min.js"
