<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('User Information')
                    ->description('Basic user details and contact information')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Full Name')
                            ->icon('heroicon-m-user')
                            ->weight('medium')
                            ->copyable()
                            ->size('lg'),
                        TextEntry::make('email')
                            ->label('Email Address')
                            ->icon('heroicon-m-envelope')
                            ->copyable()
                            ->copyMessage('Email address copied!')
                            ->color('gray'),
                        TextEntry::make('roles')
                            ->label('Assigned Roles')
                            ->formatStateUsing(fn ($state): string => Role::tryFrom($state->role)->getLabel())
                            ->badge()
                            // We have to calculate the color because this comes from a relationship, not an Enum cast
                            ->color(fn ($state): string => Role::tryFrom($state->role)?->getColor())
                            ->icon('heroicon-m-shield-check'),
                        IconEntry::make('email_verified_at')
                            ->label('Email Verified')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ]),

                Section::make('Account Timestamps')
                    ->description('Account creation and modification history')
                    ->icon('heroicon-o-clock')
                    ->columns()
                    ->schema([
                        TextEntry::make('email_verified_at')
                            ->label('Email Verified At')
                            ->placeholder('Not verified')
                            ->icon('heroicon-m-envelope-open')
                            ->color(fn ($state) => $state ? 'success' : 'warning'),
                        TextEntry::make('updated_at')
                            ->columnStart(1)
                            ->label('Last Updated')
                            ->icon('heroicon-m-pencil-square')
                            ->since()
                            ->color('gray')
                            ->placeholder('Never updated'),
                        TextEntry::make('created_at')
                            ->label('Account Created')
                            ->icon('heroicon-m-plus-circle')
                            ->since()
                            ->placeholder('N/A'),
                    ]),
            ]);
    }
}
