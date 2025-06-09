<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BasePage;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BasePage
{
    public function mount(): void
    {
        parent::mount();
    }

    public function form(Form $form): Form
    {
        if (app()->environment('local')) {

            $users = User::query()
                ->whereLike('email', '%@example.com')
                ->pluck('name', 'email');

            $defaultUserEmail = User::query()
                ->whereLike('email', '%@example.com')
                ->first()?->email;

            $form->schema([
                Select::make('user')
                    ->required()
                    ->options($users)
                    ->default($defaultUserEmail),
            ]);

            return $form;
        }

        return parent::form($form);
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
