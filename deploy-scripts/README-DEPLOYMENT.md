# 🚀 Guía de Despliegue Laravel en AWS EC2 + RDS

Esta guía te ayudará a desplegar tu aplicación Laravel en AWS usando EC2 y RDS con tu cuenta de estudiantes.

## 📋 Requisitos previos

- ✅ Cuenta de AWS Educate/Student
- ✅ Aplicación Laravel funcionando localmente
- ✅ Repositorio Git (GitHub/GitLab) con tu código

## 🏗️ Paso 1: Configurar RDS (Base de datos)

### 1.1 Crear instancia RDS
1. Ve a **RDS** en la consola de AWS
2. Haz clic en **Create database**
3. Selecciona:
   - **Engine**: MySQL 8.0
   - **Template**: Free tier
   - **DB instance identifier**: `laravel-db`
   - **Master username**: `admin`
   - **Master password**: `TuPasswordSeguro123!`
   - **DB instance class**: `db.t3.micro` (free tier)
   - **Storage**: 20 GB (free tier)

### 1.2 Configurar seguridad
1. En **Connectivity**:
   - **VPC**: Default VPC
   - **Public access**: Yes (para facilitar la configuración inicial)
   - **VPC security group**: Crear nuevo
   - **Availability Zone**: No preference

2. **¡IMPORTANTE!** Anota el **endpoint** de tu RDS cuando se cree.

### 1.3 Configurar Security Group de RDS
1. Ve a **EC2** > **Security Groups**
2. Busca el security group de RDS recién creado
3. Edita **Inbound rules**:
   - **Type**: MySQL/Aurora
   - **Protocol**: TCP
   - **Port**: 3306
   - **Source**: Custom (lo configuraremos después con la IP de EC2)

## 🖥️ Paso 2: Configurar EC2 (Servidor web)

### 2.1 Lanzar instancia EC2
1. Ve a **EC2** en la consola de AWS
2. Haz clic en **Launch instance**
3. Configuración:
   - **Name**: `Laravel-Server`
   - **AMI**: Amazon Linux 2023
   - **Instance type**: `t2.micro` (free tier)
   - **Key pair**: Crear nuevo o usar existente
   - **Security group**: Crear nuevo con estas reglas:
     - SSH (22) desde tu IP
     - HTTP (80) desde cualquier lugar (0.0.0.0/0)
     - HTTPS (443) desde cualquier lugar (0.0.0.0/0)

### 2.2 Conectar a EC2
```bash
# Cambiar permisos de la clave
chmod 400 tu-clave.pem

# Conectar por SSH
ssh -i "tu-clave.pem" ec2-user@tu-ip-publica-ec2
```

### 2.3 Instalar dependencias
```bash
# Subir y ejecutar el script de instalación
wget https://raw.githubusercontent.com/TU-USUARIO/TU-REPO/main/deploy-scripts/install-dependencies.sh
chmod +x install-dependencies.sh
./install-dependencies.sh
```

## 🔗 Paso 3: Conectar EC2 con RDS

### 3.1 Actualizar Security Group de RDS
1. Ve al Security Group de RDS
2. Edita la regla MySQL (puerto 3306)
3. Cambia **Source** por el Security Group de EC2 o la IP privada de EC2

### 3.2 Probar conexión
```bash
# Instalar cliente MySQL en EC2
sudo dnf install -y mysql

# Probar conexión a RDS
mysql -h TU-RDS-ENDPOINT -u admin -p
```

## 📦 Paso 4: Desplegar la aplicación

### 4.1 Subir código
```bash
# Primero, sube tu código a GitHub si no lo has hecho

# En EC2, clonar repositorio
git clone https://github.com/TU-USUARIO/TU-REPO.git /var/www/laravel
cd /var/www/laravel
```

### 4.2 Ejecutar script de despliegue
```bash
# Hacer ejecutable el script
chmod +x deploy-scripts/deploy-app.sh

# Ejecutar despliegue
./deploy-scripts/deploy-app.sh
```

