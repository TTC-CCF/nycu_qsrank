# Set up
> [!IMPORTANT]
> **Pre-requisite:**  
> **php 8.1, composer 2.6.3 and docker installed in your machine, I recommand to use [WSL](https://learn.microsoft.com/zh-tw/windows/wsl/install) or other Linux base system to develop**

1. Install composer via [composer installation](https://getcomposer.org/download/)
2. fork this repository to your github account
3. Clone the repository from your github account to your local machine
```
git clone https://github.com/nycu-qs/qs-website.git
cd qs-website
```
4. Run `composer install`
5. Copy a new `.env` file  
```
cp .env.example .env
```
6. Run `docker compose up -d` from the directory of your docker-compose.yml and init.sql
> [!NOTE] 
> You should request me to get the init.sql file
7. Run database migration script and migrate database
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
8. Configure database in `.env`
9. Run `php artisan serve` and visit `http://localhost:8000` to see the website

If you don't familiar with php laravel, you can check [laravel document](https://laravel.com/docs/10.x/routing) to get more information.

# Git flow
> [!NOTE]
> You should fork a repository to develop your feature, and create a pull request to merge your branch to `main` branch when you finish your feature.

1. Fork this repository to your github account
2. Develop your feature
3. Commit your code
```shell
git add .
git commit -m "<commit_message>"
```
> [!IMPORTANT]
> The commit message must be in the format of `<type>: <subject>`, you can check [commit message format](https://www.conventionalcommits.org/en/v1.0.0/) to get more information.
4. Push your code to your branch
```shell
git push origin <branch_name>
```
5. Create a pull request from your branch to `main` branch, and request me to review your code
A pull request should include:
- A quick summary of your feature
- A description of your feature
6. Wait for code review and merge your branch to `main` branch