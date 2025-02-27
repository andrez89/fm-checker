# Exit upon error
set -e
# Start a stopwatch to measure deploy time
deployStartTime=$(date +%s)
echo "Deploying application ..."

# Enter maintenance mode
#php artisan down --render=maintenance || true

# Update codebase
git fetch origin main
git reset --hard origin/main

# Install dependencies based on lock file
composer install --optimize-autoloader --no-interaction --no-dev

# Manually clear the config cache
php artisan config:cache
php artisan config:clear

# Clear cache and rebuild it
php artisan optimize

# Generate a new Vue build
#npm install
#npm run build

# Reload PHP to update opcache
# echo "" | sudo -S service php8.2-fpm reload
# Exit maintenance mode
php artisan up

# For good measure, clear the config cache once more
php artisan config:cache
php artisan config:clear

deployEndTime=$(date +%s)

echo "Application deployed! Time taken (s): " $(( $deployEndTime - $deployStartTime ))
