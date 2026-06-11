up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build --no-cache

# First-time setup: build images, install deps, generate key, run migrations
setup:
	docker compose build --no-cache
	docker compose up -d
	docker compose exec -u root app composer install --no-interaction --prefer-dist --optimize-autoloader
	docker compose exec -u root app chown -R www:www /var/www
	docker compose run --rm node sh -c "npm ci && npm run build"
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate --force
	@echo "\n✅  SANIA is ready at http://localhost:8080\n"

shell:
	docker compose exec app bash

artisan:
	docker compose exec app php artisan $(cmd)

composer:
	docker compose exec app composer $(cmd)

npm:
	docker compose exec node npm $(cmd)

test:
	docker compose exec app php artisan test

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

logs:
	docker compose logs -f app nginx
