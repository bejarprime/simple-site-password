# SPEC - Simple Site Password

## Resumen

**Simple Site Password** será un plugin WordPress sencillo para proteger un sitio completo con una contraseña global.

Cuando la protección esté activa, cualquier visitante no autenticado verá una pantalla con un formulario de contraseña antes de poder navegar por el sitio.

El objetivo no es crear un sistema de membresía ni sustituir medidas avanzadas de seguridad. El objetivo es resolver un caso común de forma simple:

> Ocultar temporalmente o privatizar un sitio WordPress con una contraseña global.

---

## Problema que resuelve

Muchos usuarios necesitan restringir el acceso público a una web sin crear usuarios ni configurar membresías:

- Web en desarrollo.
- Demo privada para cliente.
- Landing antes de lanzamiento.
- Catálogo temporalmente privado.
- Sitio interno sencillo.
- Proyecto que aún no debe indexarse ni verse públicamente.

WordPress permite proteger entradas individuales, pero no trae una opción simple para proteger todo el sitio con una contraseña global.

---

## Usuario objetivo

- Freelancers WordPress.
- Agencias que preparan webs para clientes.
- Desarrolladores que necesitan demos privadas.
- Propietarios de sitios pequeños.
- Usuarios no técnicos que quieren ocultar temporalmente una web.

---

## Nombre y slug

- Nombre: `Simple Site Password`
- Slug: `simple-site-password`
- Text Domain: `simple-site-password`

---

## MVP v1.0.0

### Funcionalidades incluidas

- Activar/desactivar protección global.
- Definir una contraseña global.
- Guardar la contraseña de forma hasheada, no en texto plano.
- Definir título de pantalla de acceso.
- Definir mensaje/descripción de pantalla de acceso.
- Mostrar formulario frontend para visitantes no autorizados.
- Recordar acceso mediante cookie temporal.
- Configurar duración de la cookie en horas.
- Permitir siempre acceso a usuarios logueados con capacidad de administración.
- No bloquear `wp-login.php`.
- No bloquear `wp-admin`.
- No bloquear AJAX, REST API ni cron.
- Añadir página de ajustes en el admin.
- Añadir acción para cerrar acceso y borrar cookie.
- Incluir `uninstall.php` para limpiar opciones.

### Funcionalidades fuera del MVP

- Múltiples contraseñas.
- Usuarios/roles personalizados.
- Integración con WooCommerce.
- Estadísticas de accesos.
- Personalización visual avanzada.
- Protección por URL específica.
- Bloqueo por IP.
- Limitación avanzada de intentos.
- Emails o notificaciones.

---

## Riesgos técnicos

- Bloquear accidentalmente el login de WordPress.
- Bloquear el admin.
- Romper llamadas AJAX o REST.
- Crear bucles de redirección.
- Guardar la contraseña en texto plano.
- Usar cookies inseguras.
- Generar problemas con plugins de caché.
- Presentarlo como una solución de seguridad absoluta.

---

## Decisiones técnicas iniciales

- Hook principal previsto: `template_redirect`.
- Página de ajustes mediante menú en `Settings`.
- Opciones guardadas en una única opción: `simple_site_password_options`.
- Contraseña guardada con `wp_hash_password()`.
- Verificación con `wp_check_password()`.
- Cookie con nombre prefijado: `simple_site_password_access`.
- Cookie firmada con hash para evitar valores triviales.
- Nonces en formularios admin y frontend.
- Capabilities: `manage_options` para ajustes.
- Textos traducibles con text domain `simple-site-password`.

---

## Excepciones obligatorias

El plugin no debe proteger:

- `wp-login.php`
- `wp-admin`
- `admin-ajax.php`
- REST API
- WP Cron
- Usuarios administradores logueados
- Peticiones CLI

---

## Criterios de aceptación

- El plugin se activa sin errores.
- El admin puede configurar contraseña y activar protección.
- Un visitante no autorizado ve el formulario de contraseña.
- Un visitante con contraseña correcta accede al sitio.
- La cookie recuerda el acceso durante el tiempo configurado.
- El login de WordPress sigue funcionando.
- El admin sigue funcionando.
- AJAX, REST y cron no quedan bloqueados.
- La contraseña no se guarda en texto plano.
- Los formularios usan nonces.
- Las entradas se sanitizan.
- Las salidas se escapan.
- El plugin funciona con `WP_DEBUG` activado.
- Existe `readme.txt`.
- Existe `uninstall.php`.
- Existe contenido inicial para LinkedIn.

