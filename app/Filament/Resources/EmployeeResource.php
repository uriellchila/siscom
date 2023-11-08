<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use App\Models\Employee;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Filament\Forms\Get;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Administracion del Sistema';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Empleados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('country_id')
                        ->options(Country::query()->pluck('name', 'id'))
                        ->live()->required(),
                        
                    Select::make('state_id')
                        ->options(fn (Get $get): Collection => State::query()
                            ->where('country_id', $get('country_id'))
                            ->pluck('name', 'id'))
                            ->live()->required(),
                    Select::make('city_id')
                        ->options(fn (Get $get): Collection => City::query()
                            ->where('state_id', $get('state_id'))
                            ->pluck('name', 'id'))->required(),
                    Select::make('department_id')
                        ->relationship(name: 'department', titleAttribute: 'name')->required(),
                    TextInput::make('first_name')->required(),
                    TextInput::make('last_name')->required(),
                    TextInput::make('address')->required(),
                    TextInput::make('phone_number')->required(),
                    TextInput::make('zip_code')->required(),
                    DatePicker::make('birth_date')->required(),
                    DatePicker::make('date_hired')->required()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('first_name')->sortable()->searchable()->toggleable(),
                TextColumn::make('last_name')->sortable()->searchable()->toggleable(),
                TextColumn::make('country.name')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('state.name')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city.name')->sortable()->toggleable(),
                TextColumn::make('department.name')->sortable()->toggleable(),
                TextColumn::make('address')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone_number')->sortable()->toggleable(),
                TextColumn::make('zip_code')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('birth_date')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_hired')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('department')->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }    
}
