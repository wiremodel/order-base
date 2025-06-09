<x-filament-widgets::widget class="fi-wi-table">
    @if($queryCount > 0)
        <x-filament::section
            :heading="$message"
            :collapsible="true"
        >
            {{ $table }}
        </x-filament::section>
    @else
        <x-filament::section>
            {{ $message }}
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
