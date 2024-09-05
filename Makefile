docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up -d --build

test:
	docker exec app-php-cli-1 php vendor/bin/phpunit --colors=always

perm:
	sudo chown ${USER}:${USER} bootstrap/cache -R
	sudo chown ${USER}:${USER} storage -R
	if [-d "node_modules"]; then sudo chown ${USER}:${USER} node_modules -R; fi
	if [-d "public/build"]; then sudo chown ${USER}:${USER} public/build -R; fi

assets-install:
	docker exec app-node-1 yarn install

assets-dev:
	docker exec app-node-1 yarn run dev

assets-prod:
	docker exec app-node-1 yarn run prod

assets-watch:
	docker exec app-node-1 yarn run watch
