<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->readOnly(),
                TextInput::make('email')
                    ->label('Email address')
                    ->readOnly(),
                Select::make('roles')
                    ->hiddenOn(Operation::Create)
                    ->multiple()
                    ->options(Role::class),
            ]);
    }
}
