# Parcial2grupo15
Sistema Web para la Asignación de Horarios, Aulas, Materias, Grupos y Asistencia  Docente para cada gestión académica de la Facultad.
Estudiantes:  Aranibar Perez Kevin Ariel y Villarroel Henzan Fernando Kenji 
## Despliegue en Render.com con Docker

### Pasos para el despliegue:

1. **Crear una base de datos PostgreSQL en Render:**
   - Ve a [Render Dashboard](https://dashboard.render.com/new/database) y crea una nueva base de datos PostgreSQL.
   - Copia la URL interna de la base de datos.

2. **Subir el proyecto a GitHub:**
   - Asegúrate de que el proyecto esté en un repositorio de GitHub.

3. **Crear un Web Service en Render:**
   - Ve a [Render Dashboard](https://dashboard.render.com/new) y selecciona "Web Service".
   - Conecta tu cuenta de GitHub y selecciona el repositorio.
   - Selecciona `Docker` como entorno.
   - Agrega las siguientes variables de entorno en la sección "Advanced":
     - `APP_KEY`: Copia la salida de `php artisan key:generate --show`
     - `DATABASE_URL`: La URL interna de la base de datos PostgreSQL creada.
     - `DB_CONNECTION`: `pgsql`
   - Elige el plan gratuito y despliega.

### Archivos incluidos para el despliegue:
- `Dockerfile`: Configuración de Docker para Laravel.
- `.dockerignore`: Archivos a excluir del build.
- `conf/nginx/nginx-site.conf`: Configuración de Nginx.
- `scripts/00-laravel-deploy.sh`: Script de despliegue que instala dependencias, construye assets y migra la base de datos.
