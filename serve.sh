#!/bin/bash

# Si la variable PORT no está definida, usar 8080 por defecto
PORT=${PORT:-8080}

php artisan serve --host=0.0.0.0 --port=$PORT