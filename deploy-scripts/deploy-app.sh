#!/bin/bash

# Script de despliegue de la aplicación Laravel
echo "🚀 Desplegando aplicación Laravel..."

# Variables (modificar según tu configuración)
REPO_URL="https://github.com/TU_USUARIO/TU_REPOSITORIO.git"  # Cambiar por tu repo
APP_DIR="/var/www/laravel"

# Crear directorio de aplicación
sudo mkdir -p $APP_DIR
sudo chown ec2-user:ec2-user $APP_DIR

# Clonar repositorio (si es primera vez) o actualizar
if [ ! -d "$APP_DIR/.git" ]; then
    echo "📦 Clonando repositorio..."
    git clone $REPO_URL $APP_DIR
else
    echo "🔄 Actualizando repositorio..."
    cd $APP_DIR
    git pull origin main
fi

cd $APP_DIR

# Instalar dependencias de PHP
echo "📦 Instalando dependencias de Composer..."
composer install --optimize-autoloader --no-dev

# Instalar dependencias de Node.js
echo "📦 Instalando dependencias de NPM..."
npm install

# Compilar assets
echo "🏗️ Compilando assets con Vite..."
npm run build

# Configurar permisos
echo "🔐 Configurando permisos..."
sudo chown -R ec2-user:nginx $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache

# Configurar variables de entorno
if [ ! -f "$APP_DIR/.env" ]; then
    echo "⚙️ Creando archivo .env..."
    cp $APP_DIR/.env.example $APP_DIR/.env
    echo "❗ IMPORTANTE: Edita el archivo .env con tus configuraciones de base de datos"
fi

# Generar clave de aplicación
php artisan key:generate

# Cachear configuraciones (solo en producción)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones (cuidado en producción!)
read -p "¿Ejecutar migraciones de base de datos? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
fi

echo "✅ Aplicación desplegada correctamente en $APP_DIR" 