# Dockerfile
FROM nimmis/apache-php5

MAINTAINER Luizhms <luizhms22@hotmail.com>
CMD ["mkdir var/www/public"]

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
ADD public/ /var/www/public

EXPOSE 80
EXPOSE 443

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_ROOT_USER=root

