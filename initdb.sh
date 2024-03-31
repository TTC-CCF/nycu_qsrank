
# Change prev qs data type (for database migration from prev qs website)
php artisan app:change-prev-qs-data-type 
# Run migration
php artisan migrate
# Insert static data into Title, Country, Industry, Academy tables
php artisan app:insert-static   
# Hash users' password
php artisan hash:users