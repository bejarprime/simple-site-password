# Plan de desarrollo por checklist - Simple Site Password

Este plan divide el desarrollo del plugin en bloques pequeños, validables y documentables.

Objetivo: avanzar de forma ordenada, generar contenido para LinkedIn y evitar rehacer por falta de definición.

---

## Fase 0 - Decisiones cerradas

- [x] Nombre del plugin: `Simple Site Password`
- [x] Slug: `simple-site-password`
- [x] Autor: `WPHubb`
- [x] Idioma base: inglés
- [x] Traducción prevista: español
- [x] Text domain: `simple-site-password`
- [x] Contraseña global hasheada
- [x] Cookie por defecto: 24 horas
- [x] Templates frontend: Minimal, Dark, Gradient
- [x] Opción para borrar ajustes al desinstalar
- [x] Design system común: `DESIGN_SYSTEM_PLUGINS_WPHUBB.md`

---

## Fase 1 - Estructura base del plugin

### Objetivo

Dejar el plugin bien organizado antes de implementar lógica.

### Tareas

- [x] Crear carpeta del proyecto.
- [x] Crear carpeta del plugin instalable.
- [x] Crear archivo principal `simple-site-password.php`.
- [x] Crear `readme.txt`.
- [x] Crear `LICENSE`.
- [x] Crear `uninstall.php`.
- [ ] Crear carpeta `includes/`.
- [ ] Crear carpeta `assets/css/`.
- [ ] Crear carpeta `assets/js/` si hace falta.
- [ ] Crear carpeta `languages/`.
- [ ] Crear clase principal del plugin.
- [ ] Crear clase de admin.
- [ ] Crear clase de frontend/gate.

### Validación

- [ ] El plugin aparece en WordPress.
- [ ] El plugin activa sin errores.
- [ ] No hay errores con `WP_DEBUG`.

### Contenido LinkedIn posible

- [x] Post 1: inicio del plugin.

---

## Fase 2 - Opciones y configuración interna

### Objetivo

Definir cómo se guardarán y recuperarán los ajustes del plugin.

### Opción principal

Nombre:

```text
simple_site_password_options
```

### Campos previstos

- [ ] `enabled`
- [ ] `password_hash`
- [ ] `cookie_duration`
- [ ] `allow_admins`
- [ ] `template`
- [ ] `title`
- [ ] `description`
- [ ] `button_text`
- [ ] `delete_on_uninstall`

### Valores por defecto

- [ ] `enabled`: `false`
- [ ] `password_hash`: vacío
- [ ] `cookie_duration`: `24`
- [ ] `allow_admins`: `true`
- [ ] `template`: `minimal`
- [ ] `title`: `Protected Site`
- [ ] `description`: `Enter the password to access this site.`
- [ ] `button_text`: `Access`
- [ ] `delete_on_uninstall`: `false`

### Validación

- [ ] Las opciones se cargan con defaults si no existen.
- [ ] No hay notices PHP por índices inexistentes.
- [ ] Los valores se normalizan antes de usarse.

---

## Fase 3 - Panel de administración

### Objetivo

Crear la página de ajustes en:

```text
Settings > Simple Site Password
```

### Secciones del panel

#### Header

- [ ] Nombre del plugin.
- [ ] Descripción corta.
- [ ] Badge de estado activo/inactivo.

#### Estado

- [ ] Toggle para activar protección.
- [ ] Estado de contraseña configurada/no configurada.
- [ ] Aviso si se activa protección sin contraseña.

#### Acceso

- [ ] Campo de nueva contraseña.
- [ ] Mantener contraseña anterior si el campo queda vacío.
- [ ] Duración de cookie en horas.
- [ ] Checkbox para permitir administradores logueados.

#### Diseño

- [ ] Selector de template: Minimal, Dark, Gradient.
- [ ] Campo título.
- [ ] Campo descripción.
- [ ] Campo texto del botón.
- [ ] Preview visual básica.

#### Avanzado

- [ ] Checkbox borrar ajustes al desinstalar.

### Seguridad admin

- [ ] Usar `manage_options`.
- [ ] Usar nonce.
- [ ] Sanitizar todos los campos.
- [ ] Escapar todas las salidas.

### Design system

