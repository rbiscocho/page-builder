<x-filament-panels::page>
<style>
.pb-root{display:flex;flex-direction:column;height:calc(100vh - 9.5rem);margin:-1rem;background:#0b0f19;color:#e5e7eb;border-radius:.75rem;overflow:hidden;border:1px solid #1f2937}
.pb-toolbar{display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.5rem 1rem;background:#111827;border-bottom:1px solid #1f2937;flex-shrink:0}
.pb-toolbar .meta{display:flex;align-items:center;gap:.5rem;font-size:.875rem}
.pb-toolbar .meta .title{font-weight:600;color:#fff}
.pb-badge{font-size:.7rem;padding:.15rem .5rem;border-radius:999px;background:#422006;color:#fbbf24}
.pb-badge.pub{background:#064e3b;color:#6ee7b7}
.pb-dirty{font-size:.75rem;color:#fbbf24}
.pb-tools{display:flex;align-items:center;gap:.25rem;flex-wrap:wrap}
.pb-btn{appearance:none;border:0;background:transparent;color:#9ca3af;padding:.35rem .6rem;border-radius:.4rem;font-size:.75rem;cursor:pointer}
.pb-btn:hover{background:#1f2937;color:#fff}
.pb-btn.active{background:#2563eb;color:#fff}
.pb-btn.primary{background:#2563eb;color:#fff;font-weight:600}
.pb-sep{width:1px;height:1.25rem;background:#374151;margin:0 .25rem}
.pb-body{display:flex;min-height:0;flex:1}
.pb-palette{width:14rem;flex-shrink:0;border-right:1px solid #1f2937;background:#111827;display:flex;flex-direction:column}
.pb-palette h3,.pb-inspector h3{margin:0;padding:.6rem .75rem;font-size:.65rem;letter-spacing:.06em;text-transform:uppercase;color:#6b7280;border-bottom:1px solid #1f2937}
.pb-palette-hint{padding:.35rem .75rem;font-size:.7rem;color:#6b7280}
.pb-palette-list{padding:.5rem;overflow:auto;flex:1;display:flex;flex-direction:column;gap:.35rem}
.pb-palette-item{display:flex;flex-direction:column;align-items:flex-start;gap:.15rem;width:100%;text-align:left;padding:.55rem .65rem;border-radius:.5rem;border:1px solid #1f2937;background:#0b0f19;color:#e5e7eb;font-size:.8125rem;cursor:pointer}
.pb-palette-item:hover{border-color:#3b82f6;background:#1e293b}
.pb-palette-item small{font-size:.65rem;color:#6b7280}
.pb-canvas-wrap{flex:1;min-width:0;overflow:auto;padding:1.25rem;background:#0f172a;display:flex;justify-content:center;align-items:flex-start}
.pb-frame{width:100%;background:#fff;color:#111827;border-radius:.75rem;box-shadow:0 25px 50px -12px rgb(0 0 0 / .5);border:1px solid #334155;min-height:28rem;position:relative}
.pb-frame-bar{padding:.35rem .75rem;font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;background:#f8fafc;border-bottom:1px solid #e2e8f0}
.pb-frame-body{padding:1.25rem;display:flex;flex-direction:column;gap:.75rem;min-height:24rem}
.pb-empty{text-align:center;padding:5rem 1rem;color:#94a3b8;font-size:.875rem}
.pb-block{position:relative;border:2px solid transparent;border-radius:.5rem;padding:.35rem;cursor:pointer}
.pb-block:hover{border-color:#cbd5e1}
.pb-block.is-selected{border-color:#3b82f6;box-shadow:0 0 0 1px #3b82f6;background:rgb(239 246 255 / .35)}
.pb-block-toolbar{position:absolute;left:50%;bottom:-2.1rem;transform:translateX(-50%);display:none;align-items:center;gap:.15rem;background:#0f172a;border-radius:.5rem;padding:.2rem;z-index:20}
.pb-block.is-selected .pb-block-toolbar{display:flex}
.pb-block-toolbar button{appearance:none;border:0;background:transparent;color:#e5e7eb;padding:.35rem .45rem;border-radius:.35rem;font-size:.7rem;cursor:pointer}
.pb-block-toolbar button:hover{background:#1e293b}
.pb-block-toolbar button.danger{color:#f87171}
.pb-inline[contenteditable=true]{outline:2px dashed #93c5fd;outline-offset:2px;border-radius:.25rem;min-width:2rem}
.pb-inline[contenteditable=true]:focus{outline-color:#2563eb;background:#eff6ff}
.pb-code{width:100%;margin:0;padding:1rem;background:#020617;color:#6ee7b7;font-size:.75rem;line-height:1.5;border-radius:.5rem;overflow:auto;white-space:pre-wrap}
.pb-inspector{width:17rem;flex-shrink:0;border-left:1px solid #1f2937;background:#111827;display:flex;flex-direction:column;overflow:hidden}
.pb-inspector-body{padding:.75rem;overflow:auto;flex:1;font-size:.8125rem}
.pb-inspector-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem}
.pb-inspector-head strong{color:#fff;text-transform:capitalize}
.pb-field{display:block;margin-bottom:.65rem;color:#9ca3af;font-size:.7rem}
.pb-field input,.pb-field textarea,.pb-field select{display:block;width:100%;margin-top:.25rem;padding:.4rem .5rem;border-radius:.4rem;border:1px solid #374151;background:#0b0f19;color:#f3f4f6;font-size:.8125rem}
.pb-field textarea{min-height:5rem;resize:vertical}
.pb-tokens{border-top:1px solid #1f2937;padding-top:.75rem;margin-top:.5rem}
.pb-tokens-title{font-size:.65rem;letter-spacing:.06em;text-transform:uppercase;color:#6b7280;margin-bottom:.5rem}
.pb-chip-row{display:flex;flex-wrap:wrap;gap:.25rem;margin-bottom:.65rem}
.pb-chip-row span{display:block;width:100%;font-size:.7rem;color:#9ca3af;margin-bottom:.2rem}
.pb-tok{appearance:none;border:0;background:#1f2937;color:#d1d5db;padding:.25rem .45rem;border-radius:.3rem;font-size:.7rem;cursor:pointer}
.pb-tok:hover{background:#374151;color:#fff}
.pb-tok.on{background:#2563eb;color:#fff}
.pb-hint{font-size:.7rem;color:#6b7280;margin-top:.75rem;line-height:1.4}
.pb-breadcrumb{font-size:.7rem;color:#64748b;padding:0 .25rem .5rem}
.pb-breadcrumb span{color:#3b82f6}
.pb-block-content{pointer-events:none}
.pb-block.is-selected .pb-block-content{pointer-events:auto}
.pb-block-content h1,.pb-block-content h2,.pb-block-content h3,.pb-block-content h4{font-weight:700;line-height:1.25;margin:0}
.pb-block-content h1{font-size:2rem}.pb-block-content h2{font-size:1.5rem}.pb-block-content h3{font-size:1.25rem}
.pb-block-content p{margin:0;line-height:1.6;color:#334155}
.pb-block-content a{display:inline-flex;padding:.5rem 1rem;border-radius:.5rem;background:#2563eb;color:#fff;text-decoration:none;font-weight:600;font-size:.875rem}
.pb-block-content img{max-width:100%;height:auto;border-radius:.5rem}
.pb-block-content hr{border:0;border-top:1px solid #e2e8f0;margin:.5rem 0}
</style>

<div class="pb-root">
  <div class="pb-toolbar">
    <div class="meta">
      <span class="title">{{ $this->record->title }}</span>
      <span @class(['pb-badge', 'pub' => ($status ?? '') === 'published'])>{{ $status ?? 'draft' }}</span>
      @if ($isDirty)<span class="pb-dirty">Unsaved</span>@endif
    </div>
    <div class="pb-tools">
      <button type="button" class="pb-btn" wire:click="undo">Undo</button>
      <button type="button" class="pb-btn" wire:click="redo">Redo</button>
      <span class="pb-sep"></span>
      @foreach (['desktop' => 'Desktop', 'tablet' => 'Tablet', 'mobile' => 'Mobile'] as $key => $label)
        <button type="button" wire:click="setPreviewWidth('{{ $key }}')" @class(['pb-btn', 'active' => $previewWidth === $key])>{{ $label }}</button>
      @endforeach
      <span class="pb-sep"></span>
      <button type="button" wire:click="setActiveTab('canvas')" @class(['pb-btn', 'active' => $activeTab === 'canvas'])>Canvas</button>
      <button type="button" wire:click="setActiveTab('code')" @class(['pb-btn', 'active' => $activeTab === 'code'])>Code</button>
      <span class="pb-sep"></span>
      <button type="button" class="pb-btn" wire:click="save">Save</button>
      <button type="button" class="pb-btn primary" wire:click="publish" wire:confirm="Publish this page to the API?">Publish</button>
    </div>
  </div>

  <div class="pb-body">
    <aside class="pb-palette">
      <h3>Blocks</h3>
      <div class="pb-palette-hint">Click a block to add it to the canvas.</div>
      <div class="pb-palette-list">
        @foreach ($palette as $item)
          <button type="button" class="pb-palette-item" wire:click="addBlock('{{ $item['type'] }}')">
            <span>{{ $item['label'] }}</span>
            <small>{{ $item['type'] }}</small>
          </button>
        @endforeach
      </div>
    </aside>

    <main class="pb-canvas-wrap">
      @if ($activeTab === 'code')
        <pre class="pb-code">{{ $liveHtml !== '' ? $liveHtml : '<!-- empty -->' }}</pre>
      @else
        <div class="pb-frame" style="max-width: {{ $previewMaxWidth }};">
          <div class="pb-frame-bar">Canvas · click a block to select · edit heading/text when selected</div>
          <div class="pb-frame-body">
            @if ($selected)
              <div class="pb-breadcrumb">Body › <span>{{ $selected['type'] ?? 'block' }}</span></div>
            @endif

            @forelse ($design as $block)
              @php
                $id = $block['id'] ?? '';
                $type = $block['type'] ?? 'unknown';
                $data = $block['data'] ?? [];
                $isSelected = ($selectedId ?? null) === $id;
                $html = $this->blockHtml($block);
              @endphp
              <div class="pb-block {{ $isSelected ? 'is-selected' : '' }}" wire:key="block-{{ $id }}" wire:click="selectBlock('{{ $id }}')">
                <div class="pb-block-content">
                  @if ($isSelected && $type === 'heading')
                    @php $tag = in_array($data['level'] ?? 'h2', ['h1','h2','h3','h4','h5','h6'], true) ? ($data['level'] ?? 'h2') : 'h2'; @endphp
                    <{{ $tag }}
                      class="pb-inline"
                      contenteditable="true"
                      wire:ignore.self
                      x-on:blur="$wire.updateField('text', $event.target.innerText.trim())"
                      x-on:keydown.enter.prevent="$event.target.blur()"
                    >{{ $data['text'] ?? 'Heading' }}</{{ $tag }}>
                  @elseif ($isSelected && $type === 'text')
                    <div
                      class="pb-inline"
                      contenteditable="true"
                      wire:ignore.self
                      x-on:blur="$wire.updateField('content', $event.target.innerText.trim())"
                    >{{ $data['content'] ?? 'Text' }}</div>
                  @else
                    {!! $html !!}
                  @endif
                </div>
                <div class="pb-block-toolbar" wire:click.stop>
                  <button type="button" wire:click="moveSelected('up')">↑</button>
                  <button type="button" wire:click="moveSelected('down')">↓</button>
                  <button type="button" wire:click="duplicateSelected">Dup</button>
                  <button type="button" class="danger" wire:click="deleteSelected" wire:confirm="Delete this block?">Del</button>
                </div>
              </div>
            @empty
              <div class="pb-empty">Empty canvas<br><span style="font-size:.75rem">Click a block on the left, then select it to edit text on the canvas</span></div>
            @endforelse
          </div>
        </div>
      @endif
    </main>

    <aside class="pb-inspector">
      <h3>Style</h3>
      <div class="pb-inspector-body">
        @if ($selected)
          @php $data = $selected['data'] ?? []; @endphp
          <div class="pb-inspector-head"><strong>{{ $selected['type'] ?? 'block' }}</strong></div>
          <p class="pb-hint" style="margin-top:0">For Heading / Text: click the text on the canvas and type.</p>

          @if (($selected['type'] ?? '') === 'heading')
            <label class="pb-field">Text<input type="text" value="{{ $data['text'] ?? '' }}" wire:change="updateField('text', $event.target.value)" /></label>
            <label class="pb-field">Level
              <select wire:change="updateField('level', $event.target.value)">
                @foreach ($tokenOptions['level'] as $opt)
                  <option value="{{ $opt }}" @selected(($data['level'] ?? 'h2') === $opt)>{{ $opt }}</option>
                @endforeach
              </select>
            </label>
          @elseif (($selected['type'] ?? '') === 'text')
            <label class="pb-field">Content<textarea wire:change="updateField('content', $event.target.value)">{{ $data['content'] ?? '' }}</textarea></label>
          @elseif (($selected['type'] ?? '') === 'button')
            <label class="pb-field">Label<input type="text" value="{{ $data['label'] ?? '' }}" wire:change="updateField('label', $event.target.value)" /></label>
            <label class="pb-field">URL<input type="text" value="{{ $data['href'] ?? '' }}" wire:change="updateField('href', $event.target.value)" /></label>
          @elseif (($selected['type'] ?? '') === 'image')
            <label class="pb-field">Src<input type="text" value="{{ $data['src'] ?? '' }}" wire:change="updateField('src', $event.target.value)" /></label>
            <label class="pb-field">Alt<input type="text" value="{{ $data['alt'] ?? '' }}" wire:change="updateField('alt', $event.target.value)" /></label>
          @elseif (($selected['type'] ?? '') === 'section')
            <label class="pb-field">Columns
              <select wire:change="updateField('columns', $event.target.value)">
                @foreach ($tokenOptions['columns'] as $opt)
                  <option value="{{ $opt }}" @selected(($data['columns'] ?? '1') === $opt)>{{ $opt }}</option>
                @endforeach
              </select>
            </label>
          @endif

          <div class="pb-tokens">
            <div class="pb-tokens-title">Design tokens</div>
            @foreach (['padding_y' => 'Padding', 'width' => 'Width', 'background' => 'Background', 'align' => 'Alignment'] as $field => $label)
              <div class="pb-chip-row">
                <span>{{ $label }}</span>
                @foreach ($tokenOptions[$field] as $opt)
                  <button type="button" wire:click="updateField('{{ $field }}', '{{ $opt }}')" @class(['pb-tok', 'on' => ($data[$field] ?? null) === $opt])>{{ $opt }}</button>
                @endforeach
              </div>
            @endforeach
          </div>
        @else
          <p class="pb-hint">Select a block on the canvas to edit content and tokens.</p>
        @endif
      </div>
    </aside>
  </div>
</div>
</x-filament-panels::page>
