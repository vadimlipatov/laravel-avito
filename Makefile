docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up -d --build

test:
	docker-compose exec php-cli php vendor/bin/phpunit --colors=always

perm:
	sudo chown ${USER}:${USER} bootstrap/cache -R
	sudo chown ${USER}:${USER} storage -R
	sudo chmod 777 -R storage
	if [-d "node_modules"]; then sudo chown ${USER}:${USER} node_modules -R; fi
	if [-d "public/build"]; then sudo chown ${USER}:${USER} public/build -R; fi

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
