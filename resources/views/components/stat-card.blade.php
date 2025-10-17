@props(['label' => '', 'value' => 0, 'icon' => '📊', 'color' => 'blue'])

<div class="bg-white rounded-lg shadow p-6 border-l-4 {{ 'border-' . $color . '-500' }}">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600 text-sm font-medium">{{ $label }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
        </div>
        <div class="text-4xl">{{ $icon }}</div>
    </div>
</div>
