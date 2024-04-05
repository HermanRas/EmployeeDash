# FROM microsoft/mssql-tools as mssql ##Depricated
FROM namoshek/php-mssql:8.2-fpm-alpine

# setup webroot
RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# setup security
RUN addgroup -g 1000 wwwphp && adduser -G wwwphp -g wwwphp -s /bin/sh -D wwwphp
RUN mkdir -p /var/www/html
RUN chown wwwphp:wwwphp /var/www/html
ADD ./www.conf /usr/local/etc/php-fpm.d/www.conf