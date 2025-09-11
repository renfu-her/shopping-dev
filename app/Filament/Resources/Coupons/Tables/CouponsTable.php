<?php

namespace App\Filament\Resources\Coupons\Tables;

use App\Models\Coupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'info',
                        'fixed' => 'success',
                        'free_shipping' => 'warning',
                        'buy_x_get_y' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('value')
                    ->formatStateUsing(fn (Coupon $record): string => $record->formatted_value)
                    ->sortable(),

                TextColumn::make('usage_count')
                    ->label('Used')
                    ->formatStateUsing(fn (Coupon $record): string => 
                        $record->used_count . ($record->usage_limit ? " / {$record->usage_limit}" : '')
                    )
                    ->sortable(),

                TextColumn::make('valid_from')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('No start date'),

                TextColumn::make('valid_until')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('No expiry date'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_welcome_coupon')
                    ->boolean()
                    ->sortable()
                    ->label('Welcome'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),

                TernaryFilter::make('is_welcome_coupon')
                    ->label('Welcome Coupons')
                    ->boolean()
                    ->trueLabel('Welcome coupons only')
                    ->falseLabel('Regular coupons only')
                    ->native(false),

                SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Percentage Discount',
                        'fixed' => 'Fixed Amount Discount',
                        'free_shipping' => 'Free Shipping',
                        'buy_x_get_y' => 'Buy X Get Y',
                    ])
                    ->native(false),

                Filter::make('valid')
                    ->label('Currently Valid')
                    ->query(fn (Builder $query): Builder => $query->where(function ($q) {
                        $now = now();
                        $q->where(function ($subQ) use ($now) {
                            $subQ->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
                        })->where(function ($subQ) use ($now) {
                            $subQ->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
                        });
                    }))
                    ->toggle(),

                Filter::make('expired')
                    ->label('Expired')
                    ->query(fn (Builder $query): Builder => $query->where('valid_until', '<', now()))
                    ->toggle(),
            ])
            ->actions([
                EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
                \Filament\Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (Coupon $record) {
                        $newCoupon = $record->replicate();
                        $newCoupon->code = $record->code . '_COPY';
                        $newCoupon->name = $record->name . ' (Copy)';
                        $newCoupon->used_count = 0;
                        $newCoupon->save();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \Filament\Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    \Filament\Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
