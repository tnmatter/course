include .env

docker-install:
	@if [ -z "$$(docker network ls | grep course-external)" ]; then \
		echo "Сеть course-external не найдена. Создаем..."; \
		docker network create -d bridge course-external; \
	else \
		echo "Сеть course-external уже существует. Пропускаем создание..."; \
	fi; \
	docker context use rootless
	docker compose -f ./docker-compose.yml up --build -d --force-recreate --remove-orphans

docker-stop:
	docker compose down

dphp:
	@docker exec -it course-php-container $(cmd)

up:
	@docker compose -f docker-compose.yml up -d

down:
	@docker compose -f docker-compose.yml down --remove-orphans

restart: down up