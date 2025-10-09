.PHONY: setup start stop logs clean help

## Setup project environment
setup:
	@echo "🚀 Setting up microservices environment..."
	@cp -n .env.example .env || true
	@cp -n services/order-service/.env.example services/order-service/.env || true
	@cp -n services/user-service/.env.example services/user-service/.env || true
	@cp -n services/notification-service/.env.example services/notification-service/.env || true
	@echo "✅ Environment files created from examples"

## Start all services
start:
	@echo "🐳 Starting all services..."
	docker-compose up -d --build

## Stop all services
stop:
	@echo "🛑 Stopping services..."
	docker-compose down

## Show logs
logs:
	docker-compose logs -f


## Clean up (remove containers, volumes)
clean:
	@echo "🧹 Cleaning up..."
	docker-compose down -v
	rm -f services/*/.env

## Reset everything and start fresh
reset: clean setup start

## Show this help
help:
	@echo "Available commands:"
	@echo "  make setup    - Create .env files from examples"
	@echo "  make start    - Start all services"
	@echo "  make stop     - Stop all services"
	@echo "  make logs     - Show logs"
	@echo "  make clean    - Remove containers and .env files"
	@echo "  make reset    - Full reset and restart"
	@echo "  make help     - Show this help"