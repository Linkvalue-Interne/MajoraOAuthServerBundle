#
# Main tasks
#
install: install-infra install-vendors

update: update-composer

#
# Directories & files
#
bin:
	mkdir -p bin/ || /bin/true
bin/composer:
	curl -sS https://getcomposer.org/installer | php -- --install-dir=bin --filename=composer
	chmod +x bin/composer || /bin/true
bin/php-cs-fixer:
	wget http://get.sensiolabs.org/php-cs-fixer.phar -O bin/php-cs-fixer
	chmod +x bin/php-cs-fixer || /bin/true
bin/phpunit:
	ln -s ../vendor/phpunit/phpunit/phpunit bin/phpunit
update-bin: bin bin/composer bin/php-cs-fixer
	./bin/composer self-update
	php bin/php-cs-fixer self-update
.git/hooks/pre-commit:
	curl https://raw.githubusercontent.com/LinkValue/symfony-git-hooks/master/pre-commit -o .git/hooks/pre-commit
	chmod +x .git/hooks/pre-commit || /bin/true

install-infra: update-bin .git/hooks/pre-commit

#
# Librairies
#
install-composer: bin/composer
	./bin/composer install
update-composer: bin/composer
	./bin/composer update --no-scripts

install-vendors: install-composer

#
# Tests
#
tests: test-phpunit-coverage

test-phpunit: bin/phpunit
	./bin/phpunit

test-phpunit-coverage: bin/phpunit
	rm -rf tests-coverage/* || /bin/true
	./bin/phpunit --coverage-html tests-coverage

#
# CI
#
ci-tests: test-phpunit

ci-install-composer: bin/composer
	./bin/composer install --prefer-dist

travis: ci-install-composer ci-tests
