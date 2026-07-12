FROM php:8.2-cli

WORKDIR /app

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080"]

