<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                TextInput::make('order_number')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(false),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->required()
                    ->native(false),

                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                        'partially_refunded' => 'Partially Refunded',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('payment_method')
                    ->maxLength(255),

                TextInput::make('payment_reference')
                    ->maxLength(255),

                TextInput::make('subtotal')
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->required(),

                TextInput::make('tax_amount')
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->default(0),

                TextInput::make('shipping_amount')
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->default(0),

                TextInput::make('discount_amount')
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->default(0),

                TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->required(),

                KeyValue::make('shipping_address')
                    ->keyLabel('Field')
                    ->valueLabel('Value')
                    ->addActionLabel('Add Field'),

                KeyValue::make('billing_address')
                    ->keyLabel('Field')
                    ->valueLabel('Value')
                    ->addActionLabel('Add Field'),

                KeyValue::make('coupon_data')
                    ->keyLabel('Field')
                    ->valueLabel('Value')
                    ->addActionLabel('Add Field'),

                Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                DateTimePicker::make('shipped_at'),

                DateTimePicker::make('delivered_at'),
            ]);
    }
}
