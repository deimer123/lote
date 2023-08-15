<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

use App\Filament\Resources\VentaResource\Pages;
use App\Filament\Resources\VentaResource\RelationManagers;
use App\Models\Venta;
use App\Models\User;
use App\Models\Lote;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Closure;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                ->reactive()
                ->label('Datos Del Cliente')
                ->searchable()
                ->getSearchResultsUsing(function($search){
                       $clientes=[];
                         $results=User::where('cedula', 'like', "%{$search}%")                        
                         ->whereHas('roles', function ($query) {
                            $query->where('name', 'cliente');
                        })
                         ->limit(50)
                         ->get();
                                    foreach($results as $result){
                                        $clientes[$result->id]=' Cedula => '.$result->cedula.' Nombre=> '.$result->name;
                                    }
                        return $clientes;
                })
                ->getOptionLabelUsing(fn ($value): ?string => ' Cedula => '.User::find($value)?->cedula.' Nombre=> '.User::find($value)?->name)
                ->placeholder('Digite La Cedula Del Cliente'),
                       
    
               
    Select::make('lote_id')
                ->reactive()
                ->label('Datos Del lote')
                ->searchable()
                ->getSearchResultsUsing(function($search){
                        $lotes=[];
                        $results=Lote::where('numero_lote', 'like', "%{$search}%")->limit(50)->get();
                        foreach($results as $result){
                            $lotes[$result->id]=
                            ' Numero => '.$result->numero_lote.
                            ' Dirección=> '.$result->direccion_lote.
                            ' Valor=> '.$result->valor_lote;
                        }
                        return $lotes;
                    })
                ->getOptionLabelUsing(fn ($value): ?string => 
                ' Numero => '.Lote::find($value)?->numero_lote.
                ' Dirección=> '.Lote::find($value)?->direccion_lote.
                ' Valor=> '.Lote::find($value)?->valor_lote)
                ->unique(ignoreRecord: true)
                ->placeholder('Digite El Numero Del Lote'),  

    

    FileUpload::make('certificate_image')
                ->image()
                ->label('Ubicacion Lote')
                ->required()
                ->enableDownload()
                ->enableOpen()
                ->directory(directory:'lote-images')
                ->storeFileNamesIn(statePath:'original_filename'),
    TextInput::make('cuotas') 
                ->label('Numero De Cuotas')
                ->required(),   
    TextInput::make('valor_cuota') 
                ->label('Valor De Cuota')
                ->required(),
    TextInput::make('valor_pagado') 
                ->label('Valor Pagado')
                ->required(), 
    TextInput::make('valor_deuda') 
                ->label('Valor Deuda')
                ->required(),  
                
                
    Select::make('agente_id')
                ->reactive()
                ->required()
                ->label('Datos Del Agente')
                ->searchable()
                ->getSearchResultsUsing(function($search){
                       $clientes=[];
                         $results=User::where('name', 'like', "%{$search}%")                        
                         ->whereHas('roles', function ($query) {
                            $query->where('name', 'Agente');
                        })
                         ->limit(50)
                         ->get();
                                    foreach($results as $result){
                                        $clientes[$result->id]=' Telefono => '.$result->cedula.' Nombre=> '.$result->name;
                                    }
                        return $clientes;
                })
                ->getOptionLabelUsing(fn ($value): ?string => ' Telefono => '.User::find($value)?->telefono.' Nombre=> '.User::find($value)?->name)
                ->placeholder('Digite El Nombre Del Agente'),            
    

                       
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('lote.numero_lote'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
             
                    return $data;
                }),
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
            'index' => Pages\ListVentas::route('/'),
            'create' => Pages\CreateVenta::route('/create'),
            'view' => Pages\ViewVenta::route('/{record}'),
            'edit' => Pages\EditVenta::route('/{record}/edit'),
        ];
    }    
    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('super_admin')){

            return parent::getEloquentQuery();

        }
         if (auth()->user()->hasRole('Auxiliar')){

            return parent::getEloquentQuery();

        }
        if (auth()->user()->hasRole('Cliente')){

            return parent::getEloquentQuery()->where('User_id',auth()->User()->id);

        }
        return parent::getEloquentQuery()->where('agente_id',auth()->User()->id);
        
    }      
}
