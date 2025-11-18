# Clothing Store - Instalación con XAMPP

## Requisitos
- XAMPP instalado con Apache y MySQL

## Pasos de instalación

### 1. Configurar la base de datos

1. Inicia XAMPP y arranca Apache y MySQL
2. Abre phpMyAdmin en tu navegador: `http://localhost/phpmyadmin`
3. Importa el archivo de base de datos:
   - Haz clic en "Importar"
   - Selecciona el archivo `database/productos.sql`
   - Haz clic en "Continuar"

### 2. Configurar el proyecto

1. Copia la carpeta del proyecto a la carpeta `htdocs` de XAMPP:
   - Windows: `C:\xampp\htdocs\Clothing Store`
   - Mac: `/Applications/XAMPP/htdocs/Clothing Store`
   - Linux: `/opt/lampp/htdocs/Clothing Store`

### 3. Acceder al sitio

1. Abre tu navegador
2. Ve a: `http://localhost/Clothing%20Store/index.php`

## Estructura de archivos

```
Clothing Store/
├── config/
│   └── conexion.php          # Configuración de la base de datos
├── database/
│   └── productos.sql         # Script SQL para crear la BD
├── img/                      # Carpeta de imágenes
├── index.php                 # Página principal (productos)
├── product.php               # Página de detalle del producto
├── styles.css                # Estilos CSS
└── README_INSTALACION.md     # Este archivo
```

## Características

- **Navegación dinámica**: Los productos se cargan desde la base de datos MySQL
- **Detalles de producto**: Al hacer clic en un producto, se muestra su información completa
- **Gestión de tallas**: Las tallas se almacenan en formato JSON en la BD
- **Seguridad**: Los datos se sanitizan antes de mostrarse

## Modificar la configuración de la base de datos

Si necesitas cambiar los datos de conexión, edita el archivo `config/conexion.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Tu contraseña si la tienes
define('DB_NAME', 'clothing_store');
```

## Agregar más productos

Puedes agregar productos desde phpMyAdmin o ejecutando queries SQL como:

```sql
INSERT INTO productos (nombre, descripcion, precio, imagen, categoria, corte, tallas) 
VALUES ('Nombre del Producto', 'Descripción', 29.99, '/img/imagen.webp', 'Categoría', 'Corte', '["S","M","L"]');
```

## Solución de problemas

### Error de conexión a la base de datos
- Verifica que MySQL esté corriendo en XAMPP
- Comprueba que la base de datos `clothing_store` exista
- Revisa las credenciales en `config/conexion.php`

### Las imágenes no se muestran
- Asegúrate de que las rutas de las imágenes en la BD sean correctas
- Verifica que las imágenes existan en la carpeta `img/`
