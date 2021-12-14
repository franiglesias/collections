.PHONY: quality1 quality2 quality3

quality: quality1 quality2 quality3

quality1:
	php8.0 ./phpcs.phar ./src ./tests --standard=PSR12

quality2:
	php8.0 ./phpmd.phar ./src ansi codesize,cleancode,controversial,design,naming,unusedcode

quality3:
	php8.0 ./phpstan.phar analyse -l 9 ./src ./tests

quality4:
	php8.0 ./psalm.phar --output-format=emacs --php-version=8.0
