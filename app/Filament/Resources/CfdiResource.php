<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CfdiResource\Pages;
use App\Filament\Resources\CfdiResource\RelationManagers;
use App\Models\Cfdi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CfdiResource extends Resource
{
    protected static ?string $model = Cfdi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->maxLength(36)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('issuer_rfc')
                    ->label('RFC Emisor')
                    ->required()
                    ->maxLength(13),
                Forms\Components\TextInput::make('receiver_rfc')
                    ->label('RFC Receptor')
                    ->required()
                    ->maxLength(13),
                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('tax_amount')
                    ->label('Impuestos')
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Forms\Components\FileUpload::make('xml_path')
                    ->label('Archivo XML')
                    ->required()
                    ->directory('cfdi/xml')
                    ->acceptedFileTypes(['text/xml', 'application/xml']),
                Forms\Components\FileUpload::make('pdf_path')
                    ->label('Archivo PDF')
                    ->directory('cfdi/pdf')
                    ->acceptedFileTypes(['application/pdf']),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->required()
                    ->options([
                        'Ingreso' => 'Ingreso',
                        'Egreso' => 'Egreso',
                        'Traslado' => 'Traslado',
                        'Nomina' => 'Nómina',
                        'Pago' => 'Pago',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('issuer_rfc')
                    ->label('RFC Emisor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver_rfc')
                    ->label('RFC Receptor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('MXN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ingreso' => 'success',
                        'Egreso' => 'danger',
                        'Pago' => 'info',
                        default => 'gray',
                    }),
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
            'index' => Pages\ListCfdis::route('/'),
            'create' => Pages\CreateCfdi::route('/create'),
            'edit' => Pages\EditCfdi::route('/{record}/edit'),
        ];
    }
}
