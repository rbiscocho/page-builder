# Design canvas setup

The package ships a full-screen **Design** page (palette + canvas + token inspector).

Wire it to **your** Filament `PageResource`.

## 1. Create the app page class

`app/Filament/Resources/Pages/DesignPage.php` (or next to your resource):

```php
<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\PageResource;
use PageBuilder\FilamentPageBuilder\Filament\Pages\DesignPage as BaseDesignPage;

class DesignPage extends BaseDesignPage
{
    protected static string $resource = PageResource::class;
}
```

## 2. Register the route on the resource

In `PageResource::getPages()`:

```php
use App\Filament\Resources\Pages\DesignPage;

public static function getPages(): array
{
    return [
        'index'  => Pages\ListPages::route('/'),
        'create' => Pages\CreatePage::route('/create'),
        'edit'   => Pages\EditPage::route('/{record}/edit'),
        'design' => DesignPage::route('/{record}/design'),
    ];
}
```

## 3. Add a Design button on Edit / table

```php
use Filament\Actions\Action;

Action::make('design')
    ->label('Design')
    ->icon('heroicon-o-paint-brush')
    ->url(fn ($record) => static::getResource()::getUrl('design', ['record' => $record])),
```

Table:

```php
Tables\Actions\Action::make('design')
    ->icon('heroicon-o-paint-brush')
    ->url(fn ($record) => PageResource::getUrl('design', ['record' => $record])),
```

## 4. Use it

1. Create a page (title + slug) via Create.
2. Open **Design** at `/admin/pages/{id}/design`.
3. Click blocks in the left palette to add them.
4. Click a block to select it and edit content + tokens on the right.
5. **Save** writes `design` and regenerates `html`.
6. **Publish** copies to `published_html` (API serves this).

## Canvas features (v1)

| Feature | Status |
|---------|--------|
| Palette (all 8 blocks) | Yes |
| Click to add | Yes |
| Select / edit content | Yes |
| Token inspector (no free CSS) | Yes |
| Move up/down, duplicate, delete | Yes |
| Undo / redo | Yes |
| Desktop / tablet / mobile width | Yes |
| Live Tailwind HTML preview | Yes |
| Code tab + copy | Yes |
| Save + Publish | Yes |
| Nested drag into section columns | Later |
| Inline contenteditable on canvas | Later |

## Model

```php
use PageBuilder\FilamentPageBuilder\Models\Page;

protected static ?string $model = Page::class;
```
