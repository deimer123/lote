<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoteResource\Pages;
use App\Filament\Resources\LoteResource\RelationManagers;
use App\Models\Lote;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoteResource extends Resource
{
    protected static ?string $model = Lote::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero_lote')
                                                                        ->label('Numero Lote')
                                                                        ->required()
                                                                        ->maxLength(50)
                                                                        ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('valor_lote')
                                                                        ->label('Valor Lote')
                                                                        ->required()
                                                                        ->maxLength(50),
                                                                        
                Forms\Components\TextInput::make('direccion_lote')
                                                                        ->label('Direccion Lote')
                                                                        ->required()
                                                                        ->maxLength(50),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_lote'),
                Tables\Columns\TextColumn::make('valor_lote'),
                Tables\Columns\TextColumn::make('direccion_lote'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLotes::route('/'),
            'create' => Pages\CreateLote::route('/create'),
            'edit' => Pages\EditLote::route('/{record}/edit'),
        ];
    }    
}
