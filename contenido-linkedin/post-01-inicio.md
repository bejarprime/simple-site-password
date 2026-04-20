Empiezo el primer plugin de esta serie: Simple Site Password.

La idea es sencilla:

Crear un plugin WordPress que permita proteger todo un sitio con una contraseña global.

Caso típico:

- Web en desarrollo.
- Demo privada para cliente.
- Landing antes del lanzamiento.
- Catálogo temporalmente privado.

Parece un plugin simple, pero tiene retos interesantes:

1. No bloquear el login de WordPress.
2. No romper el admin.
3. No interferir con AJAX, REST API o cron.
4. Guardar la contraseña de forma segura, no en texto plano.
5. Usar cookies con cuidado.
6. Proteger formularios con nonces.
7. Sanitizar entradas y escapar salidas.

El objetivo de esta serie no es hacer plugins enormes.

El objetivo es crear plugins pequeños, útiles, documentados y con buenas prácticas reales de WordPress.

Durante esta semana iré documentando el proceso: especificación, arquitectura, desarrollo, seguridad y demo final.

#WordPress #PHP #OpenSource #DesarrolloWeb

