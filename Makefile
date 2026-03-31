DC=docker compose

.PHONY: up down logs bash env

# проверка .env
env:
	@if [ ! -f .env ]; then \
		echo ".env not found, copying from .env.example"; \
		cp .env.example .env; \
	else \
		echo ".env already exists"; \
	fi

# поднять контейнеры + проверка .env
up: env
	$(DC) up -d

down:
	$(DC) down

logs:
	$(DC) logs -f

bash:
	$(DC) exec php bash