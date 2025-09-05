<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Set;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
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
                    ->unique(Product::class, 'slug', ignoreRecord: true)
                    ->rules(['alpha_dash']),

                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(255)
                    ->unique(Product::class, 'sku', ignoreRecord: true),

                Textarea::make('short_description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                RichEditor::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->minValue(0),

                TextInput::make('sale_price')
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->minValue(0)
                    ->label('Sale Price'),

                TextInput::make('stock_quantity')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->label('Stock Quantity'),

                TextInput::make('min_stock_level')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->label('Minimum Stock Level'),

                Toggle::make('manage_stock')
                    ->default(true)
                    ->label('Manage Stock'),

                Toggle::make('allow_backorder')
                    ->default(false)
                    ->label('Allow Backorder'),

                FileUpload::make('image')
                    ->image()
                    ->directory('products')
                    ->visibility('public')
                    ->label('Main Image'),

                FileUpload::make('gallery')
                    ->image()
                    ->multiple()
                    ->directory('products/gallery')
                    ->visibility('public')
                    ->label('Gallery Images'),

                CheckboxList::make('categories')
                    ->relationship('categories', 'name')
                    ->searchable()
                    ->bulkToggleable()
                    ->columns(2),

                KeyValue::make('attributes')
                    ->keyLabel('Attribute Name')
                    ->valueLabel('Attribute Value')
                    ->addActionLabel('Add Attribute'),

                TextInput::make('weight')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->suffix('kg'),

                TextInput::make('dimensions')
                    ->label('Dimensions (L x W x H)')
                    ->placeholder('10x20x30'),

                TextInput::make('meta_title')
                    ->maxLength(255)
                    ->label('Meta Title'),

                Textarea::make('meta_description')
                    ->maxLength(65535)
                    ->label('Meta Description')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),

                Toggle::make('is_featured')
                    ->default(false)
                    ->label('Featured'),
            ]);
    }
}
