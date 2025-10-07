<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Resources\MenuItems\Actions\IsAvailable;
use Filament\Actions\CreateAction;
use App\Filament\Resources\MenuItems\MenuItemResource;
use Filament\Resources\Pages\ListRecords;

class ListMenuItems extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            IsAvailable::action(),
            CreateAction::make(),
        ];
    }
}
