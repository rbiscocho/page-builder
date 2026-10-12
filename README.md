# Filament Tailwind Page Builder

A Filament (v4/v5) page builder that stores an editable **design** JSON tree and server-generated **Tailwind CSS HTML**. The HTML is served to a separate React frontend via a public API.

**Repository:** https://github.com/rbiscocho/page-builder

## Features

- Block system + TokenMap → Tailwind HTML exporter
- Design **canvas** (palette, inspector, preview, save/publish)
- Public API for React + safelist command
- Draft vs published HTML

## Install (inside your app under packages/)

```bash
mkdir -p packages
git clone https://github.com/rbiscocho/page-builder.git packages/page-builder
```

In `composer.json`:

```json
"repositories": [{ "type": "path", "url": "packages/page-builder", "options": { "symlink": true } }],
"require": { "pagebuilder/filament-page-builder": "*" }
```

```bash
composer update pagebuilder/filament-page-builder
php artisan vendor:publish --tag="filament-page-builder-migrations"
php artisan vendor:publish --tag="filament-page-builder-config"
php artisan migrate
```

## Design canvas

See **[docs/canvas-setup.md](docs/canvas-setup.md)** — extend `DesignPage`, register route `/admin/pages/{record}/design`.

## API

- `GET /api/pages`
- `GET /api/pages/{slug}`
- `GET /api/page-builder/safelist.css`

## React

See [docs/react-integration.md](docs/react-integration.md).

## License

MIT
