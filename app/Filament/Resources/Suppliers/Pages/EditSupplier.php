<?php

namespace App\Filament\Resources\Suppliers\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Suppliers\SupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditSupplier extends EditRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggle_status')
                ->label(fn () => $this->record->is_active ? 'Deactivate Supplier' : 'Activate Supplier')
                ->icon(fn () => $this->record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                ->color(fn () => $this->record->is_active ? 'warning' : 'success')
                ->action(function () {
                    $this->record->update(['is_active' => !$this->record->is_active]);

                    Notification::make()
                        ->title('Supplier status updated')
                        ->body('The supplier has been ' . ($this->record->is_active ? 'activated' : 'deactivated') . ' successfully.')
                        ->success()
                        ->send();

                    $this->refreshFormData(['is_active']);
                })
                ->requiresConfirmation()
                ->modalHeading(fn () => ($this->record->is_active ? 'Deactivate' : 'Activate') . ' Supplier')
                ->modalDescription(fn () => 'Are you sure you want to ' . ($this->record->is_active ? 'deactivate' : 'activate') . ' this supplier? This will affect their availability in selection lists.'),

            Action::make('duplicate')
                ->label('Duplicate Supplier')
                ->icon('heroicon-o-square-2-stack')
                ->color('gray')
                ->action(function () {
                    $newSupplier = $this->record->replicate();
                    $newSupplier->name = $this->record->name . ' (Copy)';
                    $newSupplier->is_active = false;
                    $newSupplier->save();

                    Notification::make()
                        ->title('Supplier duplicated')
                        ->body('A copy of this supplier has been created and set as inactive.')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.suppliers.edit', $newSupplier);
                })
                ->requiresConfirmation()
                ->modalHeading('Duplicate Supplier')
                ->modalDescription('This will create a copy of the current supplier with "(Copy)" added to the name and set as inactive.'),

            DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete Supplier')
                ->modalDescription('Are you sure you want to delete this supplier? This action cannot be undone.')
                ->successNotificationTitle('Supplier deleted successfully'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Supplier updated successfully';
    }
}
