<?php

namespace App\Filament\Resources\Members\Schemas;

use App\Models\Member;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Full Name'),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(Member::class, 'email', ignoreRecord: true)
                    ->maxLength(255)
                    ->label('Email Address'),

                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    ->label('Password'),

                TextInput::make('phone')
                    ->tel()
                    ->maxLength(20)
                    ->label('Phone Number'),

                DateTimePicker::make('date_of_birth')
                    ->displayFormat('Y-m-d')
                    ->native(false)
                    ->maxDate(now())
                    ->label('Date of Birth'),

                Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ])
                    ->label('Gender'),

                Textarea::make('address')
                    ->rows(3)
                    ->maxLength(500)
                    ->label('Address'),

                TextInput::make('city')
                    ->maxLength(255)
                    ->label('City'),

                TextInput::make('state')
                    ->maxLength(255)
                    ->label('State/Province'),

                TextInput::make('postal_code')
                    ->maxLength(20)
                    ->label('Postal Code'),

                TextInput::make('country')
                    ->maxLength(255)
                    ->label('Country'),

                Select::make('membership_type')
                    ->options([
                        'basic' => 'Basic',
                        'premium' => 'Premium',
                        'vip' => 'VIP',
                    ])
                    ->default('basic')
                    ->required()
                    ->label('Membership Type'),

                Select::make('membership_status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'expired' => 'Expired',
                    ])
                    ->default('active')
                    ->required()
                    ->label('Membership Status'),

                DateTimePicker::make('membership_start_date')
                    ->displayFormat('Y-m-d')
                    ->native(false)
                    ->label('Membership Start Date'),

                DateTimePicker::make('membership_end_date')
                    ->displayFormat('Y-m-d')
                    ->native(false)
                    ->label('Membership End Date'),

                TextInput::make('points_balance')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->default(0)
                    ->prefix('$')
                    ->label('Points Balance'),

                TextInput::make('total_spent')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->default(0)
                    ->prefix('$')
                    ->label('Total Spent'),

                DateTimePicker::make('last_login_at')
                    ->displayFormat('Y-m-d H:i:s')
                    ->native(false)
                    ->label('Last Login'),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active Status'),

                DateTimePicker::make('email_verified_at')
                    ->displayFormat('Y-m-d H:i:s')
                    ->native(false)
                    ->label('Email Verified At'),
            ]);
    }
}