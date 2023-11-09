# Set up
> [!IMPORTANT]
> **Pre-requisite:**  
> **php 8.1, composer 2.6.3 and docker installed in your machine, I recommand to use [WSL](https://learn.microsoft.com/zh-tw/windows/wsl/install) or other Linux base system to develop**

1. Install composer via [composer installation](https://getcomposer.org/download/)
2. Clone this repository  
```
git clone https://github.com/nycu-qs/qs-website.git
cd qs-website
```
3. Run `composer install`
4. Copy a new `.env` file  
```
cp .env.example .env
```
5. Run `docker compose up -d` from the directory of your docker-compose.yml and init.sql
> [!NOTE] 
> You should request me to get the init.sql file
6. Run database migration script and migrate database
```shell
# Insert static data into Title, Country, Industry, Academy tables
php artisan app:insert-static   
# Hash users' password
php artisan hash:users
# Change prev qs data type (for database migration from prev qs website)
php artisan app:change-prev-qs-data-type 
# Run migration
php artisan migrate
```
> [!NOTE]
> These commands are defined in `app/Console/Commands` directory, you can check the code to get more information
7. Configure database in `.env`
8. Run `php artisan serve` and visit `http://localhost:8000` to see the website

If you don't familiar with php laravel, you can check [laravel document](https://laravel.com/docs/10.x/routing) to get more information.