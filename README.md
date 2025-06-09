🖥️ Escritorio Web Laravel 12 con Livewire y Vite (Sin Base de Datos)

Este proyecto es una plataforma tipo escritorio web desarrollada en Laravel 12, que utiliza Livewire para la interacción dinámica en el frontend y Vite como bundler moderno. El sistema funciona completamente con almacenamiento en el navegador (localStorage), sin necesidad de una base de datos.

🚀 Características

- 🔐 Autenticación de usuarios simulada usando localStorage.
- 👤 Soporte para múltiples roles: usuario estándar y administrador.
- 🧑‍💼 Vista especial para el rol de administrador con:
  - Gestión de íconos (agregar, editar, eliminar) persistidos en localStorage.
- 🖼️ Escritorio dinámico con acceso a funcionalidades personalizadas según el usuario.
- ⚡ Interfaz reactiva gracias a Livewire.
- 📦 Integración con Vite para desarrollo moderno y construcción de assets.

📂 Estructura de Carpetas Clave

resources/
├── views/
│   ├── login.blade.php      # Vista de inicio de sesión
│   ├── admin.blade.php          # Panel de administración
│   └── desktop.blade.php        # Escritorio principal

🛠️ Requisitos

- PHP >= 8.2
- Composer
- Node.js >= 18.x

⚙️ Instalación

1. Clona el repositorio:

   git clone https://github.com/tu-usuario/nombre-del-repo.git
   cd nombre-del-repo

2. Instala dependencias de backend y frontend:

   composer install
   npm install && npm run dev

3. Levanta el servidor de desarrollo:

   php artisan serve

👤 Roles de Usuario

- Administrador: acceso al panel de administración y funcionalidades de mantenimiento.
- Usuario estándar: acceso limitado al escritorio.

💾 Almacenamiento

- Todos los datos, incluyendo sesiones, íconos y configuraciones, se almacenan localmente en el navegador usando localStorage.
- No se requiere una base de datos ni configuración adicional.

📸 Capturas de Pantalla
