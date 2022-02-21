<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerificationRequestResource\Pages;
use App\Models\VerificationRequest;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class VerificationRequestResource extends Resource
{
    /**
     * @var string|null
     */
    protected static ?string $model = VerificationRequest::class;

    /**
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-check';


    # Localization

    /**
     * @return string
     */
    public static function getBreadcrumb(): string
    {
        return 'Запрос на верификацию';
    }

    /**
     * @return string
     */
    protected static function getNavigationLabel(): string
    {
        return 'Запросы на верификацию';
    }

    /**
     * @return string
     */
    public static function getLabel(): string
    {
        return 'Запрос на верификацию';
    }

    /**
     * @return string
     */
    public static function getPluralLabel(): string
    {
        return 'Запросы на верификацию';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Имя'),

                TextColumn::make('last_name')->label('Фамилия'),

                TextColumn::make('middle_name')->label('Отчество'),

            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[Pure]
    #[ArrayShape([
        'index' => "string[]",
        'edit' => "string[]"
    ])]
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerificationRequests::route('/'),
            'edit' => Pages\EditVerificationRequest::route('/{record}/edit'),
        ];
    }
}
