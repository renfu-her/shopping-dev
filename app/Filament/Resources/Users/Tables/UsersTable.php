<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('orders_count')
                    ->label('Orders')
                    ->counts('orders')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->boolean()
                    ->trueLabel('Verified only')
                    ->falseLabel('Unverified only')
                    ->native(false),

                Filter::make('created_at')
                    ->form([
                        Components\DatePicker::make('created_from'),
                        Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
                EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
                \Filament\Tables\Actions\Action::make('view_orders')
                    ->label('View Orders')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('info')
                    ->url(fn (User $record): string => route('filament.admin.resources.orders.index', ['tableFilters[user_id][value]' => $record->id])),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
