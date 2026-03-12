DC=docker compose

up:
	$(DC) up -d

down:
	$(DC) down

logs:
	$(DC) logs -f

bash:
	$(DC) exec php bash