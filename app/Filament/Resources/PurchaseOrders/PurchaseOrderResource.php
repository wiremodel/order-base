<?php

namespace App\Filament\Resources\PurchaseOrders;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Actions;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\PurchaseOrders\Pages\ListPurchaseOrders;
use App\Filament\Resources\PurchaseOrders\Pages\CreatePurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Pages\EditPurchaseOrder;
use App\Enums\PurchaseOrderStatus;
use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Filament\Resources\PurchaseOrderResource\RelationManagers;
use App\Filament\Resources\PurchaseOrders\Widgets\HighValuePurchaseOrder;
use App\Models\PurchaseOrder;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string | \UnitEnum | null $navigationGroup = 'Purchasing';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Order Information')
                            ->description('Basic purchase order details')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('order_number')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->placeholder('Auto-generated')
                                            ->prefixIcon('heroicon-m-hashtag')
                                            ->label('Order Number'),
                                        Select::make('supplier_id')
                                            ->relationship('supplier', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->prefixIcon('heroicon-m-building-storefront')
                                            ->label('Supplier')
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('email')
                                                    ->email()
                                                    ->maxLength(255),
                                                TextInput::make('phone')
                                                    ->tel()
                                                    ->maxLength(255),
                                            ]),
                                        Select::make('status')
                                            ->options(PurchaseOrderStatus::class)
                                            ->default('draft')
                                            ->required()
                                            ->prefixIcon('heroicon-m-clipboard-document-list')
                                            ->native(false),
                                    ]),
                                Grid::make(3)
                                    ->schema([
                                        DatePicker::make('order_date')
                                            ->required()
                                            ->default(now())
                                            ->prefixIcon('heroicon-m-calendar')
                                            ->label('Order Date'),
                                        DatePicker::make('expected_delivery_date')
                                            ->prefixIcon('heroicon-m-truck')
                                            ->label('Expected Delivery'),
                                        DatePicker::make('actual_delivery_date')
                                            ->prefixIcon('heroicon-m-check-circle')
                                            ->label('Actual Delivery'),
                                    ]),
                                Textarea::make('notes')
                                    ->maxLength(65535)
                                    ->rows(3)
                                    ->placeholder('Additional notes or special instructions...')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->persistCollapsed(),

                        Section::make('Order Items')
                            ->description('Add items to this purchase order')
                            ->icon('heroicon-m-shopping-bag')
                            ->headerActions([
                                Action::make('addBulkItems')
                                    ->button()
                                    ->color('gray')
                                    ->icon('heroicon-m-plus-circle')
                                    ->label('Quick Add Items')
                            ])
                            ->schema([
                                Repeater::make('items')
                                    ->relationship()
                                    ->schema([
                                        Grid::make(12)
                                            ->schema([
                                                TextInput::make('quantity')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(1)
                                                    ->default(1)
                                                    ->step(1)
                                                    ->prefixIcon('heroicon-m-calculator')
                                                    ->columnSpan(3)
                                                    ->label('Qty')
                                                    ->live(debounce: 500)
                                                    ->afterStateUpdated(function ($state, $set, $get) {
                                                        $unitCost = floatval($get('unit_cost') ?? 0);
                                                        $quantity = intval($state ?? 0);
                                                        $set('total_cost', number_format($quantity * $unitCost, 2, '.', ''));
                                                    }),

                                                Select::make('menu_item_id')
                                                    ->relationship('menuItem', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->prefixIcon('heroicon-m-cube')
                                                    ->placeholder('Select item...')
                                                    ->columnSpan(9)
                                                    ->label('Item'),

                                                TextInput::make('unit_cost')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('$')
                                                    ->step(0.01)
                                                    ->minValue(0)
                                                    ->prefixIcon('heroicon-m-currency-dollar')
                                                    ->columnSpan(6)
                                                    ->label('Unit Cost')
                                                    ->live(debounce: 500)
                                                    ->afterStateUpdated(function ($state, $set, $get) {
                                                        $quantity = intval($get('quantity') ?? 0);
                                                        $unitCost = floatval($state ?? 0);
                                                        $set('total_cost', number_format($quantity * $unitCost, 2, '.', ''));
                                                    }),
                                                TextInput::make('total_cost')
                                                    ->numeric()
                                                    ->disabled()
                                                    ->prefix('$')
                                                    ->dehydrated()
                                                    ->prefixIcon('heroicon-m-calculator')
                                                    ->columnSpan(6)
                                                    ->label('Total')
                                                    ->extraAttributes(['class' => 'font-semibold']),
                                                Actions::make([

                                                ])
                                                ->columnSpan(2),
                                            ]),
                                        Textarea::make('notes')
                                            ->placeholder('Item notes...')
                                            ->rows(2)
                                            ->columnSpanFull()
                                            ->hiddenLabel(),
                                    ])
                                    ->itemLabel(fn (array $state): ?string =>
                                        MenuItem::find($state['menu_item_id'])?->name ?? 'New Item'
                                    )
                                    ->addActionLabel('Add Item')
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->cloneable()
                                    ->defaultItems(1)
                                    ->deleteAction(
                                        fn ($action) => $action
                                            ->requiresConfirmation()
                                            ->modalHeading('Remove Item')
                                            ->modalDescription('Are you sure you want to remove this item from the order?')
                                    )
                                    ->minItems(1)
                                    ->extraAttributes(['class' => 'repeater-compact']),
                            ]),

                        Section::make('Order Summary')
                            ->schema([
                                Placeholder::make('order_total')
                                    ->label('Order Total')
                                    ->content(function ($get) {
                                        $items = $get('items') ?? [];
                                        $total = collect($items)->sum(function ($item) {
                                            return floatval($item['total_cost'] ?? 0);
                                        });
                                        return '$' . number_format($total, 2);
                                    })
                                    ->extraAttributes(['class' => 'text-2xl font-bold text-primary-600']),
                            ])
                            ->compact(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Quick Actions')
                            ->schema([
                                Actions::make([
                                    Action::make('duplicate')
                                        ->icon('heroicon-m-document-duplicate')
                                        ->color('gray')
                                        ->outlined()
                                        ->label('Duplicate Order')
                                        ->action(fn() => Notification::make()
                                            ->color('warning')
                                            ->body('To be implemented')->send()
                                        ),
                                    Action::make('export')
                                        ->icon('heroicon-m-arrow-down-tray')
                                        ->color('gray')
                                        ->outlined()
                                        ->label('Export PDF')
                                        ->action(fn() => Notification::make()
                                            ->color('warning')
                                            ->body('To be implemented')->send()
                                        ),
                                ])
                                ->fullWidth(),
                            ]),

                        Section::make('Order History')
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Created')
                                    ->content(fn ($record) => $record?->created_at?->format('M j, Y g:i A') ?? 'New order'),
                                Placeholder::make('updated_at')
                                    ->label('Last Updated')
                                    ->content(fn ($record) => $record?->updated_at?->format('M j, Y g:i A') ?? 'Not saved yet'),
                            ]),
                    ])
                    ->hidden(fn ($record) => !$record)
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(['lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supplier.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('expected_delivery_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->money('USD', 100)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(PurchaseOrderStatus::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            HighValuePurchaseOrder::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseOrders::route('/'),
            'create' => CreatePurchaseOrder::route('/create'),
            'edit' => EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
