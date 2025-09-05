<?php

namespace App\Filament\Resources\Coupons\Schemas;

use App\Models\Coupon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(255)
                    ->unique(Coupon::class, 'code', ignoreRecord: true)
                    ->rules(['alpha_dash'])
                    ->uppercase()
                    ->live(onBlur: true),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Select::make('type')
                    ->options([
                        'percentage' => 'Percentage Discount',
                        'fixed' => 'Fixed Amount Discount',
                        'free_shipping' => 'Free Shipping',
                        'buy_x_get_y' => 'Buy X Get Y',
                    ])
                    ->required()
                    ->native(false)
                    ->live(),

                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->prefix(fn (\Filament\Forms\Components\Get $get): string => $get('type') === 'percentage' ? '' : '$')
                    ->suffix(fn (\Filament\Forms\Components\Get $get): string => $get('type') === 'percentage' ? '%' : '')
                    ->label(fn (\Filament\Forms\Components\Get $get): string => match ($get('type')) {
                        'percentage' => 'Discount Percentage',
                        'fixed' => 'Discount Amount',
                        'free_shipping' => 'Shipping Value',
                        'buy_x_get_y' => 'Buy X Get Y Value',
                        default => 'Value',
                    }),

                TextInput::make('minimum_amount')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->prefix('$')
                    ->label('Minimum Order Amount'),

                TextInput::make('maximum_discount')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->prefix('$')
                    ->label('Maximum Discount Amount')
                    ->visible(fn (\Filament\Forms\Components\Get $get): bool => $get('type') === 'percentage'),

                TextInput::make('usage_limit')
                    ->numeric()
                    ->minValue(1)
                    ->label('Total Usage Limit')
                    ->helperText('Leave empty for unlimited usage'),

                TextInput::make('usage_limit_per_user')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->label('Usage Limit Per User'),

                TextInput::make('used_count')
                    ->numeric()
                    ->minValue(0)
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Times Used'),

                DateTimePicker::make('valid_from')
                    ->label('Valid From'),

                DateTimePicker::make('valid_until')
                    ->label('Valid Until')
                    ->after('valid_from'),

                Select::make('applicable_products')
                    ->multiple()
                    ->relationship('applicableProducts', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->label('Applicable Products')
                    ->helperText('Leave empty to apply to all products'),

                Select::make('applicable_categories')
                    ->multiple()
                    ->relationship('applicableCategories', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->label('Applicable Categories')
                    ->helperText('Leave empty to apply to all categories'),

                Select::make('excluded_products')
                    ->multiple()
                    ->relationship('excludedProducts', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->label('Excluded Products'),

                Select::make('excluded_categories')
                    ->multiple()
                    ->relationship('excludedCategories', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->label('Excluded Categories'),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),

                Toggle::make('is_welcome_coupon')
                    ->default(false)
                    ->label('Welcome Coupon')
                    ->helperText('Automatically assigned to new users'),
            ]);
    }
}
