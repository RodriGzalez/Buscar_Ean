# Entorno de desarrollo Proyecto Buscar EAN 
**Soporte Omnicanal TI Chedraui** *Desarrollado por: Rodrigo González A.*

Aplicativo diseñado para la gestión, búsqueda de códigos EAN y actualización de Picking Result mediante integración con API externa.

---

## Requisitos e Instalación

### Stack Tecnológico
* **Servidor Local:** XAMPP v3.3.0
* **Framework:** Laravel 12.44.0
* **Manejador de Dependencias:** Composer v2.9.2
* **Base de Datos:** MySQL (Administrado con MySQL Workbench 8.0.45)
* **Entorno:** Visual Studio Code

### Extensiones de VS Code Requeridas
* Composer & PHP Profiler
* IntelliPHP - AI Autocomplete
* Temas: Material Icon, Minimalist Product, Bearded Theme.

---

##  Gestión de Base de Datos (mean_data)

El sistema utiliza una tabla maestra llamada `mean_data` que se alimenta de un archivo de texto plano.

### Proceso de Actualización Local
Para cargar nuevos datos desde el archivo ubicado en `C:\MEANCOM\MEANCOM.txt`, ejecutar el siguiente script SQL:

-- 1. Limpiar tabla actual
TRUNCATE TABLE mean_data;

-- 2. Asegurar estructura de la tabla
CREATE TABLE IF NOT EXISTS mean_data (
  MEAN_MANDT VARCHAR(10),
  MEAN_MATNR VARCHAR(50),
  MEAN_MEINH VARCHAR(10),
  MEAN_LFNUM VARCHAR(20),
  MEAN_EAN11 VARCHAR(20),
  MEAN_EANTP VARCHAR(10),
  MEAN_HPEAN VARCHAR(20),
  MEAN_SGT_CATV VARCHAR(50),
  MEAN_STTPEC_SER_GTIN VARCHAR(200)
) ENGINE=InnoDB;

-- 3. Carga masiva desde TXT
LOAD DATA LOCAL INFILE 'C:\\MEANCOM\\MEANCOM.txt'
INTO TABLE mean_data
CHARACTER SET utf8mb4
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES;

## Paso a Producción (Railway)

Exportar la tabla mean_data desde el phpMyAdmin local.
Conectar MySQL Workbench a la instancia de Railway.
Ejecutar el script .sql exportado para actualizar los datos en la nube.

## Estructura del Código

Controladores
Carpeta AUTH: Gestiona el ciclo completo de autenticación (Registro, Login, Recuperación de contraseñas).

ProfileController: Edición y eliminación de cuentas de usuario.

MeanController: Realiza búsquedas filtradas en la tabla mean_data. Filtra por MEAN_MEINH = 'ST' y tipos de EAN que inician con 'Z'.

EanController: Gestiona la conexión con la API externa de Azure para la actualización de manufacture.

Modelos

mean_db: Mapea la tabla mean_data. Se ha configurado con $timestamps = false y $incrementing = false para ajustarse a la estructura de la carga masiva.

User: Modelo de usuarios con hashing de contraseñas y casting de seguridad.

Recursos y UI

CSS: Ubicado en resources/css/style.css. Define los estilos del footer corporativo y el espaciado de las tablas de datos.

Views:

auth/: Vistas de seguridad.

layouts/guest.blade.php: Template base que carga los assets mediante @vite.

buscar_ean.blade.php: Interfaz principal del buscador y formulario de actualización.


## Integración de API Externa

El sistema se comunica con el siguiente endpoint de Azure para actualizar el Picking Result:

URL: https://chwhordernotificationsprod.azurewebsites.net/asignarmanufactore

Método: POST

Datos requeridos: metadata_id, sku, manufactorecode.

Seguridad: No requiere API KEY/Token actualmente.

© 2026 Soporte Omnicanal TI Chedraui
