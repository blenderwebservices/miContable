<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaxpayerResource\Pages;
use App\Filament\Resources\TaxpayerResource\RelationManagers;
use App\Models\Taxpayer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaxpayerResource extends Resource
{
    protected static ?string $model = Taxpayer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rfc')
                    ->label('RFC')
                    ->required()
                    ->maxLength(13)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tax_regime')
                    ->label('Régimen Fiscal')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('fiel_path')
                    ->label('FIEL (Archivo)')
                    ->directory('fiel'),
                Forms\Components\Textarea::make('ciec_encrypted')
                    ->label('CIEC (Encriptada)')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rfc')
                    ->label('RFC')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_regime')
                    ->label('Régimen Fiscal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListTaxpayers::route('/'),
            'create' => Pages\CreateTaxpayer::route('/create'),
            'edit' => Pages\EditTaxpayer::route('/{record}/edit'),
        ];
    }
}
