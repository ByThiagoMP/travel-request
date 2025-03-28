FROM php:8.2-fpm

# Instala extensões necessárias do PHP
RUN apt-get update && apt-get install -y \
    git curl unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia os arquivos do projeto
COPY . .

# Instala dependências
RUN composer install

# Permissões
RUN chown -R www-data:www-data storage bootstrap/cache

# Porta default do Laravel
EXPOSE 8000

# Comando ao iniciar o container
CMD php artisan serve --host=0.0.0.0 --port=8000
