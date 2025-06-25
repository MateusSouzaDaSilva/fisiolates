# Stage 1: Build do frontend com Node
FROM node:18 AS node-builder

WORKDIR /var/www

# Copia package.json e package-lock.json para cache de dependências
COPY package*.json ./

# Instala dependências Node
RUN npm install

# Copia todo o código para build dos assets
COPY . .

# Build dos assets (Vite, Tailwind, etc)
RUN npm run build


# Stage 2: Imagem PHP
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip git curl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia o código do projeto (exceto node_modules, já buildados)
COPY . .

# Copia os assets buildados da stage 1
COPY --from=node-builder /var/www/public/build ./public/build
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões (opcional)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN chmod +x /usr/local/bin/entrypoint.sh 

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]