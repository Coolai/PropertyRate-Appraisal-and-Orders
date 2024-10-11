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
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\Select;

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
        $order = $this->getRecord();

        return $table
            ->query(Appraiser::query())
            ->columns([
                TextColumn::make('appraiser_user_id')->label('Appraiser User ID'),
                TextColumn::make('rank'),
                TextColumn::make('zip_code')->searchable(),
                TextColumn::make('county')->searchable(),
            ])
            ->filters([
                Filter::make('custom')
                    ->form([
                        Select::make('appraisers')
                            ->options([
                                'great' => 'Great',
                                'good' => 'Good',
                                'okay' => 'Okay',
                            ])
                            ->default('great')
                            ->selectablePlaceholder(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['appraisers'],
                                fn (Builder $query, $selected): Builder => $query->whereIn('id', $this->getRecord()->listAppraiserMatch($selected)),
                            );
                    }),
                Filter::make('highly_rated')
                    ->label('4 Stars')
                    ->query(fn (Builder $query): Builder => $query->where('rank', 4)),
            ], layout: FiltersLayout::AboveContent)
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
    
    function distance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            return $miles;
        }
    }
}
