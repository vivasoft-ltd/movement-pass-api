# Installation

1. Pull codebase
2. (Optional) Run `docker kill $(docker container ls -q)` to stop current containers.
3. Run `docker-compose up -d` to start the project
4. Update `.env` file (see `.env.example`).
6. Navigate to project directory and install composer `docker-compose exec pass_app composer install`
7. Restart supervisor: `docker-compose exec pass_app service supervisor restart` 

# Application

1. API (Swoole)  - `http://127.0.0.1:8020`
2. API (PHP-FPM) - `http://127.0.0.1:8820`
3. Mongo Express Server - `http://127.0.0.1:8021` (Basic Auth: username: `vivasoft`, password: `vivasoft`)
4. Mail Catcher - `http://127.0.0.1:60220`

# Access Docker Container

1. To access application container: `docker-compose exec pass_app bash`

# API Docs

API docs are available here: https://documenter.getpostman.com/view/6998749/TzRPkVgN

# Postman 

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/2d0f019400bf2cb7c554?action=collection%2Fimport#?env%5BMovement%20Pass%5D=W3sia2V5IjoiQVBQX1VSTCIsInZhbHVlIjoiaHR0cDovL2FwcC50ZXN0OjgwMjAiLCJlbmFibGVkIjp0cnVlfV0=)
