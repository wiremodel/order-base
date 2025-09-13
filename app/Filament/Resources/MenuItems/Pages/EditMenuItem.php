<?php

namespace App\Filament\Resources\MenuItems\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\MenuItems\MenuItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuItem extends EditRecord
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
