# Filament Tailwind Page Builder

A Filament (v4/v5) page builder that stores an editable **design** JSON tree and server-generated **Tailwind CSS HTML**. The HTML is served to a separate React frontend via a public API.

**Repository:** https://github.com/rbiscocho/page-builder

> Note: The requested org `hasp` was not accessible with the connected GitHub account (`rbiscocho`). The repo was created under that account as `page-builder`. You can transfer it to `hasp/page-builder` in GitHub settings if you have access to that org.

## Requirements

- PHP 8.3+
- Laravel 11/12
- Filament 4.12+ or 5.x
- Tailwind CSS v4

## Install

```bash
composer require pagebuilder/filament-page-builder
php artisan vendor:publish --tag="filament-page-builder-migrations"
php artisan vendor:publish --tag="filament-page-builder-config"
php artisan migrate
```

## Commands

```bash
php artisan page-builder:regenerate --all
php artisan page-builder:tailwind-safelist
```

## API

- `GET /api/pages`
- `GET /api/pages/{slug}` (published only)
- `GET /api/page-builder/safelist.css`

See `docs/react-integration.md` for React + DOMPurify setup.

## License

MIT
