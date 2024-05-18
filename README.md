# Final

| Task                                          | Completed | By                             |
| --------------------------------------------- | :-------: | ------------------------------ |
| Bilingual functionality                       |     ✔     | Samuel Takáci                  |
| Login functionality (2 roles)                 |     ✔     | Marek Šmogrovič                |
| GUI and functionality for non-logged-in users |     ✔     | Marek Šmogrovič                |
| GUI and functionality for logged-in users     |     ✔     | Marek Šmogrovič, Dominik Kysel |
| GUI and functionality for administrators      |     ✔     | Dominik Kysel                  |
| Export to CSV and PDF                         |     ✔     | Dominik Kysel                  |
| Docker package                                |     ✔     | Marek Šmogrovič                |
| Guide                                         |     ✔     | Slavomír Mikolášek             |
| Version control usage by all team members     |     ✔     |                                |
| Finalization of the application               |     ✔     |                                |
| Video                                         |     ✔     | Samuel Takáci                  |

### App demo admin credentials

- Username: `user@example.com`
- Password: `string`

### App demo user credentials

- Username: `user2@example.com`
- Password: `string`

## Production

### Start the app in production mode

```bash
docker compose -f compose.yaml up -d --build
```

### Run in PHP container

```bash
composer install
php artisan migrate
# php artisan migrate:fresh --seed
```

### URLs

- Frontend: [https://node107.webte.fei.stuba.sk:7443](https://node107.webte.fei.stuba.sk:7443)
- Backend: [https://node107.webte.fei.stuba.sk:7443/api](https://node107.webte.fei.stuba.sk:7443/api)
- PHPMyAdmin: [https://node107.webte.fei.stuba.sk:7443/phpmyadmin/](https://node107.webte.fei.stuba.sk:7443/phpmyadmin/)

### DB credentials

- Username: `webte`
- Password: `WebteFinal123[]`
- Database: `laravel`

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

### App demo admin credentials

- Username: `user@example.com`
- Password: `string`

### Config

`frontend/env.development`
`backend/config`