- [ ] Usar `.wphubb-admin`.
- [ ] Usar `.wphubb-header`.
- [ ] Usar `.wphubb-card`.
- [ ] Usar `.wphubb-field`.
- [ ] Usar `.wphubb-input`.
- [ ] Usar `.wphubb-button`.
- [ ] Usar `.wphubb-notice`.
- [ ] CSS cargado solo en la pantalla del plugin.
- [ ] No modificar globalmente el admin.

### Validación

- [ ] Guardar ajustes funciona.
- [ ] Contraseña no se muestra de vuelta.
- [ ] Contraseña se guarda como hash.
- [ ] Campo vacío mantiene hash existente.
- [ ] Mensajes de éxito/error son claros.

### Contenido LinkedIn posible

- [ ] Post técnico: construir una página admin sin romper WordPress.

---

## Fase 4 - Protección frontend

### Objetivo

Interceptar visitantes públicos y mostrar pantalla de contraseña cuando corresponda.

### Hook previsto

```text
template_redirect
```

### Lógica

- [ ] Si protección está desactivada, no hacer nada.
- [ ] Si no hay contraseña configurada, no bloquear.
- [ ] Si usuario admin logueado y `allow_admins` activo, no bloquear.
- [ ] Si petición está excluida, no bloquear.
- [ ] Si cookie válida existe, no bloquear.
- [ ] Si se envía formulario, validar contraseña.
- [ ] Si contraseña correcta, crear cookie y redirigir.
- [ ] Si contraseña incorrecta, mostrar error.
- [ ] Si no hay acceso, renderizar gate.

### Excepciones obligatorias

- [ ] `wp-login.php`
- [ ] `wp-admin`
- [ ] `admin-ajax.php`
- [ ] REST API
- [ ] WP Cron
- [ ] WP CLI

### Seguridad frontend

- [ ] Nonce en formulario.
- [ ] Sanitizar contraseña recibida.
- [ ] Usar `wp_check_password()`.
- [ ] No revelar información sensible en errores.
- [ ] Escapar todas las salidas.

### Validación

- [ ] Visitante ve pantalla de contraseña.
- [ ] Contraseña correcta permite acceso.
- [ ] Contraseña incorrecta muestra error.
- [ ] Login sigue funcionando.
- [ ] Admin sigue funcionando.
- [ ] REST/AJAX/cron no se bloquean.

### Contenido LinkedIn posible

- [ ] Post técnico: lo difícil de proteger todo un sitio sin romper WordPress.

---

## Fase 5 - Cookies y logout

### Objetivo

Gestionar el acceso temporal de visitantes.

### Cookie

Nombre:

```text
simple_site_password_access
```

### Tareas

- [ ] Crear cookie tras contraseña correcta.
- [ ] Duración configurable.
- [ ] Usar `secure` si el sitio usa HTTPS.
- [ ] Usar `httponly`.
- [ ] Usar `samesite` cuando sea posible.
- [ ] Validar cookie con valor firmado.
- [ ] Invalidar cookie si cambia la contraseña.

### Logout

URL prevista:

```text
?simple_site_password_logout=1
```

Tareas:

- [ ] Detectar parámetro logout.
- [ ] Validar nonce o método seguro si aplica.
- [ ] Borrar cookie.
- [ ] Redirigir a home.

### Validación

- [ ] Cookie se crea correctamente.
- [ ] Cookie expira según configuración.
- [ ] Logout borra cookie.
- [ ] Cambiar contraseña invalida accesos anteriores.

---

## Fase 6 - Templates frontend

### Objetivo

Crear 3 estilos visuales para la pantalla de contraseña.

### HTML común

- [ ] Wrapper.
- [ ] Card.
- [ ] Título.
- [ ] Descripción.
- [ ] Formulario.
- [ ] Input password.
- [ ] Botón.
- [ ] Error si existe.

### Templates

#### Minimal

- [ ] Fondo claro.
- [ ] Tarjeta blanca.
- [ ] Texto oscuro.
- [ ] Botón sobrio.

#### Dark

- [ ] Fondo oscuro WPHubb.
- [ ] Tarjeta oscura.
- [ ] Acento verde lima.
- [ ] Botón WPHubb.

#### Gradient

- [ ] Fondo degradado.
- [ ] Tarjeta legible.
- [ ] Botón visual.

### CSS

