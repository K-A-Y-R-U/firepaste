<p align="center">
  <img src="public/assets/images/banner.jpg" alt="Firepaste Banner" width="100%">
</p>

<h1 align="center">🔥 Firepaste</h1>

<p align="center">
  Plataforma de contenido con sistema VIP, membresías y panel de administración — construida con Laravel 11 + Filament 3
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
  <img src="https://img.shields.io/badge/Filament-3-FDAE4B?style=for-the-badge&logo=filament&logoColor=white">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/Livewire-3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white">
</p>

---

## ✨ Características

- 📝 **Sistema de posts** con editor TipTap enriquecido
- 👑 **Membresías VIP** con expiración automática
- 🎁 **Gift Codes** — genera y canjea códigos para activar VIP
- 🗂️ **Catálogos** para organizar el contenido por categorías
- 🔗 **Acortador de URLs** configurable para links externos
- 🛡️ **Panel de administración** completo con Filament 3
- 👥 **Gestión de usuarios y roles** desde el panel admin
- 📊 **Contador de visitas** por post
- 📱 **Diseño responsive** optimizado para móviles y tablets

---

## 🛠️ Requisitos previos

Antes de instalar, asegúrate de tener:

- **PHP >= 8.2** con extensiones: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`
- **Composer** (para gestionar dependencias PHP)
- **MySQL >= 8.0** / MariaDB / PostgreSQL
- **Node.js >= 18** y **npm**
- **Servidor Web** (Apache, Nginx, etc.)

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/K-A-Y-R-U/firepaste.git
cd firepaste
```

### 2. Instalar dependencias

```bash
composer install
npm install
npm run build
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` y configura tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=firepaste
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Correr las migraciones

```bash
php artisan migrate
```

### 5. Crear cuenta de administrador

```bash
php artisan hexa:account --create
```

### 6. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

Visita `http://localhost:8000` en tu navegador. El panel de administración está en `http://localhost:8000/admin`.

---

## ⚙️ Configuración del acortador de URLs

En el panel de administración ve a **Configuración General** y:

1. Activa el acortador de URLs
2. Ingresa la URL completa de tu API de acortamiento en `url_shortener_api_full`

Los links externos dentro de los posts serán acortados automáticamente al cargar la página.

---

## 🎁 Sistema de Gift Codes

Desde el panel admin puedes crear códigos de regalo con los siguientes parámetros:

| Campo | Descripción |
|---|---|
| `code` | Código único (se genera automáticamente) |
| `vip_days` | Días de VIP que otorga el código |
| `expires_at` | Fecha límite para canjear |
| `max_uses` | Número máximo de usos |
| `is_active` | Activar o desactivar el código |

Los usuarios pueden canjear sus códigos en `/gift-codes/redeem`. Si ya tienen VIP activo, los días se **extienden** sin sobreescribir la fecha actual.

---

## 👑 Sistema VIP

Los usuarios VIP tienen acceso al contenido exclusivo de cada post en la pestaña **👑 VIP**. El acceso se controla con dos campos en la tabla `users`:

- `is_vip_active` — estado activo/inactivo
- `vip_expires_at` — fecha y hora de expiración

El `VipMiddleware` protege automáticamente todas las rutas `/vip/*`. Si el usuario no tiene VIP activo, es redirigido al formulario de canje de códigos.

Desde el panel admin puedes activar VIP manualmente a cualquier usuario indicando los días a asignar.

---

## 🗂️ Estructura del proyecto

```
app/
├── Filament/Resources/     # Panel admin (Posts, Users, GiftCodes, Catalogs)
├── Http/
│   ├── Controllers/        # PostController, VipPostController, GiftCodeController
│   └── Middleware/         # VipMiddleware
├── Models/                 # User, Post, GiftCode, Catalog, GiftCodeRedemption
resources/
├── views/
│   ├── posts/              # Vista de posts públicos
│   ├── vip/                # Vista de contenido VIP
│   ├── gift-codes/         # Vistas de canje de códigos
│   └── layouts/            # Layouts base (app, guest, header, footer)
database/
└── migrations/             # Todas las migraciones del proyecto
routes/
├── web.php                 # Rutas principales
└── auth.php                # Rutas de autenticación
```

---

## 🧰 Stack tecnológico

| Tecnología | Versión | Uso |
|---|---|---|
| Laravel | 11 | Framework principal |
| Filament | 3 | Panel de administración |
| Livewire | 3 | Componentes reactivos |
| TipTap Editor | 3 | Editor de contenido enriquecido |
| Tailwind CSS | 3 | Estilos del panel admin |
| Bootstrap | 5 | Estilos del frontend público |
| Hexa Lite | 1 | Sistema de roles y autenticación admin |

---

## 📋 Comandos útiles

```bash
# Limpiar caché
php artisan optimize:clear

# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Subir cambios a GitHub
git add .
git commit -m "descripción del cambio"
git push
```

---

## 📄 Licencia

Este proyecto está bajo la licencia [MIT](LICENSE).

---

<p align="center">Hecho con ❤️ y mucho ☕</p>