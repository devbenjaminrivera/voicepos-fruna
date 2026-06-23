# VoicePOS - Fruna 

Sistema de Punto de Venta (POS) operado por comandos de voz, diseñado para agilizar la toma de pedidos en el mesón de atención. El sistema procesa dictados en tiempo real, filtra imprecisiones mediante algoritmos de procesamiento de lenguaje natural y emite boletas estructuradas.

Desarrollado por **Benjamín Rivera Araneda** como proyecto aplicativo para la asignatura de Electivo Profesional I (Programación Web con Laravel).

---

## Características Principales

* **Dictado por Voz en Tiempo Real:** Integración con Web Speech API para capturar y transcribir los pedidos del cliente directamente desde el micrófono.
* **Procesamiento Inteligente (NLP):** Utiliza un algoritmo de similitud porcentual (PHP `similar_text()`) con un umbral estricto del 75% para corregir errores tipográficos de dictado (ej. "babas fritas" a "papas fritas") y rechazar falsos positivos.
* **Emisión de Boletas:** Generación automática de tickets en formato `.txt` con alineación estricta y diseño corporativo tradicional.
* **Panel de Control Corporativo:** Dashboard administrativo estilizado con Tailwind CSS para acceso rápido a operaciones express e historial de ventas.
* **Autenticación Segura:** Sistema de login protegido y hasheo de contraseñas mediante Laravel Breeze (Bcrypt/Argon2id).

---

## Stack Tecnológico

* **Backend:** Laravel (PHP)
* **Frontend:** Blade Templates, Tailwind CSS, Vite (Node.js)
* **Base de Datos:** PostgreSQL alojada en Supabase
* **Reconocimiento de Voz:** Web Speech API (Navegador)

---

## Requisitos Previos

Asegúrate de tener instalados los siguientes componentes en tu entorno local (el sistema está optimizado para su ejecución y desarrollo continuo tanto en entornos **Windows** como en distribuciones Linux como **Fedora**):

* PHP 8.2 o superior
* Composer
* Node.js y npm
* Git
* Navegador moderno con soporte para Web Speech API (Google Chrome recomendado)
* Cuenta activa en Supabase (para la base de datos PostgreSQL)

---

## Instalación y Configuración Local

**1. Clonar el repositorio**
```bash
git clone <tu-enlace-de-github>
cd voicepos-fruna
```

**2. Instalar dependencias del Backend y Frontend**
```bash
composer install
npm install
```

**3. Configurar variables de entorno**
Copia el archivo de ejemplo para crear tu configuración local:
```bash
cp .env.example .env
```
Abre el archivo `.env` recién creado y asegúrate de configurar la conexión a tu base de datos de Supabase y definir la URL de la aplicación:
```env
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=aws-0-us-east-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

**4. Generar la clave de la aplicación**
```bash
php artisan key:generate
```

**5. Preparar la Base de Datos**
Ejecuta las migraciones y el seeder para generar el usuario administrador base y el catálogo de productos por defecto:
```bash
php artisan migrate:fresh --seed
```

**6. Compilar los recursos visuales**
Para empaquetar los estilos de Tailwind CSS y los scripts:
```bash
npm run build
```
*(Nota: Durante el desarrollo activo de vistas, puedes usar `npm run dev` en una terminal secundaria).*

**7. Levantar el servidor local**
```bash
php artisan serve
```

El sistema estará disponible en `http://127.0.0.1:8000`.

---

## Uso Básico

1.  **Iniciar Sesión:** Accede con las credenciales creadas por el seeder (por defecto, correo: `vendedor@fruna.cl` / clave: `password123`).
2.  **Realizar una Venta:** Desde el Dashboard, haz clic en **Iniciar Venta por Voz**. Concede permisos de micrófono al navegador.
3.  **Dictar Pedido:** Presiona "Iniciar Escucha" y dicta los productos con su cantidad (ej. "Dos gomitas, una Coca Cola, tres galletas"). Di la palabra clave **"APARTE"** para detener el micrófono y procesar el carrito.
4.  **Generar Boleta:** Verifica la tabla de resumen y exporta el ticket a formato `.txt`.

---

## 🛑 Posibles Problemas y Soluciones

* **Error 419 (Page Expired) en Login:** Asegúrate de acceder al sistema escribiendo exactamente `http://127.0.0.1:8000` en tu navegador para que coincida con tu `APP_URL`, y ejecuta `php artisan optimize:clear`.
* **Lentitud en la transcripción de voz:** En algunosequipos portátiles el uso prolongado y concurrente de motores de compilación (Node) y APIs del navegador puede causar estrangulamiento térmico (*thermal throttling*). Si detectas retraso en el dictado, verifica el Administrador de Tareas. Cierra pestañas redundantes, finaliza procesos "fantasma" de Node.js o recarga el navegador de forma limpia.
* **Problemas con el microfono ("Iniciar Escucha"):** El sistema utiliza la Web Speech API para transcribir el audio en tiempo real. Debido a que esta tecnología depende de motores de procesamiento en la nube, navegadores como Opera, Brave o Firefox suelen bloquearla por políticas de privacidad o falta de soporte nativo. Para garantizar el funcionamiento del módulo de voz, es estrictamente necesario utilizar **Google Chrome** o **Microsoft Edge**.