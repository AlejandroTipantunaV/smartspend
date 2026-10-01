# SmartSpend

Aplicación PHP/PDO/MySQL con MVC simplificado: autenticación, transacciones privadas por usuario, categorías compartidas, dashboard y componentes accesibles.

## Abrir en este equipo

Con Apache de XAMPP activo, abre **http://localhost/smartspend/**.

MySQL de XAMPP se detuvo durante la importación; sus registros contenían errores previos de InnoDB de otra base. Se configuró una instancia independiente en **127.0.0.1:3307**, con datos en `tmp/mysql/`. `.env` apunta a ella. No se repararon las bases ajenas. La importación en 3306 quedó incompleta; SmartSpend utiliza exclusivamente 3307.

Después de reiniciar el equipo, inicia la base con:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/start-local-db.ps1
```

No borres `tmp/mysql/`: contiene los datos locales. La instancia no es un servicio de Windows. Para detenerla: `C:\xampp\mysql\bin\mysqladmin.exe -h 127.0.0.1 -P 3307 -u root shutdown`.

Cuenta de demostración: **gabriel@ejemplo.com / password123**. Totales iniciales: ingresos $1.350,00; gastos $150,50; balance $1.199,50. También puedes registrarte; las nuevas cuentas empiezan sin movimientos.

## Instalar en otro equipo

1. Usa PHP 8+ con `pdo_mysql` y `mbstring`, Apache y MySQL/MariaDB.
2. Copia el proyecto a `htdocs/smartspend`.
3. Crea una base vacía `smartspend` con `utf8mb4_unicode_ci`.
4. Selecciónala en phpMyAdmin e importa `sql/script_database.sql`. **El script elimina y recrea sus tres tablas: úsalo en una base nueva o con respaldo.**
5. Copia `.env.example` a `.env` y configura la conexión. XAMPP normal usa puerto 3306, usuario `root` y contraseña vacía, salvo personalización.
6. Abre `http://localhost/smartspend/`.

No se necesitan Node ni Composer para utilizar la aplicación. Los iconos decorativos usan Iconify; los controles mantienen sus etiquetas si el CDN no está disponible.

## InfinityFree

Crea una base en el panel, selecciona su nombre asignado en phpMyAdmin e importa el script. Configura `.env` con `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASS` del alojamiento. Las variables del servidor tienen prioridad; no se adivinan credenciales a partir del dominio.

Sube la aplicación y `.htaccess`, excluyendo `.git`, `tmp` y los datos locales. Comprueba que `.env` no pueda descargarse por HTTP. Sustituye la cuenta de demostración antes de usar una instalación pública. No se realizó un despliegue remoto.

## Equipo y arquitectura

| Responsable | Módulos |
| --- | --- |
| Jonathan Zurita | Autenticación, `views/auth/`, `AuthController`, `User` |
| Gabriel Campo | Transacciones, formularios, `TransactionController`, `Transaction` |
| Carlos Erraes | Dashboard, `DashboardController`, estilos |
| Jonnathan Gallegos | Includes, accesibilidad y despliegue |
| Gabriel Tipantuña | Conexión, script SQL e integración |

`includes/header.php`, `nav.php` y `footer.php` forman el contenedor común. `includes/alerts.php` y `set_flash()` ofrecen notificaciones reutilizables. `assets/css/styles.css` importa los módulos CSS; la paleta está en `variables.css`.

Las categorías son compartidas, conforme al esquema existente: todos los usuarios autenticados pueden administrarlas. Una categoría utilizada no puede eliminarse ni cambiar de tipo. Los movimientos solo pueden ser consultados, editados y eliminados por su propietario.

Las contraseñas usan `password_hash` y `password_verify`; los cambios requieren POST y CSRF. Se regenera la sesión al entrar y salir. Las consultas usan parámetros PDO y los errores internos no se muestran al visitante.

## Verificación

Consulta [el informe de pruebas](docs/verification.md). WAVE y la revisión manual completa con lector de pantalla siguen pendientes; no se declara certificación WCAG 2.2.
