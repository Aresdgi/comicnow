# ComicNow - Plataforma de Cómics Digitales

ComicNow es una aplicación web para la gestión, lectura y compra de cómics digitales, desarrollada con Laravel.

## Rutas Disponibles

A continuación se presentan todas las rutas disponibles en la aplicación:

### Rutas Públicas

| Ruta | Descripción | Nombre de Ruta |
|------|-------------|----------------|
| `/` | Página de inicio | `home` |
| `/comics` | Listado de cómics disponibles | `comics.index` |
| `/comics/{id}` | Ver detalles de un cómic específico | `comics.show` |
| `/autores` | Listado de autores | `autores.index` |
| `/autores/{id}` | Ver detalles de un autor específico | `autores.show` |
| `/buscar` | Buscador de cómics | `comics.buscar` |
| `/contacto` | Página de contacto | `contacto` |
| `/sobre-nosotros` | Acerca de la plataforma | `about` |
| `/login` | Iniciar sesión | `login` |
| `/register` | Registrarse | `register` |

### Rutas para Usuarios Autenticados

| Ruta | Descripción | Nombre de Ruta |
|------|-------------|----------------|
| `/dashboard` | Panel de control del usuario | `dashboard` |
| `/perfil` | Ver perfil del usuario | `usuario.perfil` |
| `/biblioteca` | Biblioteca personal del usuario | `biblioteca.index` |
| `/biblioteca/{id}` | Leer un cómic de la biblioteca | `biblioteca.leer` |
| `/carrito` | Carrito de compras | `carrito.index` |
| `/checkout` | Proceso de pago | `pedido.checkout` |
| `/pedidos` | Historial de pedidos | `pedidos.index` |
| `/pedidos/{id}` | Ver detalles de un pedido específico | `pedidos.show` |

### Rutas de Administración

Estas rutas requieren privilegios de administrador:

| Ruta | Descripción | Nombre de Ruta |
|------|-------------|----------------|
| `/admin` | Panel de administración | `admin.dashboard` |
| `/admin/comics` | Gestión de cómics | `admin.comics` |
| `/admin/comics/crear` | Crear nuevo cómic | `admin.comics.crear` |
| `/admin/comics/editar/{id}` | Editar un cómic existente | `admin.comics.editar` |
| `/admin/autores` | Gestión de autores | `admin.autores` |
| `/admin/autores/crear` | Crear nuevo autor | `admin.autores.crear` |
| `/admin/autores/editar/{id}` | Editar un autor existente | `admin.autores.editar` |
| `/admin/usuarios` | Gestión de usuarios | `admin.usuarios` |
| `/admin/usuarios/editar/{id}` | Editar un usuario | `admin.usuarios.editar` |
| `/admin/pedidos` | Gestión de pedidos | `admin.pedidos` |
| `/admin/pedidos/{id}` | Ver detalles de un pedido | `admin.pedidos.show` |
| `/admin/estadisticas` | Estadísticas del sistema | `admin.estadisticas` |

### API para interacciones AJAX

| Ruta | Descripción | Nombre de Ruta |
|------|-------------|----------------|
| `/api/comics` | Listado de cómics en formato JSON | `api.comics` |
| `/api/comics/{id}` | Detalles de un cómic en formato JSON | `api.comic` |
| `/api/autores` | Listado de autores en formato JSON | `api.autores` |
| `/api/busqueda` | Búsqueda de cómics en formato JSON | `api.busqueda` |

## Requisitos

- PHP >= 8.1
- Composer
- MySQL o SQLite
- Node.js y NPM

## Instalación

1. Clonar el repositorio
```bash
git clone https://github.com/tuusuario/comicnow.git
cd comicnow
```

2. Instalar dependencias de PHP
```bash
composer install
```

3. Instalar dependencias de JavaScript
```bash
npm install
```

4. Configurar variables de entorno
```bash
cp .env.example .env
php artisan key:generate
```

5. Configurar la base de datos en el archivo .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comicnow
DB_USERNAME=root
DB_PASSWORD=
```

6. Ejecutar migraciones y seeders
```bash
php artisan migrate --seed
```

7. Compilar assets
```bash
npm run dev
```

8. Iniciar el servidor
```bash
php artisan serve
```

La aplicación estará disponible en http://localhost:8000

## Acceso al Sistema

- **Usuario normal:**
  - Email: usuario@ejemplo.com
  - Contraseña: password

- **Administrador:**
  - Email: admin@ejemplo.com
  - Contraseña: password

## Licencia

Este proyecto está licenciado bajo [MIT license](LICENSE).
