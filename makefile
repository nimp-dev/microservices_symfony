.PHONY: setup start stop reset logs clean test lint help update

## Update dependencies in all services
update:
	@echo "📦 Updating shared and service dependencies..."
	@set -e; \
	if [ -f "shared/composer.json" ]; then \
		echo "→ Updating shared package..."; \
		(cd shared && composer update --no-interaction --prefer-dist); \
	fi; \
	for service in services/*; do \
		if [ -f "$$service/composer.json" ]; then \
			echo "→ Updating $$service..."; \
			(cd "$$service" && composer update --no-interaction --prefer-dist); \
		fi; \
	done
	@echo "✅ All dependencies updated successfully."

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
	@rm -f services/*/.env
	@rm -rf services/*/var/ 2>/dev/null || true

## Reset everything and start fresh
reset: clean setup start

## Run PHPUnit tests for all services
test:
	@echo "🧪 Running tests for all services..."
	@for dir in services/*; do \
		if [ -f $$dir/vendor/bin/phpunit ]; then \
			echo "→ Testing $$dir"; \
			( cd $$dir && APP_ENV=test vendor/bin/phpunit ); \
		fi; \
	done

## Run PHPStan static analysis
lint:
	@echo "🔍 Running PHPStan..."
	@for dir in services/*; do \
		if [ -f $$dir/vendor/bin/phpstan ]; then \
			echo "→ Linting $$dir"; \
			( cd $$dir && vendor/bin/phpstan analyse src --level=max ); \
		fi; \
	done

## Show this help
help:
	@echo "Available commands:"
	@echo "  make setup    - Create .env files from examples"
	@echo "  make start    - Start all services"
	@echo "  make stop     - Stop all services"
	@echo "  make logs     - Show logs"
	@echo "  make clean    - Remove containers and .env files"
	@echo "  make reset    - Full reset and restart"
	@echo "  make update   - Update dependencies in all services"
	@echo "  make test     - Run test for services"
	@echo "  make lint     - Check services with PhpStan"
	@echo "  make help     - Show this help"