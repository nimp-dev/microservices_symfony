
Symfony-based PHP microservices example (lightweight) — gateway + 3 services + PostgreSQL

How to run:
1. Ensure Docker and Docker Compose are installed.
2. In the project root run:
   docker compose up --build -d
3. Open http://localhost:8080/ and use endpoints:
   - http://localhost:8080/users
   - http://localhost:8080/users/ping
   - http://localhost:8080/orders
   - http://localhost:8080/orders/ping
   - http://localhost:8080/notify
   - http://localhost:8080/notify/ping

Notes:
- Each service contains a composer.json requiring Symfony components. Composer will run during image build.
- PostgreSQL container creates empty databases for each service using the init script in `postgres-init/`.
- This is a minimal educational example. In production you'd use php-fpm + nginx, proper env management, migrations, and stronger security.
