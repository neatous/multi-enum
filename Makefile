-include local/Makefile

all: deps qa

deps:
	docker run -it --rm -v ${PWD}:/app -w /app composer composer update --prefer-dist --verbose --no-interaction --optimize-autoloader --ignore-platform-reqs

qa:
	$(MAKE) phpstan
	$(MAKE) cs
	$(MAKE) phpunit

phpstan:
	docker run -it --rm -v ${PWD}:/app -w /app php:8.1-cli-alpine php -d error_reporting=-1 -d memory_limit=-1 vendor/bin/phpstan --ansi analyse

cs:
	docker run -it --rm -v ${PWD}:/app -w /app php:8.1-cli-alpine php vendor/bin/phpcs --standard=./phpcs.xml --extensions=php --tab-width=4 -sp ./src ./tests

csf:
	docker run -it --rm -v ${PWD}:/app -w /app php:8.1-cli-alpine php vendor/bin/phpcbf --standard=./phpcs.xml --extensions=php --tab-width=4 -sp ./src ./tests

phpunit:
	docker run -it --rm -v ${PWD}:/app -w /app php:8.1-cli-alpine php -d error_reporting=-1 vendor/bin/phpunit --colors=always -c phpunit.xml
