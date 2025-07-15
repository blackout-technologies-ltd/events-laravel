# Getting set up

### Backend
```Bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
(Follow the prompts to create an SQLite DB)
```

### Frontend
```Bash
npm install
npm run build
```

### With Herd
```Bash
herd link
```