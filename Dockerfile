# Usamos PHP CLI con servidor embebido
FROM php:8.2-cli

# Carpeta de trabajo dentro del contenedor
WORKDIR /app

# Copiamos los archivos
COPY . /app

# Puerto que Render asignará
EXPOSE 10000

# Ejecutamos el servidor PHP embebido
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} foodlitebot.php"]
