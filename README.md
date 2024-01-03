# AaxisTest

## Descripción
AaxisTest es un proyecto desarrollado utilizando PHP 8.2, Symfony 6 y Postgresql. Proporciona una API para la gestión de usuarios y productos.

## Requisitos
- PHP 8.2
- Symfony 6
- Postgresql

## Instalación y Configuración Local
Para levantar el proyecto en un entorno local, sigue estos pasos:

1. **Instalación de Dependencias:**
   - Ejecuta `composer install` para instalar las dependencias del proyecto.

2. **Configuración de Entorno:**
   - Crea un archivo `.env` basándote en el archivo de ejemplo `.env.example`.
   - Configura la variable `DATABASE_URL` según tu motor de base de datos. Para Postgresql, asegúrate de agregar el usuario, la contraseña y el nombre de la base de datos.

3. **Migraciones de Base de Datos:**
   - Ejecuta `symfony console doctrine:migrations:migrate` en la consola para generar las tablas correspondientes en la base de datos.

4. **Levantar el Servidor Local:**
   - Utiliza `symfony serve` para iniciar un servidor local.

## Uso de la API
La API proporciona los siguientes endpoints:

1. **Registro de Usuarios:**
   - `POST http://localhost:8000/api/register`
   - Body de ejemplo:
     ```json
     {
       "email": "usuario@mail.com",
       "password": "password123"
     }
     ```

2. **Verificación de Usuario:**
   - `POST http://localhost:8000/api/login_check`
   - Body de ejemplo:
     ```json
     {
       "username": "usuario@mail.com",
       "password": "password123"
     }
     ```

3. **Listado de Productos:**
   - `GET http://localhost:8000/api/product`

4. **Creación de Productos:**
   - `POST http://localhost:8000/api/product`
   - Body de ejemplo:
     ```json
     {
       "Sku": "fzeee8n965",
       "Product_name": "Mac",
       "description": "this is it a description"
     }
     ```
   - También puede ser un arreglo del objeto.

5. **Actualización de Productos:**
   - `PUT http://localhost:8000/api/product`
   - Body de ejemplo:
     ```json
     {
       "Sku": "fzeee8n965",
       "Product_name": "Mac",
       "description": "this is it a description"
     }
     ```
   - También puede ser un arreglo del objeto.
