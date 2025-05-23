#!/bin/bash

# Script de instalación para EC2 Amazon Linux 2023
# Para aplicación Laravel con PHP 8.2, Node.js y Nginx

echo "🚀 Instalando dependencias en EC2..."

# Actualizar el sistema
sudo dnf update -y

# Instalar PHP 8.2 y extensiones necesarias
sudo dnf install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-pdo php8.2-tokenizer php8.2-bcmath php8.2-json php8.2-openssl

# Instalar Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Node.js 18
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo dnf install -y nodejs

# Instalar Nginx
sudo dnf install -y nginx

# Instalar Git
sudo dnf install -y git

# Habilitar y iniciar servicios
sudo systemctl enable nginx
sudo systemctl enable php8.2-fpm
sudo systemctl start nginx
sudo systemctl start php8.2-fpm

echo "✅ Dependencias instaladas correctamente" 