# CHECKLIST - Simple Site Password

## Definición

- [x] Nombre definido: Simple Site Password.
- [x] Slug definido: `simple-site-password`.
- [x] Problema definido.
- [x] MVP definido.
- [x] Funcionalidades fuera del MVP definidas.
- [x] Riesgos técnicos identificados.

## Estructura

- [x] Carpeta del proyecto creada.
- [x] Carpeta del plugin creada.
- [x] Carpeta de contenido LinkedIn creada.
- [x] `SPEC.md` creado.
- [x] `CHECKLIST.md` creado.
- [x] `README_PROYECTO.md` creado.
- [x] Primer post de LinkedIn creado.

## Plugin WordPress

- [ ] Header válido del plugin.
- [ ] `readme.txt` creado.
- [ ] `LICENSE` creado.
- [ ] `uninstall.php` creado.
- [ ] Página de ajustes creada.
- [ ] Protección frontend implementada.
- [ ] Cookie de acceso implementada.
- [ ] Acción de cerrar acceso implementada.
- [ ] Excepciones implementadas.

## Seguridad

- [ ] Contraseña guardada con hash.
- [ ] Nonces en formulario admin.
- [ ] Nonces en formulario frontend.
- [ ] `current_user_can( 'manage_options' )` en ajustes.
- [ ] Sanitización de entradas.
- [ ] Escape de salidas.
- [ ] Sin SQL personalizado innecesario.
- [ ] Sin tracking.
- [ ] Sin llamadas externas.
- [ ] Sin ejecución de código remoto.

## Validación

- [ ] Activa sin errores.
- [ ] Desactiva sin errores.
- [ ] Desinstala limpiando opciones.
- [ ] Funciona con `WP_DEBUG`.
- [ ] No bloquea `wp-login.php`.
- [ ] No bloquea `wp-admin`.
- [ ] No bloquea AJAX.
- [ ] No bloquea REST API.
- [ ] No bloquea cron.
- [ ] Un visitante sin cookie ve el formulario.
- [ ] Un visitante con contraseña correcta accede.
- [ ] Un visitante con contraseña incorrecta ve error seguro.

## LinkedIn

- [x] `post-01-inicio.md` creado.
- [ ] Post técnico creado.
- [ ] Post final/demo creado.
- [ ] Aprendizajes documentados.

