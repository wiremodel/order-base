<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\Role;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Ladder\Models\UserRole;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var User $this->record */
        $userRoles = UserRole::query()
            ->where('user_id', $this->record->id)
            ->pluck('role')
            ->toArray();

        $data['roles'] = $userRoles;

        return $data;
    }

    public function handleRecordUpdate(Model $record, array $data): Model
    {
        $rolesToPersist = fluent($data)
            ->collect('roles');

        // Delete Roles that are not in the form data
        /** @var User $record */
        UserRole::query()
            ->where('user_id', $record->id)
            ->whereNotIn('role', $rolesToPersist->toArray())
            ->delete();

        // Add new roles, ignoring the ones that already exist
        $rolesToPersist
            ->each(fn(Role $role) => $record->roles()->firstOrCreate([
                'role' => $role,
            ]));

        return $record;
    }
}
