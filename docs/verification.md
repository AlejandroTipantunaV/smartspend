# Verificación — 1 de octubre de 2026

Entorno: PHP 8.2.12, MariaDB 10.4.32 aislada en puerto 3307, Apache y Chrome. Se importó el script del repositorio y se corrigió el hash de demostración para `password123`.

## Pruebas funcionales aprobadas

- Login válido e inválido, registro real, cierre de sesión y rutas protegidas.
- Dashboard con cinco movimientos y balance inicial de $1.199,50.
- Creación, edición y eliminación de transacciones; actualización del balance.
- Rechazo de fecha inexistente y token CSRF incorrecto.
- Una segunda cuenta no puede ver ni eliminar movimientos de la primera.
- CRUD de categorías; rechazo de eliminación de una categoría utilizada.
- Perfil, configuración, listados y formularios sin advertencias PHP.
- Sintaxis PHP y JavaScript sin errores.

Se retiraron los usuarios temporales de las pruebas y se preservaron los datos iniciales.

## Accesibilidad

- Chrome/axe: **10 comprobaciones, 0 infracciones detectadas**, reglas WCAG 2 A/AA, 2.1 AA y 2.2 AA.
- Páginas: inicio, registro, login, dashboard, transacciones, perfil, listado de categorías, creación de categoría y edición de transacción; transacciones también a 375 × 812.
- Lighthouse: **100/100 en accesibilidad en login**, emulación móvil. El resultado no representa todas las páginas ni certifica conformidad.
- Teclado: enlace de salto enfoca el contenido; menú móvil actualiza `aria-expanded`, cierra con Escape y devuelve el foco al botón.
- Sin desbordamiento horizontal del documento a 375 px; tablas con desplazamiento horizontal.
- Notificaciones persistentes, cierre manual, etiquetas de campos, errores relacionados y foco visible.
- Se oscurecieron textos secundarios sobre gris claro: `#6c757d` sobre `#f8f9fa` dio 4,44:1. Las combinaciones corregidas pasaron la auditoría.

Iconify se bloqueó durante las auditorías para hacerlas independientes del CDN. Los iconos son decorativos. Informes locales: `tmp/accessibility.json`, `tmp/lighthouse-final.json`; capturas: `tmp/dashboard.png`, `tmp/mobile.png`.

Pendiente: WAVE (no se ejecutó su extensión ni API), lector de pantalla, revisión manual completa y alojamiento remoto. La automatización no garantiza conformidad total. Referencia: [WCAG 2.2 del W3C](https://www.w3.org/TR/WCAG22/).
