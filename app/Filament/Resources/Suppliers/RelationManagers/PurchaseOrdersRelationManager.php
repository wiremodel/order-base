<?php

namespace App\Filament\Resources\Suppliers\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use App\Enums\PurchaseOrderStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseOrders';

    protected static ?string $recordTitleAttribute = 'order_number';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->disabled()
                    ->label('Order Number'),
                Select::make('status')
                    ->options(PurchaseOrderStatus::class)
                    ->required(),
                DatePicker::make('order_date')
                    ->required()
                    ->default(now()),
                DatePicker::make('expected_delivery_date')
                    ->label('Expected Delivery'),
                DatePicker::make('actual_delivery_date')
                    ->label('Actual Delivery'),
                TextInput::make('total_amount')
                    ->disabled()
                    ->label('Total Amount')
                    ->formatStateUsing(fn ($state) => $state ? '$' . number_format($state / 100, 2) : '$0.00'),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_number')
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('expected_delivery_date')
                    ->label('Expected Delivery')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('actual_delivery_date')
                    ->label('Actual Delivery')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('USD', divideBy: 100)
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->money('USD', divideBy: 100)
                            ->label('Total Value'),
                    ]),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PurchaseOrderStatus::class)
                    ->multiple(),
                Filter::make('delivery_overdue')
                    ->label('Overdue Deliveries')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('status', '!=', PurchaseOrderStatus::Received)
                        ->where('status', '!=', PurchaseOrderStatus::Cancelled)
                        ->where('expected_delivery_date', '<', now())
                    )
                    ->indicator('Overdue'),
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->after(function(PurchaseOrdersRelationManager $livewire) {
                        $livewire->dispatch('refresh-page');
                    }),
                Action::make('view')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.purchase-orders.edit', $record))
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('mark_as_received')
                        ->label('Mark as Received')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function (array $records) {
                            collect($records)->each(function ($record) {
                                $record->update([
                                    'status' => 'received',
                                    'actual_delivery_date' => now(),
                                ]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Mark Orders as Received')
                        ->modalDescription('Are you sure you want to mark the selected orders as received?'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No Purchase Orders')
            ->emptyStateDescription('This supplier has no purchase orders yet.')
            ->emptyStateIcon('heroicon-o-shopping-cart');
    }
}
