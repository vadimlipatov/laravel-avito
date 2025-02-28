# Docker
docker-up: memory
	docker-compose up -d

docker-down:
	docker-compose down

docker-build: memory
	docker-compose up --build -d

# Test
test:
	docker-compose exec php-cli vendor/bin/phpunit

# Assets
assets-install:
	docker-compose exec node yarn install

assets-rebuild:
	docker-compose exec node npm rebuild node-sass --force

assets-dev:
	docker-compose exec node yarn run dev

assets-watch:
	docker-compose exec node yarn run watch

# Queue
queue:
	docker-compose exec php-cli php artisan queue:work

# Horizon
horizon:
	docker-compose exec php-cli php artisan horizon

horizon-pause:
	docker-compose exec php-cli php artisan horizon:pause

horizon-continue:
	docker-compose exec php-cli php artisan horizon:continue

horizon-terminate:
	docker-compose exec php-cli php artisan horizon:terminate

# Linux
memory:
	sudo sysctl -w vm.max_map_count=262144

perm:
	sudo chgrp -R www-data storage bootstrap/cache
	sudo chmod -R ug+rwx storage bootstrap/cache
