FROM php:8-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

WORKDIR /app

COPY ./api ./api
COPY ./database ./database
COPY ./entities ./entities
COPY ./infrastructure ./infrastructure
COPY ./interface_adapters ./interface_adapters
COPY ./use_cases ./use_cases
COPY ./env.php .

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000"]