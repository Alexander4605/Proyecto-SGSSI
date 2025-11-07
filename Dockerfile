FROM php:7.2.2-apache
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
RUN a2enmod headers

# --- Configuración de Seguridad de Apache (Oculta ServerTokens) ---
RUN echo "\n# --- Configuración de Seguridad Personalizada ---" >> /etc/apache2/apache2.conf && \
    echo "ServerTokens Prod" >> /etc/apache2/apache2.conf && \
    echo "ServerSignature Off" >> /etc/apache2/apache2.conf

# --- Configuración de Seguridad de PHP (Oculta X-Powered-By) ---
RUN echo "expose_php = Off" > /usr/local/etc/php/conf.d/zz-security.ini


RUN sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

