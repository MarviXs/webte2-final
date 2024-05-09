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
# php artisan migrate:fresh --seed
```

### URLs

- Frontend: [http://localhost:7080](http://localhost:7080)
- API Docs: [http://localhost:7080/docs/api](http://localhost:7080/docs/api)
- Backend: [http://localhost:7080/api](http://localhost:7080/api)
- PHPMyAdmin: [http://localhost:7080/phpmyadmin/](http://localhost:7080/phpmyadmin/)

### DB credentials

- Username: `root`
- Password: `root`
- Database: `laravel`

### Config

`frontend/env.development`
`backend/config`

## TODO

- [X]  Wordcloud
- [ ]  Preklad (SK, EN)
- [X]  Porovnanie historického hlasovania - pridať aktuálne výsledky
- [X]  Export otázok
- [X]  Zmena hesla
- [X]  Admin zobrazenie otázok + filtrovanie
- [ ]  Admin správa použivateľov
- [ ]  Príručka + export
- [ ]  Video dokumentácia
