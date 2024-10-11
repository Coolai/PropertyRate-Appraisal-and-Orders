<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Appraiser;
use App\Models\Order;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
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

    public function table(Table $table): Table
    {
        $order = $this->getRecord();

        return $table
            ->query(Appraiser::query())
            ->columns([
                TextColumn::make('appraiser_user_id')->label('Appraiser User ID'),
                TextColumn::make('rank')->icon('heroicon-o-star')->iconColor('primary')->sortable(),
                TextColumn::make('total_files_uploaded')->label('Completed Orders')->sortable(),
                TextColumn::make('zip_code')->searchable()->icon('heroicon-o-map-pin'),
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
                    ->label('4 Stars Only')
                    ->query(fn (Builder $query): Builder => $query->where('rank', 4)),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Action::make('appraiser')
                    ->label('Assign')
                    ->action(function (Appraiser $record) {
                        $this->getRecord()->appraiser_id = $record->id;
                        $this->getRecord()->save();
                    }),
            ])
            ->bulkActions([
                // ...
            ])
            ->defaultSort(fn ($query) => $query->orderBy('rank', 'desc')->orderBy('total_files_uploaded', 'desc'));
    }

    public function orderInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->getRecord())
            ->schema([
                Fieldset::make('Order Details')
                    ->schema([
                        TextEntry::make('order_id'),
                        TextEntry::make('product')->icon('heroicon-o-home-modern'),
                        TextEntry::make('city'),
                        TextEntry::make('state_code'),
                        TextEntry::make('zip_code')->icon('heroicon-o-map-pin'),
                        TextEntry::make('county'),
                    ])
                    ->columns(4)
            ]);
    }

    public function appraiserInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->getRecord())
            ->schema([
                Fieldset::make('Assigned Appraiser')
                    ->schema([
                        TextEntry::make('appraiser.appraiser_user_id')->label('User ID')->default('-not set-'),
                        TextEntry::make('appraiser.rank')->label('Rank')->icon('heroicon-o-star')->default('-not set-'),
                        TextEntry::make('appraiser.total_files_uploaded')->label('Completed Orders')->icon('heroicon-o-wrench-screwdriver')->default('-not set-'),
                        TextEntry::make('appraiser.zip_code')->label('Zip Code')->default('-not set-'),
                        TextEntry::make('appraiser.county')->label('County')->default('-not set-'),
                    ])
                    ->columns(3)
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
