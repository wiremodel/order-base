<?php

namespace App\Filament\Resources\MenuItems\Actions;

use App\Models\MenuItem;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class IsAvailable
{
    protected const string Action_ID = 'is_available_toggle';

    public static function action(): Action
    {
        return Action::make(static::Action_ID)
            ->action(static::handleAction(...))
            // You may have a custom form schema if needed, and get that in the arguments' injection
            // ->schema([
            //     TextInput...
            // ])
            ->requiresConfirmation()
            ->modalHeading(fn($arguments) => 'Change Availability of: ' . data_get($arguments, 'record.name') . '?')
            ->extraAttributes(['class' => 'hidden']);
    }

    public static function updateStateUsing(Page $livewire, MenuItem $record, $state): void
    {
        $livewire->mountAction(static::Action_ID, [
            'record' => $record,
            'state' => $state,
        ]);
    }

    public static function handleAction(Page $livewire, Action $action, array $arguments): void
    {
        $state = data_get($arguments, 'state');

        /** @var MenuItem $record */
        $record = $arguments['record'];

        // Prevent changing availability of chicken items, as an example business rule
        if (str($record->name)->contains(['Chicken', 'chicken'])) {
            Notification::make()
                ->title('You cannot change availability of chicken items.')
                ->danger()
                ->send();

            $livewire->unmountAction();

            $action->halt();
        }

        $record->is_available = $state;
        $record->save();
    }
}
