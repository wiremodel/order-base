<x-filament-widgets::widget class="fi-wi-table">
    <x-filament::section
        :heading="$heading"
        :collapsible="true"
        :collapsed="$queryCount === 0"
    >
        {{ $table }}
    </x-filament::section>
</x-filament-widgets::widget>
