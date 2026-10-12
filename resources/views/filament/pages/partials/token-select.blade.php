@php
    $isAssoc = $assoc ?? false;
@endphp
<label class="block text-xs text-gray-400">{{ $label }}
    <select
        wire:change="updateField('{{ $field }}', $event.target.value)"
        class="mt-1 w-full rounded-lg border-gray-700 bg-gray-950 text-sm text-white"
    >
        @if ($isAssoc)
            @foreach ($options as $optLabel => $optValue)
                <option value="{{ $optValue }}" @selected(($value ?? '') === $optValue)>{{ $optLabel }}</option>
            @endforeach
        @else
            @foreach ($options as $opt)
                <option value="{{ $opt }}" @selected(($value ?? '') === $opt)>{{ $opt }}</option>
            @endforeach
        @endif
    </select>
</label>
