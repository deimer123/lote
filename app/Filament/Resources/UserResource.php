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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                                                        ->label('Nombre')
                                                                        ->required()
                                                                        ->maxLength(50),
                Forms\Components\TextInput::make('cedula')
                                                                        ->label('Cedula')
                                                                        ->required()
                                                                        ->maxLength(50)
                                                                        ->unique(ignoreRecord: true),
                                                                       
                Forms\Components\TextInput::make('email')
                                                                        ->label('Email')
                                                                        ->email()
                                                                        ->required()
                                                                        ->maxLength(50)
                                                                        ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('password')
                                                                        ->label('Password')
                                                                        ->password()
                                                                        ->required()
                                                                        ->maxLength(50)
                                                                        ->minLength(8)
                                                                        ->same('passwordConfirmation')
                                                                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                                                        ->hiddenOn('edit'),
                Forms\Components\TextInput::make('passwordConfirmation') 
                                                                        ->label('Confirmar Password')
                                                                        ->password()
                                                                        ->required()
                                                                        ->maxLength(50)
                                                                        ->minLength(8)
                                                                        ->dehydrated(false)
                                                                        ->hiddenOn('edit'),
                
                Forms\Components\CheckboxList::make('roles')
                ->columnSpan('full')
                ->relationship('Roles', 'name', function (Builder $query) {
                    if (! auth()->User()->hasRole('super_admin'))  {
                       
                       
                        return $query->where('name', '<>', 'super_admin')->where('name', '<>', 'auxiliar');
                        
                    }

                    return $query->where('name', '<>', 'super_admin');                 



                })
                ->getOptionLabelFromRecordUsing(function ($record) {
                    return Str::of($record->name)->headline();
                }),
                
                

                Forms\Components\TextInput::make('telefono')->required(),
                Forms\Components\TextInput::make('address')->required()
                                                            ->label('Direccion'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('Roles.name'),
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
