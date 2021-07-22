
#Import the image with basic ubuntu system and php along with extensions installed.
#FROM sandymadaan/php7.3-docker:0.4

# Copy local code to the container image.
COPY . /var/www/html/

EXPOSE 8080
RUN echo "Listen 8080" >> /etc/apache2/ports.conf
# Restart apache2
RUN service apache2 restart
