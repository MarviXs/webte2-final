# Zadanie 4

## Local development

### Start the app in development mode
```bash
docker compose -f compose.yaml -f compose.dev.yaml up -d --build
```

### Run in PHP container
```bash
composer install
php artisan migrate
```

### URLs
- Frontend: [http://localhost:7080](http://localhost:7080)
- Backend: [http://localhost:7080/api](http://localhost:7080/api)
- PHPMyAdmin: [http://localhost:7080/phpmyadmin/](http://localhost:7080/phpmyadmin/)

### DB credentials
- Username: `root`
- Password: `root`
- Database: `laravel`

### Config
`frontend/env.development`
`backend/config`