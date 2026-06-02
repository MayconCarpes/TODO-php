FROM php:8.2-apache

# Instalar extensões necessárias do PHP
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Alterar o DocumentRoot do Apache para a pasta /app
ENV APACHE_DOCUMENT_ROOT /var/www/html/app
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copiar os arquivos do projeto
COPY . /var/www/html/

# Ajustar permissões (se necessário)
RUN chown -R www-data:www-data /var/www/html
