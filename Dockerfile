FROM php:8.4-cli


# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip zip curl npm \
    libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-install \
        pdo pdo_mysql zip intl mbstring bcmath gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Permisos Laravel
RUN chmod -R 775 storage bootstrap/cache

# Dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

# Puerto dinámico Railway
ENV PORT=8080
EXPOSE 8080

RUN php artisan config:clear \
 && php artisan cache:clear \
 && php artisan view:clear \
 && php artisan route:clear

# Arranque Laravel
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
