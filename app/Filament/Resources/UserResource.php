<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Carbon\Carbon;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class UserResource extends Resource
{
    /**
     * @var string|null
     */
    protected static ?string $model = User::class;

    /**
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    /**
     * @param Form $form
     *
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    /**
     * @param Table $table
     *
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),

                TextColumn::make('full_name')
                    ->getStateUsing(fn(User $record): ?string => $record->getFullName())
                    ->label('ФИО'),

                TextColumn::make('phone_number')
                    ->getStateUsing(fn(User $record): ?string => $record->getFormattedPhone())
                    ->label('Номер телефона'),

                TextColumn::make('email')->label('E-mail'),

                TextColumn::make('telegram_login')->label('Telegram логин'),

                BooleanColumn::make('is_email_verified')->label('E-mail подтвержден'),

                TextColumn::make('ref_code')
                    ->url(fn(User $record): ?string => $record->getReferralLink())
                    ->openUrlInNewTab()
                    ->label('Реферальная ссылка'),

                BooleanColumn::make('is_verified')->label('Верифицирован'),

                TextColumn::make('created_at')
                    ->getStateUsing(fn(User $record): ?string => Carbon::parse($record->created_at)->translatedFormat('d M, Y — H:i:s'))
                    ->label('Создан'),

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
        'create' => "string[]",
        'edit' => "string[]"
    ])]
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
