<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;
use Filament\Forms\Components\Card;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Clientes Ebenezer';
    protected static ?string $navigationGroup = '<-CLIENTES-> ';


    public static function form(Form $form): Form
    {
        return $form

        
            ->schema([

                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Digite El Nombre Completo Del Cliente'),
                        Forms\Components\TextInput::make('cedula')
                            ->label('Cedula')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Digite El Numero De Cedula'),
                ])->columns(2),

                Card::make()
                     ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Digite El Correo'),
                     ]),

                Card::make()
                     ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->maxLength(50)
                            ->minLength(8)
                            ->same('passwordConfirmation')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->hiddenOn('edit')
                            ->placeholder('Digite Un Password -Minimo 8 Digitos-'),
                        Forms\Components\TextInput::make('passwordConfirmation') 
                            ->label('Confirmar Password')
                            ->password()
                            ->required()
                            ->maxLength(50)
                            ->minLength(8)
                            ->dehydrated(false)
                            ->hiddenOn('edit')
                            ->placeholder('Confirme Su Password'),
                     ])
                     ->columns(2),
                                                                       
                Card::make()
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->columnSpan('full')
                            ->relationship('Roles', 'name', function (Builder $query) {
                                if (! auth()->User()->hasRole('super_admin'))  {
                                
                                
                                    return $query->where('name', '<>', 'super_admin')->where('name', '<>', 'auxiliar')->where('name', '<>', 'agente');
                                    
                                }
            
                                return $query->where('name', '<>', 'super_admin');                 
            
            
            
                            })
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                return Str::of($record->name)->headline();
                            }),
                        ]),

                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('telefono')
                            ->required()
                            ->placeholder('Digite Un Numero De Celular'),
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->label('Direccion')
                            ->placeholder('Digite Una Direccion'),
        
                        ])
                        ->columns(2)
                
                
                
                

                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->label('Nombre'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('Roles.name')->label('Tipo Cliente'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            //'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    } 
    
    public static function getEloquentQuery(): Builder
    {

        if (auth()->user()->hasRole('super_admin')){

            return parent::getEloquentQuery()->where('id','<>',auth()->user()->id);
            

        }

        return parent::getEloquentQuery()->whereHas('roles', function ($query) {
            $query = $query->where('name', 'Cliente');
            return $query;
        }); 
    }
 
}
