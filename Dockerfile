# Usar una imagen base de PHP con Apache
FROM php:8.1-apache

# Instalar extensiones de PHP necesarias para MariaDB
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite para URLs amigables (por si acaso)
RUN a2enmod rewrite

# Copiar los archivos de la aplicación al contenedor
COPY html/ /var/www/html/

# Cambiar los permisos del directorio
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80
