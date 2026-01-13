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
                Forms\Components\FileUpload::make('xml_path')
                    ->label('Archivo XML')
                    ->required()
                    ->directory('cfdi/xml')
                    ->acceptedFileTypes(['text/xml', 'application/xml'])
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if (!$state) {
                            return;
                        }

                        try {
                            $parser = new \App\Services\CfdiParserService();
                            $xmlPath = storage_path('app/public/' . $state);
                            
                            if (!file_exists($xmlPath)) {
                                return;
                            }

                            $data = $parser->parseXml($xmlPath);
                            
                            // Auto-fill form fields
                            $set('uuid', $data['uuid']);
                            $set('emission_date', $data['emission_date']);
                            $set('issuer_rfc', $data['issuer_rfc']);
                            $set('receiver_rfc', $data['receiver_rfc']);
                            $set('total', $data['total']);
                            $set('tax_amount', $data['tax_amount']);
                            $set('type', $data['type']);
                            $set('payment_method', $data['payment_method']);
                            $set('payment_form', $data['payment_form']);
                            $set('currency', $data['currency']);
                        } catch (\Exception $e) {
                            // Silent fail - user can still fill manually
                        }
                    }),
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->maxLength(36)
                    ->unique(ignoreRecord: true)
                    ->readOnly(),
                Forms\Components\DateTimePicker::make('emission_date')
                    ->label('Fecha de Emisión')
                    ->readOnly(),
                Forms\Components\TextInput::make('issuer_rfc')
                    ->label('RFC Emisor')
                    ->required()
                    ->maxLength(13)
                    ->readOnly(),
                Forms\Components\TextInput::make('receiver_rfc')
                    ->label('RFC Receptor')
                    ->required()
                    ->maxLength(13)
                    ->readOnly(),
                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->readOnly(),
                Forms\Components\TextInput::make('tax_amount')
                    ->label('Impuestos')
                    ->numeric()
                    ->default(0)
                    ->prefix('$')
                    ->readOnly(),
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
                    ])
                    ->readOnly(),
                Forms\Components\TextInput::make('payment_method')
                    ->label('Método de Pago')
                    ->maxLength(255)
                    ->readOnly(),
                Forms\Components\TextInput::make('payment_form')
                    ->label('Forma de Pago')
                    ->maxLength(255)
                    ->readOnly(),
                Forms\Components\TextInput::make('currency')
                    ->label('Moneda')
                    ->default('MXN')
                    ->maxLength(3)
                    ->readOnly(),
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
