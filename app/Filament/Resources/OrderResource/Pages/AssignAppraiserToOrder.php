<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Appraiser;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Fieldset;

class AssignAppraiserToOrder extends Page implements HasInfolists, HasTable, HasForms
{
    use InteractsWithRecord;
    use InteractsWithInfolists;
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.resources.order-resource.pages.assign-appraiser-to-order';

    protected static ?string $title = 'Assign Appraiser to Order';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('assign')->url('/admin/orders'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Appraiser::query())
            ->columns([
                TextColumn::make('appraiser_user_id'),
                TextColumn::make('rank'),
                TextColumn::make('zip_code'),
                TextColumn::make('county'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function orderInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->getRecord())
            ->schema([
                Fieldset::make('Order Details')
                    ->schema([
                        TextEntry::make('order_id'),
                        TextEntry::make('product'),
                        TextEntry::make('city'),
                        TextEntry::make('state_code'),
                        TextEntry::make('zip_code'),
                        TextEntry::make('county'),
                    ])
                    ->columns(4)
            ]);
    }
}
