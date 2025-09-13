<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Tables;
use App\Models\MenuItem;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MenuItems\MenuItemResource;
use Filament\Widgets\TableWidget as BaseWidget;

class UnavailableItems extends BaseWidget
{
    protected string $view = 'filament.widgets.unavailable-items';

    protected int $queryCount;

    protected int $perPage = 5;

    public function boot()
    {
        $this->queryCount = $this->getBaseQuery()->count();
    }

    protected function getBaseQuery(): Builder
    {
        return MenuItem::query()
            ->where('is_available', false);
    }

    protected function makeTable(): Table
    {
        return $this->makeBaseTable()
            ->heading(null);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption($this->perPage)
            ->paginated(fn() => $this->queryCount > $this->perPage)
            ->query($this->getBaseQuery())
            ->columns([
                TextColumn::make('name'),
            ])
            ->recordUrl($this->getRecordUrl(...))
            ->recordActions([
                Action::make('view')
                    ->url($this->getRecordUrl(...))
                    ->color('gray')
                    ->icon('heroicon-m-eye')
                    ->label('View'),
            ]);
    }

    private function getRecordUrl(MenuItem $record)
    {
        return MenuItemResource::getUrl('edit', [
            'record' => $record,
        ]);
    }

    protected function getViewData(): array
    {
        return [
            'queryCount' => $this->queryCount,
            'message' => $this->getMessage(),
            'table' => $this->getTable(),
        ];
    }

    protected function getMessage(): HtmlString
    {
        if ($this->queryCount === 0) {
            return new HtmlString(<<<HTML
            <div class="text-gray-500">
                All menu items are available.
            </div>
            HTML
            );
        }

        return new HtmlString(<<<HTML
        <div class="text-base tracking-[0.07rem] uppercase font-normal text-red-700 dark:text-red-400">
            Unavailable menu items
        </div>
        HTML
        );
    }
}
