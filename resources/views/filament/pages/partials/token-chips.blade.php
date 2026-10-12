<div>
    <div class="mb-1 text-xs text-gray-400">{{ $label }}</div>
    <div class="flex flex-wrap gap-1">
        @foreach ($options as $opt)
            <button
                type="button"
                wire:click="updateField('{{ $field }}', '{{ $opt }}')"
                @class([
                    'rounded-md px-2 py-1 text-xs font-medium transition',
                    'bg-primary-600 text-white' => ($value ?? '') === $opt,
                    'bg-gray-800 text-gray-300 hover:bg-gray-700' => ($value ?? '') !== $opt,
                ])
            >{{ $opt }}</button>
        @endforeach
    </div>
</div>
