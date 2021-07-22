
#Import the image with basic ubuntu system and php along with extensions installed.
FROM sandymadaan/php7.3-docker:0.4

# Copy local code to the container image.
COPY . /var/www/html/

EXPOSE 8080
RUN echo "Listen 8080" >> /etc/apache2/ports.conf
# Use the PORT environment variable in Apache configuration files.
#RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf


# Authorise .htaccess files
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

#RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
#RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


# Restart apache2
RUN service apache2 restart
