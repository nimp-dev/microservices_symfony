
Symfony-based PHP microservices example (lightweight) — gateway + 3 services + PostgreSQL

lightweight


## Services
- **user-service** — manages user data
- **order-service** — manages orders
- **notification-service** — sends notifications




### Using Make (Recommended)
```bash
# 1. Setup environment
make setup

# 2. Start all services
make start

# 3. Access the application
open http://localhost:8080
```
### Manual Setup
```bash
# 1. Start services
docker-compose up -d --build
```

```bash
make setup          # Create .env files
make start          # Start all services
make stop           # Stop services
make logs           # View logs
make reset          # Full reset and restart
make help           # Show all commands
```

## Visit:

http://localhost:8080/user/

http://localhost:8080/order/

http://localhost:8080/notification/