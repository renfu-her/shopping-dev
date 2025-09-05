<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Set;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Category::class, 'slug', ignoreRecord: true)
                    ->rules(['alpha_dash']),

                Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->native(false),

                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->image()
                    ->directory('categories')
                    ->visibility('public'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
            ]);
    }
}
