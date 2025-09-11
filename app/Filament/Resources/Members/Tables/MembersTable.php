<?php

namespace App\Filament\Resources\Members\Tables;

use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->copyable(),

                BadgeColumn::make('membership_type')
                    ->label('Membership Type')
                    ->colors([
                        'secondary' => 'basic',
                        'warning' => 'premium',
                        'success' => 'vip',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                BadgeColumn::make('membership_status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'suspended',
                        'gray' => 'expired',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('points_balance')
                    ->label('Points')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('last_login_at')
                    ->label('Last Login')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Never'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('membership_type')
                    ->options([
                        'basic' => 'Basic',
                        'premium' => 'Premium',
                        'vip' => 'VIP',
                    ])
                    ->label('Membership Type'),

                SelectFilter::make('membership_status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'expired' => 'Expired',
                    ])
                    ->label('Membership Status'),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All members')
                    ->trueLabel('Active members only')
                    ->falseLabel('Inactive members only'),

                SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ])
                    ->label('Gender'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Member $record) {
                        $record->update(['is_active' => true]);
                    })
                    ->visible(fn (Member $record): bool => !$record->is_active),

                Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (Member $record) {
                        $record->update(['is_active' => false]);
                    })
                    ->visible(fn (Member $record): bool => $record->is_active),

                Action::make('reset_password')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->action(function (Member $record) {
                        // TODO: Implement password reset functionality
                        // This could send an email with reset link
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate_selected')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                        }),

                    BulkAction::make('deactivate_selected')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                        }),

                    BulkAction::make('upgrade_to_premium')
                        ->label('Upgrade to Premium')
                        ->icon('heroicon-o-arrow-up')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each->update(['membership_type' => 'premium']);
                        }),

                    BulkAction::make('upgrade_to_vip')
                        ->label('Upgrade to VIP')
                        ->icon('heroicon-o-star')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['membership_type' => 'vip']);
                        }),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}