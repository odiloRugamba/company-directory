up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build --no-cache

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
