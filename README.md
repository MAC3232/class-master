
# Gestión Académica - Laravel

Este es un sistema de gestión académica desarrollado en Laravel, diseñado para facilitar la administración de materias, estudiantes, actividades y rúbricas de evaluación en un entorno académico.

## Características principales

- **Roles de usuario**: Administrador y Docente.
- **Gestión de materias**:
  - Crear, editar y eliminar materias.
  - Asignar docentes a materias.
  - Visualización de materias 
- **Gestión de estudiantes**:
  - Registrar estudiantes y asociarlos a materias.
- **Gestión de actividades**:
  - Crear actividades asociadas a materias.
  - Evaluar actividades utilizando rúbricas personalizadas.
- **Disñador de syllabus**:
  - crea syllabus de la asignatura
  - crea los Ra de la asignatura
- **Rúbricas de evaluación**:
  - Rúbrica general por estudiante.
  - Rúbricas específicas para actividades.
- **Autenticación de usuarios**:
  - Registro y login con roles definidos.

## Tecnologías utilizadas

- **Backend**: Laravel 10
- **Frontend**: Blade Templates, HTML, CSS, Bootstrap
- **Base de datos**: MySQL
- **Servidor local**: XAMPP
- **Panel administrativo**: Backpack
- **Entorno de desarrollo**: Laragon (Windows)

## Requisitos del sistema

1. PHP >= 8.1
2. Composer
3. MySQL
4. Node.js y npm (opcional para recursos de frontend)

## Instalación

1. Clona este repositorio:
   ```bash
   git clone <url-del-repositorio>
   ```

2. Ve al directorio del proyecto:
   ```bash
   cd gestion-academica
   ```

3. Instala las dependencias de Composer:
   ```bash
   composer install
   ```

4. Configura el archivo `.env`:
   - Copia el archivo de ejemplo:
     ```bash
     cp .env.example .env
     ```
   - Configura los valores para la conexión de la base de datos y otras variables necesarias.

5. Genera la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```

6. Crea un enlace simbólico para el almacenamiento:
   ```bash
   php artisan storage:link
   ```

7. Asegúrate de tener la base de datos configurada correctamente y ejecútala.

8. Ejecuta las migraciones y semillas de la base de datos:
   ```bash
   php artisan migrate db:seed
   ```

9. Inicia el servidor local:
   ```bash
   php artisan serve
   ```
## Credenciales de administrador predefinido
Dado que el sistema no cuenta con una funcionalidad de registro se ha creado un usuario administrador predefinido:

Correo electrónico: admin@google.com
Contraseña: admin
Accede con estas credenciales para gestionar las materias, estudiantes, y demás funcionalidades administrativas.

## Credenciales de Docente predefinido
Dado que el sistema no cuenta con una funcionalidad de registro se ha creado un usuario docente predefinido:

Correo electrónico: docente@google.com
Contraseña: docente
Accede con estas credenciales para gestionar las materias, estudiantes, y demás funcionalidades administrativas.
## Uso

- Accede al sistema desde: [http://localhost:8000](http://localhost:8000)
- Usa las credenciales de administrador o docente según el rol asignado durante el registro.

## Estructura del proyecto

- **Usuarios**: Administradores y docentes gestionan las asignaturas y actividades.
- **Materias**: Listado, creación, edición y asignación de docentes.
- **Estudiantes**: Relación con materias.
- **Actividades y Rúbricas**: Evaluación de estudiantes.
- **Panel administrativo**: Backpack se utilizó para facilitar la gestión de datos en el backend.

## Contribuciones

¡Las contribuciones son bienvenidas! Si deseas colaborar, crea un `fork` del proyecto, realiza tus cambios y envía un `pull request`.

