<?php

namespace App\Filament\Resources\SupplierResource\RelationManagers;

use App\Enums\PurchaseOrderStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseOrders';

    protected static ?string $recordTitleAttribute = 'order_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->disabled()
                    ->label('Order Number'),
                Forms\Components\Select::make('status')
                    ->options(PurchaseOrderStatus::class)
                    ->required(),
                Forms\Components\DatePicker::make('order_date')
                    ->required()
                    ->default(now()),
                Forms\Components\DatePicker::make('expected_delivery_date')
                    ->label('Expected Delivery'),
                Forms\Components\DatePicker::make('actual_delivery_date')
                    ->label('Actual Delivery'),
                Forms\Components\TextInput::make('total_amount')
                    ->disabled()
                    ->label('Total Amount')
                    ->formatStateUsing(fn ($state) => $state ? '$' . number_format($state / 100, 2) : '$0.00'),
                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_number')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors(PurchaseOrderStatus::getColorsAsKeys())
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_delivery_date')
                    ->label('Expected Delivery')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('actual_delivery_date')
                    ->label('Actual Delivery')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('USD', divideBy: 100)
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('USD', divideBy: 100)
                            ->label('Total Value'),
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(PurchaseOrderStatus::class)
                    ->multiple(),
                Tables\Filters\Filter::make('delivery_overdue')
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.purchase-orders.edit', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_as_received')
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
