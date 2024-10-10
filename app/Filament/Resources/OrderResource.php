<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')->required()->maxLength(255),
                Forms\Components\TextInput::make('product')->required()->maxLength(255),
                Forms\Components\TextInput::make('city')->required()->maxLength(255),
                Forms\Components\TextInput::make('state_code')->required()->maxLength(2),
                Forms\Components\TextInput::make('zip_code')->required()->maxLength(255),
                Forms\Components\TextInput::make('county')->required()->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->searchable(),
                Tables\Columns\TextColumn::make('product'),
                Tables\Columns\TextColumn::make('city')->searchable(),
                Tables\Columns\TextColumn::make('state_code'),
                Tables\Columns\TextColumn::make('zip_code')->searchable(),
                Tables\Columns\TextColumn::make('county'),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('assign')
                    ->url(fn (Order $record): string => route('filament.admin.resources.orders.assign', $record))
                    ->icon('heroicon-o-user'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'assign' => Pages\AssignAppraiserToOrder::route('/{record}/assign-appraiser-to-order'),
        ];
    }
}
