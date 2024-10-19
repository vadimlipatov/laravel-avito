docker-up: memory
	docker-compose up -d

docker-down:
	docker-compose down

docker-build: memory
	docker-compose up -d --build

test:
	docker-compose exec php-cli php vendor/bin/phpunit --colors=always

perm:
	sudo chown ${USER}:${USER} bootstrap/cache -R
	sudo chown ${USER}:${USER} storage -R
	sudo chmod 777 -R storage

assets-install:
	docker-compose exec node yarn install

assets-dev:
	docker-compose exec node yarn run dev

assets-prod:
	docker-compose exec node yarn run prod

assets-watch:
	docker-compose exec node yarn run watch

admin:
	docker-compose exec php-cli php artisan make:admin Admin admin@mail.ru 123

dump:
	docker-compose exec php-cli php composer.phar dump-autoload

memory:
	sudo sysctl -w vm.max_map_count=262144
