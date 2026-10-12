<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use PageBuilder\FilamentPageBuilder\Models\Page as PageModel;
use PageBuilder\FilamentPageBuilder\Support\BlockRegistry;
use PageBuilder\FilamentPageBuilder\Support\HtmlExporter;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

class DesignPage extends Page
{
    use InteractsWithRecord;

    /** @var view-string */
    protected static string $view = 'filament-page-builder::filament.pages.design-page';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $title = 'Design';

    /** @var array<int, array<string, mixed>> */
    public array $design = [];

    public ?string $selectedId = null;

    public string $previewWidth = 'desktop';

    public string $activeTab = 'canvas';

    public string $liveHtml = '';

    /** @var list<array{design: array, selectedId: ?string}> */
    public array $undoStack = [];

    /** @var list<array{design: array, selectedId: ?string}> */
    public array $redoStack = [];

    public bool $isDirty = false;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->design = $this->normalizeDesign($this->record->design ?? []);
        $this->refreshHtml();
        $this->pushUndoSnapshot(clearRedo: true);
        $this->isDirty = false;
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->title ?? 'Design';
    }

    public function getHeading(): string|Htmlable
    {
        return $this->record->title ?? 'Design';
    }

    public function getMaxContentWidth(): string|MaxWidth|null
    {
        return MaxWidth::Full;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'palette' => app(BlockRegistry::class)->palette(),
            'selected' => $this->findBlock($this->selectedId),
            'tokenOptions' => $this->tokenOptions(),
            'previewMaxWidth' => match ($this->previewWidth) {
                'mobile' => '375px',
                'tablet' => '768px',
                default => '100%',
            },
            'status' => $this->record->status ?? 'draft',
        ];
    }

    public function addBlock(string $type): void
    {
        $registry = app(BlockRegistry::class);
        if (! $registry->has($type)) {
            return;
        }

        /** @var class-string<\PageBuilder\FilamentPageBuilder\Contracts\Block> $class */
        $class = $registry->get($type);
        $id = uniqid('b_', true);

        $block = [
            'id' => $id,
            'type' => $type,
            'data' => $class::defaults(),
            'children' => $type === 'section' ? [] : null,
            'position' => count($this->design),
        ];

        if ($type !== 'section') {
            unset($block['children']);
        }

        $this->snapshotBeforeChange();
        $this->design[] = $block;
        $this->selectedId = $id;
        $this->isDirty = true;
        $this->refreshHtml();
    }

    public function selectBlock(?string $id): void
    {
        $this->selectedId = $id;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateSelectedData(array $data): void
    {
        if (! $this->selectedId) {
            return;
        }

        $this->snapshotBeforeChange();
        $this->design = $this->mapBlocks($this->design, function (array $block) use ($data) {
            if (($block['id'] ?? null) !== $this->selectedId) {
                return $block;
            }
            $block['data'] = array_merge($block['data'] ?? [], $data);

            return $block;
        });
        $this->isDirty = true;
        $this->refreshHtml();
    }

    public function updateField(string $key, mixed $value): void
    {
        if (! $this->selectedId) {
            return;
        }

        $this->snapshotBeforeChange();
        $this->design = $this->mapBlocks($this->design, function (array $block) use ($key, $value) {
            if (($block['id'] ?? null) !== $this->selectedId) {
                return $block;
            }
            $block['data'][$key] = $value;

            return $block;
        });
        $this->isDirty = true;
        $this->refreshHtml();
    }

    public function deleteSelected(): void
    {
        if (! $this->selectedId) {
            return;
        }

        $this->snapshotBeforeChange();
        $this->design = $this->filterBlocks($this->design, $this->selectedId);
        $this->selectedId = null;
        $this->isDirty = true;
        $this->refreshHtml();
    }

    public function moveSelected(string $direction): void
    {
        if (! $this->selectedId) {
            return;
        }

        $index = null;
        foreach ($this->design as $i => $block) {
            if (($block['id'] ?? null) === $this->selectedId) {
                $index = $i;
                break;
            }
        }
        if ($index === null) {
            return;
        }

        $swapWith = $direction === 'up' ? $index - 1 : $index + 1;
        if ($swapWith < 0 || $swapWith >= count($this->design)) {
            return;
        }

        $this->snapshotBeforeChange();
        $tmp = $this->design[$index];
        $this->design[$index] = $this->design[$swapWith];
        $this->design[$swapWith] = $tmp;
        $this->design = array_values($this->design);
        $this->isDirty = true;
        $this->refreshHtml();
    }

    public function duplicateSelected(): void
    {
        if (! $this->selectedId) {
            return;
        }

        $copy = null;
        $insertAt = null;
        foreach ($this->design as $i => $block) {
            if (($block['id'] ?? null) === $this->selectedId) {
                $copy = $this->remintIds($block);
                $insertAt = $i + 1;
                break;
            }
        }
        if ($copy === null || $insertAt === null) {
            return;
        }

        $this->snapshotBeforeChange();
        array_splice($this->design, $insertAt, 0, [$copy]);
        $this->selectedId = $copy['id'];
        $this->isDirty = true;
        $this->refreshHtml();
    }

    public function undo(): void
    {
        if (count($this->undoStack) <= 1) {
            return;
        }
        $current = array_pop($this->undoStack);
        $this->redoStack[] = $current;
        $prev = end($this->undoStack);
        $this->design = $prev['design'];
        $this->selectedId = $prev['selectedId'];
        $this->isDirty = true;
        $this->refreshHtml();
    }

    public function redo(): void
    {
        if ($this->redoStack === []) {
            return;
        }
        $next = array_pop($this->redoStack);
        $this->undoStack[] = $next;
        $this->design = $next['design'];
        $this->selectedId = $next['selectedId'];
        $this->isDirty = true;
        $this->refreshHtml();
    }

    public function setPreviewWidth(string $width): void
    {
        if (in_array($width, ['desktop', 'tablet', 'mobile'], true)) {
            $this->previewWidth = $width;
        }
    }

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, ['canvas', 'code'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function save(): void
    {
        /** @var PageModel $record */
        $record = $this->record;
        $exporter = app(HtmlExporter::class);

        $record->design = $this->design;
        $record->regenerateHtml($exporter);
        $record->save();

        $this->liveHtml = $record->html ?? '';
        $this->isDirty = false;

        Notification::make()
            ->title('Saved')
            ->success()
            ->send();
    }

    public function publish(): void
    {
        $this->save();

        /** @var PageModel $record */
        $record = $this->record;
        $record->publish();
        $record->save();

        Notification::make()
            ->title('Published')
            ->success()
            ->body('API will now serve this version.')
            ->send();
    }

    protected function refreshHtml(): void
    {
        $this->liveHtml = app(HtmlExporter::class)->export($this->design);
    }

    /**
     * @param  array<string, mixed>  $block
     */
    public function blockHtml(array $block): string
    {
        return app(HtmlExporter::class)->export([$block]);
    }

    protected function snapshotBeforeChange(): void
    {
        $this->pushUndoSnapshot(clearRedo: true);
    }

    protected function pushUndoSnapshot(bool $clearRedo = false): void
    {
        $this->undoStack[] = [
            'design' => $this->design,
            'selectedId' => $this->selectedId,
        ];
        if (count($this->undoStack) > 30) {
            array_shift($this->undoStack);
        }
        if ($clearRedo) {
            $this->redoStack = [];
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $blocks
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeDesign(array $blocks): array
    {
        return collect($blocks)->values()->map(function (array $block, int $i) {
            $block['id'] = $block['id'] ?? uniqid('b_', true);
            $block['position'] = $block['position'] ?? $i;
            $block['data'] = $block['data'] ?? [];
            if (($block['type'] ?? '') === 'section' && ! isset($block['children'])) {
                $block['children'] = [];
            }

            return $block;
        })->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $blocks
     * @param  callable(array): array  $callback
     * @return array<int, array<string, mixed>>
     */
    protected function mapBlocks(array $blocks, callable $callback): array
    {
        return array_map(function (array $block) use ($callback) {
            $block = $callback($block);
            if (! empty($block['children']) && is_array($block['children'])) {
                $block['children'] = $this->mapBlocks($block['children'], $callback);
            }

            return $block;
        }, $blocks);
    }

    /**
     * @param  array<int, array<string, mixed>>  $blocks
     * @return array<int, array<string, mixed>>
     */
    protected function filterBlocks(array $blocks, string $removeId): array
    {
        $out = [];
        foreach ($blocks as $block) {
            if (($block['id'] ?? null) === $removeId) {
                continue;
            }
            if (! empty($block['children']) && is_array($block['children'])) {
                $block['children'] = $this->filterBlocks($block['children'], $removeId);
            }
            $out[] = $block;
        }

        return array_values($out);
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function findBlock(?string $id): ?array
    {
        if ($id === null) {
            return null;
        }

        $found = null;
        $this->mapBlocks($this->design, function (array $block) use ($id, &$found) {
            if (($block['id'] ?? null) === $id) {
                $found = $block;
            }

            return $block;
        });

        return $found;
    }

    /**
     * @param  array<string, mixed>  $block
     * @return array<string, mixed>
     */
    protected function remintIds(array $block): array
    {
        $block['id'] = uniqid('b_', true);
        if (! empty($block['children']) && is_array($block['children'])) {
            $block['children'] = array_map(fn ($c) => $this->remintIds($c), $block['children']);
        }

        return $block;
    }

    /**
     * @return array<string, array<string, string>>
     */
    protected function tokenOptions(): array
    {
        return [
            'padding_y' => array_keys(TokenMap::PADDING_Y),
            'width' => array_keys(TokenMap::WIDTH),
            'background' => array_keys(TokenMap::BACKGROUND),
            'align' => array_keys(TokenMap::ALIGN),
            'gap' => array_keys(TokenMap::GAP),
            'columns' => array_keys(TokenMap::COLUMNS),
            'text_color' => array_keys(TokenMap::TEXT_COLOR),
            'button' => array_keys(TokenMap::BUTTON),
            'spacer' => array_keys(TokenMap::SPACER),
            'divider' => array_keys(TokenMap::DIVIDER),
            'level' => array_keys(TokenMap::HEADING),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->icon('heroicon-o-bookmark')
                ->action('save')
                ->color('gray'),
            Action::make('publish')
                ->label('Publish')
                ->icon('heroicon-o-globe-alt')
                ->action('publish')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Publish page?')
                ->modalDescription('The API will serve this HTML to the React frontend.'),
        ];
    }
}
