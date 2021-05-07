# Installation

1. Pull codebase
2. Run `docker kill $(docker container ls -q)` to stop current containers.
3. Run `docker-compose up -d` to start the project

# Application

1. Update `.env` file (see `.env.example`).
2. API - `http://127.0.0.1:8020`
3. Mongo Express Server - `http://127.0.0.1:8021` (Basic Auth: username: `vivasoft`, password: `vivasoft`)
4. Mail Catcher - `http://127.0.0.1:60220`

# API Docs

API docs are available here: https://documenter.getpostman.com/view/6998749/TzRPkVgN
