# Test Project

## Requirements
- Docker
- Docker Compose

## Setup

1. Clone repository
2. Copy environment file:
   cp .env.example .env

3. Start project:
   make up

4. Open in browser:
   http://localhost:${NGINX_PORT}

## Useful commands

make up        # start containers

make down      # stop containers

make build     # rebuild images

make logs      # show logs

make bash      # enter php container