- [ ] Crear `assets/css/frontend.css`.
- [ ] Prefijar todas las clases con `.ssp-`.
- [ ] No afectar al tema.
- [ ] Responsive móvil.
- [ ] Cargar solo cuando se muestra el gate.

### Validación

- [ ] Los 3 templates se ven correctamente.
- [ ] Funciona en móvil.
- [ ] Contraste suficiente.
- [ ] No rompe estilos del tema.

### Contenido LinkedIn posible

- [ ] Post UX: mismo HTML, 3 templates con CSS.

---

## Fase 7 - Internacionalización

### Objetivo

Preparar el plugin para inglés y español.

### Tareas

- [ ] Todos los textos visibles usan funciones i18n.
- [ ] Text domain correcto: `simple-site-password`.
- [ ] Crear carpeta `languages/`.
- [ ] Crear `.pot`.
- [ ] Crear traducción `es_ES` si procede.

### Validación

- [ ] No quedan textos visibles hardcodeados sin traducción.
- [ ] El plugin carga text domain.

---

## Fase 8 - Desinstalación

### Objetivo

Limpiar datos solo si el usuario lo ha decidido.

### Tareas

- [ ] `uninstall.php` verifica `WP_UNINSTALL_PLUGIN`.
- [ ] Leer opción `delete_on_uninstall`.
- [ ] Si está activa, borrar `simple_site_password_options`.
- [ ] Si no está activa, mantener datos.

### Validación

- [ ] Desinstalar con opción desactivada mantiene ajustes.
- [ ] Desinstalar con opción activada borra ajustes.

---

## Fase 9 - Documentación

### Objetivo

Dejar documentación suficiente para GitHub y WordPress.org.

### Tareas

- [ ] Completar `readme.txt`.
- [ ] Descripción clara.
- [ ] Instalación.
- [ ] FAQ.
- [ ] Screenshots descritas.
- [ ] Changelog.
- [ ] Aviso de limitaciones.
- [ ] README GitHub si se crea repo separado.

### Mensaje importante

No vender como seguridad absoluta.

Usar:

```text
Simple password protection for temporary/private access.
```

Evitar:

```text
Complete security protection.
```

---

## Fase 10 - Validación final

### Pruebas funcionales

- [ ] Activar plugin.
- [ ] Desactivar plugin.
- [ ] Guardar ajustes.
- [ ] Activar protección.
- [ ] Probar contraseña correcta.
- [ ] Probar contraseña incorrecta.
- [ ] Probar cookie.
- [ ] Probar logout.
- [ ] Cambiar contraseña e invalidar cookie previa.

### Pruebas de no regresión WordPress

- [ ] `wp-login.php` funciona.
- [ ] `wp-admin` funciona.
- [ ] `admin-ajax.php` funciona.
- [ ] REST API funciona.
- [ ] Cron no queda bloqueado.

### Seguridad

- [ ] Nonces revisados.
- [ ] Capabilities revisadas.
- [ ] Sanitización revisada.
- [ ] Escaping revisado.
- [ ] Sin llamadas externas.
- [ ] Sin tracking.

### WordPress.org

- [ ] Header correcto.
- [ ] `readme.txt` correcto.
- [ ] Licencia correcta.
- [ ] Sin marcas de terceros.
- [ ] Sin CSS invasivo.
- [ ] Sin código remoto.
- [ ] ZIP limpio.

---

## Fase 11 - Contenido LinkedIn

### Posts mínimos

- [x] `post-01-inicio.md`
- [ ] `post-02-panel-admin.md`
- [ ] `post-03-proteccion-frontend.md`
- [ ] `post-04-templates-ux.md`
- [ ] `post-05-demo-final.md`

### Ideas de narrativa

- Un plugin simple también tiene decisiones técnicas.
- Proteger todo un sitio no debe romper login/admin.
- Guardar contraseñas correctamente importa incluso en plugins pequeños.
- La UI también forma parte del producto.
- IA acelera, pero la revisión técnica decide.

---

## Orden recomendado de implementación

1. Estructura de clases.
2. Opciones/defaults.
3. Admin básico.
4. Guardado seguro de ajustes.
5. CSS admin según design system.
6. Protección frontend sin templates.
7. Cookie y logout.
8. Templates frontend.
9. Internacionalización.
10. Uninstall avanzado.
11. Documentación.
12. Validación final.
13. Posts LinkedIn restantes.

