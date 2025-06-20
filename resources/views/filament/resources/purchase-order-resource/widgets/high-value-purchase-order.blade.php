<x-filament::modal
    :id="self::ID"
    heading="High Value Purchase Order"
    width="lg"
    :close-by-clicking-away="false"
    :close-by-escaping="false"
    x-init="() => {
        if ($wire.get('shouldTriggerOnMount')) {
            open()
        }
    }"
    x-on:modal-opened="$wire.$refresh"
>
    <div>
        <p> The order to {{ $this->record->supplier->name }} may be delayed if insufficient funds.</p>
        <p>Check the bank balance, for at least ${{ $this->record->present_total }} !</p>
    </div>

    <x-slot name="footerActions">
        <x-filament::button color="primary" outlined @click="close">
            I have checked the bank balance - all good!
        </x-filament::button>
    </x-slot>
</x-filament::modal>