### 4.3 Configurar variables de entorno
```bash
# Editar .env con datos reales
nano /var/www/laravel/.env

# Usar estos valores:
# DB_HOST=tu-rds-endpoint.region.rds.amazonaws.com
# DB_DATABASE=laravel
# DB_USERNAME=admin
# DB_PASSWORD=TuPasswordSeguro123!
# APP_URL=http://tu-ip-publica-ec2
```

### 4.4 Crear base de datos
```bash
# Conectar a RDS y crear DB
mysql -h TU-RDS-ENDPOINT -u admin -p

# En MySQL:
CREATE DATABASE laravel;
exit;

# Ejecutar migraciones
cd /var/www/laravel
php artisan migrate
```

## 🌐 Paso 5: Configurar Nginx

### 5.1 Configurar Nginx
```bash
# Copiar configuración
sudo cp deploy-scripts/nginx-config.conf /etc/nginx/conf.d/laravel.conf

# Editar para poner tu IP
sudo nano /etc/nginx/conf.d/laravel.conf
# Cambiar YOUR_DOMAIN_OR_IP por tu IP pública de EC2

# Desactivar configuración por defecto
sudo mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.backup

# Crear nueva configuración base
sudo nano /etc/nginx/nginx.conf
```

### 5.2 Configuración básica de Nginx (`/etc/nginx/nginx.conf`):
```nginx
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log notice;
pid /run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /var/log/nginx/access.log  main;

    sendfile            on;
    tcp_nopush          on;
    keepalive_timeout   65;
    types_hash_max_size 4096;

    include             /etc/nginx/mime.types;
    default_type        application/octet-stream;

    include /etc/nginx/conf.d/*.conf;
}
```

### 5.3 Reiniciar servicios
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
```

## 🎉 Paso 6: Verificar funcionamiento

1. **Probar en navegador**: `http://tu-ip-publica-ec2`
2. **Verificar logs**: 
   ```bash
   sudo tail -f /var/log/nginx/error.log
   sudo tail -f /var/log/nginx/access.log
   ```

## 🛡️ Paso 7: Seguridad (Opcional pero recomendado)

### 7.1 Configurar HTTPS con Let's Encrypt
```bash
# Instalar Certbot
sudo dnf install -y certbot python3-certbot-nginx

# Obtener certificado (necesitas un dominio)
sudo certbot --nginx -d tu-dominio.com
```

### 7.2 Configurar firewall
```bash
# Habilitar firewall
sudo systemctl enable firewalld
sudo systemctl start firewalld

# Configurar reglas
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --permanent --add-service=ssh
sudo firewall-cmd --reload
```

## 🔄 Actualizaciones futuras

Para actualizar tu aplicación:
```bash
cd /var/www/laravel
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload nginx
```

## 💰 Costos estimados (Cuenta estudiantes)

- **EC2 t2.micro**: GRATUITO (750 horas/mes)
- **RDS db.t3.micro**: GRATUITO (750 horas/mes)
- **Storage**: GRATUITO (hasta 30GB)
- **Tráfico**: GRATUITO (hasta 15GB/mes)

## 🆘 Troubleshooting

### Error 502 Bad Gateway
```bash
# Verificar PHP-FPM
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm

# Verificar permisos
sudo chown -R ec2-user:nginx /var/www/laravel
sudo chmod -R 755 /var/www/laravel
sudo chmod -R 775 /var/www/laravel/storage
```

### Error de conexión a base de datos
```bash
# Verificar conectividad
mysql -h TU-RDS-ENDPOINT -u admin -p

# Verificar .env
cat /var/www/laravel/.env | grep DB_
```

### Error de permisos Laravel
```bash
cd /var/www/laravel
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R ec2-user:nginx storage bootstrap/cache
```

¡Tu aplicación Laravel debería estar funcionando en AWS! 🎉 