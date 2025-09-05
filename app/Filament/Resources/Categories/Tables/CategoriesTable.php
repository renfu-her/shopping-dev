<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->circular()
                    ->size(40),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->sortable()
                    ->placeholder('Root Category'),

                TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->sortable()
                    ->alignCenter(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

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

                SelectFilter::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
                EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
