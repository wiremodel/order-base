<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();

        if (app()->environment('local')) {
            $this->form->fill([
                'credential' => 'test@example.com',
//                'credential' => '123456789',
//                'credential' => 'test-user',
                'password' => 'password',
                'remember' => true,
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('credential')
                    ->label('Credential')
                    ->required()
                    ->autocomplete()
                    ->autofocus()
                    ->extraInputAttributes(['tabindex' => 1]),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        $credential = $data['credential'];

        // Try to find user by email, username, or ss_number
        $user = User::where('email', $credential)
            ->orWhere('username', $credential)
            ->orWhere('ss_number', $credential)
            ->first();

        if (!$user) {
            return [
                'email' => $credential, // Fallback to email for validation error
                'password' => $data['password'],
            ];
        }

        // Return the actual field that matched
        $field = match(true) {
            $user->email === $credential => 'email',
            $user->username === $credential => 'username',
            $user->ss_number === $credential => 'ss_number',
            default => 'email',
        };

        return [
            $field => $credential,
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.credential' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        return parent::authenticate();
    }

}
