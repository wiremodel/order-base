<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\MenuItem;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MenuItemResource;
use Filament\Widgets\TableWidget as BaseWidget;

class UnavailableItems extends BaseWidget
{
    protected static string $view = 'filament.widgets.unavailable-items';

    protected int $queryCount;

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
            ->defaultPaginationPageOption(5)
            ->paginated(fn() => $this->queryCount > 5)
            ->query($this->getBaseQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('visit')
                    ->alignEnd()
                    ->state($this->getUrlHTMLFor(...))
                    ->label(''),
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

    protected function getUrlHTMLFor(MenuItem $record): HtmlString
    {
        $url = MenuItemResource::getUrl('edit', [
            'record' => $record,
        ]);

        $label = 'Edit';

        return new HtmlString(<<<HTML
            <a href="{$url}" class="text-blue-600 underline">
                {$label}
            </a>
        HTML);
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
        HTML);
    }
}
