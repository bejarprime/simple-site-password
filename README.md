# Simple Site Password

Simple Site Password is a small WordPress plugin that protects an entire site with one global password.

It is useful for development sites, private client previews, pre-launch pages and temporary private websites.

## Features

- Global password protection for visitors.
- Password stored as a hash, never in plain text.
- Signed access cookie with configurable duration.
- Optional administrator bypass.
- Does not block WordPress login, admin, AJAX, REST API, cron or WP-CLI.
- Three password screen templates:
  - Minimal
  - Dark
  - Gradient
- Custom title, description and button text.
- Live preview in the settings screen.
- Spanish translation included.
- Optional settings cleanup on uninstall.

## What it is not

This plugin is not a replacement for:

- membership systems,
- firewalls,
- role-based access control,
- enterprise authentication,
- advanced protection for highly sensitive data.

It is intended for simple password-based access restriction.

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate it from the WordPress Plugins screen.
3. Go to `Settings > Simple Site Password`.
4. Set a password.
5. Enable global protection.
6. Save settings.

## Security decisions

### Password storage

The password is stored with WordPress password hashing functions. The original password cannot be recovered or shown after saving.

### Access cookie

Visitor access is remembered using a signed cookie. If the password changes, previous cookies are invalidated.

### WordPress routes

The plugin avoids blocking critical WordPress routes:

- `wp-login.php`
- `wp-admin`
- AJAX
- REST API
- WP Cron
- WP-CLI

## Development notes

This plugin is part of a public portfolio series focused on building small, maintainable WordPress plugins with good practices.

The repository contains only the public plugin files. Internal planning and LinkedIn content are kept outside the public repository.

## License

GPLv2 or later.

