<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Schema;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use App\Models\User;
use Filament\Forms\Components\Select;

class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();
    }

    public function form(Schema $schema): Schema
    {
        if (app()->environment('local')) {

            $users = User::query()
                ->whereLike('email', '%@example.com')
                ->pluck('name', 'email');

            $defaultUserEmail = User::query()
                ->whereLike('email', '%@example.com')
                ->first()?->email;

            $schema->components([
                Select::make('user')
                    ->required()
                    ->options($users)
                    ->default($defaultUserEmail),
            ]);

            return $schema;
        }

        return parent::form($schema);
    }

    public function authenticate(): ?LoginResponse
    {
        if (app()->environment('local')) {

            $authenticateAs = $this->form->getState()['user'] ?? null;

            auth()->login(
                User::query()
                    ->whereLike('email', '%@example.com')
                    ->where('email', $authenticateAs)->first()
            );

            return app(LoginResponse::class);
        }

        return parent::authenticate();
    }

}
